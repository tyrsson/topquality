<?php

/**
 * JsonController
 *
 * @author
 * @version
 */
require_once 'Zend/Controller/Action.php';

class Page_JsonController extends System_Controller_AdminAction
{

    public $context = array(
        'pagenamestore' => array(
            'json'
        ),
        'pagenamestore' => array(
            'json'
        ),
        'rolestore' => array(
            'json'
        ),
        'quickpageedit' => array(
            'ajax',
            'json'
        ),
        'subpagecontent' => array(
            'ajax',
            'json'
        )
    );

    private $_roles;

    /**
     * The default action - show the home page
     */
    public function init()
    {
        $this->_roles = new User_Model_Roles();
        
        $this->_helper->contextSwitch()->initContext();
        $this->_helper->layout->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender(true);
    }

    public function rolestoreAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $roles = $this->_roles->fetchRoles();
        
        foreach ($roles->toArray() as $r) {
            foreach ($r as $name) {
                $entries[] = array(
                    'role' => $name
                );
            }
        }
        
        $data = new Zend_Dojo_Data('role', $entries);
        echo $data->toJson();
    }

    public function pagenamestoreAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $pages = new Page_Model_Page();
        $names = $pages->fetchPageNames();
        
        foreach ($names->toArray() as $n) {
            foreach ($n as $name) {
                $entries[] = array(
                    'name' => $name
                );
            }
        }
        
        $data = new Zend_Dojo_Data('name', $entries);
        echo $data->toJson();
    }
    public function treeAction()
    {
            try {
                $this->conManager = new System_Db_Categories();
	            //$this->conManager->getTree()->toNavigation();
                //Zend_Debug::dump($zendNav->toArray());
                $container = new Zend_Dojo_Data();
                $container->setIdentifier('categoryId');
                $container->setItems($this->conManager->getTree()->toZendArray());
                echo $container->toJson();
            } catch (Zend_Exception $e) {
                //$this->log->crit($e->getMessage());
            }

    }
    public function pagestoreAction()
    {
        try {
            $pages = new Page_Model_Page();
            $categories = new System_Db_Categories();
            
            $data = $pages->fetchAll()->toArray();
            $index = count($data);
            $names = array();

            
            $container = new Zend_Dojo_Data();
            $container->setIdentifier('id');
            $container->setItems($data);
            echo $container->toJson();
        } catch (Zend_Exception $e) {
            $this->log->crit($e->getMessage());
        }
    }

    public function quickpageeditAction()
    {
        $pages = new Page_Model_Page();
        
        $row = $pages->fetchById($this->_request->id);
        $row->setFromArray($this->_request->getParams());
        $row->save();
        $this->_response->setBody(new Zend_Dojo_Data('page', $row->toArray()));
    }

    public function subpagecontentAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->_helper->layout->disableLayout();
        if ($this->_request->isXmlHttpRequest()) {
            if (isset($this->_request->name)) {
                $page = new Page_Model_Page();
                $child = $page->fetchByName($this->_request->name);
                $this->_response->setBody('<h2>' . $child->name . '</h2><p>' . $child->content . '</p>');
            }
        }
    }
}
