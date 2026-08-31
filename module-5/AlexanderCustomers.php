<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alexander's Customers</title>
</head>
<body>
	<header>
		<h1>Customer Records</h1>
	</header>

	<main>
		<?php
		/*
		 * Student: Alexander Hunt
		 * Course: CSD-440 Server-Side Scripting
		 * File: AlexanderCustomers.php
		 * Date: August 31, 2026
		 * Purpose: Create customer records and locate records using array methods.
		 */

		function displayCustomerTable(array $customers): void
		{
			if (count($customers) === 0) {
				echo "<p>No customers were found.</p>";
				return;
			}
			?>
			<table>
				<thead>
					<tr>
						<th scope="col">First Name</th>
						<th scope="col">Last Name</th>
						<th scope="col">Age</th>
						<th scope="col">Phone Number</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($customers as $customer) { ?>
						<tr>
							<td><?php echo htmlspecialchars($customer["firstName"], ENT_QUOTES, "UTF-8"); ?></td>
							<td><?php echo htmlspecialchars($customer["lastName"], ENT_QUOTES, "UTF-8"); ?></td>
							<td><?php echo htmlspecialchars((string) $customer["age"], ENT_QUOTES, "UTF-8"); ?></td>
							<td><?php echo htmlspecialchars($customer["phoneNumber"], ENT_QUOTES, "UTF-8"); ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php
		}

		$customers = [
			["firstName" => "Amelia", "lastName" => "Bennett", "age" => 29, "phoneNumber" => "555-0101"],
			["firstName" => "Benjamin", "lastName" => "Carter", "age" => 42, "phoneNumber" => "555-0102"],
			["firstName" => "Chloe", "lastName" => "Davis", "age" => 35, "phoneNumber" => "555-0103"],
			["firstName" => "Daniel", "lastName" => "Evans", "age" => 29, "phoneNumber" => "555-0104"],
			["firstName" => "Elena", "lastName" => "Foster", "age" => 51, "phoneNumber" => "555-0105"],
			["firstName" => "Felix", "lastName" => "Garcia", "age" => 38, "phoneNumber" => "555-0106"],
			["firstName" => "Grace", "lastName" => "Hughes", "age" => 24, "phoneNumber" => "555-0107"],
			["firstName" => "Henry", "lastName" => "Irving", "age" => 42, "phoneNumber" => "555-0108"],
			["firstName" => "Isla", "lastName" => "Johnson", "age" => 31, "phoneNumber" => "555-0109"],
			["firstName" => "James", "lastName" => "King", "age" => 46, "phoneNumber" => "555-0110"],
		];

		$customersNamedAmelia = array_values(array_filter(
			$customers,
			fn(array $customer): bool => $customer["firstName"] === "Amelia"
		));
		$customersAgeFortyTwo = array_values(array_filter(
			$customers,
			fn(array $customer): bool => $customer["age"] === 42
		));
		$customerWithPhoneNumber = array_values(array_filter(
			$customers,
			fn(array $customer): bool => $customer["phoneNumber"] === "555-0105"
		));
		$customersNamedOlivia = array_values(array_filter(
			$customers,
			fn(array $customer): bool => $customer["firstName"] === "Olivia"
		));
		?>

		<section>
			<h2>All Customers</h2>
			<?php displayCustomerTable($customers); ?>
		</section>

		<section>
			<h2>Search by First Name: Amelia</h2>
			<?php displayCustomerTable($customersNamedAmelia); ?>
		</section>

		<section>
			<h2>Search by Age: 42</h2>
			<?php displayCustomerTable($customersAgeFortyTwo); ?>
		</section>

		<section>
			<h2>Search by Phone Number: 555-0105</h2>
			<?php displayCustomerTable($customerWithPhoneNumber); ?>
		</section>

		<section>
			<h2>Search by First Name: Olivia</h2>
			<?php displayCustomerTable($customersNamedOlivia); ?>
		</section>
	</main>

	<footer>
		<p>Created for CSD-440.</p>
	</footer>
</body>
</html>