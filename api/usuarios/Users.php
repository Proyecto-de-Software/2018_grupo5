<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 18:21
 */
#todo: tenemos que charlar para poner una convension a los nombrles de los modulos,
# view, api, modulo , etc.

class Users extends Api_CRUD
{
    private $model;

    /**
     * Need a instances of the model
     **/
    function get_model()
    {
        $this->model = new User();
        return $this->model;
    }


    function action_read()
    {
        // TODO: Implement action_read() method.
    }
}

