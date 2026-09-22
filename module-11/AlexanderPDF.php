<?php
/*
 * Student: Alexander Hunt
 * Course: CSD-440 Server-Side Scripting
 * File: AlexanderPDF.php
 * Date: September 22, 2026
 * Purpose: Export all Module 8 book_collection records in a formatted PDF report.
 */

require_once __DIR__ . "/fpdf19/fpdf.php";

function sendReportError(string $message): never
{
	http_response_code(500);
	header("Content-Type: text/plain; charset=UTF-8");
	echo $message;
	exit(1);
}

function pdfText(string $value): string
{
	$convertedValue = iconv("UTF-8", "Windows-1252//TRANSLIT", $value);
	return $convertedValue === false ? "" : $convertedValue;
}

function formatPublicationDate(string $value): string
{
	$publicationDate = DateTime::createFromFormat("!Y-m-d", $value);
	return $publicationDate === false ? $value : $publicationDate->format("M j, Y");
}

class BookCollectionPdf extends FPDF
{
	private array $columnWidths = [12, 57, 48, 32, 17, 34, 17, 20];
	private array $columnAlignments = ["C", "L", "L", "L", "R", "L", "C", "C"];
	private float $lineHeight = 4.5;

	public function Header(): void
	{
		$this->SetFont("Helvetica", "B", 15);
		$this->SetTextColor(30, 41, 51);
		$this->Cell(0, 7, "Book Collection Database Report", 0, 1, "C");
		$this->SetFont("Helvetica", "", 8);
		$this->Cell(0, 5, "CSD-440 Module 11 | Complete book_collection data from Module 8", 0, 1, "C");
		$this->SetDrawColor(148, 163, 184);
		$this->Line(12, 25, 267, 25);
		$this->Ln(4);
	}

	public function Footer(): void
	{
		$this->SetY(-12);
		$this->SetFont("Helvetica", "", 8);
		$this->SetTextColor(71, 85, 105);
		$this->Cell(0, 5, "CSD-440 Book Collection Report | Page " . $this->PageNo() . "/{nb}", 0, 0, "C");
	}

	public function renderIntroduction(int $recordCount): void
	{
		$this->SetFont("Helvetica", "", 9);
		$this->SetTextColor(30, 41, 51);
		$this->MultiCell(
			0,
			5,
			"This report presents the complete personal book collection stored in the Module 8 database. " .
			"Each record identifies a book's title, author, genre, page count, publication date, reader rating, and completion status. " .
			"The collection currently contains " . $recordCount . " book record" . ($recordCount === 1 ? "" : "s") . "."
		);
		$this->Ln(3);
	}

	public function renderTableHeader(): void
	{
		$headings = ["ID", "Title", "Author", "Genre", "Pages", "Publication Date", "Rating", "Read"];
		$this->SetFont("Helvetica", "B", 7);
		$this->SetFillColor(51, 65, 85);
		$this->SetTextColor(255, 255, 255);

		foreach ($headings as $index => $heading) {
			$this->Cell($this->columnWidths[$index], 7, $heading, 1, 0, $this->columnAlignments[$index], true);
		}

		$this->Ln();
		$this->SetTextColor(30, 41, 51);
	}

	public function renderBookRow(array $book): void
	{
		$values = [
			(string) $book["book_id"],
			$book["title"],
			$book["author"],
			$book["genre"],
			(string) $book["page_count"],
			formatPublicationDate($book["publication_date"]),
			number_format((float) $book["rating"], 1),
			$book["is_read"] === "1" ? "Yes" : "No",
		];

		$this->SetFont("Helvetica", "", 7);
		$wrappedValues = [];
		$maximumLineCount = 1;

		foreach ($values as $index => $value) {
			$wrappedValues[$index] = $this->wrapText(pdfText($value), $this->columnWidths[$index]);
			$maximumLineCount = max($maximumLineCount, count($wrappedValues[$index]));
		}

		$rowHeight = $maximumLineCount * $this->lineHeight;
		if ($this->GetY() + $rowHeight > $this->GetPageHeight() - 18) {
			$this->AddPage();
			$this->renderTableHeader();
		}

		$rowStartX = $this->GetX();
		$rowStartY = $this->GetY();

		foreach ($wrappedValues as $index => $lines) {
			$this->SetXY($rowStartX, $rowStartY);
			$this->MultiCell(
				$this->columnWidths[$index],
				$this->lineHeight,
				implode("\n", $lines),
				1,
				$this->columnAlignments[$index]
			);
			$rowStartX += $this->columnWidths[$index];
		}

		$this->SetXY(12, $rowStartY + $rowHeight);
	}

	public function renderTableFooter(int $recordCount): void
	{
		$footerWidth = array_sum($this->columnWidths);
		$this->SetFont("Helvetica", "B", 8);
		$this->SetFillColor(226, 232, 240);
		$this->SetTextColor(30, 41, 51);
		$this->Cell($footerWidth, 7, "Total books: " . $recordCount, 1, 1, "R", true);
	}

	private function wrapText(string $value, float $width): array
	{
		$words = preg_split('/\s+/', trim($value)) ?: [""];
		$lines = [];
		$currentLine = "";

		foreach ($words as $word) {
			$candidate = $currentLine === "" ? $word : $currentLine . " " . $word;
			if ($this->GetStringWidth($candidate) <= $width - 2) {
				$currentLine = $candidate;
				continue;
			}

			if ($currentLine !== "") {
				$lines[] = $currentLine;
			}

			$currentLine = $this->splitLongWord($word, $width, $lines);
		}

		if ($currentLine !== "" || count($lines) === 0) {
			$lines[] = $currentLine;
		}

		return $lines;
	}

	private function splitLongWord(string $word, float $width, array &$lines): string
	{
		if ($this->GetStringWidth($word) <= $width - 2) {
			return $word;
		}

		$fragment = "";
		for ($index = 0; $index < strlen($word); $index++) {
			$candidate = $fragment . $word[$index];
			if ($this->GetStringWidth($candidate) > $width - 2 && $fragment !== "") {
				$lines[] = $fragment;
				$fragment = $word[$index];
			} else {
				$fragment = $candidate;
			}
		}

		return $fragment;
	}
}

try {
	$connection = new mysqli("localhost", "student1", "pass", "baseball_01");
	$connection->set_charset("utf8mb4");
	$result = $connection->query(
		"SELECT book_id, title, author, genre, page_count, publication_date, rating, is_read
		 FROM book_collection
		 ORDER BY author, title"
	);
	$books = $result->fetch_all(MYSQLI_ASSOC);
	$result->free();
	$connection->close();
} catch (mysqli_sql_exception $exception) {
	sendReportError("Unable to create the PDF report. Create and populate the book_collection table before requesting this page.");
}

if (count($books) === 0) {
	sendReportError("The book_collection table has no records. Populate the table before requesting this report.");
}

$pdf = new BookCollectionPdf("L", "mm", "Letter");
$pdf->SetTitle("Alexander Book Collection Report");
$pdf->SetAuthor("Alexander Hunt");
$pdf->SetMargins(12, 12, 12);
$pdf->SetAutoPageBreak(false);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->renderIntroduction(count($books));
$pdf->renderTableHeader();

foreach ($books as $book) {
	$pdf->renderBookRow($book);
}

$pdf->renderTableFooter(count($books));
$pdf->Output("D", "AlexanderBookCollection.pdf", true);