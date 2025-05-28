<?php
/**
 * Summary of namespace Imjolwp\Admin\Settings
 */
namespace Imjolwp\Admin\Settings;
class Imjolwp_Ai_Automation_For_Wordpress_Settings {

    /**
	 * Display the settings page.
	 *
	 * @since 1.0.0
	 */
	public function display_settings_page() {
		?>
			<div class="wrap">
				<h1>Deepinfra API Settings</h1>
				<p>Configure the Deepinfra API settings.</p>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'imjolwp_ai_options_group' ); // Register settings group
					do_settings_sections( 'imjolwp-ai-settings' ); // Display settings sections
					submit_button();
					?>
				</form>
			</div>
    	<?php
	}
}