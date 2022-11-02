const colorPickerOptions = {
    // you can declare a default color here,
    // or in the data-default-color attribute on the input
    // defaultColor: colorsConfig.defaults[id],
    // hide the color picker controls on load
    hide: true,
    // show a group of common colors beneath the square
    // or, supply an array of colors to customize further
    palettes: true
}

jQuery(document).ready(function ($) {
    $('.instantroofer-color-picker').each((idx, val) => {
        $(val).wpColorPicker(colorPickerOptions)
    });
});