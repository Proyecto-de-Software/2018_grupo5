<?php
const MESSAGE = "message";
const TEXT = "text";

$TOKEN = '798730946:AAHtnDjJnj63AbDK6qEKag9GE61FRjLHIMM';
$URL = 'https://api.telegram.org/bot' . $TOKEN . '/sendMessage';

$request = json_decode(file_get_contents('php://input'), true);
$cmd = $request[MESSAGE][TEXT];

$response = [
    'chat_id' => $request[MESSAGE]['chat']['id'],
    TEXT => '',
    'disable_web_page_preview' => true,
    'reply_to_message_id' => null,
    'reply_markup' => null,
];

$comando = explode(":", $cmd)[0];


switch ($comando) {
    case '/start':
        $response[TEXT] = 'Hola ' . $request[MESSAGE]['from']['first_name'] .
            " Usuario: " . $request[MESSAGE]['from']['username'] . '!' . PHP_EOL;
        $response[TEXT] .= '¿Como puedo ayudarte? /help';
        break;

    case '/help':
        $response[TEXT] = 'Los comandos disponibles son:' . PHP_EOL;
        $response[TEXT] .= '/start Inicializa el bot' . PHP_EOL;
        $response[TEXT] .= '/instituciones Devolverá un listado de Instituciones disponibles' . PHP_EOL;
        $response[TEXT] .= '/instituciones:ID Devolverá los datos de la Instituciones' . PHP_EOL;
        $response[TEXT] .= '/instituciones/region-sanitaria:ID Devolverá un listado de Instituciones a
    partir de una la región sanitaria indicada por parámetro.' . PHP_EOL;
        $response[TEXT] .= '/help Muestra ayuda.';
        break;

    case '/instituciones':

        $data = fetchData('https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/');


        $response[TEXT] = 'Las instituciones disponibles son' . PHP_EOL;
        foreach ($data as $institucion) {
            $response[TEXT] .= $institucion['nombre'] . ", Calle " . $institucion['direccion'] . PHP_EOL;
        }
        break;

    case '/instituciones/region-sanitaria':
        $id_region = isset(explode(":", $cmd)[1]) ? explode(":", $cmd)[1] : "";

        $url = 'https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/region-sanitaria/' . $id_region;
        $data = fetchData($url);


        $response[TEXT] = 'Las instituciones disponibles para la region sanitaria ' . $id_region . ' son:' . PHP_EOL;
        foreach ($data as $institucion) {
            $response[TEXT] .= $institucion['nombre'] . ", Calle " . $institucion['direccion'] . PHP_EOL;
        }
        break;


    default:
        $response[TEXT] = 'Lo siento, aun no soy tan inteligente para entender ' . $comando . PHP_EOL;
        $response[TEXT] .= 'Prueba /help para ver lo que puedo hacer.';
        $response['reply_to_message_id'] = $request[MESSAGE]['message_id'];
        break;
}


$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($response),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($URL, false, $context);

function fetchData($url) {
    return json_decode(file_get_contents($url), true);
}