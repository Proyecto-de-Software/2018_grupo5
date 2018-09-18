<?php

class MatcherPath extends Matcher {

     const re_integer = "([0..9]+)";
     const re_string = "([a-zA-Z]+)";

    function __construct($path) {
        /*
         *
        url_path = /usuairo/p/<id:integer>/<p:integer>;
        -buscar grupos <..>
        -tengo 2 grupos : [<id:inttg45eger >,<p:integer > ]
        - x cada grupo reemplazar cada tipo , lo que esta a la derecha de : , por la constante adecuada
        re_final = /usuairo/p/re_integer/re_integer;
        iterar por cada grupo, encontrado en la ejecucion de la regex, y asignaro a un array
        asociativo, con clave igual a lo que esta la izquirda del :

        */

    }

    function isThis($url) {
        // TODO: Implement isThis() method.
    }

    function getParameters($url_request) {
        // TODO: Implement getParameters() method.
    }
}