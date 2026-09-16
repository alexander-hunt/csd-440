<?php
/*
 * Student: Alexander Hunt
 * Course: CSD-440 Server-Side Scripting
 * File: AlexanderDropTable.php
 * Date: September 15, 2026
 * Purpose: Drop the book_collection table when a fresh database reset is needed.
 */

$message = "";
$isSuccess = false;

try {
	$connection = new mysqli("localhost", "student1", "pass", "baseball_01");
	$connection->set_charset("utf8mb4");
	$connection->query("DROP TABLE IF EXISTS book_collection");
	$message = "The book_collection table has been dropped.";
	$isSuccess = true;
	$connection->close();
} catch (mysqli_sql_exception $exception) {
	$message = "Unable to drop the book_collection table. Confirm that MySQL is running and the student1 account can access baseball_01.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Book Table Dropper</title>
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
		<h1>Drop Book Collection Table</h1>
	</header>
	<main>
		<section>
			<h2>Database Result</h2>
			<p class="<?php echo $isSuccess ? "success" : "error"; ?>"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
			<p>Run the table creation page again before populating or querying records.</p>
		</section>
	</main>
	<footer>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>