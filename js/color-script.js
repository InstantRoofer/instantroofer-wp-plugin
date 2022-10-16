// const colorsConfig is prepended here with add_inline_script call in custom-settings-page.php

const colorRgx = /(#([0-9A-Fa-f]{3,6})\b)|(aqua)|(black)|(blue)|(fuchsia)|(gray)|(green)|(lime)|(maroon)|(navy)|(olive)|(orange)|(purple)|(red)|(silver)|(teal)|(white)|(yellow)|(rgb\(\s*\b([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\b\s*,\s*\b([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\b\s*,\s*\b([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\b\s*\))|(rgb\(\s*(\d?\d%|100%)+\s*,\s*(\d?\d%|100%)+\s*,\s*(\d?\d%|100%)+\s*\))/i

const colorPickerFields = {}
for(const id in colorsConfig.defaults) {
    colorPickerFields[id] = {
        // you can declare a default color here,
        // or in the data-default-color attribute on the input
        defaultColor: colorsConfig.defaults[id],
        // hide the color picker controls on load
        hide: true,
        // show a group of common colors beneath the square
        // or, supply an array of colors to customize further
        palettes: true
    }
}
console.log('colorPickerFields',colorPickerFields)

jQuery(document).ready(function($){
    for(const fieldId in colorPickerFields) {
        // Initialize color picker:
        const input = $(`#${fieldId}`);
        const options = colorPickerFields[fieldId];
        input.wpColorPicker(options);
        // Set color of button to black if none selected ever:
        const colorButton = input.parents('div.wp-picker-container').first().find('button.wp-color-result').first();
        if(!colorRgx.test(input.val() || '')) {
            colorButton.css({backgroundColor: options.defaultColor});
        }
    }
});