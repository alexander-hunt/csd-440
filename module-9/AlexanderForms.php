<?php
/*
 * Student: Alexander Hunt
 * Course: CSD-440 Server-Side Scripting
 * File: AlexanderForms.php
 * Date: September 21, 2026
 * Purpose: Add a validated book record to the book_collection table.
 */

$formData = [
	"title" => "",
	"author" => "",
	"genre" => "",
	"pageCount" => "",
	"publicationDate" => "",
	"rating" => "",
	"isRead" => "",
];
$errors = [];
$successMessage = "";
$databaseError = "";
$submitted = $_SERVER["REQUEST_METHOD"] === "POST";

function escapeValue(string $value): string
{
	return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

function getPostedString(string $field): string
{
	$value = $_POST[$field] ?? "";
	return is_string($value) ? trim($value) : "";
}

function validateRequiredText(string $value, string $fieldName, int $maximumLength, array &$errors, string $field): void
{
	if ($value === "") {
		$errors[$field] = "Please enter the " . $fieldName . ".";
	} elseif (strlen($value) > $maximumLength) {
		$errors[$field] = "The " . $fieldName . " must contain " . $maximumLength . " characters or fewer.";
	}
}

if ($submitted) {
	foreach (["title", "author", "genre", "pageCount", "publicationDate", "rating"] as $field) {
		$formData[$field] = getPostedString($field);
	}
	$formData["isRead"] = isset($_POST["isRead"]) && $_POST["isRead"] === "1" ? "1" : "";

	validateRequiredText($formData["title"], "book title", 150, $errors, "title");
	validateRequiredText($formData["author"], "author name", 120, $errors, "author");
	validateRequiredText($formData["genre"], "genre", 60, $errors, "genre");

	if ($formData["pageCount"] === "") {
		$errors["pageCount"] = "Please enter the page count.";
	} elseif (filter_var($formData["pageCount"], FILTER_VALIDATE_INT) === false || (int) $formData["pageCount"] < 1 || (int) $formData["pageCount"] > 65535) {
		$errors["pageCount"] = "Page count must be a whole number from 1 to 65,535.";
	}

	$publicationDate = DateTime::createFromFormat("!Y-m-d", $formData["publicationDate"]);
	if ($formData["publicationDate"] === "") {
		$errors["publicationDate"] = "Please enter the publication date.";
	} elseif ($publicationDate === false || $publicationDate->format("Y-m-d") !== $formData["publicationDate"]) {
		$errors["publicationDate"] = "Please enter a valid publication date.";
	}

	if ($formData["rating"] === "") {
		$errors["rating"] = "Please enter the rating.";
	} elseif (!is_numeric($formData["rating"]) || (float) $formData["rating"] < 0 || (float) $formData["rating"] > 5) {
		$errors["rating"] = "Rating must be a number from 0.0 to 5.0.";
	}

	if (count($errors) === 0) {
		$title = $formData["title"];
		$author = $formData["author"];
		$genre = $formData["genre"];
		$pageCount = (int) $formData["pageCount"];
		$publicationDateValue = $formData["publicationDate"];
		$rating = (float) $formData["rating"];
		$isRead = $formData["isRead"] === "1" ? 1 : 0;

		try {
			$connection = new mysqli("localhost", "student1", "pass", "baseball_01");
			$connection->set_charset("utf8mb4");
			$statement = $connection->prepare(
				"INSERT INTO book_collection (title, author, genre, page_count, publication_date, rating, is_read)
				 VALUES (?, ?, ?, ?, ?, ?, ?)"
			);
			$statement->bind_param("sssisdi", $title, $author, $genre, $pageCount, $publicationDateValue, $rating, $isRead);
			$statement->execute();
			$bookId = $connection->insert_id;
			$statement->close();
			$connection->close();
			$successMessage = "The book record was added successfully with ID " . $bookId . ".";
			$formData = [
				"title" => "",
				"author" => "",
				"genre" => "",
				"pageCount" => "",
				"publicationDate" => "",
				"rating" => "",
				"isRead" => "",
			];
		} catch (mysqli_sql_exception $exception) {
			$databaseError = "Unable to add the book record. Create the table first and confirm that MySQL is running.";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Add Book Form</title>
	<style>
		body { background: #eef4f7; color: #1e2933; font-family: Arial, sans-serif; line-height: 1.5; margin: 0; }
		header, main, footer { margin: 0 auto; max-width: 760px; padding: 1.5rem; }
		section { background: #ffffff; border: 1px solid #cbd8df; border-radius: 6px; margin-bottom: 1.5rem; padding: 1.5rem; }
		.form-grid { display: grid; gap: 1rem; grid-template-columns: repeat(2, minmax(0, 1fr)); }
		label { display: block; font-weight: bold; }
		input { box-sizing: border-box; font: inherit; margin-top: 0.35rem; padding: 0.55rem; width: 100%; }
		.full-width { grid-column: 1 / -1; }
		.checkbox-label { align-items: center; display: flex; gap: 0.5rem; }
		.checkbox-label input { margin: 0; width: auto; }
		button { background: #176b87; border: 0; border-radius: 4px; color: #ffffff; cursor: pointer; font: inherit; font-weight: bold; margin-top: 1.25rem; padding: 0.7rem 1.2rem; }
		.error-section { background: #fff4f2; border-color: #d9584b; }
		.error-list { margin-bottom: 0; }
		.error { color: #b42318; }
		.success { color: #146c43; }
		a { color: #176b87; font-weight: bold; }
		@media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .full-width { grid-column: auto; } }
	</style>
</head>
<body>
	<header>
		<h1>Add a Book Record</h1>
		<p>Enter the details for a book to add it to the collection.</p>
	</header>
	<main>
		<?php if (count($errors) > 0) { ?>
			<section class="error-section" aria-labelledby="error-heading">
				<h2 id="error-heading">Please Correct These Errors</h2>
				<ul class="error-list">
					<?php foreach ($errors as $error) { ?>
						<li><?php echo escapeValue($error); ?></li>
					<?php } ?>
				</ul>
			</section>
		<?php } elseif ($databaseError !== "") { ?>
			<section class="error-section">
				<p class="error"><?php echo escapeValue($databaseError); ?></p>
			</section>
		<?php } elseif ($successMessage !== "") { ?>
			<section>
				<p class="success"><?php echo escapeValue($successMessage); ?></p>
			</section>
		<?php } ?>
		<section>
			<h2>Book Details</h2>
			<form method="post" action="AlexanderForms.php">
				<div class="form-grid">
					<div class="full-width">
						<label for="title">Title</label>
						<input type="text" id="title" name="title" value="<?php echo escapeValue($formData["title"]); ?>" maxlength="150" required>
					</div>
					<div>
						<label for="author">Author</label>
						<input type="text" id="author" name="author" value="<?php echo escapeValue($formData["author"]); ?>" maxlength="120" required>
					</div>
					<div>
						<label for="genre">Genre</label>
						<input type="text" id="genre" name="genre" value="<?php echo escapeValue($formData["genre"]); ?>" maxlength="60" required>
					</div>
					<div>
						<label for="pageCount">Page Count</label>
						<input type="number" id="pageCount" name="pageCount" value="<?php echo escapeValue($formData["pageCount"]); ?>" min="1" max="65535" step="1" required>
					</div>
					<div>
						<label for="publicationDate">Publication Date</label>
						<input type="date" id="publicationDate" name="publicationDate" value="<?php echo escapeValue($formData["publicationDate"]); ?>" required>
					</div>
					<div>
						<label for="rating">Rating</label>
						<input type="number" id="rating" name="rating" value="<?php echo escapeValue($formData["rating"]); ?>" min="0" max="5" step="0.1" required>
					</div>
					<div class="full-width">
						<label class="checkbox-label" for="isRead">
							<input type="checkbox" id="isRead" name="isRead" value="1"<?php echo $formData["isRead"] === "1" ? " checked" : ""; ?>>
							I have read this book.
						</label>
					</div>
				</div>
				<button type="submit">Add Book</button>
			</form>
		</section>
	</main>
	<footer>
		<p><a href="AlexanderIndex.php">Return to Book Collection Home</a></p>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>