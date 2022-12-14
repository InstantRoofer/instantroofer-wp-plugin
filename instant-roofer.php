<?php
/**
* Plugin Name:       Instant Roofer Roofing Calculator
* Plugin URI:        https://instantroofer.com/integrations/wordpress-plugin
* Description:       Provide instant roof quotes online for the entire United States. Embed with shortcode to instantly provide roof replacement quotes.
* Version:           1.11.2
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Instant Roofer
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Update URI:        https://instantroofer.com/integrations/wordpress-plugin
* Text Domain:       instant-roofer-plugin
* Domain Path:       /languages
*/

require_once('custom-settings-page.php');

const ANCHORS = [
    'Online Roof Quote',
    'Free Roof Quote',
    'Instant Roofer',
    'Roof Replacement Cost',
    'Roofing Calculator',
    'Cost of Roof Replacement',
    'Cost to Replace Roof'
];

function get_anchor_text($id) {
    $i = intval(count(ANCHORS)/16*hexdec($id[0]));
    return ANCHORS[$i];
}

function callback_for_setting_up_scripts() {
    wp_register_style( 'instantroofer', plugins_url('styles/style.css', __FILE__) );
    wp_enqueue_style( 'instantroofer' );
}

/**
 * /**
 * The [instantroofer] shortcode.
 *
 * @return string Shortcode output.
 */
function instantroofer_shortcode() {

    $options = get_option('instantroofer_options');

    $accountId = $options['instantroofer_field_account_id'];

    if(!$accountId) {
        return <<<STR
            <p>Your Instant Roofer account ID is missing or invalid in the plugin settings.</p>
STR;
    }
    $anchorText = get_anchor_text($accountId);
    $spinnerUrl = plugins_url('assets/Iphone-spinner-2.gif', __FILE__);

    $iframeQueryStringVals = array(
        'id' => $accountId,
        'fontFamily' => $options['instantroofer_field_font_family'],
        'fontColor' => $options['instantroofer_field_font_color'],
        'primaryColor' => $options['instantroofer_field_primary_color'],
        'secondaryColor' => $options['instantroofer_field_secondary_color'],
        'backgroundColor' => $options['instantroofer_field_background_color'],
        'appearanceMode' => $options['instantroofer_field_appearance_mode'],
    );

    $iframeQueryString = http_build_query($iframeQueryStringVals);

    $output = <<<STR
	    <iframe
	        id="instantroofer-iframe"
	        title="Instant Roofer Booking Engine"
	        src="https://book.instantroofer.com?$$iframeQueryString"
	        style="border:0; aspect-ratio: 0.6761; height: 100%; width: 100%; background-image: url('$spinnerUrl'); background-repeat: no-repeat; background-position: center;"
	    ></iframe>
	    <p style="text-align:center"><a href="https://www.instantroofer.com" target="_blank">$anchorText</a> - Instant Roofer</p>
STR;
//	$output .= var_export($options, true);
    return $output;
}

/**
 * Central location to create all shortcodes.
 */
function instantroofer_shortcodes_init() {
	add_shortcode( 'instantroofer', 'instantroofer_shortcode' );
}

add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');
add_action( 'init', 'instantroofer_shortcodes_init' );