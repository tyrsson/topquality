<?php
/**
 * @author Joey Smith
 * @version 1.1.1
 * @package Aurora
 * @subpackage Admin
 */
require_once ('Zend/Application/Module/Bootstrap.php');

class Admin_Bootstrap extends System_Application_Module_Bootstrap
{

    protected $hasFrontEndNav = true;

    protected $hasAdminNav = true;

    public function _initRoutes()
    {
        $front = Zend_Controller_Front::getInstance();
        $this->getRouter();
        $route = new Zend_Controller_Router_Route('admin/', array(
            'action' => 'index',
            'controller' => 'index',
            'module' => 'admin'
        )
        );
        $this->router->addRoute('admin-index', $route);
    }
}