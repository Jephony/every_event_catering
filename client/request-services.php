<?php
require_once __DIR__ . '/_layout.php';
$message = null;
$error = null;
$customer = clientCustomer();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
	$date = trim($_POST['event_date'] ?? '');
	$time = trim($_POST['event_time'] ?? '');
	$guests = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
	try {
		$product = clientConnection()->prepare('SELECT id, name, price FROM products WHERE id = :id AND status = "Active" LIMIT 1');
		$product->execute(['id' => $productId]);
		$selectedProduct = $product->fetch();
		$validDate = DateTime::createFromFormat('Y-m-d', $date);
		if (!$selectedProduct || !$validDate || $validDate->format('Y-m-d') !== $date || !$guests || $time === '') {
			throw new InvalidArgumentException('Please complete all request details.');
		}
		$insert = clientConnection()->prepare(
			'INSERT INTO bookings (customer_id, product_id, customer_name, customer_email, event_date, event_time, guests, total, status)
			 VALUES (:customer_id, :product_id, :name, :email, :event_date, :event_time, :guests, :total, "Pending")'
		);
		$insert->execute(['customer_id' => $customer['id'] ?: null, 'product_id' => $selectedProduct['id'], 'name' => $customer['name'], 'email' => $customer['email'], 'event_date' => $date, 'event_time' => $time, 'guests' => $guests, 'total' => (float) $selectedProduct['price'] * $guests]);
		header('Location: request-detail.php?id=' . clientConnection()->lastInsertId());
		exit;
	} catch (Throwable $exception) {
		$error = $exception->getMessage();
	}
}
$products = clientConnection()->query('SELECT id, name, price FROM products WHERE status = "Active" ORDER BY price ASC')->fetchAll();
clientPageStart('Request a Service', 'services');
?>
<section class="client-page-heading"><p class="hero-label">EVERY EVENT CATERING</p><h1>Request a service</h1><p>Choose a package and tell us when your event is happening.</p></section>
<?php if ($error): ?><div class="client-alert client-alert-error"><?php echo clientEscape($error); ?></div><?php endif; ?>
<section class="client-form-layout"><div class="client-service-list"><?php foreach ($products as $product): ?><article class="client-service-option"><div><h2><?php echo clientEscape($product['name']); ?></h2><p>Professional catering support tailored to your event.</p></div><strong>&#8369;<?php echo number_format((float) $product['price'], 2); ?><small>/ person</small></strong></article><?php endforeach; ?></div><form class="client-panel client-form" method="post"><label for="product_id">Package<select id="product_id" name="product_id" required><?php foreach ($products as $product): ?><option value="<?php echo (int) $product['id']; ?>"><?php echo clientEscape($product['name']); ?> - &#8369;<?php echo number_format((float) $product['price'], 2); ?></option><?php endforeach; ?></select></label><label for="event_date">Event date<input id="event_date" name="event_date" type="date" min="<?php echo date('Y-m-d'); ?>" required></label><label for="event_time">Event time<input id="event_time" name="event_time" type="time" required></label><label for="guests">Number of guests<input id="guests" name="guests" type="number" min="1" required></label><button class="btn" type="submit">SEND REQUEST</button></form></section>
<?php clientPageEnd(); ?>
