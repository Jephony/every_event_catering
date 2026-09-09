<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please log in before booking.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed.']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true) ?: [];
$name = trim((string) ($payload['name'] ?? ''));
$email = trim((string) ($payload['email'] ?? ''));
$date = trim((string) ($payload['date'] ?? ''));
$time = trim((string) ($payload['time'] ?? ''));
$packageName = trim((string) ($payload['package_name'] ?? ''));
$guests = filter_var($payload['guests'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$total = filter_var($payload['total'] ?? null, FILTER_VALIDATE_FLOAT);

$dateIsValid = DateTime::createFromFormat('Y-m-d', $date);
$timeIsValid = DateTime::createFromFormat('H:i', $time);

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$dateIsValid || $dateIsValid->format('Y-m-d') !== $date || !$timeIsValid || $guests === false || $total === false || $total < 0 || $packageName === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Please provide valid booking details.']);
    exit;
}

try {
    require_once __DIR__ . '/client.php';
    $connection = createDatabaseConnection();

    $product = $connection->prepare(
        'SELECT id FROM products WHERE name = :name LIMIT 1'
    );
    $product->execute(['name' => $packageName]);
    $productId = $product->fetchColumn();

    if (!$productId) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'The selected package is no longer available.']);
        exit;
    }

    $customer = $connection->prepare(
        'SELECT id FROM customers WHERE user_id = :user_id OR email = :email LIMIT 1'
    );
    $customer->execute([
        'user_id' => (int) $_SESSION['user_id'],
        'email' => $email,
    ]);
    $customerId = $customer->fetchColumn();

    if (!$customerId) {
        $newCustomer = $connection->prepare(
            'INSERT INTO customers (user_id, name, email) VALUES (:user_id, :name, :email)'
        );
        $newCustomer->execute([
            'user_id' => (int) $_SESSION['user_id'] ?: null,
            'name' => $name,
            'email' => $email,
        ]);
        $customerId = $connection->lastInsertId();
    }

    $booking = $connection->prepare(
        'INSERT INTO bookings
            (customer_id, product_id, customer_name, customer_email, event_date, event_time, guests, total, status)
         VALUES
            (:customer_id, :product_id, :customer_name, :customer_email, :event_date, :event_time, :guests, :total, "Pending")'
    );
    $booking->execute([
        'customer_id' => $customerId,
        'product_id' => $productId,
        'customer_name' => $name,
        'customer_email' => $email,
        'event_date' => $date,
        'event_time' => $time,
        'guests' => $guests,
        'total' => $total,
    ]);

    echo json_encode([
        'success' => true,
        'booking' => [
            'id' => (int) $connection->lastInsertId(),
            'name' => $name,
            'email' => $email,
            'date' => $date,
            'time' => $time,
            'guests' => $guests,
            'total' => $total,
            'status' => 'Pending',
            'package_name' => $packageName,
        ],
    ]);
} catch (PDOException $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Could not save the booking to the database.']);
}
