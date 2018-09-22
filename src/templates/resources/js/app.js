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


function onSubmitFormGetJson(selector, successCallback, errorCallback, completeCallback, action) {
    $(selector).submit(function (event) {
        event.preventDefault();
        var form = $(this);
        url = form.attr('action');
        method = form.attr('method');
        data = getFormData(form);
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
