<?php

function ensureUsersTable(PDO $connection): void
{
	$connection->exec(
		'CREATE TABLE IF NOT EXISTS users (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(120) NOT NULL,
			email VARCHAR(190) NOT NULL UNIQUE,
			password VARCHAR(255) NOT NULL,
			role VARCHAR(30) NOT NULL DEFAULT "customer",
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
	);
}

function createUser(
	PDO $connection,
	string $name,
	string $email,
	string $password,
	string $role = 'customer'
): int {
	if (strlen($password) < 6) {
		throw new InvalidArgumentException('Password must be at least 6 characters.');
	}

	$statement = $connection->prepare(
		'INSERT INTO users (name, email, password, role)
		 VALUES (:name, :email, :password, :role)'
	);

	$statement->execute([
		'name' => trim($name),
		'email' => strtolower(trim($email)),
		'password' => password_hash($password, PASSWORD_DEFAULT),
		'role' => $role,
	]);

	return (int) $connection->lastInsertId();
}

function ensureDefaultAdmin(
	PDO $connection,
	string $email = 'admin@everyevent.com',
	string $password = '1324veamea'
): void {
	$findUser = $connection->prepare(
		'SELECT id, password FROM users WHERE email = :email LIMIT 1'
	);
	$findUser->execute(['email' => strtolower(trim($email))]);
	$admin = $findUser->fetch(PDO::FETCH_ASSOC);

	if (!$admin) {
		createUser($connection, 'Admin', $email, $password, 'admin');
		return;
	}

	if (!password_verify($password, $admin['password'])) {
		$updatePassword = $connection->prepare(
			'UPDATE users
			 SET password = :password, role = "admin"
			 WHERE id = :id'
		);
		$updatePassword->execute([
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'id' => $admin['id'],
		]);
	}
}

function getAllCustomers(PDO $connection): array
{
	$statement = $connection->query(
		'SELECT id, name, email, phone, address
		 FROM customers
		 ORDER BY name ASC'
	);

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function ensureBusinessRelationships(PDO $connection): void
{
	$hasColumn = static function (string $table, string $column) use ($connection): bool {
		$statement = $connection->prepare(
			'SELECT COUNT(*) FROM information_schema.COLUMNS
			 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table_name AND COLUMN_NAME = :column_name'
		);
		$statement->execute(['table_name' => $table, 'column_name' => $column]);
		return (bool) $statement->fetchColumn();
	};

	$hasConstraint = static function (string $table, string $constraint) use ($connection): bool {
		$statement = $connection->prepare(
			'SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
			 WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = :table_name AND CONSTRAINT_NAME = :constraint_name'
		);
		$statement->execute(['table_name' => $table, 'constraint_name' => $constraint]);
		return (bool) $statement->fetchColumn();
	};

	if (!$hasColumn('products', 'service_id')) {
		$connection->exec('ALTER TABLE products ADD service_id INT UNSIGNED NULL AFTER id');
	}
	if (!$hasColumn('inventory', 'supplier_id')) {
		$connection->exec('ALTER TABLE inventory ADD supplier_id INT UNSIGNED NULL AFTER id');
	}

	$connection->exec(
		'UPDATE products p
		 JOIN services s ON s.name = CASE p.name
			WHEN "Essential Setup Packages" THEN "Customizable Catering Packages"
			WHEN "Signature Full-Service" THEN "Event Planning Support"
			WHEN "Bespoke & Specialty" THEN "Specialty Menus"
		 END
		 SET p.service_id = s.id
		 WHERE p.service_id IS NULL'
	);
	$connection->exec(
		'UPDATE inventory i
		 JOIN suppliers s ON s.category = CASE
			WHEN LOWER(i.name) LIKE "%ingredient%" THEN "Ingredients"
			ELSE "Equipment"
		 END
		 SET i.supplier_id = s.id
		 WHERE i.supplier_id IS NULL'
	);

	if (!$hasConstraint('products', 'fk_products_service')) {
		$connection->exec(
			'ALTER TABLE products ADD CONSTRAINT fk_products_service
			 FOREIGN KEY (service_id) REFERENCES services(id)
			 ON DELETE SET NULL ON UPDATE CASCADE'
		);
	}
	if (!$hasConstraint('inventory', 'fk_inventory_supplier')) {
		$connection->exec(
			'ALTER TABLE inventory ADD CONSTRAINT fk_inventory_supplier
			 FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
			 ON DELETE SET NULL ON UPDATE CASCADE'
		);
	}
}
