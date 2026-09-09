<?php
require_once __DIR__ . '/_layout.php';
$statement = clientConnection()->prepare(
	'SELECT b.*, p.name AS package_name FROM bookings b LEFT JOIN products p ON p.id = b.product_id
	 WHERE b.id = :id AND (b.customer_id = :customer_id OR b.customer_email = :email) LIMIT 1'
);
$customer = clientCustomer();
$statement->execute(['id' => (int) ($_GET['id'] ?? 0), 'customer_id' => $customer['id'] ?: 0, 'email' => $customer['email']]);
$booking = $statement->fetch();
clientPageStart('Request Details', 'requests');
?>
<a class="client-back-link" href="my-requests.php">&larr; BACK TO MY REQUESTS</a>
<?php if (!$booking): ?><section class="client-panel"><h1>Request not found</h1><p class="client-empty">This request does not belong to your account or no longer exists.</p></section><?php else: ?>
<section class="client-page-heading"><p class="hero-label">REQUEST #<?php echo (int) $booking['id']; ?></p><h1><?php echo clientEscape($booking['package_name'] ?: 'Catering request'); ?></h1><span class="client-status status-<?php echo strtolower(clientEscape($booking['status'])); ?>"><?php echo clientEscape($booking['status']); ?></span></section>
<section class="client-detail-grid"><article class="client-panel"><h2>Event details</h2><dl class="client-details"><dt>Customer</dt><dd><?php echo clientEscape($booking['customer_name']); ?></dd><dt>Email</dt><dd><?php echo clientEscape($booking['customer_email']); ?></dd><dt>Event date</dt><dd><?php echo clientEscape($booking['event_date']); ?></dd><dt>Event time</dt><dd><?php echo clientEscape($booking['event_time']); ?></dd><dt>Guests</dt><dd><?php echo (int) $booking['guests']; ?></dd></dl></article><article class="client-panel client-total"><span>ESTIMATED TOTAL</span><strong>&#8369;<?php echo number_format((float) $booking['total'], 2); ?></strong><p>Our team will contact you with the next steps for this request.</p></article></section>
<?php endif; ?>
<?php clientPageEnd(); ?>
