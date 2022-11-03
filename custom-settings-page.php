<?php

define("FONT_FAMILIES", parseJsonFile('config/fonts.json'));

define("APPEARANCE_MODES", parseJsonFile('config/appearance.json'));

$defaults = array(
    'instantroofer_field_account_id' => '',
    'instantroofer_field_width' => 640,
    'instantroofer_field_height' => 700,
    'instantroofer_field_font_family' => 'arial',
);
$colorsConfig = parseJsonFile('config/colors.json');
foreach ($colorsConfig['defaults'] as $id => $color) {
    $defaults[$id] = $color;
}
$defaults['instantroofer_field_appearance_mode'] = 'light';
define("DEFAULTS", $defaults);

// Developers: uncomment this temporarily to delete all the settings:
// delete_option('instantroofer_options');

const UUID_RGX = "/[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-(:?8|9|A|B)[a-f0-9]{3}-[a-f0-9]{12}/i";

function getOptionSafe($key) {
	$options = get_option('instantroofer_options');
    if(!is_array($options) || !isset($options[$key])) return DEFAULTS[$key];
	return $options[$key];
}

function parseJsonFile($path)
{
    $json = file_get_contents(plugins_url($path, __FILE__));
    return json_decode($json, true);
}

/**
 * Register a new field in the "instantroofer_section_developers" section, inside the "general" page.
 *
 * @param string $idSuffix The part after "instantroofer_field_"
 * @param string $label
 * @return void
 */
function addSimpleField($idSuffix, $label)
{
    $id = "instantroofer_field_{$idSuffix}";
    add_settings_field(
        $id,
        __($label, 'general'),
        "{$id}_cb",
        'general',
        'instantroofer_section_developers'
    );
}

/**
 * Register a new field in the "instantroofer_section_developers" section, inside the "general" page.
 *
 * @param string $idSuffix The part after "instantroofer_field_"
 * @param string $label
 * @return void
 */
function addSelectField($idSuffix, $label)
{
    $id = "instantroofer_field_{$idSuffix}";
    add_settings_field(
        $id,
        __($label, 'general'),
        "{$id}_cb",
        'general',
        'instantroofer_section_developers',
        array(
            'label_for' => $id,
            'class' => 'instantroofer_row',
            'instantroofer_custom_data' => 'custom',
        )
    );
}

function colorFieldCallback($idSuffix)
{
    $id = esc_attr("instantroofer_field_{$idSuffix}");
	$value = getOptionSafe($id);
    echo <<<STR
    <input
        type="text"
        id="$id"
        name="instantroofer_options[$id]"
        value="$value"
        class="instantroofer-color-picker"
        size="9"
    >
STR;
}

/**
 * Define all field callbacks:
 */

function instantroofer_field_account_id_cb()
{
	$value = getOptionSafe('instantroofer_field_account_id');
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

function instantroofer_field_width_cb()
{
    $value = getOptionSafe('instantroofer_field_width');
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

function instantroofer_field_height_cb()
{
    $value = getOptionSafe('instantroofer_field_height');
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
	$value = getOptionSafe('instantroofer_field_font_family');
    $escLabel = esc_attr($args['label_for']);
    echo <<<STR
    <select
        id="{$args['label_for']}"
        data-custom="{$args['instantroofer_custom_data']}"
        name="instantroofer_options[$escLabel]"
    >
STR;
    foreach (FONT_FAMILIES as $id => $stack) {
        $stackName = explode(',', $stack)[0];
        $selectedAttr = isset($value) ? (selected($value, $id, false)) : ('');
        echo <<<STR
        <option value="$id" $selectedAttr>$stackName</option>
STR;
    }
    echo '</select>';
}

function instantroofer_field_font_color_cb()
{
    colorFieldCallback('font_color');
}

function instantroofer_field_primary_color_cb()
{
    colorFieldCallback('primary_color');
}

function instantroofer_field_secondary_color_cb()
{
    colorFieldCallback('secondary_color');
}

function instantroofer_field_background_color_cb()
{
    colorFieldCallback('background_color');
}

/**
 * Appearance mode field callback function.
 *
 * @param array $args
 */
function instantroofer_field_appearance_mode_cb($args)
{
	$value = getOptionSafe('instantroofer_field_appearance_mode');
    $escLabel = esc_attr($args['label_for']);
    echo <<<STR
    <select
        id="{$args['label_for']}"
        data-custom="{$args['instantroofer_custom_data']}"
        name="instantroofer_options[$escLabel]"
    >
STR;
    foreach (APPEARANCE_MODES as $id => $label) {
        $selectedAttr = isset($value) ? (selected($value, $id, false)) : ('');
        echo <<<STR
        <option value="$id" $selectedAttr>$label</option>
STR;
    }
    echo '</select>';
}

function sanitize_settings($input)
{
    return array(
        'instantroofer_field_account_id' => preg_match(UUID_RGX, $input['instantroofer_field_account_id']) === 1 ? $input['instantroofer_field_account_id'] : DEFAULTS['instantroofer_field_account_id'],
        'instantroofer_field_width' => (int)$input['instantroofer_field_width'] > 0 ? $input['instantroofer_field_width'] : DEFAULTS['instantroofer_field_width'],
        'instantroofer_field_height' => (int)$input['instantroofer_field_height'] > 0 ? $input['instantroofer_field_height'] : DEFAULTS['instantroofer_field_height'],
        'instantroofer_field_font_family' => FONT_FAMILIES[$input['instantroofer_field_font_family']] ? $input['instantroofer_field_font_family'] : DEFAULTS['instantroofer_field_font_family'],
        'instantroofer_field_font_color' => $input['instantroofer_field_font_color'] ?: DEFAULTS['instantroofer_field_font_color'],
        'instantroofer_field_primary_color' => $input['instantroofer_field_primary_color'] ?: DEFAULTS['instantroofer_field_primary_color'],
        'instantroofer_field_secondary_color' => $input['instantroofer_field_secondary_color'] ?: DEFAULTS['instantroofer_field_secondary_color'],
        'instantroofer_field_background_color' => $input['instantroofer_field_background_color'] ?: DEFAULTS['instantroofer_field_background_color'],
        'instantroofer_field_appearance_mode' => APPEARANCE_MODES[$input['instantroofer_field_appearance_mode']] ? $input['instantroofer_field_appearance_mode'] : DEFAULTS['instantroofer_field_appearance_mode'],
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

    addSimpleField('account_id', 'Account ID');
    addSimpleField('width', 'Width in pixels');
    addSimpleField('height', 'Height in pixels');
    addSelectField('font_family', 'Font Family');
    addSimpleField('font_color', 'Font Color');
    addSimpleField('primary_color', 'Call-to-Action Color');
    addSimpleField('secondary_color', 'Other UI Elements Color');
    addSimpleField('background_color', 'Background Color');
    addSelectField('appearance_mode', 'Appearance Mode');
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
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Customize the Instant Roofer Booking Engine.', 'general'); ?></p>
    <p><i>Start giving roof replacement quotes online in minutes with Instant Roofer. <strong><a href="https://signup.instantroofer.com/">Sign Up</a>.</i></strong></p>
    <?php
}

/**
 * Add the top level menu page.
 */
function instantroofer_options_page()
{
    add_menu_page(
        'Instant Roofer Booking Engine',
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
function mw_enqueue_color_picker()
{
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('color-script-handle', plugins_url('js/color-script.js', __FILE__), array('wp-color-picker'), '1.0.22', true);
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