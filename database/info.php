<?php

function getCustomerInformation(PDO $connection, int $customerId): ?array
{
	$statement = $connection->prepare(
		'SELECT id, name, email, phone, address
		 FROM customers
		 WHERE id = :customer_id
		 LIMIT 1'
	);

	$statement->execute(['customer_id' => $customerId]);
	$customer = $statement->fetch(PDO::FETCH_ASSOC);

	return $customer ?: null;
}
