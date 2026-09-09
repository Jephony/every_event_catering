<?php

require_once __DIR__ . '/client.php';

function authenticateUser(PDO $connection, string $email, string $password): ?array
{
	$statement = $connection->prepare(
		'SELECT id, name, email, password, role
		 FROM users
		 WHERE email = :email
		 LIMIT 1'
	);

	$statement->execute(['email' => trim($email)]);
	$user = $statement->fetch();

	if (!$user || !password_verify($password, $user['password'])) {
		return null;
	}

	unset($user['password']);
	return $user;
}
