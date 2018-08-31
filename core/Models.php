<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 20:28
 */

class Models
{
    private $isApiAccessible;

    private $value;

    private $default;

    private $isNullable;

    private $blank;

    private $max_length;

    private $validate_regex;

    private $validate_callback;

    function __construct() {

        $this->value=null;
        $this->isApiAccessible = false;
        $this->default = null;
        $this->isNullable = null;
        $this->blank = null;
        $this->max_length = null;
        $this->validate_callback = null;
    }

    /**
     * @param null $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param null $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    public function getDefault(){
        return $this->default;
    }

    /**
     * @param null $isNullable
     */
    public function setIsNullable($isNullable)
    {
        $this->isNullable = $isNullable;
    }

    /**
     * @param null $blank
     */
    public function setBlank($blank)
    {
        $this->blank = $blank;
    }

    /**
     * @param null $max_length
     */
    public function setMaxLength($max_length)
    {
        $this->max_length = $max_length;
    }


    /**
     * @param bool $value
     * @return $this
     */
    public function setApiAccessible($value = true)
    {
        $this->isApiAccessible = $value;
        return $this;
    }


    /**
     * @param $regex
     * @return $this
     */
    public function setValidateRegex($regex)
    {
        $this->validate_regex = $regex;
        return $this;
    }

    /**
     * @param $callback
     * @return $this
     */
    public function setValidateCallback($callback)
    {
        $this->validate_callback = $callback;
        return $this;
    }

    /**
     * @return bool
     */
    public function isApiAccessible()
    {
        return $this->isApiAccessible;
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
        return ($this->value != null) ? $this->value : 'null model';
    }

    public function validate($data) {
        #TODO hacer validacion
        return true;
    }


}