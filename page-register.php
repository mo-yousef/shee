<?php
/**
 * Template Name: Register
 *
 * @package SheCy
 */

// If user is already logged in, redirect to dashboard.
if ( is_user_logged_in() ) {
	wp_redirect( home_url( '/dashboard' ) );
	exit;
}

$reg_errors = new WP_Error;

// Handle form submission
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'shecy-register' ) {
	if ( ! isset( $_POST['register_nonce'] ) || ! wp_verify_nonce( $_POST['register_nonce'], 'shecy_register_action' ) ) {
		wp_die( 'Security check failed.' );
	}

	$user_login = sanitize_user( $_POST['user_login'] );
	$user_email = sanitize_email( $_POST['user_email'] );
	$user_pass1 = $_POST['user_pass1'];
	$user_pass2 = $_POST['user_pass2'];

	// --- Validation ---
	if ( empty( $user_login ) || empty( $user_email ) || empty( $user_pass1 ) || empty( $user_pass2 ) ) {
		$reg_errors->add( 'field', 'All fields are required.' );
	}
	if ( username_exists( $user_login ) ) {
		$reg_errors->add( 'username_exists', 'Sorry, that username already exists!' );
	}
	if ( ! is_email( $user_email ) ) {
		$reg_errors->add( 'email_invalid', 'Please enter a valid email address.' );
	}
	if ( email_exists( $user_email ) ) {
		$reg_errors->add( 'email_exists', 'Sorry, that email address is already in use!' );
	}
	if ( $user_pass1 !== $user_pass2 ) {
		$reg_errors->add( 'password_mismatch', 'Passwords do not match.' );
	}

	// If no errors, create the user
	if ( ! $reg_errors->has_errors() ) {
		$userdata = array(
			'user_login' => $user_login,
			'user_email' => $user_email,
			'user_pass'  => $user_pass1,
			'display_name' => $user_login,
		);
		$user_id = wp_insert_user( $userdata );

		if ( ! is_wp_error( $user_id ) ) {
			// Log the user in
			wp_set_current_user( $user_id, $user_login );
			wp_set_auth_cookie( $user_id );
			// Redirect to the dashboard
			wp_redirect( home_url( '/dashboard' ) );
			exit;
		} else {
			$reg_errors->add('wp_error', $user_id->get_error_message());
		}
	}
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">
		<div class="shecy-max-w-md shecy-mx-auto shecy-bg-white shecy-p-8 shecy-rounded-lg shecy-shadow-md">
			<header class="shecy-text-center shecy-mb-8">
				<h1 class="shecy-text-3xl shecy-font-bold">Create an Account</h1>
				<p class="shecy-text-gray-600 shecy-mt-2">Join the She Cy community.</p>
			</header>

			<?php if ( $reg_errors->has_errors() ) : ?>
				<div class="shecy-bg-red-100 shecy-border-l-4 shecy-border-red-500 shecy-text-red-700 shecy-p-4 shecy-mb-6" role="alert">
					<?php foreach ( $reg_errors->get_error_messages() as $error ) : ?>
						<p><?php echo $error; ?></p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<form id="register-form" method="post">
				<input type="hidden" name="action" value="shecy-register">
				<?php wp_nonce_field( 'shecy_register_action', 'register_nonce' ); ?>

				<div class="shecy-space-y-6">
					<div>
						<label for="user_login" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Username</label>
						<input type="text" name="user_login" id="user_login" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
					</div>
					<div>
						<label for="user_email" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Email Address</label>
						<input type="email" name="user_email" id="user_email" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
					</div>
					<div>
						<label for="user_pass1" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Password</label>
						<input type="password" name="user_pass1" id="user_pass1" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
					</div>
					<div>
						<label for="user_pass2" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Confirm Password</label>
						<input type="password" name="user_pass2" id="user_pass2" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
					</div>
				</div>

				<div class="shecy-mt-8">
					<button type="submit" class="shecy-w-full shecy-inline-flex shecy-justify-center shecy-py-3 shecy-px-4 shecy-border shecy-border-transparent shecy-shadow-sm shecy-text-base shecy-font-medium shecy-rounded-md shecy-text-white shecy-bg-pink-500 hover:shecy-bg-pink-600">Register</button>
				</div>

				<div class="shecy-text-center shecy-mt-6">
					<p class="shecy-text-sm shecy-text-gray-600">
						Already have an account?
						<a href="<?php echo home_url('/login'); ?>" class="shecy-font-medium shecy-text-pink-600 hover:shecy-text-pink-500">
							Log in here
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
