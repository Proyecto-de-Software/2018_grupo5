//Solo acepta numeros en los input
$(document).ready(function() {
    $("#nro_historia_clinica").keydown(isNumber);
    $("#nro_carpeta").keydown(isNumber);
    $("#numero").keydown(isNumber);
    $("#tel").keydown(isTelephone);
});
function isNumber (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ( validate_numbers(e) || otherValidationsForIsNumber(e)) {
             // let it happen, don't do anything
             return;
    }
    stopKeyPressIfIsNumber(e);
}
function isTelephone (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ( validate_numbers_telephone(e) || otherValidationsForIsNumber(e)) {
             // let it happen, don't do anything
             return;
    }
    stopKeyPressIfIsNumber(e);
}

function stopKeyPressIfIsNumber(e){
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}
function validate_numbers(e){
    return ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1);
}
function validate_numbers_telephone(e){
    return ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 109, 173]) !== -1);
}
function otherValidationsForIsNumber(e){
    return(
         // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
         // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40));
}

function validate_passwords_match() {
    return $('#re_password').val() === $('#password').val();
}