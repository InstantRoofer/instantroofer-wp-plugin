<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

const FONT_FAMILIES = [
    'Arial, Helvetica Neue, Helvetica, sans-serif.',
    'Baskerville, Baskerville Old Face, Garamond, Times N',
    'Bodoni MT, Bodoni 72, Didot, Didot LT STD, Hoefler T',
    'Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sa',
    'Calisto MT, Bookman Old Style, Bookman, Goudy Old St',
    'Cambria, Georgia, serif.',
    'Candara, Calibri, Segoe, Segoe UI, Optima, Arial, sa',
    'Century Gothic, CenturyGothic, AppleGothic, sans-ser',
    'Consolas, monaco, monospace.',
    'Copperplate, Copperplate Gothic Light, fantasy.',
    'Courier New, Courier, Lucida Sans Typewriter, Lucida',
    'Dejavu Sans, Arial, Verdana, sans-serif.',
    'Didot, Didot LT STD, Hoefler Text, Garamond, Calisto',
    'Franklin Gothic, Arial Bold.',
    'Garamond, Baskerville, Baskerville Old Face, Hoefler',
    'Georgia, Times, Times New Roman, serif.',
    'Gill Sans, Gill Sans MT, Calibri, sans-serif.',
    'Goudy Old Style, Garamond, Big Caslon, Times New Rom',
    'Helvetica Neue, Helvetica, Arial, sans-serif.',
    'Impact, Charcoal, Helvetica Inserat, Bitstream Vera ',
    'Lucida Bright, Georgia, serif.',
    'Lucida Sans, Helvetica, Arial, sans-serif.',
    'MS Sans Serif, sans-serif.',
    'Optima, Segoe, Segoe UI, Candara, Calibri, Arial, sa',
    'Palatino, Palatino Linotype, Palatino LT STD, Book A',
    'Perpetua, Baskerville, Big Caslon, Palatino Linotype',
    'Rockwell, Courier Bold, Courier, Georgia, Times, Tim',
    'Segoe UI, Frutiger, Dejavu Sans, Helvetica Neue, Ari',
    'Tahoma, Verdana, Segoe, sans-serif.',
    'Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lu',
    'Verdana, Geneva, sans-serif.',
];

/**
 * custom option and settings
 */
function instantroofer_settings_init() {
    // Register a new setting for "general" page.
    register_setting( 'general', 'instantroofer_options' );

    // Register a new section in the "general" page.
    add_settings_section(
        'instantroofer_section_developers',
        __( 'General Settings', 'general' ),
        'instantroofer_section_developers_callback',
        'general'
    );

    // Register a new field in the "instantroofer_section_developers" section, inside the "general" page.
    add_settings_field(
        'instantroofer_field_font_family', // As of WP 4.6 this value is used only internally.
        // Use $args' label_for to populate the id inside the callback.
        __( 'Font', 'general' ),
        'instantroofer_field_font_family_cb',
        'general',
        'instantroofer_section_developers',
        array(
            'label_for'         => 'instantroofer_field_font_family',
            'class'             => 'instantroofer_row',
            'instantroofer_custom_data' => 'custom',
        )
    );
}

/**
 * Register our instantroofer_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'instantroofer_settings_init' );


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function instantroofer_section_developers_callback( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Customize the Instant Roofer booking wizard.', 'general' ); ?></p>
    <?php
}

/**
 * Font_family field callback function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function instantroofer_field_font_family_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'instantroofer_options' );
    ?>
    <select
        id="<?php echo esc_attr( $args['label_for'] ); ?>"
        data-custom="<?php echo esc_attr( $args['instantroofer_custom_data'] ); ?>"
        name="instantroofer_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
    >
        <?php
            foreach (FONT_FAMILIES as $stack) {
                $stackName = explode(',', $stack)[0];
                $selectedAttr = isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], $stack, false)) : ('');
                echo <<<STR
                    <option value="$stack" $selectedAttr>$stackName</option>
STR;
            }
        ?>
    </select>
    <?php
}

/**
 * Add the top level menu page.
 */
function instantroofer_options_page() {
    add_menu_page(
        'Instantroofer',
        'Instant Roofer',
        'manage_options',
        'general',
        'instantroofer_options_page_html'
    );
}


/**
 * Register our instantroofer_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'instantroofer_options_page' );


/**
 * Top level menu callback function
 */
function instantroofer_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'instantroofer_messages', 'instantroofer_message', __( 'Settings Saved', 'general' ), 'updated' );
    }

    // show error/update messages
    settings_errors( 'instantroofer_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "general"
            settings_fields( 'general' );
            // output setting sections and their fields
            // (sections are registered for "general", each field is registered to a specific section)
            do_settings_sections( 'general' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}