<?php

require_once __DIR__ . '/login.php';

function loginAndStartSession(PDO $connection, string $email, string $password): bool
{
	$user = authenticateUser($connection, $email, $password);

	if (!$user) {
		return false;
	}

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	session_regenerate_id(true);
	$_SESSION['user'] = $user;
	$_SESSION['user_id'] = $user['id'];
	$_SESSION['user_role'] = $user['role'];

	return true;
}

function loginAsLocalAdmin(string $email, string $password): bool
{
	$adminEmail = 'admin@everyevent.com';
	$adminPasswordHash = '$2y$10$MEWjdFvwfcLgpB3pOW4Dd.PoERmKrxKh4D1lY0fTzbarRota5.bES';

	if (strtolower(trim($email)) !== $adminEmail || !password_verify($password, $adminPasswordHash)) {
		return false;
	}

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	session_regenerate_id(true);
	$_SESSION['user'] = [
		'id' => 0,
		'name' => 'Admin',
		'email' => $adminEmail,
		'role' => 'admin',
	];
	$_SESSION['user_id'] = 0;
	$_SESSION['user_role'] = 'admin';

	return true;
}

function loginAsLocalClient(string $email, string $password): bool
{
	$clientEmail = 'jephonybalbuena3@gmail.com';
	$clientPasswordHash = '$2y$10$MEWjdFvwfcLgpB3pOW4Dd.PoERmKrxKh4D1lY0fTzbarRota5.bES';

	if (strtolower(trim($email)) !== $clientEmail || !password_verify($password, $clientPasswordHash)) {
		return false;
	}

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	session_regenerate_id(true);
	$_SESSION['user'] = [
		'id' => 1,
		'name' => 'Jephony Balbuena',
		'email' => $clientEmail,
		'role' => 'customer',
	];
	$_SESSION['user_id'] = 1;
	$_SESSION['user_role'] = 'customer';

	return true;
}
