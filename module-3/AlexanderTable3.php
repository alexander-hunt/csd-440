<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Summed Random Number Table</title>
</head>
<body>
	<header>
		<h1>Summed Random Number Table</h1>
	</header>

	<main>
		<section>
			<h2>PHP-Generated Sums</h2>

			<?php
			require_once __DIR__ . "/AlexanderFunctions.php";

			$rows = 10;
			$columns = 10;
			$minimumValue = 1;
			$maximumValue = 100;
			?>

			<table>
				<caption>A 10 by 10 table of sums from two random numbers</caption>
				<thead>
					<tr>
						<th scope="col">Row</th>
						<?php for ($column = 1; $column <= $columns; $column++) { ?>
							<th scope="col">Column <?php echo $column; ?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php for ($row = 1; $row <= $rows; $row++) { ?>
						<tr>
							<th scope="row">Row <?php echo $row; ?></th>
							<?php for ($column = 1; $column <= $columns; $column++) { ?>
								<?php
								$firstRandomNumber = random_int($minimumValue, $maximumValue);
								$secondRandomNumber = random_int($minimumValue, $maximumValue);
								$cellValue = addRandomNumbers($firstRandomNumber, $secondRandomNumber);
								?>
								<td><?php echo $cellValue; ?></td>
							<?php } ?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</section>
	</main>

	<footer>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>
