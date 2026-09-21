<?php
/*
 * Student: Alexander Hunt
 * Course: CSD-440 Server-Side Scripting
 * File: AlexanderQuery.php
 * Date: September 21, 2026
 * Purpose: Search the book_collection table by title, author, or genre.
 */

$searchTerm = "";
$books = [];
$message = "";
$errorMessage = "";
$searched = isset($_GET["search"]);

function escapeValue(string $value): string
{
	return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

if ($searched) {
	$submittedTerm = $_GET["search"] ?? "";
	$searchTerm = is_string($submittedTerm) ? trim($submittedTerm) : "";

	if ($searchTerm === "") {
		$errorMessage = "Please enter a title, author, or genre to search.";
	} elseif (strlen($searchTerm) > 150) {
		$errorMessage = "Your search term must contain 150 characters or fewer.";
	} else {
		try {
			$connection = new mysqli("localhost", "student1", "pass", "baseball_01");
			$connection->set_charset("utf8mb4");
			$statement = $connection->prepare(
				"SELECT book_id, title, author, genre, page_count, publication_date, rating, is_read
				 FROM book_collection
				 WHERE title LIKE ? OR author LIKE ? OR genre LIKE ?
				 ORDER BY author, title"
			);
			$searchPattern = "%" . $searchTerm . "%";
			$statement->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
			$statement->execute();
			$result = $statement->get_result();
			$books = $result->fetch_all(MYSQLI_ASSOC);
			$result->free();
			$statement->close();
			$connection->close();
			$message = count($books) . (count($books) === 1 ? " matching book was found." : " matching books were found.");
		} catch (mysqli_sql_exception $exception) {
			$errorMessage = "Unable to search the book collection. Create and populate the table before searching.";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Book Search</title>
	<style>
		body { background: #eef4f7; color: #1e2933; font-family: Arial, sans-serif; line-height: 1.5; margin: 0; }
		header, main, footer { margin: 0 auto; max-width: 1100px; padding: 1.5rem; }
		section { background: #ffffff; border: 1px solid #cbd8df; border-radius: 6px; margin-bottom: 1.5rem; padding: 1.5rem; }
		label { display: block; font-weight: bold; }
		input { box-sizing: border-box; font: inherit; margin-top: 0.35rem; padding: 0.55rem; width: 100%; }
		button { background: #176b87; border: 0; border-radius: 4px; color: #ffffff; cursor: pointer; font: inherit; font-weight: bold; margin-top: 1rem; padding: 0.7rem 1.2rem; }
		table { border-collapse: collapse; width: 100%; }
		th, td { border-bottom: 1px solid #d9e2e7; padding: 0.65rem; text-align: left; vertical-align: top; }
		th { background: #e7edf0; }
		.error { color: #b42318; }
		.success { color: #146c43; }
		.table-container { overflow-x: auto; }
		a { color: #176b87; font-weight: bold; }
	</style>
</head>
<body>
	<header>
		<h1>Search Book Collection</h1>
		<p>Search by a word or phrase found in a book title, author name, or genre.</p>
	</header>
	<main>
		<section>
			<h2>Search Records</h2>
			<form method="get" action="AlexanderQuery.php">
				<label for="search">Title, Author, or Genre</label>
				<input type="search" id="search" name="search" value="<?php echo escapeValue($searchTerm); ?>" maxlength="150" required>
				<button type="submit">Search Books</button>
			</form>
		</section>
		<?php if ($errorMessage !== "") { ?>
			<section>
				<p class="error"><?php echo escapeValue($errorMessage); ?></p>
			</section>
		<?php } elseif ($searched) { ?>
			<section>
				<h2>Search Results</h2>
				<p class="success"><?php echo escapeValue($message); ?></p>
				<?php if (count($books) > 0) { ?>
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
		<?php } ?>
	</main>
	<footer>
		<p><a href="AlexanderIndex.php">Return to Book Collection Home</a></p>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>