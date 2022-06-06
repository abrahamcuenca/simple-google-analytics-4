<?php
/*
 * Plugin Name: Simple Google Analytics 4
 * Plugin URI: https://github.com/abrahamcuenca/simple-google-analytics-4
 * Description: A simple Google Analytics 4 plugin
 * Version: 1.0.0
 * Requiers at least: 5.2
 * Requires PHP: 7.1
 * Author: Abraham Cuenca
 * Author URI: https://abrahamcuenca.com
 * License: Apache-2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Update URI: https://github.com/abrahamcuenca/simple-google-analytics-4
 * Text Domain: base-plugin
 * Domain Path /plugins
 */
/*
   Copyright 2022 Abraham Cuenca

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License. 
 */

add_action( 'admin_menu', 'sg4a_add_admin_menu' );
add_action( 'admin_init', 'sg4a_settings_init' );


function sg4a_add_admin_menu(  ) { 

	add_menu_page( 'Simple Google Analytics 4 Plugin', 'Simple G4A Plugin', 'manage_options', 'simple_google_analytics_4_plugin', 'sg4a_options_page' );

}


function sg4a_settings_init(  ) { 

	register_setting( 'pluginPage', 'sg4a_settings' );

	add_settings_section(
		'sg4a_pluginPage_section', 
		__( 'Global Site Tag', 'sg4a' ), 
		'sg4a_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'sg4a_measurement_id', 
		__( 'Measurement ID', 'sg4a' ), 
		'sg4a_measurement_id_render', 
		'pluginPage', 
		'sg4a_pluginPage_section' 
	);


}


function sg4a_measurement_id_render(  ) { 

	$options = get_option( 'sg4a_settings' );
	?>
	<input type='text' name='sg4a_settings[sg4a_measurement_id]' value='<?php echo $options['sg4a_measurement_id']; ?>'>
	<?php

}


function sg4a_settings_section_callback(  ) { 

	echo __( 'The Measurement ID can be retrieved from the Tagging Instructions in your Google Analytics Dashboard.', 'sg4a' );

}


function sg4a_options_page(  ) { 

		?>
		<form action='options.php' method='post'>

			<h2>Simple Google Analytics v4 plugin</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php
}


add_action(
    'wp_head',
    'sga4_add_js_snippet'
);


/**
 * Add the Google Analytics v4 Snippet to the header
 */
function sga4_add_js_snippet() {
    $options = get_option('sg4a_settings');
    $measurement_id = $options['sg4a_measurement_id'];
?>
<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $measurement_id ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $measurement_id ?>');
</script>

<?php 
}

