<?php
require_once __DIR__ . '/_layout.php';
$customer = clientCustomer();
$message = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = trim($_POST['name'] ?? '');
	$phone = trim($_POST['phone'] ?? '');
	$address = trim($_POST['address'] ?? '');
	if ($name === '') {
		$error = 'Name is required.';
	} else {
		$connection = clientConnection();
		if ($customer['id']) {
			$update = $connection->prepare('UPDATE customers SET name = :name, phone = :phone, address = :address WHERE id = :id');
			$update->execute(['name' => $name, 'phone' => $phone, 'address' => $address, 'id' => $customer['id']]);
		} else {
			$insert = $connection->prepare('INSERT INTO customers (user_id, name, email, phone, address) VALUES (:user_id, :name, :email, :phone, :address)');
			$insert->execute(['user_id' => $_SESSION['user_id'], 'name' => $name, 'email' => $customer['email'], 'phone' => $phone, 'address' => $address]);
		}
		$_SESSION['user']['name'] = $name;
		$customer = clientCustomer();
		$message = 'Profile updated successfully.';
	}
}
clientPageStart('My Profile', 'profile');
?>
<section class="client-page-heading"><p class="hero-label">ACCOUNT SETTINGS</p><h1>My Profile</h1><p>Keep your contact details ready for event coordination.</p></section>
<?php if ($message): ?><div class="client-alert client-alert-success"><?php echo clientEscape($message); ?></div><?php endif; ?>
<?php if ($error): ?><div class="client-alert client-alert-error"><?php echo clientEscape($error); ?></div><?php endif; ?>
<form class="client-panel client-form client-profile-form" method="post"><label for="name">Full name<input id="name" name="name" value="<?php echo clientEscape($customer['name']); ?>" required></label><label for="email">Email address<input id="email" value="<?php echo clientEscape($customer['email']); ?>" disabled></label><label for="phone">Phone number<input id="phone" name="phone" value="<?php echo clientEscape($customer['phone']); ?>"></label><label for="address">Address<textarea id="address" name="address" rows="4"><?php echo clientEscape($customer['address']); ?></textarea></label><button class="btn" type="submit">SAVE PROFILE</button></form>
<?php clientPageEnd(); ?>
