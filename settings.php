<?php

function wistiapress_settings_init() {

	add_settings_section(
		'wistiapress_settings_section_api',
		'API Key',
		'wistiapress_api_settings_callback',
		__FILE__
	);

	add_settings_field(
		'wistiapress_api_key',
		'Wistia API Key',
		'wistiapress_settings_apikey_callback',
		__FILE__,
		'wistiapress_settings_section_api'
	);

	register_setting('wistiapress_settings_group',  'wistiapress_api_key' );
}
add_action( 'admin_init', 'wistiapress_settings_init' );


// ------------------------------------------------------------------
// Settings section callback function
// ------------------------------------------------------------------
//
// This function is needed if we added a new section. This function
// will be run at the start of our section
//

function wistiapress_api_settings_callback() {
	echo '<p>Enter the API Key from the Wistia Account Dashboard.  This is required to access the data in your account from WordPress.</p>';
}

// ------------------------------------------------------------------
// Callback function for our example setting
// ------------------------------------------------------------------
//
// creates a checkbox true/false option. Other types are surely possible
//

function wistiapress_settings_apikey_callback() {
	echo '<input name="wistiapress_api_key" id="wistiapress_api_key" size="50" value=" ' . esc_attr(get_option( 'wistiapress_api_key' ) ) . '" />';
}



add_action('admin_menu', 'wistiapress_settings_menu');
// Add sub page to the Settings Menu
function wistiapress_settings_menu() {
	add_options_page('wistiapress', 'WistiaPress', 'administrator', __FILE__, 'wistiapress_options_page_callback');
}

function wistiapress_options_page_callback() { ?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>WistiaPress</h2>
		<form action="options.php" method="post">
			<?php settings_fields('wistiapress_settings_group'); ?>
			<?php do_settings_sections(__FILE__); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
		</form>
	</div>
<?php
}
