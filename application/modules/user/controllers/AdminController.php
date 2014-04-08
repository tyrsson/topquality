<?php
/**
 * UserAdminController
 *
 * @author
 * @version
 */
require_once 'Zend/Controller/Action.php';
class User_AdminController extends System_Controller_AdminAction
{
    public $context = array('rolestore' => array('json'));
    /**
     * The default action - show the home page
     */
	public function init()
	{
		// Call the parent init to make sure we have the parents properties.
		parent::init();
		
		$ajax = $this->_helper->getHelper('AjaxContext');
		$ajax
		->addActionContext('list', 'html')
		->addActionContext('edit', 'html')
		->addActionContext('create', 'html')
		->initContext();
		
// 		if($this->_request->isXmlHttpRequest())
// 		{
// 		    if(isset($this->context[$this->_request->action]))
// 		    {
// 		        $this->_helper->contextSwitch()->initContext();
// 		        $this->_helper->layout->disableLayout();
// 		        $this->getHelper('viewRenderer')->setNoRender(true);
// 		    }
// 		}
	}
    public function indexAction ()
    {
        //Zend_Debug::dump($this->isAjax());
        $page = $this->_request->getParam('page', 1);
        Zend_Registry::set('userListPage', $page);
        $this->view->page = $page;
        $table = new User_Model_User();
        $paginator = $table->getOnePage($page);
        $this->view->paginator = $paginator;
    }
    public function editAction ()
    {
        if (Zend_Auth::getInstance()->hasIdentity())
        {
            $ident = Zend_Auth::getInstance()->getIdentity();

            $form = new User_Form_EditUser();
            $form->setAction('/admin/user/edit/' . $this->_request->id . '/' . $this->_request->page);
            $table = new User_Model_User();
            $row = $table->fetch($this->_request->id);
            

            if ($this->getRequest()->isPost())
            {
                $postData = $this->getRequest()->getPost();
                if ($form->isValid($postData))
                {
                    $values = $form->getValues();
                    $row->setFromArray($values);

                    $id = (int) $row->save();
                    if($id > 0) {
                    	$this->_redirect('/admin/user/'. $postData['page']);
                    }
                }
             }
             else
             {
                    // pre-populate form
                    $form->populate( array_merge($row->toArray(), array('page' => $this->_request->page)) );
             }
             $this->view->form = $form;
        }
    }
    public function createAction()
    {
        Zend_Dojo_View_Helper_Dojo::setUseDeclarative(true);
        // this is called here cause we need it for post as well
        $form = new User_Form_AdminCreateUser();
        $model = new User_Model_User();
        
        //$profile = new User_Model_Profile();
        //Zend_Debug::dump($profile->fetchProfileRoles());
        
        switch(true) {
        	case (!$this->isAjax() && $this->_request->isPost()) :
            	    //Zend_Debug::dump($this->_request->getPost());
            	    if($form->isValid($this->_request->getPost())) {
            	        $post = $form->getValues();
//             	        Zend_Debug::dump($post);
                        
            	    }
            	    else {
            	        echo $form->getErrorMessages();
            	    }
        	    break;
        	case (!$this->isAjax() && $this->_request->isGet()) :
        	    
        	    break;
        	case ($this->isAjax() && $this->_request->isPost()) :
        	    
        	    break;
    	    case ($this->isAjax() && $this->_request->isGet()) :
    	         
    	        break;
        }
        $this->view->form = $form;
    }
    public function deleteAction()
    {
    	try {

    		$this->_helper->viewRenderer->setNoRender(true);
    		//Zend_Debug::dump(Zend_Auth::getInstance());
    		//die();
    		$model = new User_Model_User();
    		switch(isset($this->_request->id)) {
    			case true :
    				$row = $model->fetch($this->_request->id);
    				$result = $row->delete();
    				if($result > 0) {
    					$this->log->addUserEvent(Zend_Auth::getInstance(), null, null);
    					$this->log->info('Deleted User');
    					$this->_redirect('/admin/user/'. $this->request->page);
    				}
    				break;
    			case false :

    				break;
    		}
    	} catch (Zend_Exception $e) {
    		$this->log->crit($e);
    	}

    }
    public function rolestoreAction()
    {
        if($this->_request->isXmlHttpRequest())
        {
	        $roles = new Zend_Db_Table('roles');
	        foreach( $roles->fetchAll( $roles->select( array('role')))->toArray() as $role)
	        {
	        	foreach($role as $r)
	        	{
	        	    $name[] = array('rName' => $r);
	        	}
	        }
	        $data = new Zend_Dojo_Data('rName', $name);
	        echo $data->toJson();
        } else {
            exit;
        }
    }
}
