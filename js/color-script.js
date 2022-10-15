const colorPickerFields = {
    instantroofer_field_font_color: {
        // you can declare a default color here,
        // or in the data-default-color attribute on the input
        defaultColor: '#000000',
        // a callback to fire whenever the color changes to a valid color
        change: function (event, ui) {
        },
        // a callback to fire when the input is emptied or an invalid color
        clear: function () {
        },
        // hide the color picker controls on load
        hide: true,
        // show a group of common colors beneath the square
        // or, supply an array of colors to customize further
        palettes: true
    }
}

jQuery(document).ready(function($){
    for(const fieldId in colorPickerFields) {
        // Initialize color picker:
        const input = $(`#${fieldId}`);
        const options = colorPickerFields[fieldId];
        input.wpColorPicker(options);
        // Set color of button to black if none selected ever:
        const colorButton = input.parents('div.wp-picker-container').first().find('button.wp-color-result').first();
        console.log('hasClass', colorButton.hasClass('wp-color-result'))
        if(!CSS.supports('color',input.val())) {
            colorButton.css({backgroundColor: options.defaultColor});
        }
    }
});