<?php
require_once ('System/Application/Module/Bootstrap.php');
class Page_Bootstrap extends System_Application_Module_Bootstrap
{
    /*
     * @var boolean flag to include front end navigation to be overridden in class childern
    */
    protected $hasFrontEndNav = true;
    /*
     * @var boolean flag to include admin navigation to be overridden in class childern
    */
    protected $hasAdminNav = true;

    /* (non-PHPdoc)
	 * @see System_Application_Module_Bootstrap::_initModuleAutoloader()
	 */
	protected function _initModuleAutoloader() {
		$this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
				'basePath'  => APPLICATION_PATH . '/modules/page',
				'namespace' => 'Page_',
		)
		);
	}
	protected function _initRoutes()
	{
	    $this->getRouter();
	    
	    $route = new Zend_Controller_Router_Route(
	        'admin/page/manager',
	        array(
	            'module'     => 'page',
	            'controller' => 'admin',
	            'action'     => 'index'
	        )
	    );
	    $this->router->addRoute('admin_page_index', $route);

	    $route = new Zend_Controller_Router_Route(
	            'admin/page/create',
                array(
                        'module'     => 'page',
                        'controller' => 'admin',
                        'action'     => 'create',
                        'format'     => 'html'
                )
	    );
	    $this->router->addRoute('create_page', $route);

	    $route = new Zend_Controller_Router_Route(
	            'admin/page/edit/:caturi/:uri',
	            array(
	                    'module'     => 'page',
	                    'controller' => 'admin',
	                    'action'     => 'edit',
	                    'caturi'     => '',
	                    'uri'    => '',
	                    'format'     => 'html'
	            ),
	            array(
	                  'caturi' => '[a-zA-Z0-9_-]+',
	    	          'uri' => '[a-zA-Z0-9_-]+',
	                  'format'  => '[a-z]+'
	            )
	    );
	    $this->router->addRoute('edit_page', $route);
	    
	    $route = new Zend_Controller_Router_Route(
	        'admin/page/delete/:caturi/:uri',
	        array(
	            'module'     => 'page',
	            'controller' => 'admin',
	            'action'     => 'delete',
	            'caturi'     => '',
	            'uri'    => '',
	            'format'     => 'html'
	        ),
	        array(
	            'caturi' => '[a-zA-Z0-9_-]+',
	            'uri' => '[a-zA-Z0-9_-]+',
	            'format'  => '[a-z]+'
	        )
	    );
	    $this->router->addRoute('delete_page', $route);

	    $route = new Zend_Controller_Router_Route(
	        'admin/page/edit/category/:caturi',
	        array(
	            'module'     => 'page',
	            'controller' => 'admin',
	            'action'     => 'edit-category',
	            'caturi'         => '',
	            'format'     => 'html'
	        ),
	        array(
	            'caturi' => '[a-zA-Z0-9_-]+',
	            'format'      => '[a-z]+'
	        )
	    );
	    $this->router->addRoute('edit_page_category', $route);
	    
	    $route = new Zend_Controller_Router_Route(
	        'admin/page/create/category/:format',
	        array(
	            'module'     => 'page',
	            'controller' => 'admin',
	            'action'     => 'create-category',
	            'format'     => 'html'
	        ),
	        array(
	            'format'  => '[a-z]+'
	        )
	    );
	    $this->router->addRoute('create_category', $route);
	    
	    $route = new Zend_Controller_Router_Route(
	        'admin/page/delete/category/:caturi/:format',
	        array(
	            'module'     => 'page',
	            'controller' => 'admin',
	            'action'     => 'delete-category',
	            'caturi'         => '',
	            'format'     => 'html'
	        ),
	        array(
	            'caturi' => '[a-zA-Z0-9_-]+',
	            'format'  => '[a-z]+'
	        )
	    );
	    $this->router->addRoute('delete_page_category', $route);

	    
// 	    $route = new Zend_Controller_Router_Route(
// 	        'admin/page/manage/categories/:format',
// 	        array(
// 	            'module'     => 'page',
// 	            'controller' => 'admin-category',
// 	            'action'     => 'manage',
// 	            'format'     => 'html'
// 	        ),
// 	        array(
// 	            'format'  => '[a-z]+'
// 	        )
// 	    );
// 	    $this->router->addRoute('manage_categories', $route);
	    
// 	    $route = new Zend_Controller_Router_Route(
// 	        'admin/page/create/collection/:format',
// 	        array(
// 	            'module'     => 'page',
// 	            'controller' => 'admin-collections',
// 	            'action'     => '',
// 	            'format'     => 'html'
// 	        ),
// 	        array(
// 	            'format'  => '[a-z]+'
// 	        )
// 	    );
// 	    $this->router->addRoute('collection_manager', $route);
	}
}