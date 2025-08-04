<?php
/**
 * Template Name: Profile Settings
 *
 * @package SheCy
 */

// Redirect non-logged-in users.
if ( ! is_user_logged_in() ) {
	wp_redirect( home_url( '/login' ) );
	exit;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Handle form submission
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'update-profile' ) {
	// Security check
	if ( ! isset( $_POST['profile_nonce'] ) || ! wp_verify_nonce( $_POST['profile_nonce'], 'update_profile_' . $user_id ) ) {
		wp_die( 'Security check failed.' );
	}

	// Update profile information
	if(isset($_POST['update_info'])) {
		// Sanitize and update user data
		$display_name = sanitize_text_field($_POST['display_name']);
		$email = sanitize_email($_POST['user_email']);
		$description = sanitize_textarea_field($_POST['description']);

		$user_data = array(
			'ID' => $user_id,
			'display_name' => $display_name,
			'user_email' => $email,
			'description' => $description,
		);
		wp_update_user($user_data);
	}

	// Update password
	if(isset($_POST['update_password'])) {
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];

		if ( !empty($pass1) && !empty($pass2) && $pass1 === $pass2 ) {
			wp_set_password($pass1, $user_id);
			// Log the user in again after password change.
			wp_set_current_user($user_id);
			wp_set_auth_cookie($user_id);
		}
	}

	// Redirect back to the profile page with a success message
	wp_redirect( home_url('/profile-settings?updated=true') );
	exit;
}


get_header();

// Get fresh user data for display
$user_info = get_userdata($user_id);
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-2xl mx-auto">
			<header class="text-center mb-8">
				<h1 class="text-3xl font-bold">Profile Settings</h1>
			</header>

			<?php if ( isset($_GET['updated']) && $_GET['updated'] == 'true' ): ?>
				<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
					<p>Profile updated successfully!</p>
				</div>
			<?php endif; ?>

			<form id="profile-form" method="post">
				<input type="hidden" name="action" value="update-profile">
				<?php wp_nonce_field( 'update_profile_' . $user_id, 'profile_nonce' ); ?>

				<div class="bg-white p-8 rounded-lg shadow-md mb-8">
					<h2 class="text-2xl font-bold mb-6">Your Information</h2>
					<div class="space-y-6">
						<div>
							<label class="block text-sm font-medium text-gray-700">Avatar</label>
							<div class="mt-2 flex items-center">
								<?php echo get_avatar($user_id, 96, '', 'User Avatar', ['class' => 'rounded-full']); ?>
								<p class="ml-4 text-gray-600">You can change your profile picture on <a href="https://gravatar.com/" target="_blank" class="text-pink-500 hover:underline">Gravatar</a>.</p>
							</div>
						</div>
						<div>
							<label for="display_name" class="block text-sm font-medium text-gray-700">Display Name</label>
							<input type="text" name="display_name" id="display_name" value="<?php echo esc_attr($user_info->display_name); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
						</div>
						<div>
							<label for="user_email" class="block text-sm font-medium text-gray-700">Email Address</label>
							<input type="email" name="user_email" id="user_email" value="<?php echo esc_attr($user_info->user_email); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
						</div>
						<div>
							<label for="description" class="block text-sm font-medium text-gray-700">Biographical Info</label>
							<textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><?php echo esc_textarea($user_info->description); ?></textarea>
							<p class="mt-2 text-sm text-gray-500">Share a little about yourself. This will be displayed on your author profile.</p>
						</div>
					</div>
					<div class="mt-8">
						<button type="submit" name="update_info" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-pink-500 hover:bg-pink-600">Update Profile</button>
					</div>
				</div>

				<div class="bg-white p-8 rounded-lg shadow-md">
					<h2 class="text-2xl font-bold mb-6">Change Password</h2>
					<p class="text-sm text-gray-600 mb-4">If you would like to change your password, enter a new one below. Otherwise, leave these fields blank.</p>
					<div class="space-y-6">
						<div>
							<label for="pass1" class="block text-sm font-medium text-gray-700">New Password</label>
							<input type="password" name="pass1" id="pass1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
						</div>
						<div>
							<label for="pass2" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
							<input type="password" name="pass2" id="pass2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
						</div>
					</div>
					<div class="mt-8">
						<button type="submit" name="update_password" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-gray-800 hover:bg-gray-900">Change Password</button>
					</div>
				</div>

			</form>
		</div>
	</div>
</main>

<?php
get_footer();
?>
