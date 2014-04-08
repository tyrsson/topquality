<?php

/**
 * IndexController
 * 
 * @author Joey Smith
 * @version 1.1.1
 */
require_once 'System/Controller/Action.php';

class Slider_IndexController extends System_Controller_Action
{

    public $table; 
    

	public function init() {
	    parent::init();
	    $this->table = new Slider_Model_Slider();
	}

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        $this->view->slides = $this->table->fetchOrdered();
    }
}
