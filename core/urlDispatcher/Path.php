<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 04/09/18
 * Time: 20:40
 *  Esta clase tiene que poder resolver los url path
 *      ex: /api/encuenta/<intenger:anio>/<string:persona>
 *         deberia retornar una array asociativo, con los valores matcheados.
 *          lo podemos hacer bastante siemple con substr,
 *          o usar un par de regex simples.
 *
 *  r'^api/pepe/([a-Z]+)'
 * deberia poder entender expreisiones regulares,
 * y retornarla como un array ordenado por los grupos matcheados
 *
 *
 *
 */

class Path {

    function __construct() {
    }

    static function re($regex) {
        return new MatcherRegex($regex);
    }

    static function path($path) {
        return new MatcherPath($path);
    }

}