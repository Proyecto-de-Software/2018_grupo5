<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 07/09/18
 * Time: 00:29
 */

abstract class Matcher {

    /*
     * Este metodo recibe una url como parametro,
     * y debe poder hacer el matcheo, con su url almacenada
     * para saber si es la misma.
     *
     * */
    abstract function isThis($url_request);

    abstract function getParameters($url_request);

}