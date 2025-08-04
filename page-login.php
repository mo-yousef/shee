<?php
/**
 * Template Name: Login
 *
 * @package SheCy
 */

// If user is already logged in, redirect to dashboard.
if ( is_user_logged_in() ) {
	wp_redirect( home_url( '/dashboard' ) );
	exit;
}

$login_error = null;

// Handle form submission
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'login' ) {
	// Security check
	if ( ! isset( $_POST['login_nonce'] ) || ! wp_verify_nonce( $_POST['login_nonce'], 'shecy_login_action' ) ) {
		wp_die( 'Security check failed.' );
	}

	$creds = array();
	$creds['user_login']    = sanitize_user( $_POST['log'] );
	$creds['user_password'] = $_POST['pwd'];
	$creds['remember']      = isset( $_POST['rememberme'] );

	// Attempt to sign the user on
	$user = wp_signon( $creds, false );

	if ( is_wp_error( $user ) ) {
		$login_error = $user->get_error_message();
	} else {
		// Success! Redirect to the dashboard.
		wp_redirect( home_url( '/dashboard' ) );
		exit;
	}
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
			<header class="text-center mb-8">
				<h1 class="text-3xl font-bold">Log In</h1>
				<p class="text-gray-600 mt-2">Welcome back! Access your dashboard.</p>
			</header>

			<?php if ( $login_error ) : ?>
				<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
					<p><?php echo $login_error; ?></p>
				</div>
			<?php endif; ?>

			<form id="login-form" method="post">
				<input type="hidden" name="action" value="login">
				<?php wp_nonce_field( 'shecy_login_action', 'login_nonce' ); ?>

				<div class="space-y-6">
					<div>
						<label for="log" class="block text-sm font-medium text-gray-700">Username or Email Address</label>
						<input type="text" name="log" id="log" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
					</div>

					<div>
						<label for="pwd" class="block text-sm font-medium text-gray-700">Password</label>
						<input type="password" name="pwd" id="pwd" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
					</div>

					<div class="flex items-center justify-between">
						<div class="flex items-center">
							<input id="rememberme" name="rememberme" type="checkbox" class="h-4 w-4 text-pink-600 border-gray-300 rounded">
							<label for="rememberme" class="ml-2 block text-sm text-gray-900">Remember Me</label>
						</div>

						<div class="text-sm">
							<a href="<?php echo home_url('/reset-password'); ?>" class="font-medium text-pink-600 hover:text-pink-500">Forgot your password?</a>
						</div>
					</div>
				</div>

				<div class="mt-8">
					<button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-pink-500 hover:bg-pink-600">Log In</button>
				</div>

				<div class="text-center mt-6">
					<p class="text-sm text-gray-600">
						Don't have an account?
						<a href="<?php echo home_url('/register'); ?>" class="font-medium text-pink-600 hover:text-pink-500">
							Register here
						</a>
					</p>
				</div>
			</form>
		</div>
	</div>
</main>

<?php
get_footer();
?>
