<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 27/05/19
 * Time: 20:14
 */

class SettingLoader extends Singleton {
    private $settings = [];

    function __construct() {
        $default_setting = file_get_contents(CODE_ROOT . '/config/settings.default.json');

        if ( file_exists(CODE_ROOT . "/config/settings.json")) {
            $override_setting = file_get_contents(CODE_ROOT . "/config/settings.json");
            $this->settings = array_merge(json_decode($default_setting, true),json_decode($override_setting, true));
        } else {
            $this->settings = json_decode($default_setting, true);
        }
        define('DEBUG', SETTINGS['debug']);
    }

    function setSettingsGlobally(){
        define('SETTINGS', $this->settings, true);
    }

    /**
     * @param $key 
     * @return mixed
     */
    function get($key){
        return $this->settings[$key];
    }
}