<?php
class Singleton
{
    // Hold an instance of the class
    private static $instance;

    // The singleton method
    public static function getOrCreateInstance()
    {
        if (!isset(self::$instance)) {
            echo "instancia nuevsa ";
          $clazz = get_called_class();
          self::$instance = new $clazz();
        }
        return self::$instance;
    }

}
