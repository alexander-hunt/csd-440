<?php
/*
 * Student: Alexander Hunt
 * Course: CSD-440 Server-Side Scripting
 * File: AlexanderPopulateTable.php
 * Date: September 15, 2026
 * Purpose: Populate the book_collection table with five book records.
 */

$books = [
	["Dune", "Frank Herbert", "Science Fiction", 688, "1965-08-01", 4.5, 1],
	["Kindred", "Octavia E. Butler", "Science Fiction", 288, "1979-06-01", 4.7, 1],
	["The Left Hand of Darkness", "Ursula K. Le Guin", "Science Fiction", 336, "1969-03-01", 4.4, 0],
	["The Name of the Wind", "Patrick Rothfuss", "Fantasy", 662, "2007-03-27", 4.6, 1],
	["A Wizard of Earthsea", "Ursula K. Le Guin", "Fantasy", 205, "1968-09-01", 4.3, 0],
];
$message = "";
$isSuccess = false;
$connection = null;
$transactionStarted = false;

try {
	$connection = new mysqli("localhost", "student1", "pass", "baseball_01");
	$connection->set_charset("utf8mb4");
	$connection->begin_transaction();
	$transactionStarted = true;
	$connection->query("DELETE FROM book_collection");

	$statement = $connection->prepare(
		"INSERT INTO book_collection (title, author, genre, page_count, publication_date, rating, is_read)
		 VALUES (?, ?, ?, ?, ?, ?, ?)"
	);

	foreach ($books as [$title, $author, $genre, $pageCount, $publicationDate, $rating, $isRead]) {
		$statement->bind_param("sssisdi", $title, $author, $genre, $pageCount, $publicationDate, $rating, $isRead);
		$statement->execute();
	}

	$statement->close();
	$connection->commit();
	$transactionStarted = false;
	$message = count($books) . " book records were added to the book_collection table.";
	$isSuccess = true;
	$connection->close();
} catch (mysqli_sql_exception $exception) {
	if ($connection instanceof mysqli && $transactionStarted) {
		$connection->rollback();
	}

	$message = "Unable to populate the book_collection table. Create the table first and confirm that MySQL is running.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Book Table Populator</title>
	<style>
		body { background: #eef4f7; color: #1e2933; font-family: Arial, sans-serif; line-height: 1.5; margin: 0; }
		header, main, footer { margin: 0 auto; max-width: 760px; padding: 1.5rem; }
		section { background: #ffffff; border: 1px solid #cbd8df; border-radius: 6px; padding: 1.5rem; }
		.success { color: #146c43; }
		.error { color: #b42318; }
	</style>
</head>
<body>
	<header>
		<h1>Populate Book Collection Table</h1>
	</header>
	<main>
		<section>
			<h2>Database Result</h2>
			<p class="<?php echo $isSuccess ? "success" : "error"; ?>"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
			<p>Existing records are replaced so this page can be run again without adding duplicates.</p>
		</section>
	</main>
	<footer>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>