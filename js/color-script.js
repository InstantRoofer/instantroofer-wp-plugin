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
        console.info('setting up ', fieldId)
        const input = $(`#${fieldId}`)
        // const colorButton = $(input).parent()
        const options = colorPickerFields[fieldId]
        input.wpColorPicker(options);
        if($(input).val().length === 0) {
            console.info('no color assigned')
            $(input).val(options.defaultColor)
        }
    }
});