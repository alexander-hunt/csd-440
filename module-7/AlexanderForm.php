<?php
/*
 * Student: Alexander Hunt
 * Course: CSD-440 Server-Side Scripting
 * File: AlexanderForm.php
 * Date: September 2, 2026
 * Purpose: Collect, validate, and display seven fields of user information.
 */

$programs = [
	"Web Development" => "Web Development",
	"Information Technology" => "Information Technology",
	"Cybersecurity" => "Cybersecurity",
	"Data Analytics" => "Data Analytics",
];

$formData = [
	"fullName" => "",
	"email" => "",
	"age" => "",
	"gpa" => "",
	"enrollmentDate" => "",
	"program" => "",
	"terms" => "",
];
$errors = [];
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

if ($submitted) {
	foreach (["fullName", "email", "age", "gpa", "enrollmentDate", "program"] as $field) {
		$formData[$field] = getPostedString($field);
	}
	$formData["terms"] = isset($_POST["terms"]) && $_POST["terms"] === "1" ? "1" : "";

	if ($formData["fullName"] === "") {
		$errors["fullName"] = "Please enter your full name.";
	} elseif (strlen($formData["fullName"]) < 2) {
		$errors["fullName"] = "Your full name must contain at least two characters.";
	}

	if ($formData["email"] === "") {
		$errors["email"] = "Please enter your email address.";
	} elseif (filter_var($formData["email"], FILTER_VALIDATE_EMAIL) === false) {
		$errors["email"] = "Please enter a valid email address.";
	}

	if ($formData["age"] === "") {
		$errors["age"] = "Please enter your age.";
	} elseif (filter_var($formData["age"], FILTER_VALIDATE_INT) === false || (int) $formData["age"] < 16 || (int) $formData["age"] > 120) {
		$errors["age"] = "Age must be a whole number from 16 to 120.";
	}

	if ($formData["gpa"] === "") {
		$errors["gpa"] = "Please enter your GPA.";
	} elseif (!is_numeric($formData["gpa"]) || (float) $formData["gpa"] < 0 || (float) $formData["gpa"] > 4) {
		$errors["gpa"] = "GPA must be a number from 0.00 to 4.00.";
	}

	$date = DateTime::createFromFormat("!Y-m-d", $formData["enrollmentDate"]);
	if ($formData["enrollmentDate"] === "") {
		$errors["enrollmentDate"] = "Please enter an enrollment date.";
	} elseif ($date === false || $date->format("Y-m-d") !== $formData["enrollmentDate"]) {
		$errors["enrollmentDate"] = "Please enter a valid enrollment date.";
	}

	if (!array_key_exists($formData["program"], $programs)) {
		$errors["program"] = "Please select a valid program.";
	}

	if ($formData["terms"] !== "1") {
		$errors["terms"] = "You must confirm that the information is correct.";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Information Form</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			line-height: 1.5;
			margin: 0;
			background: #eef4f7;
			color: #1e2933;
		}
		header, main, footer {
			max-width: 760px;
			margin: 0 auto;
			padding: 1.5rem;
		}
		header {
			padding-bottom: 0.5rem;
		}
		section {
			background: #ffffff;
			border: 1px solid #cbd8df;
			border-radius: 6px;
			margin-bottom: 1.5rem;
			padding: 1.5rem;
		}
		.form-grid {
			display: grid;
			gap: 1rem;
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}
		label {
			display: block;
			font-weight: bold;
		}
		input, select {
			box-sizing: border-box;
			font: inherit;
			margin-top: 0.35rem;
			padding: 0.55rem;
			width: 100%;
		}
		.full-width {
			grid-column: 1 / -1;
		}
		.checkbox-label {
			align-items: center;
			display: flex;
			gap: 0.5rem;
		}
		.checkbox-label input {
			margin: 0;
			width: auto;
		}
		button {
			background: #176b87;
			border: 0;
			border-radius: 4px;
			color: #ffffff;
			cursor: pointer;
			font: inherit;
			font-weight: bold;
			margin-top: 1.25rem;
			padding: 0.7rem 1.2rem;
		}
		.error-section {
			background: #fff4f2;
			border-color: #d9584b;
		}
		.error-list {
			margin-bottom: 0;
		}
		table {
			border-collapse: collapse;
			width: 100%;
		}
		th, td {
			border-bottom: 1px solid #d9e2e7;
			padding: 0.65rem;
			text-align: left;
			vertical-align: top;
		}
		th {
			width: 38%;
		}
		@media (max-width: 600px) {
			.form-grid {
				grid-template-columns: 1fr;
			}
			.full-width {
				grid-column: auto;
			}
		}
	</style>
</head>
<body>
	<header>
		<h1>Student Information Form</h1>
		<p>Enter all seven fields to display a formatted summary.</p>
	</header>

	<main>
		<?php if ($submitted && count($errors) > 0) { ?>
			<section class="error-section" aria-labelledby="error-heading">
				<h2 id="error-heading">Please Correct These Errors</h2>
				<ul class="error-list">
					<?php foreach ($errors as $error) { ?>
						<li><?php echo escapeValue($error); ?></li>
					<?php } ?>
				</ul>
			</section>
		<?php } elseif ($submitted) { ?>
			<section aria-labelledby="result-heading">
				<h2 id="result-heading">Submitted Information</h2>
				<table>
					<tbody>
						<tr><th scope="row">Full Name</th><td><?php echo escapeValue($formData["fullName"]); ?></td></tr>
						<tr><th scope="row">Email</th><td><?php echo escapeValue($formData["email"]); ?></td></tr>
						<tr><th scope="row">Age</th><td><?php echo escapeValue($formData["age"]); ?></td></tr>
						<tr><th scope="row">GPA</th><td><?php echo escapeValue(number_format((float) $formData["gpa"], 2)); ?></td></tr>
						<tr><th scope="row">Enrollment Date</th><td><?php echo escapeValue($formData["enrollmentDate"]); ?></td></tr>
						<tr><th scope="row">Program</th><td><?php echo escapeValue($programs[$formData["program"]]); ?></td></tr>
						<tr><th scope="row">Information Confirmed</th><td>Yes</td></tr>
					</tbody>
				</table>
			</section>
		<?php } ?>

		<section>
			<h2>Enter Your Information</h2>
			<form method="post" action="<?php echo escapeValue($_SERVER["PHP_SELF"]); ?>">
				<div class="form-grid">
					<div>
						<label for="fullName">Full Name</label>
						<input type="text" id="fullName" name="fullName" value="<?php echo escapeValue($formData["fullName"]); ?>" required>
					</div>
					<div>
						<label for="email">Email Address</label>
						<input type="email" id="email" name="email" value="<?php echo escapeValue($formData["email"]); ?>" required>
					</div>
					<div>
						<label for="age">Age</label>
						<input type="number" id="age" name="age" min="16" max="120" step="1" value="<?php echo escapeValue($formData["age"]); ?>" required>
					</div>
					<div>
						<label for="gpa">GPA</label>
						<input type="number" id="gpa" name="gpa" min="0" max="4" step="0.01" value="<?php echo escapeValue($formData["gpa"]); ?>" required>
					</div>
					<div>
						<label for="enrollmentDate">Enrollment Date</label>
						<input type="date" id="enrollmentDate" name="enrollmentDate" value="<?php echo escapeValue($formData["enrollmentDate"]); ?>" required>
					</div>
					<div>
						<label for="program">Program</label>
						<select id="program" name="program" required>
							<option value="">Select a program</option>
							<?php foreach ($programs as $value => $label) { ?>
								<option value="<?php echo escapeValue($value); ?>"<?php echo $formData["program"] === $value ? " selected" : ""; ?>><?php echo escapeValue($label); ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="full-width">
						<label class="checkbox-label" for="terms">
							<input type="checkbox" id="terms" name="terms" value="1"<?php echo $formData["terms"] === "1" ? " checked" : ""; ?>>
							I confirm that the information entered is correct.
						</label>
					</div>
				</div>
				<button type="submit">Submit Information</button>
			</form>
		</section>
	</main>

	<footer>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>