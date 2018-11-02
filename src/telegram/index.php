<?php



            
        $TOKEN = '798730946:AAHtnDjJnj63AbDK6qEKag9GE61FRjLHIMM';

        $returnArray = true;
        $rawData = file_get_contents('php://input');
        $response = json_decode($rawData, $returnArray);
        $id_del_chat = $response['message']['chat']['id'];

        $cmd=$response['message']['text'];

        $msg = array();
        $msg['chat_id'] = $response['message']['chat']['id'];
        $msg['text'] = "";
        $msg['disable_web_page_preview'] = true;
        $msg['reply_to_message_id'] = $response['message']['message_id'];
        $msg['reply_markup'] = null;


        $comando = explode(":", $cmd)[0];

        isset(explode(":", $cmd)[1]) ? $id_region_consultada=explode(":", $cmd)[1] : $id_region_consultada=""; 






        switch ($comando) {
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

            $data = json_decode(file_get_contents('https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/'),true);


            $msg['text']  = 'Las instituciones disponibles son' . PHP_EOL;
            foreach ($data as $institucion) {
                $msg['text'] .= $institucion['nombre']. ", Calle ".$institucion['direccion']. PHP_EOL;
            }
            $msg['reply_to_message_id'] = null;
            break;

        case '/instituciones-region-sanitaria':

            $data = json_decode(file_get_contents('https://grupo5.proyecto2018.linti.unlp.edu.ar/api/instituciones/region-sanitaria/'$id_region_consultada),true);


            $msg['text']  = 'Las instituciones disponibles para la region sanitaria'.$id_region_consultada.' son' . PHP_EOL;
            foreach ($data as $institucion) {
                $msg['text'] .= $institucion['nombre']. ", Calle ".$institucion['direccion']. PHP_EOL;
            }
            $msg['reply_to_message_id'] = null;
            break;

       

        default:
                $msg['text']  = 'Lo siento, no es un comando válido.' . PHP_EOL;
                $msg['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
                break;
        }



        $url = 'https://api.telegram.org/bot'.$TOKEN.'/sendMessage';

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



?>