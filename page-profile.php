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
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">
		<div class="shecy-max-w-2xl shecy-mx-auto">
			<header class="shecy-text-center shecy-mb-8">
				<h1 class="shecy-text-3xl shecy-font-bold">Profile Settings</h1>
			</header>

			<?php if ( isset($_GET['updated']) && $_GET['updated'] == 'true' ): ?>
				<div class="shecy-bg-green-100 shecy-border-l-4 shecy-border-green-500 shecy-text-green-700 shecy-p-4 shecy-mb-6" role="alert">
					<p>Profile updated successfully!</p>
				</div>
			<?php endif; ?>

			<form id="profile-form" method="post">
				<input type="hidden" name="action" value="update-profile">
				<?php wp_nonce_field( 'update_profile_' . $user_id, 'profile_nonce' ); ?>

				<div class="shecy-bg-white shecy-p-8 shecy-rounded-lg shecy-shadow-md shecy-mb-8">
					<h2 class="shecy-text-2xl shecy-font-bold shecy-mb-6">Your Information</h2>
					<div class="shecy-space-y-6">
						<div>
							<label class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Avatar</label>
							<div class="shecy-mt-2 shecy-flex shecy-items-center">
								<?php echo get_avatar($user_id, 96, '', 'User Avatar', ['class' => 'shecy-rounded-full']); ?>
								<p class="shecy-ml-4 shecy-text-gray-600">You can change your profile picture on <a href="https://gravatar.com/" target="_blank" class="shecy-text-pink-500 hover:shecy-underline">Gravatar</a>.</p>
							</div>
						</div>
						<div>
							<label for="display_name" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Display Name</label>
							<input type="text" name="display_name" id="display_name" value="<?php echo esc_attr($user_info->display_name); ?>" class="shecy-mt-1 shecy-block shecy-w-full shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
						</div>
						<div>
							<label for="user_email" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Email Address</label>
							<input type="email" name="user_email" id="user_email" value="<?php echo esc_attr($user_info->user_email); ?>" class="shecy-mt-1 shecy-block shecy-w-full shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
						</div>
						<div>
							<label for="description" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Biographical Info</label>
							<textarea name="description" id="description" rows="4" class="shecy-mt-1 shecy-block shecy-w-full shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm"><?php echo esc_textarea($user_info->description); ?></textarea>
							<p class="shecy-mt-2 shecy-text-sm shecy-text-gray-500">Share a little about yourself. This will be displayed on your author profile.</p>
						</div>
					</div>
					<div class="shecy-mt-8">
						<button type="submit" name="update_info" class="shecy-w-full shecy-inline-flex shecy-justify-center shecy-py-3 shecy-px-4 shecy-border shecy-border-transparent shecy-shadow-sm shecy-text-base shecy-font-medium shecy-rounded-md shecy-text-white shecy-bg-pink-500 hover:shecy-bg-pink-600">Update Profile</button>
					</div>
				</div>

				<div class="shecy-bg-white shecy-p-8 shecy-rounded-lg shecy-shadow-md">
					<h2 class="shecy-text-2xl shecy-font-bold shecy-mb-6">Change Password</h2>
					<p class="shecy-text-sm shecy-text-gray-600 shecy-mb-4">If you would like to change your password, enter a new one below. Otherwise, leave these fields blank.</p>
					<div class="shecy-space-y-6">
						<div>
							<label for="pass1" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">New Password</label>
							<input type="password" name="pass1" id="pass1" class="shecy-mt-1 shecy-block shecy-w-full shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
						</div>
						<div>
							<label for="pass2" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Confirm New Password</label>
							<input type="password" name="pass2" id="pass2" class="shecy-mt-1 shecy-block shecy-w-full shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
						</div>
					</div>
					<div class="shecy-mt-8">
						<button type="submit" name="update_password" class="shecy-w-full shecy-inline-flex shecy-justify-center shecy-py-3 shecy-px-4 shecy-border shecy-border-transparent shecy-shadow-sm shecy-text-base shecy-font-medium shecy-rounded-md shecy-text-white shecy-bg-gray-800 hover:shecy-bg-gray-900">Change Password</button>
					</div>
				</div>

			</form>
		</div>
	</div>
</main>

<?php
get_footer();
?>
