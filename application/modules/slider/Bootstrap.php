<?php
/**
 * @author Joey Smith
 * @version 0.9.1
 * @package System
 */
require_once ('Zend/Application/Module/Bootstrap.php');
class Slider_Bootstrap extends System_Application_Module_Bootstrap
{

    /*
     * @var boolean flag to include front end navigation to be overridden in class childern
    */
    protected $hasFrontEndNav = false;
    /*
     * @var boolean flag to include admin navigation to be overridden in class childern
    */
    protected $hasAdminNav = true;

    protected function _initRoutes()
    {
        $this->getRouter();
         
        $route = new Zend_Controller_Router_Route(
            'slider/index',
            array(
                'module'     => 'slider',
                'controller' => 'index',
                'action'     => 'index',
                'format'     => 'html'
            ),
            array(
                'format'  => '[a-z]+'
            )
        );
        $this->router->addRoute('slider_index', $route);
        
        
        $route = new Zend_Controller_Router_Route(
            'admin/slider/:page',
            array(
                'module'     => 'slider',
                'controller' => 'admin',
                'action'     => 'index',
                'page'       => null,
                'format'     => 'html'
            ),
            array(
                'page'    => '\d+',
                'format'  => '[a-z]+'
            )
        );
        $this->router->addRoute('slider_manager', $route);
        
        $route = new Zend_Controller_Router_Route(
            'admin/slider/create',
            array(
                'module'     => 'slider',
                'controller' => 'admin',
                'action'     => 'create',
                'format'     => 'html'
            ),
            array(
                'format'  => '[a-z]+'
            )
        );
        $this->router->addRoute('slider_create', $route);
        
        $route = new Zend_Controller_Router_Route(
            'admin/slider/edit/:slideId',
            array(
                'module'     => 'slider',
                'controller' => 'admin',
                'action'     => 'edit',
                'slideId'       => null,
                'format'     => 'html'
            ),
            array(
                'slideId'    => '\d+',
                'format'  => '[a-z]+'
            )
        );
        $this->router->addRoute('slider_edit', $route);
        
        $route = new Zend_Controller_Router_Route(
            'admin/slider/delete/:slideId',
            array(
                'module'     => 'slider',
                'controller' => 'admin',
                'action'     => 'delete',
                'slideId'       => null,
                'format'     => 'html'
            ),
            array(
                'slideId'    => '\d+',
                'format'  => '[a-z]+'
            )
        );
        $this->router->addRoute('slider_delete', $route);
        
    }
}