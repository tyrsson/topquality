<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Sidebar helper
 *
 * @uses viewHelper Topquality_View_Helper
 */
class Topquality_View_Helper_Sidebar
{

    /**
     *
     * @var Zend_View_Interface
     */
    public $view;

    /**
     */
    public function sidebar($uri)
    {
         
    }

    /**
     * Sets the view field
     * 
     * @param $view Zend_View_Interface            
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
