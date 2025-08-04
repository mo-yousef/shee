<?php
/**
 * Template Name: Reset Password
 *
 * @package SheCy
 */

$message = '';
$error = '';

// Check if a key and login are present, which means we are in the password reset phase.
if ( isset( $_GET['key'] ) && isset( $_GET['login'] ) ) {
	$rp_key = $_GET['key'];
	$rp_login = $_GET['login'];
	$user = check_password_reset_key( $rp_key, $rp_login );

	if ( ! $user || is_wp_error( $user ) ) {
		$error = 'The password reset link is invalid or has expired.';
	}
}

// Handle form submissions
if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	// Handle the request form
	if ( isset( $_POST['action'] ) && $_POST['action'] == 'retrieve-password' ) {
		$user_login = sanitize_text_field( $_POST['user_login'] );
		$result = retrieve_password( $user_login );
		if ( is_wp_error( $result ) ) {
			$error = $result->get_error_message();
		} else {
			$message = 'Please check your email for a link to reset your password.';
		}
	}
	// Handle the reset form
	elseif ( isset( $_POST['action'] ) && $_POST['action'] == 'reset-password' ) {
		if ( isset( $_POST['pass1'] ) && isset( $_POST['pass2'] ) && $_POST['pass1'] === $_POST['pass2'] ) {
			// The check_password_reset_key() function is called again here
			// to ensure the key is still valid before resetting the password.
			$user = check_password_reset_key( $_POST['rp_key'], $_POST['rp_login'] );
			if($user && !is_wp_error($user)) {
				reset_password( $user, $_POST['pass1'] );
				$message = 'Your password has been reset. You can now <a href="' . home_url('/login') . '">log in</a>.';
			} else {
				$error = 'The password reset link is invalid or has expired.';
			}
		} else {
			$error = 'The passwords you entered do not match.';
		}
	}
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
			<header class="text-center mb-8">
				<h1 class="text-3xl font-bold">Reset Password</h1>
			</header>

			<?php if ( ! empty( $message ) ) : ?>
				<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
					<p><?php echo $message; ?></p>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $error ) ) : ?>
				<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
					<p><?php echo $error; ?></p>
				</div>
			<?php endif; ?>

			<?php
			// State 2: Display the password reset form.
			if ( isset( $_GET['key'] ) && isset( $_GET['login'] ) && ( ! $user || ! is_wp_error( $user ) ) && empty($message) ) :
			?>
				<form id="reset-password-form" method="post">
					<input type="hidden" name="action" value="reset-password">
					<input type="hidden" name="rp_key" value="<?php echo esc_attr( $_GET['key'] ); ?>">
					<input type="hidden" name="rp_login" value="<?php echo esc_attr( $_GET['login'] ); ?>">
					<div class="space-y-6">
						<div>
							<label for="pass1">New Password</label>
							<input type="password" name="pass1" id="pass1" required class="mt-1 block w-full border-gray-300 rounded-md">
						</div>
						<div>
							<label for="pass2">Confirm New Password</label>
							<input type="password" name="pass2" id="pass2" required class="mt-1 block w-full border-gray-300 rounded-md">
						</div>
					</div>
					<div class="mt-8">
						<button type="submit" class="w-full bg-violet-500 text-white py-3 rounded-md">Reset Password</button>
					</div>
				</form>
			<?php
			// State 1: Display the password retrieval form.
			elseif ( empty( $message ) ):
			?>
				<p class="text-center mb-6">Please enter your username or email address. You will receive a link to create a new password via email.</p>
				<form id="retrieve-password-form" method="post">
					<input type="hidden" name="action" value="retrieve-password">
					<div class="space-y-6">
						<div>
							<label for="user_login">Username or Email</label>
							<input type="text" name="user_login" id="user_login" required class="mt-1 block w-full border-gray-300 rounded-md">
						</div>
					</div>
					<div class="mt-8">
						<button type="submit" class="w-full bg-violet-500 text-white py-3 rounded-md">Get New Password</button>
					</div>
				</form>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php
get_footer();
?>
