<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 29/10/18
 * Time: 23:31
 */
// si viene un post, buscar la cookie, o el args csrf_token y matchearlo con la session
// si no matcher retorar un 403, y un msg en el header con CSRF invalido
// ver si tiene cookie, si no tiene cookie setearla