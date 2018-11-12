<?php
$TOKEN = '798730946:AAHtnDjJnj63AbDK6qEKag9GE61FRjLHIMM';
$URL = 'https://api.telegram.org/bot' . $TOKEN . '/sendMessage';

$request = json_decode(file_get_contents('php://input'), true);
$cmd = $request['message']['text'];

$response = [
    'chat_id' => $request['message']['chat']['id'],
    'text' => '',
    'disable_web_page_preview' => true,
    'reply_to_message_id' => $request['message']['message_id'],
    'reply_markup' => null,
];

$comando = explode(":", $cmd)[0];


switch ($comando) {
    case '/start':
        $response['text'] = 'Hola ' . $request['message']['from']['first_name'] .
            " Usuario: " . $request['message']['from']['username'] . '!' . PHP_EOL;
        $response['text'] .= '¿Como puedo ayudarte? /help';
        $response['reply_to_message_id'] = null;
        break;

    case '/help':
        $response['text'] = 'Los comandos disponibles son:' . PHP_EOL;
        $response['text'] .= '/start Inicializa el bot' . PHP_EOL;
        $response['text'] .= '/instituciones Devolverá un listado de Instituciones disponibles' . PHP_EOL;
        $response['text'] .= '/instituciones/region-sanitaria: region-sanitaria : Devolverá un listado de Instituciones a
    partir de una la región sanitaria indicada por parámetro.' . PHP_EOL;
        $response['text'] .= '/help Muestra ayuda.';
        $response['reply_to_message_id'] = null;
        break;

    case '/instituciones':

        $data = fetchData('https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/');


        $response['text'] = 'Las instituciones disponibles son' . PHP_EOL;
        foreach ($data as $institucion) {
            $response['text'] .= $institucion['nombre'] . ", Calle " . $institucion['direccion'] . PHP_EOL;
        }
        $response['reply_to_message_id'] = null;
        break;

    case '/instituciones/region-sanitaria':
        $id_region = isset(explode(":", $cmd)[1]) ? explode(":", $cmd)[1] : "";

        $url = 'https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/region-sanitaria/' . $id_region;
        $data = fetchData($url);


        $response['text'] = 'Las instituciones disponibles para la region sanitaria ' . $id_region . ' son:' . PHP_EOL;
        foreach ($data as $institucion) {
            $response['text'] .= $institucion['nombre'] . ", Calle " . $institucion['direccion'] . PHP_EOL;
        }
        $response['reply_to_message_id'] = null;
        break;


    default:
        $response['text'] = 'Lo siento, aun no soy tan inteligente para entender ' . $comando . PHP_EOL;
        $response['text'] .= 'Prueba /help para ver lo que puedo hacer.';
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