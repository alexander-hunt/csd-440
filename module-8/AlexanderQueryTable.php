<?php
/*
 * Student: Alexander Hunt
 * Course: CSD-440 Server-Side Scripting
 * File: AlexanderQueryTable.php
 * Date: September 15, 2026
 * Purpose: Query and display the book_collection table for testing.
 */

$books = [];
$errorMessage = "";

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
	$errorMessage = "Unable to query the book_collection table. Create and populate the table before viewing records.";
}

function escapeValue(string $value): string
{
	return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Book Table Query</title>
	<style>
		body { background: #eef4f7; color: #1e2933; font-family: Arial, sans-serif; line-height: 1.5; margin: 0; }
		header, main, footer { margin: 0 auto; max-width: 1100px; padding: 1.5rem; }
		section { background: #ffffff; border: 1px solid #cbd8df; border-radius: 6px; padding: 1.5rem; }
		table { border-collapse: collapse; width: 100%; }
		th, td { border-bottom: 1px solid #d9e2e7; padding: 0.65rem; text-align: left; vertical-align: top; }
		th { background: #e7edf0; }
		.error { color: #b42318; }
		.table-container { overflow-x: auto; }
	</style>
</head>
<body>
	<header>
		<h1>Book Collection Query</h1>
	</header>
	<main>
		<section>
			<h2>Stored Book Records</h2>
			<?php if ($errorMessage !== "") { ?>
				<p class="error"><?php echo escapeValue($errorMessage); ?></p>
			<?php } elseif (count($books) === 0) { ?>
				<p>No books were found. Run the populate table page to add the sample records.</p>
			<?php } else { ?>
				<div class="table-container">
					<table>
						<thead>
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Title</th>
								<th scope="col">Author</th>
								<th scope="col">Genre</th>
								<th scope="col">Pages</th>
								<th scope="col">Publication Date</th>
								<th scope="col">Rating</th>
								<th scope="col">Read</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($books as $book) { ?>
								<tr>
									<td><?php echo escapeValue((string) $book["book_id"]); ?></td>
									<td><?php echo escapeValue($book["title"]); ?></td>
									<td><?php echo escapeValue($book["author"]); ?></td>
									<td><?php echo escapeValue($book["genre"]); ?></td>
									<td><?php echo escapeValue((string) $book["page_count"]); ?></td>
									<td><?php echo escapeValue($book["publication_date"]); ?></td>
									<td><?php echo escapeValue(number_format((float) $book["rating"], 1)); ?></td>
									<td><?php echo escapeValue($book["is_read"] === "1" ? "Yes" : "No"); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
		</section>
	</main>
	<footer>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>