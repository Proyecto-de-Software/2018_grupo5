function getJson(url, data, successCallback, errorCallback, completeCallback) {
    $.ajax({
        dataType: "json",
        method: 'get',
        url: url,
        data: data,
        success: successCallback,
        error: errorCallback,
        complete: completeCallback
    });
}


function postJson(url, data, successCallback, errorCallback, completeCallback) {
    data['type'] = 'json';
    $.ajax({
        dataType: "json",
        method: 'post',
        url: url,
        data: data,
        success: successCallback,
        error: errorCallback,
        complete: completeCallback
    });
}


function onSubmitFormGetJson(selector, successCallback, errorCallback, completeCallback) {
    $(selector).submit(function (event) {
        event.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');
        var data = form.serialize();
        data['type'] = 'json';
        $.ajax({
            dataType: "json",
            method: method,
            url: url,
            data: data,
            success: successCallback,
            error: errorCallback,
            complete: completeCallback
        });
    });
}

function getFormData($formElement) {
    /* retorna la data del form en json*/
    var unindexed_array = $formElement.serializeArray();
    var indexed_array = {};
    $.map(unindexed_array, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}


function cargarLocalidades(idPartido) {
    getJson('/api/localidades/partido/' + idPartido, {}, generarOptions);
}

function generarOptions(data) {
    var x = $('#localidad')[0];
    x.length = 0;
    var option;
    for (let y = 0; y < data.length; y++) {
        option = document.createElement("option");
        option.text = data[y].nombre;
        option.value = data[y].id_localidad;
        x.add(option);
    }
}

function cargarRegionSanitaria(idPartido) {
    getJson('/api/region_sanitaria/partido/' + idPartido, {}, cargarRegion);
}

function cargarRegion(data) {
    var x = $('#region_sanitaria')[0];
    x.value = data.nombre;
}


class ConfirmationButton {

    constructor(idButton, idConfirmationButtons, callbackConfirm, callbackCancel, isCheckbox) {
        let last_state = null;
        if (isCheckbox) {
            last_state = document.getElementById(idButton).checked;
        }

        let buttons = document.getElementById(idConfirmationButtons);
        buttons.style.display = "none";

        let btn_confirmar = '<button id="%id%" type="button" class="btn btn-warning btn-sm"><i class="fas fa-exclamation-triangle"></i> Confirmar </button>';
        let btn_cancelar = ' <button id="%id%" type="button" class="btn btn-success btn-sm"> Cancelar </button> ';
        let id_confirmar = idButton + '__confimar';
        let id_cancelar = idButton + '__cancelar';
        btn_confirmar = btn_confirmar.replace("%id%", id_confirmar);
        btn_cancelar = btn_cancelar.replace("%id%", id_cancelar);
        document.getElementById(idConfirmationButtons).innerHTML = btn_confirmar + btn_cancelar;
        document.getElementById(idButton).addEventListener('click', clickSubmit);
        document.getElementById(id_confirmar).addEventListener('click', callbackConfirm);
        document.getElementById(id_cancelar).addEventListener('click', cancel);

        function clickSubmit() {
            buttons.style.display = "block";
        }

        function cancel() {
            if (isCheckbox) {
                last_state = document.getElementById(idButton).checked = last_state;
            }
            buttons.style.display = "none";
            if (callbackCancel !== undefined) {
                callbackCancel();
            }
        }
    }
}

// inicializa todos los tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});


function showSuccessMessage(msg) {
    $("#alert-success").addClass('show');
    $("#alert-success-msg").val(msg);
}

function showErrorMessage(msg) {
    $("#alert-danger").addClass('show');
    $("#alert-danger-msg").val(msg);
}
