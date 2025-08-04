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
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'shecy-login' ) {
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
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">
		<div class="shecy-max-w-md shecy-mx-auto shecy-bg-white shecy-p-8 shecy-rounded-lg shecy-shadow-md">
			<header class="shecy-text-center shecy-mb-8">
				<h1 class="shecy-text-3xl shecy-font-bold">Log In</h1>
				<p class="shecy-text-gray-600 shecy-mt-2">Welcome back! Access your dashboard.</p>
			</header>

			<?php if ( $login_error ) : ?>
				<div class="shecy-bg-red-100 shecy-border-l-4 shecy-border-red-500 shecy-text-red-700 shecy-p-4 shecy-mb-6" role="alert">
					<p><?php echo $login_error; ?></p>
				</div>
			<?php endif; ?>

			<form id="login-form" method="post">
				<input type="hidden" name="action" value="shecy-login">
				<?php wp_nonce_field( 'shecy_login_action', 'login_nonce' ); ?>

				<div class="shecy-space-y-6">
					<div>
						<label for="log" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Username or Email Address</label>
						<input type="text" name="log" id="log" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
					</div>

					<div>
						<label for="pwd" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Password</label>
						<input type="password" name="pwd" id="pwd" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
					</div>

					<div class="shecy-flex shecy-items-center shecy-justify-between">
						<div class="shecy-flex shecy-items-center">
							<input id="rememberme" name="rememberme" type="checkbox" class="shecy-h-4 shecy-w-4 shecy-text-pink-600 shecy-border-gray-300 shecy-rounded">
							<label for="rememberme" class="shecy-ml-2 shecy-block shecy-text-sm shecy-text-gray-900">Remember Me</label>
						</div>

						<div class="shecy-text-sm">
							<a href="<?php echo home_url('/reset-password'); ?>" class="shecy-font-medium shecy-text-pink-600 hover:shecy-text-pink-500">Forgot your password?</a>
						</div>
					</div>
				</div>

				<div class="shecy-mt-8">
					<button type="submit" class="shecy-w-full shecy-inline-flex shecy-justify-center shecy-py-3 shecy-px-4 shecy-border shecy-border-transparent shecy-shadow-sm shecy-text-base shecy-font-medium shecy-rounded-md shecy-text-white shecy-bg-pink-500 hover:shecy-bg-pink-600">Log In</button>
				</div>

				<div class="shecy-text-center shecy-mt-6">
					<p class="shecy-text-sm shecy-text-gray-600">
						Don't have an account?
						<a href="<?php echo home_url('/register'); ?>" class="shecy-font-medium shecy-text-pink-600 hover:shecy-text-pink-500">
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
