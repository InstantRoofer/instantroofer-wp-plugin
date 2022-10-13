<?php
/**
* Plugin Name:       Instant Roofer Booking Engine
* Plugin URI:        https://instantroofer.com/integrations/wordpress-plugin
* Description:       Embed the Instant Roofer Booking Engine on your WP site.
* Version:           1.10.6
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Charles Koehl
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Update URI:        https://instantroofer.com/integrations/wordpress-plugin
* Text Domain:       instant-roofer-plugin
* Domain Path:       /languages
*/

const uuidv4Pattern = "/[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-(:?8|9|A|B)[a-f0-9]{3}-[a-f0-9]{12}/gi";

$anchors = [
    'Anchor text 1',
    'Anchor text 2',
    'Anchor text 3',
    'Anchor text 4',
    'Anchor text 5',
    'Anchor text 6',
    'Anchor text 7',
    'Anchor text 8',
    'Anchor text 9',
    'Anchor text 10',
];

/**
 * /**
 * The [instantroofer] shortcode.
 *
 * Accepts a title and will display a box.
 *
 * @param array  $atts    Shortcode attributes. Default empty.
 * @return string Shortcode output.
 */
function instantroofer_shortcode( $atts = [] ) {
	// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );

	if(preg_match(uuidv4Pattern, $atts['id']) === 0) {
        return '<p>You need to add your instantroofer "id" to the "instantroofer" shortcode. You get the id from your <a href="https://account.instantroofer.com">account profile</a></p>.';
    }

	// override default attributes with user attributes
	$ir_atts = shortcode_atts(
		array(
			'width' => 1024,
			'height' => 1024,
		), $atts
	);

	return <<<STR
        <div class="instantroofer-container">
            <iframe
                id="instantroofer-iframe"
                title="Instant Roofer Booking Engine"
                src="https://book.instantroofer.com"></iframe>"
                width="$ir_atts.width"
                height="$ir_atts.height"
            ></iframe>
            <p><a href="https://instantroofer.com"></a></p>
        </div>
STR;
}

/**
 * Central location to create all shortcodes.
 */
function instantroofer_shortcodes_init() {
	add_shortcode( 'instantroofer', 'instantroofer_shortcode' );
}

add_action( 'init', 'instantroofer_shortcodes_init' );