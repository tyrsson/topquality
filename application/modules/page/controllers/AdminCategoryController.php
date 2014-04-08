<?php
/**
 * Page_AdminCollectionController
 * 
 * @author Joey Smith
 * @version 1.1.1
 */
require_once 'System/Controller/AdminAction.php';
class Page_AdminCategoryController extends System_Controller_AdminAction
{
    protected $collections; 
    protected $urlFilter;
    public function init() {
        parent::init();
        $this->cats = new System_Db_Categories();
        // this turns white space into -'s
        $this->urlFilter = new Zend_Filter_Word_SeparatorToDash();
    }
    /**
     * The default action - show the home page
     */
    public function manageAction ()
    {
        //echo 'manage action';
        $this->conManager = new System_Db_Categories();
        //$this->conManager->getTree()->toNavigation('Pages', false);
        $this->view->navContainer = $this->conManager->getTree()->toPageManagerNavigation();
    } 
    public function createAction() 
    {
        $form = new Page_Form_CreateCategory();
        
        switch(true) {
        	case (!$this->isAjax() && $this->_request->isPost()) :
        	    $row = $this->cats->fetchNew();
        	    Zend_Debug::dump($this->_request->getPost());
        	    //die('create category action');
        	    
        	    // the pre-validation data, never ever use this raw
        	    $post = $this->_request->getPost();
        	    // the subForm container
        	    $wSoObj = $form->getSubForm('wSoObj');
        	    $data = array();
        	    
        	    foreach ($wSoObj as $subForm) {
        	        if (array_key_exists($subForm->getName(), $post[$wSoObj->getName()])) {
        	            if ($subForm->isValid($post[$wSoObj->getName()][$subForm->getName()])) {
        	                $valid = $subForm->getValues();
        	                $section = $valid[$subForm->getName()];
        	    
        	                $row->setFromArray($section);
        	            } else {
        	                // TODO:: Add validation handling
        	            }
        	        }
        	        continue;
        	    }
        	    $urlFilter = new Zend_Filter_Word_SeparatorToDash();
        	    //if ($this->validatePageName->isValid($row->name)) {
        	        $row->url = $urlFilter->filter($row->catName);
        	        $result = $row->save();
        	        if ($result > 0 && isset($post['categories'])) {
//         	            foreach ($post['categories'] as $collectionId) {
//         	                $mapping = $page->map(array(
//         	                    'pageId' => $page->id,
//         	                    'collectionId' => $collectionId
//         	                ));
//         	            }
        	        }
        	    //}
        	    break;
        	case (!$this->isAjax() && $this->_request->isGet()) :
        	   
        	    break;
        	default:
        	   
        	    break;
        }
        $this->view->form = $form;
    }
    public function editAction() 
    {
        
    }
    public function deleteAction() 
    {
        
    }
}
