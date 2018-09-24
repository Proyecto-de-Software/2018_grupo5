<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class AuthenticationController extends Controller {

    public static function login(){
        /**@api
         * code : null = not set
         * code : 0 = auth OK
         * code : 1 = username doesn't exists
         * code : 2 = password fail
         */

        $response = [
            'code' => null,
            'msg' => null,
        ];

        $instance =  new self();
        try {
            $usr = $_POST['username'];
            $psw = $_POST['password'];
        }catch (Exception $e){
            //echo $e;
        }

        $user = $instance->getModel('Usuario')->findOneBy(
            array(
                'email'=> 'admin@admin',
                'password'=>'sinhash',
                'activo'=>true
            )
        );

        if (isset($user)) {
            $instance->session->createAuthenticatedSession($user->getId(),[]);
            $response['code'] = 0;
            $response['msg'] = 'Usuario autenticado correctemtne';

        } else {
            $response['code'] = 1;
            $response['msg'] = 'El usuario o contraseña no existe';
        }

        return $instance->jsonResponse($response);
    }

   public static function logout(){
        $instance =  new self();
        $instance->session->destroyAuthenticatedSession();
    }


}