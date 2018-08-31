<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 18:28
 */

abstract class Api_CRUD extends Api
{
    private $model;

    function __construct()
    {
        $this->get_model();
        $this->model = Model;
    }

    /**
     * Need a instances of the model
     **/
    abstract function get_model();


    /**
     * Hook method pre create
     **/
    private function pre_create()
    {
        return true;
    }

    /**
     * Hook method post create
     **/
    private function post_create()
    {
        return true;
    }

    function action_create()
    {
        if ($this->pre_create()) {
            $this->model->create($this->get_params(), true);
            $this->post_create();
        }
    }


    /**
     * Hook method pre delete
     **/
    private function pre_delete()
    {
        return true;
    }

    /**
     * Hook method post delete
     **/
    private function post_delete()
    {
        return true;
    }

    function action_delete()
    {
        if ($this->pre_delete()) {
            $this->model->delete($this->get_params());
            $this->post_delete();
        }
    }


    /**
     * Hook method pre update
     **/
    private function pre_update()
    {
        return true;
    }

    /**
     * Hook method post update
     **/
    private function post_update()
    {
        return true;
    }

    function action_update()
    {
        if ($this->pre_update()) {
            $this->model->update($this->get_params());
            $this->post_update();
        }
    }


    abstract function action_read();
}