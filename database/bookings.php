<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please log in first.']);
    exit;
}

try {
    require_once __DIR__ . '/client.php';
    $connection = createDatabaseConnection();
    $query = 'SELECT b.id, b.customer_name AS name, b.customer_email AS email,
                     b.event_date AS date, b.event_time AS time, b.guests, b.total,
                     b.status, COALESCE(p.name, "Essential Setup Packages") AS package_name
              FROM bookings b
              LEFT JOIN products p ON p.id = b.product_id';
    $parameters = [];

    if (($_SESSION['user_role'] ?? '') !== 'admin') {
        $query .= ' WHERE b.customer_email = :customer_email';
        $parameters['customer_email'] = $_SESSION['user']['email'] ?? '';
    }

    $query .= ' ORDER BY b.created_at DESC';
    $statement = $connection->prepare($query);
    $statement->execute($parameters);

    echo json_encode(['success' => true, 'bookings' => $statement->fetchAll()]);
} catch (PDOException $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Could not load bookings from the database.']);
}
