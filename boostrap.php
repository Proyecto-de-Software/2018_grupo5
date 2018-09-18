<?php
$json = file_get_contents('configs/settings.json');
$GLOBALS["SETTINGS"] = json_decode($json, true);
