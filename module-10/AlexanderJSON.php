<?php
/*
 * Student: Alexander Hunt
 * Course: CSD-440 Server-Side Scripting
 * File: AlexanderJSON.php
 * Date: September 22, 2026
 * Purpose: Collect book data and display it in JSON format.
 */

$genres = [
	"Fiction" => "Fiction",
	"Mystery" => "Mystery",
	"Science Fiction" => "Science Fiction",
	"Fantasy" => "Fantasy",
	"Biography" => "Biography",
	"History" => "History",
	"Technology" => "Technology",
];

$formData = [
	"title" => "",
	"author" => "",
	"genre" => "",
	"isbn" => "",
	"pageCount" => "",
	"publicationDate" => "",
	"rating" => "",
	"isRead" => "",
];
$errors = [];
$jsonOutput = "";
$jsonError = "";
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
	foreach (["title", "author", "genre", "isbn", "pageCount", "publicationDate", "rating"] as $field) {
		$formData[$field] = getPostedString($field);
	}
	$formData["isRead"] = isset($_POST["isRead"]) && $_POST["isRead"] === "1" ? "1" : "";

	validateRequiredText($formData["title"], "book title", 150, $errors, "title");
	validateRequiredText($formData["author"], "author name", 120, $errors, "author");

	if (!array_key_exists($formData["genre"], $genres)) {
		$errors["genre"] = "Please select a valid genre.";
	}

	if ($formData["isbn"] === "") {
		$errors["isbn"] = "Please enter the ISBN.";
	} elseif (!preg_match('/^[0-9Xx-]{10,17}$/', $formData["isbn"])) {
		$errors["isbn"] = "ISBN must contain 10 to 17 digits, hyphens, or an X check digit.";
	}

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
		$payload = [
			"title" => $formData["title"],
			"author" => $formData["author"],
			"genre" => $formData["genre"],
			"isbn" => $formData["isbn"],
			"pageCount" => (int) $formData["pageCount"],
			"publicationDate" => $formData["publicationDate"],
			"rating" => (float) $formData["rating"],
			"isRead" => $formData["isRead"] === "1",
		];

		try {
			$jsonOutput = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
		} catch (JsonException $exception) {
			$jsonError = "Unable to encode the submitted book data as JSON.";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's JSON Book Form</title>
	<style>
		body { background: #eef4f7; color: #1e2933; font-family: Arial, sans-serif; line-height: 1.5; margin: 0; }
		header, main, footer { margin: 0 auto; max-width: 760px; padding: 1.5rem; }
		section { background: #ffffff; border: 1px solid #cbd8df; border-radius: 6px; margin-bottom: 1.5rem; padding: 1.5rem; }
		.form-grid { display: grid; gap: 1rem; grid-template-columns: repeat(2, minmax(0, 1fr)); }
		label { display: block; font-weight: bold; }
		input, select { box-sizing: border-box; font: inherit; margin-top: 0.35rem; padding: 0.55rem; width: 100%; }
		.full-width { grid-column: 1 / -1; }
		.checkbox-label { align-items: center; display: flex; gap: 0.5rem; }
		.checkbox-label input { margin: 0; width: auto; }
		button { background: #176b87; border: 0; border-radius: 4px; color: #ffffff; cursor: pointer; font: inherit; font-weight: bold; margin-top: 1.25rem; padding: 0.7rem 1.2rem; }
		.error-section { background: #fff4f2; border-color: #d9584b; }
		.error-list { margin-bottom: 0; }
		.error { color: #b42318; }
		.json-output { background: #f4f8fa; border: 1px solid #cbd8df; overflow-x: auto; padding: 1rem; }
		.json-output code { font-family: Consolas, monospace; }
		@media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .full-width { grid-column: auto; } }
	</style>
</head>
<body>
	<header>
		<h1>JSON Book Form</h1>
		<p>Enter book details to receive a JSON representation of the submitted data.</p>
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
		<?php } elseif ($jsonError !== "") { ?>
			<section class="error-section">
				<p class="error"><?php echo escapeValue($jsonError); ?></p>
			</section>
		<?php } elseif ($jsonOutput !== "") { ?>
			<section aria-labelledby="json-heading">
				<h2 id="json-heading">JSON Output</h2>
				<pre class="json-output"><code><?php echo escapeValue($jsonOutput); ?></code></pre>
			</section>
		<?php } ?>

		<section aria-labelledby="form-heading">
			<h2 id="form-heading">Book Details</h2>
			<form method="post" action="<?php echo escapeValue($_SERVER["PHP_SELF"]); ?>">
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
						<select id="genre" name="genre" required>
							<option value="">Select a genre</option>
							<?php foreach ($genres as $value => $label) { ?>
								<option value="<?php echo escapeValue($value); ?>"<?php echo $formData["genre"] === $value ? " selected" : ""; ?>><?php echo escapeValue($label); ?></option>
							<?php } ?>
						</select>
					</div>
					<div>
						<label for="isbn">ISBN</label>
						<input type="text" id="isbn" name="isbn" value="<?php echo escapeValue($formData["isbn"]); ?>" maxlength="17" required>
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
				<button type="submit">Create JSON</button>
			</form>
		</section>
	</main>
	<footer>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>