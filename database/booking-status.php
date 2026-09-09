<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

if (($_SESSION['user_role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Admin access is required.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed.']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true) ?: [];
$bookingId = filter_var($payload['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$status = $payload['status'] ?? '';
$allowedStatuses = ['Pending', 'Confirmed', 'Completed'];

if ($bookingId === false || !in_array($status, $allowedStatuses, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid booking or status.']);
    exit;
}

try {
    require_once __DIR__ . '/client.php';
    $connection = createDatabaseConnection();
    $statement = $connection->prepare(
        'UPDATE bookings SET status = :status WHERE id = :id'
    );
    $statement->execute(['status' => $status, 'id' => $bookingId]);

    if ($statement->rowCount() === 0) {
        $check = $connection->prepare('SELECT id FROM bookings WHERE id = :id LIMIT 1');
        $check->execute(['id' => $bookingId]);
        if (!$check->fetch()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Booking not found.']);
            exit;
        }
    }

    echo json_encode(['success' => true, 'status' => $status]);
} catch (PDOException $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Could not update the booking status.']);
}
