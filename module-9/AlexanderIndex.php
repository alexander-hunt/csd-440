<?php
/*
 * Student: Alexander Hunt
 * Course: CSD-440 Server-Side Scripting
 * File: AlexanderIndex.php
 * Date: September 21, 2026
 * Purpose: Provide navigation for the Module 9 book collection pages.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Book Collection Home</title>
	<style>
		body { background: #eef4f7; color: #1e2933; font-family: Arial, sans-serif; line-height: 1.5; margin: 0; }
		header, main, footer { margin: 0 auto; max-width: 760px; padding: 1.5rem; }
		section { background: #ffffff; border: 1px solid #cbd8df; border-radius: 6px; margin-bottom: 1.5rem; padding: 1.5rem; }
		ul { margin-bottom: 0; padding-left: 1.25rem; }
		li { margin-bottom: 0.65rem; }
		a { color: #176b87; font-weight: bold; }
	</style>
</head>
<body>
	<header>
		<h1>Book Collection Manager</h1>
		<p>Use the pages below to search and add records in the Module 8 and 9 book collection database.</p>
	</header>
	<main>
		<section>
			<h2>Module 9 Pages</h2>
			<nav aria-label="Module 9 pages">
				<ul>
					<li><a href="AlexanderQuery.php">Search the Book Collection</a></li>
					<li><a href="AlexanderForms.php">Add a Book Record</a></li>
				</ul>
			</nav>
		</section>
		<section>
			<h2>Module 8 Database Utilities</h2>
			<nav aria-label="Module 8 utility pages">
				<ul>
					<li><a href="AlexanderCreateTable.php">Create Book Collection Table</a></li>
					<li><a href="AlexanderPopulateTable.php">Populate Book Collection Table</a></li>
					<li><a href="AlexanderQueryTable.php">View All Book Records</a></li>
					<li><a href="AlexanderDropTable.php">Drop Book Collection Table</a></li>
				</ul>
			</nav>
		</section>
	</main>
	<footer>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>