//Solo acepta numeros en los input
function isNumber(evt) {
    var iKeyCode = (evt.which) ? evt.which : evt.keyCode
    if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
        return false;

    return true;
}

function validate_passwords_match() {
    return $('#re_password').val() === $('#password').val();
}