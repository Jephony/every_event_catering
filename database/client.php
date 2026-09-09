<?php

function createDatabaseConnection(
	string $host = '127.0.0.1',
	string $database = 'every_event_catering',
	string $username = 'root',
	string $password = '',
	int $port = 4306
): PDO {
	$dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

	return new PDO($dsn, $username, $password, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	]);
}
