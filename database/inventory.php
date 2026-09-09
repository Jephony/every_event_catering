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

$payload = json_decode(file_get_contents('php://input'), true);
$inventoryId = filter_var($payload['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$adjustment = filter_var($payload['adjustment'] ?? null, FILTER_VALIDATE_INT);

if ($inventoryId === false || $adjustment === false || $adjustment === 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Inventory ID and adjustment must be valid whole numbers.']);
    exit;
}

try {
    require_once __DIR__ . '/client.php';
    $connection = createDatabaseConnection();
    $connection->beginTransaction();
    $findItem = $connection->prepare(
        'SELECT stock FROM inventory WHERE id = :id FOR UPDATE'
    );
    $findItem->execute(['id' => $inventoryId]);
    $item = $findItem->fetch();

    if (!$item) {
        $connection->rollBack();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Inventory item not found.']);
        exit;
    }

    $newStock = (int) $item['stock'] + $adjustment;
    if ($newStock < 0) {
        $connection->rollBack();
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Cannot remove more stock than is available.']);
        exit;
    }

    $updateItem = $connection->prepare(
        'UPDATE inventory SET stock = :stock WHERE id = :id'
    );
    $updateItem->execute(['stock' => $newStock, 'id' => $inventoryId]);
    $connection->commit();

    echo json_encode(['success' => true, 'stock' => $newStock]);
} catch (PDOException $exception) {
    if (isset($connection) && $connection->inTransaction()) {
        $connection->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Could not update the inventory database.']);
}
