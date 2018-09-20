<?php
function load_all_controllers() {
  $CONTROLLER_PATH = dirname(__FILE__) . "/";
  $files = scandir($CONTROLLER_PATH);
  foreach ($files as $filename) {
    if (preg_match('/php$/', $filename)) {
      $path = $CONTROLLER_PATH . $filename;
      if ($path != __FILE__) {
        include_once($path);
      }
    }
  }
}
