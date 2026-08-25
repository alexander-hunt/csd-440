<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Palindrome Checker</title>
</head>
<body>
	<header>
		<h1>Palindrome Checker</h1>
	</header>

	<main>
		<section>
			<h2>Test Results</h2>

			<?php
			function isPalindrome(string $value): bool
			{
				// Compare only letters/digits, case-insensitively, so phrases like "A man a plan..." still qualify.
				$normalized = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $value));
				return $normalized === strrev($normalized);
			}

			function displayPalindromeResult(string $original): void
			{
				$reversed = strrev($original);
				$result = isPalindrome($original) ? "Palindrome" : "Not a Palindrome";
				?>
				<p>
					Original: "<?php echo htmlspecialchars($original, ENT_QUOTES, "UTF-8"); ?>"
					| Reversed: "<?php echo htmlspecialchars($reversed, ENT_QUOTES, "UTF-8"); ?>"
					| Result: <?php echo htmlspecialchars($result, ENT_QUOTES, "UTF-8"); ?>
				</p>
				<?php
			}

			$testStrings = [
				"racecar",
				"level",
				"A man a plan a canal Panama",
				"hello",
				"php",
				"programming",
			];

			foreach ($testStrings as $testString) {
				displayPalindromeResult($testString);
			}
			?>
		</section>
	</main>

</body>
</html>
