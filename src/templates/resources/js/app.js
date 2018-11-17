function getCookie(name) {
    var cookieValue = null;
    if (document.cookie && document.cookie !== '') {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = jQuery.trim(cookies[i]);
            // Does this cookie string begin with the name we want?
            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}

function getTokenCSRF() {
    let cookienValue = getCookie('csrf_token');
    if (cookienValue == null) {
        console.error("Missing cookie csrf_token")
    }
    return cookienValue;
}

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


function onSubmitFormGetJson(selector, successCallback, errorCallback, completeCallback, preSubmit) {
    $(selector).submit(function (event) {
        event.preventDefault();
        if (preSubmit !== undefined) {
            preSubmit();
        }
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


function showAlert(msg, id) {

    $("#alert-container").addClass('fade show');
    if (id !== '' && id !== undefined) {
        $("#alert-msg").html(msg + ' <a href="ver/' + id + '">Ver detalle</a>');
    } else {
        $("#alert-msg").html(msg);
    }
}


function showSuccessMessage(msg, id) {
    let alert = $("#alert-container");
    alert.removeClass('alert-danger');
    alert.addClass('alert-success');
    showAlert(msg, id);


}


function showErrorMessage(msg) {
    let alert = $("#alert-container");
    alert.removeClass('alert-success');
    alert.addClass('alert-danger');
    showAlert(msg, '');
}

function showOverlay(title, body) {
    $("#modal-small-header").html(title);
    $("#modal-small-body").html(body);
    $("#modal-small").modal("show");

}

function hideOverlay() {
    $("#modal-small").modal("hide");

}

function showOverlayError(title, body) {
    body = "<p class='text-danger'>" + body + "</p>";
    showOverlay(title, body);

}

$(document).on('click', '#volver', function () {
    parent.history.back();
});


function redirectWithMessage(url, timeout, title, body) {
    showOverlay(
        title,
        "<div> " + body + " </div> <div class='text-center'>Redirigiendo <i class='fa fa-spinner fa-spin' style='font-size:24px'></i></div>"
    );
    setTimeout(function () {
        window.location.href = url;
    }, timeout);
}

let multiselectData = {
    searchBoxText: 'Buscar rol..',
    checkAllText: 'Seleccionar todos',
    uncheckAllText: 'Deseleccionar todos',
    invertSelectText: 'Invertir seleccion',
};

$(function () {
    let datepicker = $("#datepicker");
    if (datepicker !== undefined) {
        datepicker.datepicker({dateFormat: 'dd-mm-yy', maxDate: '0'});
    }

    let dataTablesConfig = {
        "pageLength": PAGINATION_SIZE,
        "lengthChange": false,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ entradas totales)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    };
});
