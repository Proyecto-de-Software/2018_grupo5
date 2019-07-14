<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 27/05/19
 * Time: 20:13
 */

class MiddlewareRunner {
    public $middlewares = [];

    function register($middlewareName) {
        $this->middlewares[] = $middlewareName;
    }

    function run() {
        foreach ($this->middlewares as $middleware) {
            /** @var Middleware $m */
            $m = new $middleware();
            $m->run();
        }
    }
}
