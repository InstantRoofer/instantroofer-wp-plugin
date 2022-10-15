<?php
/**
* Plugin Name:       Instant Roofer Booking Engine
* Plugin URI:        https://instantroofer.com/integrations/wordpress-plugin
* Description:       Embed the Instant Roofer Booking Engine on your WP site.
* Version:           1.10.36
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Charles Koehl
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Update URI:        https://instantroofer.com/integrations/wordpress-plugin
* Text Domain:       instant-roofer-plugin
* Domain Path:       /languages
*/

require_once('custom-settings-page.php');

const ANCHORS = [
    'online roof quote',
    'free roof quote',
    'Instant Roofer',
    'roof replacement cost',
    'roofing calculator',
    'cost of roof replacement',
    'cost to replace roof'
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

    $settings = get_option('instantroofer_options');

    $accountId = $settings['instantroofer_field_account_id'];

    if(!$accountId) {
        return <<<STR
            <p>Your Instant Roofer account ID is missing or invalid in the plugin settings.</p>
STR;
    }
    $anchorText = get_anchor_text($accountId);
    $width = $settings['instantroofer_field_width'];
    $widthAttr = $width.'px';
    $height = $settings['instantroofer_field_height'];
    $heightAttr = $height.'px';
    $spinnerUrl = plugins_url('assets/Iphone-spinner-2.gif', __FILE__);

    $iframeQueryStringVals = array(
        'id' => $accountId,
        'fontFamily' => $settings['instantroofer_field_font_family'],
    );

    $iframeQueryString = http_build_query($iframeQueryStringVals);

	return <<<STR
        <div
            class="instantroofer-container"
            style="width: $widthAttr; height: $heightAttr; background-image: url('$spinnerUrl'); background-repeat: no-repeat; background-position: center;"
        >
            <iframe
                id="instantroofer-iframe"
                title="Instant Roofer Booking Engine"
                src="https://book.instantroofer.com?$$iframeQueryString"
                width="$widthAttr"
                height="$heightAttr"
            ></iframe>
            <p><a href="https://instantroofer.com" target="_blank">$anchorText</a></p>
        </div>
STR;
}

/**
 * Central location to create all shortcodes.
 */
function instantroofer_shortcodes_init() {
	add_shortcode( 'instantroofer', 'instantroofer_shortcode' );
}

add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');
add_action( 'init', 'instantroofer_shortcodes_init' );