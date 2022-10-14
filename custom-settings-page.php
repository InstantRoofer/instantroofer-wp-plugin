<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function instantroofer_settings_init() {
    // Register a new setting for "instantroofer" page.
    register_setting( 'instantroofer', 'instantroofer_options' );

    // Register a new section in the "instantroofer" page.
    add_settings_section(
        'instantroofer_section_developers',
        __( 'The Matrix has you.', 'instantroofer' ), 'instantroofer_section_developers_callback',
        'instantroofer'
    );

    // Register a new field in the "instantroofer_section_developers" section, inside the "instantroofer" page.
    add_settings_field(
        'instantroofer_field_pill', // As of WP 4.6 this value is used only internally.
        // Use $args' label_for to populate the id inside the callback.
        __( 'Pill', 'instantroofer' ),
        'instantroofer_field_pill_cb',
        'instantroofer',
        'instantroofer_section_developers',
        array(
            'label_for'         => 'instantroofer_field_pill',
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
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'instantroofer' ); ?></p>
    <?php
}

/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function instantroofer_field_pill_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'instantroofer_options' );
    ?>
    <select
        id="<?php echo esc_attr( $args['label_for'] ); ?>"
        data-custom="<?php echo esc_attr( $args['instantroofer_custom_data'] ); ?>"
        name="instantroofer_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
        <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'red pill', 'instantroofer' ); ?>
        </option>
        <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'blue pill', 'instantroofer' ); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'instantroofer' ); ?>
    </p>
    <p class="description">
        <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'instantroofer' ); ?>
    </p>
    <?php
}

/**
 * Add the top level menu page.
 */
function instantroofer_options_page() {
    add_menu_page(
        'instantroofer',
        'instantroofer Options',
        'manage_options',
        'instantroofer',
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
        add_settings_error( 'instantroofer_messages', 'instantroofer_message', __( 'Settings Saved', 'instantroofer' ), 'updated' );
    }

    // show error/update messages
    settings_errors( 'instantroofer_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "instantroofer"
            settings_fields( 'instantroofer' );
            // output setting sections and their fields
            // (sections are registered for "instantroofer", each field is registered to a specific section)
            do_settings_sections( 'instantroofer' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}