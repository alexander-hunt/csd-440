<?php
/*
 * Student: Alexander Hunt
 * Course: CSD-440 Server-Side Scripting
 * File: AlexanderCreateTable.php
 * Date: September 15, 2026
 * Purpose: Create the book_collection table for Modules 8 and 9.
 */

$message = "";
$isSuccess = false;

try {
	$connection = new mysqli("localhost", "student1", "pass", "baseball_01");
	$connection->set_charset("utf8mb4");

	$sql = "CREATE TABLE IF NOT EXISTS book_collection (
		book_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		title VARCHAR(150) NOT NULL,
		author VARCHAR(120) NOT NULL,
		genre VARCHAR(60) NOT NULL,
		page_count SMALLINT UNSIGNED NOT NULL,
		publication_date DATE NOT NULL,
		rating DECIMAL(2,1) NOT NULL,
		is_read TINYINT(1) NOT NULL DEFAULT 0
	)";

	$connection->query($sql);
	$message = "The book_collection table is ready to use.";
	$isSuccess = true;
	$connection->close();
} catch (mysqli_sql_exception $exception) {
	$message = "Unable to create the book_collection table. Confirm that MySQL is running and the student1 account can access baseball_01.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Book Table Creator</title>
	<style>
		body { background: #eef4f7; color: #1e2933; font-family: Arial, sans-serif; line-height: 1.5; margin: 0; }
		header, main, footer { margin: 0 auto; max-width: 760px; padding: 1.5rem; }
		section { background: #ffffff; border: 1px solid #cbd8df; border-radius: 6px; padding: 1.5rem; }
		.success { color: #146c43; }
		.error { color: #b42318; }
		code { background: #e7edf0; padding: 0.15rem 0.3rem; }
	</style>
</head>
<body>
	<header>
		<h1>Create Book Collection Table</h1>
	</header>
	<main>
		<section>
			<h2>Database Result</h2>
			<p class="<?php echo $isSuccess ? "success" : "error"; ?>"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
			<p>The table contains fields for an ID, title, author, genre, page count, publication date, rating, and read status.</p>
		</section>
	</main>
	<footer>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>