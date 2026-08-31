<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's MyInteger Class</title>
</head>
<body>
	<header>
		<h1>MyInteger Class</h1>
	</header>

	<main>
		<?php
		/*
		 * Student: Alexander Hunt
		 * Course: CSD-440 Server-Side Scripting
		 * File: AlexanderMyInteger.php
		 * Date: August 31, 2026
		 * Purpose: Define and test a class that stores and evaluates an integer.
		 */

		class MyInteger
		{
			private int $value;

			public function __construct(int $value)
			{
				$this->value = $value;
			}

			public function isEven(int $number): bool
			{
				return $number % 2 === 0;
			}

			public function isOdd(int $number): bool
			{
				return $number % 2 !== 0;
			}

			public function isPrime(): bool
			{
				if ($this->value < 2) {
					return false;
				}

				for ($divisor = 2; $divisor * $divisor <= $this->value; $divisor++) {
					if ($this->value % $divisor === 0) {
						return false;
					}
				}

				return true;
			}

			public function getValue(): int
			{
				return $this->value;
			}

			public function setValue(int $value): void
			{
				$this->value = $value;
			}
		}

		function displayBooleanResult(bool $result): string
		{
			return $result ? "Yes" : "No";
		}

		$firstInteger = new MyInteger(17);
		$secondInteger = new MyInteger(24);
		$secondIntegerPrimeBeforeUpdate = $secondInteger->isPrime();
		$secondInteger->setValue(29);
		?>

		<section>
			<h2>First Instance</h2>
			<p>Stored value: <?php echo htmlspecialchars((string) $firstInteger->getValue(), ENT_QUOTES, "UTF-8"); ?></p>
			<p>Is 18 even? <?php echo htmlspecialchars(displayBooleanResult($firstInteger->isEven(18)), ENT_QUOTES, "UTF-8"); ?></p>
			<p>Is 17 odd? <?php echo htmlspecialchars(displayBooleanResult($firstInteger->isOdd(17)), ENT_QUOTES, "UTF-8"); ?></p>
			<p>Is the stored value prime? <?php echo htmlspecialchars(displayBooleanResult($firstInteger->isPrime()), ENT_QUOTES, "UTF-8"); ?></p>
		</section>

		<section>
			<h2>Second Instance</h2>
			<p>Original stored value: 24</p>
			<p>Is 24 even? <?php echo htmlspecialchars(displayBooleanResult($secondInteger->isEven(24)), ENT_QUOTES, "UTF-8"); ?></p>
			<p>Is 24 odd? <?php echo htmlspecialchars(displayBooleanResult($secondInteger->isOdd(24)), ENT_QUOTES, "UTF-8"); ?></p>
			<p>Was the original stored value prime? <?php echo htmlspecialchars(displayBooleanResult($secondIntegerPrimeBeforeUpdate), ENT_QUOTES, "UTF-8"); ?></p>
			<p>Stored value after setter: <?php echo htmlspecialchars((string) $secondInteger->getValue(), ENT_QUOTES, "UTF-8"); ?></p>
			<p>Is the updated stored value prime? <?php echo htmlspecialchars(displayBooleanResult($secondInteger->isPrime()), ENT_QUOTES, "UTF-8"); ?></p>
		</section>
	</main>

	<footer>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>