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
    'reply_markup' => null
];

$comando = explode(":", $cmd)[0];

$id_region_consultada = isset(explode(":", $cmd)[1]) ? explode(":", $cmd)[1] : "";

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
        $response['text'] .= '/instituciones-region-sanitaria: region-sanitaria : Devolverá un listado de Instituciones a
    partir de una la región sanitaria indicada por parámetro.' . PHP_EOL;
        $response['text'] .= '/help Muestra ayuda.';
        $response['reply_to_message_id'] = null;
        break;

    case '/instituciones':

        $data = json_decode(file_get_contents('https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/'), true);


        $response['text'] = 'Las instituciones disponibles son' . PHP_EOL;
        foreach ($data as $institucion) {
            $response['text'] .= $institucion['nombre'] . ", Calle " . $institucion['direccion'] . PHP_EOL;
        }
        $response['reply_to_message_id'] = null;
        break;

    case '/instituciones-region-sanitaria':

        $data = json_decode(file_get_contents('https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/region-sanitaria/' . $id_region_consultada), true);


        $response['text'] = 'Las instituciones disponibles para la region sanitaria' . $id_region_consultada . ' son' . PHP_EOL;
        foreach ($data as $institucion) {
            $response['text'] .= $institucion['nombre'] . ", Calle " . $institucion['direccion'] . PHP_EOL;
        }
        $response['reply_to_message_id'] = null;
        break;


    default:
        $response['text'] = 'Lo siento, no es un comando válido.' . PHP_EOL;
        $response['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
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
