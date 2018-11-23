<?php
const MESSAGE = "message";
const TEXT = "text";

$TOKEN = '798730946:AAHtnDjJnj63AbDK6qEKag9GE61FRjLHIMM';
$URL = 'https://api.telegram.org/bot' . $TOKEN . '/sendMessage';

$options_available = [
    "/^\/start$/" => "fn_start",
    "/^\/help$/" => "fn_help",
    "/^\/instituciones$/" => "fn_instituciones",
    "/^\/instituciones\/(\d+)$/" => "fn_instituciones_id",
    "/^\/instituciones\/region-sanitaria\/(\d+)$/" => "fn_instituciones_region_sanitaria",
    "/./" => "fn_dont_understand",
];

$request = json_decode(file_get_contents('php://input'), true);
$cmd = $request[MESSAGE][TEXT];

$response = [
    'chat_id' => $request[MESSAGE]['chat']['id'],
    TEXT => '',
    'disable_web_page_preview' => true,
    'reply_to_message_id' => null,
    'reply_markup' => null,
];

function fn_start($request, $param) {
    global  $response;
    $response[TEXT] = 'Hola ' . $request[MESSAGE]['from']['first_name'] .
        " Usuario: " . $request[MESSAGE]['from']['username'] . '!' . PHP_EOL;
    $response[TEXT] .= '¿Como puedo ayudarte? /help';
}


function fn_help($request, $matches) {
    global  $response;
    $response[TEXT] = 'Los comandos disponibles son:' . PHP_EOL;
    $response[TEXT] .= '/start Inicializa el bot' . PHP_EOL;
    $response[TEXT] .= '/instituciones Devolverá un listado de Instituciones disponibles' . PHP_EOL;
    $response[TEXT] .= '/instituciones/ID Devolverá los datos de la Instituciones' . PHP_EOL;
    $response[TEXT] .= '/instituciones/region-sanitaria/ID Devolverá un listado de Instituciones a
    partir de una la región sanitaria indicada por parámetro.' . PHP_EOL;
    $response[TEXT] .= '/help Muestra ayuda.';
}

function fn_instituciones($request, $matches) {
    global  $response;
    $data = fetchData('https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/');
    $response[TEXT] = 'Las instituciones disponibles son' . PHP_EOL;
    foreach ($data as $institucion) {
        $response[TEXT] .= $institucion['nombre'] . ", Calle " . $institucion['direccion'] . PHP_EOL;
    }
}

function fn_instituciones_id($request, $matches) {
    global  $response;
    $id_institucion = $matches[1][0];
    $url = 'https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/' . $id_institucion;
    $data = fetchData($url);
    $response[TEXT] = 'Las instituciones disponibles son' . PHP_EOL;
    foreach ($data as $institucion) {
        $response[TEXT] .= $institucion['nombre'] . ", Calle " . $institucion['direccion'] . PHP_EOL;
    }
}

function fn_instituciones_region_sanitaria($request, $matches) {
    global  $response;
    $id_region = $matches[1][0];

    $url = 'https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/region-sanitaria/' . $id_region;
    $data = fetchData($url);


    $response[TEXT] = 'Las instituciones disponibles para la region sanitaria ' . $id_region . ' son:' . PHP_EOL;
    foreach ($data as $institucion) {
        $response[TEXT] .= $institucion['nombre'] . ", Calle " . $institucion['direccion'] . PHP_EOL;
    }
}

function fn_dont_understand($request, $matches) {
    global  $response;
    $response[TEXT] = 'Lo siento, aun no soy tan inteligente para entender lo que me pides.' . PHP_EOL;
    $response[TEXT] .= 'Prueba /help para ver lo que puedo hacer.';
    $response['reply_to_message_id'] = $request[MESSAGE]['message_id'];

}


/**@doc: Seria como el dispatcher */
foreach ($options_available as $regex=>$fn) {
    $ok = preg_match($regex, $cmd, $matches);
    if ($ok){
        call_user_func_array($fn,[$request, $matches]);
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
$result = file_get_contents($URL, false, $response_header);

function fetchData($url) {
    return json_decode(file_get_contents($url), true);
}