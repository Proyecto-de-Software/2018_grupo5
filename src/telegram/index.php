<?php
const MESSAGE = "message";
const TEXT = "text";

/**
 * @doc: define the credential and endpoints for telegram
 */
$TOKEN = '798730946:AAHtnDjJnj63AbDK6qEKag9GE61FRjLHIMM';
$URL = 'https://api.telegram.org/bot' . $TOKEN . '/sendMessage';

/**
 * @doc: define all the available command as a key,
 * with the name of the function for callback if match
 */
$options_available = [
    "/^\/start$/" => "fn_start",
    "/^\/help$/" => "fn_help",
    "/^\/instituciones$/" => "fn_instituciones",
    "/^\/instituciones\/(\d+)$/" => "fn_institucion_id",
    "/^\/instituciones\/region-sanitaria\/(\d+)$/" => "fn_instituciones_region_sanitaria",
    "/./" => "fn_dont_understand",
];

$request = json_decode(file_get_contents('php://input'), true);

/**
 * @doc: Preparing the response for telegram
 */
$response = [
    'chat_id' => $request[MESSAGE]['chat']['id'],
    TEXT => '',
    'disable_web_page_preview' => true,
    'reply_to_message_id' => null,
    'reply_markup' => null,
    'parse_mode' => 'Markdown',
];

/**
 * @doc: helper function to make a request
 * and get the response as Associative array
 */
function fetchData($url) {
    return json_decode(file_get_contents($url), true);
}


function fn_start($request, $param) {
    global $response;
    $response[TEXT] = 'Hola ' . $request[MESSAGE]['from']['first_name'] .
        " Usuario: " . $request[MESSAGE]['from']['username'] . '!' . PHP_EOL;
    $response[TEXT] .= '¿Como puedo ayudarte? /help';
}


function fn_help($request, $matches) {
    global $response;
    $response[TEXT] = 'Los comandos disponibles son:' . PHP_EOL;
    $response[TEXT] .= '*/start* Inicializa el bot' . PHP_EOL;
    $response[TEXT] .= '*/instituciones* Devolverá un listado de Instituciones disponibles' . PHP_EOL;
    $response[TEXT] .= '*/instituciones/ID* Devolverá los datos de la Instituciones' . PHP_EOL;
    $response[TEXT] .= '*/instituciones/region-sanitaria/ID* Devolverá un listado de Instituciones a
    partir de una la región sanitaria indicada por parámetro.' . PHP_EOL;
    $response[TEXT] .= '*/help* Muestra la ayuda.';
}

function fn_instituciones($request, $matches) {
    global $response;
    $data = fetchData('https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/');
    $response[TEXT] = 'Las instituciones disponibles son:' . PHP_EOL;
    foreach ($data as $institucion) {
        $response[TEXT] .= $institucion['nombre'] . ", Dir.: " . $institucion['direccion'] . PHP_EOL;
    }
}

function fn_institucion_id($request, $matches) {
    global $response;
    $id_institucion = $matches[1][0];
    error_log('institucion_id');
    error_log($id_institucion);
    $url = 'https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/' . $id_institucion . "/";
    $data = fetchData($url);
    if($data == null) {
        $response[TEXT] = 'La institución solicitada no existe.' . PHP_EOL;
    } else {
        $response[TEXT] = 'Los datos de la institución ' . $id_institucion . ' son:' . PHP_EOL;
        $response[TEXT] .= $data['nombre'] . ", Dir.: " . $data['direccion'] . PHP_EOL;
    }
}

function fn_instituciones_region_sanitaria($request, $matches) {
    global $response;
    $id_region = $matches[1][0];

    $url = 'https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/region-sanitaria/' . $id_region;
    $data = fetchData($url);
    if($data == null) {
        $response[TEXT] = 'No se encontraron instituciones para esa región sanitaria.' . PHP_EOL;
    } else {
        $response[TEXT] = 'Las instituciones disponibles para la region sanitaria ' . $id_region . ' son:' . PHP_EOL;
        foreach ($data as $institucion) {
            $response[TEXT] .= $institucion['nombre'] . ", Dir.: " . $institucion['direccion'] . PHP_EOL;
        }

    }
}

function fn_dont_understand($request, $matches) {
    global $response;
    $response[TEXT] = 'Lo siento, aun no soy tan inteligente para entender lo que me pedis.' . PHP_EOL;
    $response[TEXT] .= 'Prueba /help para ver lo que puedo hacer.';
    $response['reply_to_message_id'] = $request[MESSAGE]['message_id'];

}


/**
 * @doc: dispatch the command request
 */
foreach ($options_available as $regex => $fn) {
    $ok = preg_match($regex, $request[MESSAGE][TEXT], $matches);
    if($ok) {
        call_user_func_array($fn, [$request, $matches]);
        break;
    }
}

$response_header = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($response),
    ],
];

$response_header = stream_context_create($response_header);

/**
 * @doc: Send the request with the headers previously generated.
 */
$result = file_get_contents($URL, false, $response_header);
