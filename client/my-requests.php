<?php
require_once __DIR__ . '/_layout.php';
$bookings = clientBookings();
clientPageStart('My Requests', 'requests');
?>
<section class="client-page-heading"><p class="hero-label">CLIENT PORTAL</p><h1>My Requests</h1><p>Review the catering requests connected to your account.</p></section>
<section class="client-panel">
	<?php if (!$bookings): ?><p class="client-empty">No requests found.</p><?php else: ?>
		<?php foreach ($bookings as $booking): ?>
			<a class="client-request-row" href="request-detail.php?id=<?php echo (int) $booking['id']; ?>"><div><strong><?php echo clientEscape($booking['package_name'] ?: 'Catering request'); ?></strong><span>Event: <?php echo clientEscape($booking['event_date']); ?> at <?php echo clientEscape($booking['event_time']); ?> &middot; <?php echo (int) $booking['guests']; ?> guests</span></div><em class="client-status status-<?php echo strtolower(clientEscape($booking['status'])); ?>"><?php echo clientEscape($booking['status']); ?></em></a>
		<?php endforeach; ?>
	<?php endif; ?>
</section>
<?php clientPageEnd(); ?>
