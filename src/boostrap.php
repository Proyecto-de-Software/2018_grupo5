<?php
$json = file_get_contents('configs/settings.json');
define('SETTINGS', json_decode($json, true));
