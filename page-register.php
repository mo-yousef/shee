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
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'register' ) {
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
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
			<header class="text-center mb-8">
				<h1 class="text-3xl font-bold">Create an Account</h1>
				<p class="text-gray-600 mt-2">Join the She Cy community.</p>
			</header>

			<?php if ( $reg_errors->has_errors() ) : ?>
				<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
					<?php foreach ( $reg_errors->get_error_messages() as $error ) : ?>
						<p><?php echo $error; ?></p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<form id="register-form" method="post">
				<input type="hidden" name="action" value="register">
				<?php wp_nonce_field( 'shecy_register_action', 'register_nonce' ); ?>

				<div class="space-y-6">
					<div>
						<label for="user_login" class="block text-sm font-medium text-gray-700">Username</label>
						<input type="text" name="user_login" id="user_login" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
					</div>
					<div>
						<label for="user_email" class="block text-sm font-medium text-gray-700">Email Address</label>
						<input type="email" name="user_email" id="user_email" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
					</div>
					<div>
						<label for="user_pass1" class="block text-sm font-medium text-gray-700">Password</label>
						<input type="password" name="user_pass1" id="user_pass1" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
					</div>
					<div>
						<label for="user_pass2" class="block text-sm font-medium text-gray-700">Confirm Password</label>
						<input type="password" name="user_pass2" id="user_pass2" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
					</div>
				</div>

				<div class="mt-8">
					<button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-pink-500 hover:bg-pink-600">Register</button>
				</div>

				<div class="text-center mt-6">
					<p class="text-sm text-gray-600">
						Already have an account?
						<a href="<?php echo home_url('/login'); ?>" class="font-medium text-pink-600 hover:text-pink-500">
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
