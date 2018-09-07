<?php
$json = file_get_contents('./settings.json');
$GLOBALS["SETTINGS"] = json_decode($json, true);
