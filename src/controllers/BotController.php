<?php


class BotController {

    public function index(){

  
        $returnArray = true;
        $rawData = file_get_contents('php://input');
        $response = json_decode($rawData, $returnArray);
        $id_del_chat = $response['message']['chat']['id'];

/*
        // Obtener comando (y sus posibles parametros)
        $regExp = '#^(\/[a-zA-Z0-9\/]+?)(\ .*?)$#i';


        $tmp = preg_match($regExp, $response['message']['text'], $aResults);

        if (isset($aResults[1])) {
            $cmd = trim($aResults[1]);
            $cmd_params = trim($aResults[2]);
        } else {
            $cmd = trim($response['message']['text']);
            $cmd_params = '';
        }
*/
        $cmd=$response['message']['text'];

        $msg = array();
        $msg['chat_id'] = $response['message']['chat']['id'];
        $msg['text'] = "algo";
        $msg['disable_web_page_preview'] = true;
        $msg['reply_to_message_id'] = $response['message']['message_id'];
        $msg['reply_markup'] = null;

        switch ($cmd) {
        case '/start':
            $msg['text']  = 'Hola ' . $response['message']['from']['first_name'] . 
                       " Usuario: " . $response['message']['from']['username'] . '!' . PHP_EOL;
            $msg['text'] .= '¿Como puedo ayudarte? /help';
            $msg['reply_to_message_id'] = null;
            break;

        case '/help':
            $msg['text']  = 'Los comandos disponibles son:' . PHP_EOL;
            $msg['text'] .= '/start Inicializa el bot' . PHP_EOL;
            $msg['text'] .= '/instituciones Devolverá un listado de Instituciones disponibles' . PHP_EOL;
            $msg['text'] .= '/instituciones-region-sanitaria: region-sanitaria : Devolverá un listado de Instituciones a
    partir de una la región sanitaria indicada por parámetro.' . PHP_EOL;
            $msg['text'] .= '/help Muestra ayuda.';
            $msg['reply_to_message_id'] = null;
            break;

        case '/instituciones':

            $data = json_decode(file_get_contents('/api/instituciones/'));


            $msg['text']  = 'Las instituciones disponible son' . var_dump($data);
           /* foreach ($data as $institucion) {
                $msg['text'] .= $institucion['nombre']. ", Calle ".$institucion['direccion']. PHP_EOL;
            }*/
            $msg['reply_to_message_id'] = null;
            break;

       

        default:
                $msg['text']  = 'Lo siento, no es un comando válido.' . PHP_EOL;
                $msg['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
                break;
        }


        $url = 'https://api.telegram.org/bot798730946:AAHtnDjJnj63AbDK6qEKag9GE61FRjLHIMM/sendMessage';

        $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($msg)
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        exit(0);


    }
}

?>