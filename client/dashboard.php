<?php
require_once __DIR__ . '/_layout.php';
$customer = clientCustomer();
$bookings = clientBookings();
clientPageStart('Client Dashboard', 'dashboard');
?>
<section class="client-hero">
	<div><p class="hero-label">EVERY EVENT CLIENT PORTAL</p><h1>Welcome back, <?php echo clientEscape($customer['name']); ?>.</h1><p>Keep track of your event requests and manage your catering details in one place.</p></div>
	<a class="btn" href="request-services.php">REQUEST A SERVICE</a>
</section>
<section class="client-stat-grid">
	<article><span>TOTAL REQUESTS</span><strong><?php echo count($bookings); ?></strong></article>
	<article><span>PENDING</span><strong><?php echo count(array_filter($bookings, fn ($booking) => $booking['status'] === 'Pending')); ?></strong></article>
	<article><span>UPCOMING EVENTS</span><strong><?php echo count(array_filter($bookings, fn ($booking) => $booking['event_date'] >= date('Y-m-d'))); ?></strong></article>
</section>
<section class="client-panel">
	<div class="client-panel-heading"><div><p class="hero-label">RECENT ACTIVITY</p><h2>My requests</h2></div><a class="text-link" href="my-requests.php">VIEW ALL &rarr;</a></div>
	<?php if (!$bookings): ?><p class="client-empty">You have no requests yet. Start by choosing a catering service.</p><?php else: ?>
		<?php foreach (array_slice($bookings, 0, 3) as $booking): ?>
			<a class="client-request-row" href="request-detail.php?id=<?php echo (int) $booking['id']; ?>"><div><strong><?php echo clientEscape($booking['package_name'] ?: 'Catering request'); ?></strong><span><?php echo clientEscape($booking['event_date']); ?> &middot; <?php echo (int) $booking['guests']; ?> guests</span></div><em class="client-status status-<?php echo strtolower(clientEscape($booking['status'])); ?>"><?php echo clientEscape($booking['status']); ?></em></a>
		<?php endforeach; ?>
	<?php endif; ?>
</section>
<?php clientPageEnd(); ?>
