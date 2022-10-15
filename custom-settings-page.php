<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * Latest
 */

const FONT_FAMILIES = [
    'Arial, Helvetica Neue, Helvetica, sans-serif',
    'Baskerville, Baskerville Old Face, Garamond, Times New Roman, serif',
    'Bodoni MT, Bodoni 72, Didot, Didot LT STD, Hoefler Text, Garamond, Times New Roman, serif',
    'Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif',
    'Calisto MT, Bookman Old Style, Bookman, Goudy Old Style, Garamond, Hoefler Text, Bitstream Charter, Georgia, serif',
    'Cambria, Georgia, serif',
    'Candara, Calibri, Segoe, Segoe UI, Optima, Arial, sans-serif',
    'Century Gothic, CenturyGothic, AppleGothic, sans-serif',
    'Consolas, monaco, monospace',
    'Copperplate, Copperplate Gothic Light, fantasy',
    'Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace',
    'Dejavu Sans, Arial, Verdana, sans-serif',
    'Didot, Didot LT STD, Hoefler Text, Garamond, Calisto MT, Times New Roman, serif',
    'Franklin Gothic, Arial Bold',
    'Garamond, Baskerville, Baskerville Old Face, Hoefler Text, Times New Roman, serif',
    'Georgia, Times, Times New Roman, serif',
    'Gill Sans, Gill Sans MT, Calibri, sans-serif',
    'Goudy Old Style, Garamond, Big Caslon, Times New Roman, serif',
    'Helvetica Neue, Helvetica, Arial, sans-serif',
    'Impact, Charcoal, Helvetica Inserat, Bitstream Vera Sans Bold, Arial Black, sans serif',
    'Lucida Bright, Georgia, serif',
    'Lucida Sans, Helvetica, Arial, sans-serif',
    'MS Sans Serif, sans-serif',
    'Optima, Segoe, Segoe UI, Candara, Calibri, Arial, sans-serif',
    'Palatino, Palatino Linotype, Palatino LT STD, Book Antiqua, Georgia, serif',
    'Perpetua, Baskerville, Big Caslon, Palatino Linotype, Palatino, serif',
    'Rockwell, Courier Bold, Courier, Georgia, Times, Times New Roman, serif',
    'Segoe UI, Frutiger, Dejavu Sans, Helvetica Neue, Arial, sans-serif',
    'Tahoma, Verdana, Segoe, sans-serif',
    'Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, sans-serif',
    'Verdana, Geneva, sans-serif',
];

const DEFAULTS = array(
    'instantroofer_field_account_id' => '',
    'instantroofer_field_font_family' => FONT_FAMILIES[0],
    'instantroofer_field_width' => 640,
    'instantroofer_field_height' => 690,
    'instantroofer_field_font_color' => '#000000'
);

const UUID_RGX = "/[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-(:?8|9|A|B)[a-f0-9]{3}-[a-f0-9]{12}/i";

const FONT_FAMILY_RGX = "/^[a-z,- ]+$/i";

function sanitize_settings($input)
{
    return array(
        'instantroofer_field_account_id' => preg_match(UUID_RGX, $input['instantroofer_field_account_id']) === 1 ? $input['instantroofer_field_account_id'] : DEFAULTS['instantroofer_field_account_id'],
        'instantroofer_field_font_family' => preg_match(FONT_FAMILY_RGX, $input['instantroofer_field_font_family']) === 1 ? $input['instantroofer_field_font_family'] : DEFAULTS['instantroofer_field_font_family'],
        'instantroofer_field_width' => (int)$input['instantroofer_field_width'] > 0 ? $input['instantroofer_field_width'] : DEFAULTS['instantroofer_field_width'],
        'instantroofer_field_height' => (int)$input['instantroofer_field_height'] > 0 ? $input['instantroofer_field_height'] : DEFAULTS['instantroofer_field_height'],
        'instantroofer_field_font_color' => $input['instantroofer_field_font_color'] || DEFAULTS['instantroofer_field_font_color'],
    );
}

/**
 * custom option and settings
 */
function instantroofer_settings_init()
{
    // Register a new setting for "general" page.
    register_setting('general', 'instantroofer_options', 'sanitize_settings');

    // Register a new section in the "general" page.
    add_settings_section(
        'instantroofer_section_developers',
        __('General Settings', 'general'),
        'instantroofer_section_developers_callback',
        'general'
    );

    // Register a new field in the "instantroofer_section_developers" section, inside the "general" page.
    add_settings_field(
        'instantroofer_field_account_id',
        __('Account ID', 'general'),
        'instantroofer_field_account_id_cb',
        'general',
        'instantroofer_section_developers'
    );

    add_settings_field(
        'instantroofer_field_width',
        __('Width', 'general'),
        'instantroofer_field_width_cb',
        'general',
        'instantroofer_section_developers'
    );

    add_settings_field(
        'instantroofer_field_height',
        __('Height', 'general'),
        'instantroofer_field_height_cb',
        'general',
        'instantroofer_section_developers'
    );

    add_settings_field(
        'instantroofer_field_font_family',
        // Use $args' label_for to populate the id inside the callback.
        __('Font Family', 'general'),
        'instantroofer_field_font_family_cb',
        'general',
        'instantroofer_section_developers',
        array(
            'label_for' => 'instantroofer_field_font_family',
            'class' => 'instantroofer_row',
            'instantroofer_custom_data' => 'custom',
        )
    );

    add_settings_field(
        'instantroofer_field_font_color',
        __('Font Color', 'general'),
        'instantroofer_field_font_color_cb',
        'general',
        'instantroofer_section_developers'
    );
}

/**
 * Register our instantroofer_settings_init to the admin_init action hook.
 */
add_action('admin_init', 'instantroofer_settings_init');


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function instantroofer_section_developers_callback($args)
{
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Customize the Instant Roofer booking wizard.', 'general'); ?></p>
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
function instantroofer_field_font_family_cb($args)
{
    $options = get_option('instantroofer_options');
    $escLabel = esc_attr($args['label_for']);
    echo <<<STR
    <select
        id="{$args['label_for']}"
        data-custom="{$args['instantroofer_custom_data']}"
        name="instantroofer_options[$escLabel]"
    >
STR;
    foreach (FONT_FAMILIES as $stack) {
        $stackName = explode(',', $stack)[0];
        $selectedAttr = isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], $stack, false)) : ('');
        echo <<<STR
        <option value="$stack" $selectedAttr>$stackName</option>
STR;
    }
    echo <<<STR
    </select>
STR;
}

/**
 * Account ID field callback function.
 */
function instantroofer_field_account_id_cb()
{
    $options = get_option('instantroofer_options');
    $value = $options['instantroofer_field_account_id'];
    echo <<<STR
    <input
        type="text"
        name="instantroofer_options[instantroofer_field_account_id]"
        id="instantroofer_field_account_id"
        value="$value"
        size="38"
        maxlength="36"
    >
STR;
}

/**
 * Width field callback function.
 */
function instantroofer_field_width_cb()
{
    $options = get_option('instantroofer_options');
    $value = $options['instantroofer_field_width'];
    echo <<<STR
    <input
        type="text"
        name="instantroofer_options[instantroofer_field_width]"
        id="instantroofer_field_width"
        value="$value"
        size="4"
    >
STR;
}

/**
 * Height field callback function.
 */
function instantroofer_field_height_cb()
{
    $options = get_option('instantroofer_options');
    $value = $options['instantroofer_field_height'];
    echo <<<STR
    <input
        type="text"
        name="instantroofer_options[instantroofer_field_height]"
        id="instantroofer_field_height"
        value="$value"
        size="4"
    >
STR;
}

/**
 * Font color field callback function.
 */
function instantroofer_field_font_color_cb()
{
    $options = get_option('instantroofer_options');
    $value = $options['instantroofer_field_font_color'];
    echo <<<STR
    <input
        type="text"
        name="instantroofer_options[instantroofer_field_font_color]"
        id="instantroofer_field_font_color"
        value="$value"
        size="9"
    >
STR;
}

/**
 * Add the top level menu page.
 */
function instantroofer_options_page()
{
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
add_action('admin_menu', 'instantroofer_options_page');

/**
 * Make color-script.js file declare wp-color-picker as a dependency
 * so we can use the wpColorPicker jQuery method inside it:
 */
add_action('admin_enqueue_scripts', 'mw_enqueue_color_picker');
function mw_enqueue_color_picker($hook_suffix)
{
// first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('color-script-handle', plugins_url('js/color-script.js', __FILE__), array('wp-color-picker'), '1.0.18', true);
}


/**
 * Top level menu callback function
 */
function instantroofer_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('instantroofer_messages', 'instantroofer_message', __('Settings Saved', 'general'), 'updated');
    }

    // show error/update messages
    settings_errors('instantroofer_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "general"
            settings_fields('general');
            // output setting sections and their fields
            // (sections are registered for "general", each field is registered to a specific section)
            do_settings_sections('general');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}