<?php

session_start();

require_once __DIR__ . '/../database/client.php';
require_once __DIR__ . '/../database/function.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (($_SESSION['user_role'] ?? '') === 'admin') {
    header('Location: ../index.php#admin');
    exit;
}

ensureBusinessRelationships(createDatabaseConnection());

function clientConnection(): PDO
{
    static $connection;
    return $connection ??= createDatabaseConnection();
}

function clientUser(): array
{
    return $_SESSION['user'] ?? [];
}

function clientCustomer(): array
{
    $user = clientUser();
    $statement = clientConnection()->prepare(
        'SELECT c.id, c.name, c.email, c.phone, c.address
         FROM customers c
         WHERE c.user_id = :user_id OR c.email = :email
         ORDER BY c.user_id DESC LIMIT 1'
    );
    $statement->execute([
        'user_id' => (int) ($_SESSION['user_id'] ?? 0),
        'email' => $user['email'] ?? '',
    ]);
    return $statement->fetch() ?: [
        'id' => null,
        'name' => $user['name'] ?? '',
        'email' => $user['email'] ?? '',
        'phone' => '',
        'address' => '',
    ];
}

function clientBookings(): array
{
    $customer = clientCustomer();
    $statement = clientConnection()->prepare(
        'SELECT b.id, b.customer_name, b.customer_email, b.event_date, b.event_time,
                b.guests, b.total, b.status, b.created_at, p.name AS package_name
         FROM bookings b
         LEFT JOIN products p ON p.id = b.product_id
         WHERE b.customer_id = :customer_id OR b.customer_email = :email
         ORDER BY b.created_at DESC'
    );
    $statement->execute([
        'customer_id' => $customer['id'] ?: 0,
        'email' => $customer['email'],
    ]);
    return $statement->fetchAll();
}

function clientEscape(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function clientPageStart(string $title, string $active): void
{
    $user = clientUser();
    $nav = [
        'dashboard' => 'dashboard.php',
        'requests' => 'my-requests.php',
        'services' => 'request-services.php',
        'profile' => 'profile.php',
    ];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo clientEscape($title); ?> | Every Event</title>
        <link rel="stylesheet" href="../index.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    <body class="client-portal">
        <header class="client-header">
            <a class="client-brand" href="dashboard.php"><img src="../logo.image.png" alt="Every Event logo"><strong>Every Event</strong></a>
            <nav class="client-nav" aria-label="Client navigation">
                <?php foreach ($nav as $key => $url): ?>
                    <a class="<?php echo $active === $key ? 'active' : ''; ?>" href="<?php echo $url; ?>"><?php echo $key === 'requests' ? 'My Requests' : ucfirst($key); ?></a>
                <?php endforeach; ?>
            </nav>
            <div class="client-account"><span><?php echo clientEscape($user['name'] ?? 'Customer'); ?></span><a href="../logout.php">LOG OUT</a></div>
        </header>
        <main class="client-main">
    <?php
}

function clientPageEnd(): void
{
    ?>
        </main>
        <footer class="client-footer">Every Event Catering Services</footer>
    </body>
    </html>
    <?php
}
