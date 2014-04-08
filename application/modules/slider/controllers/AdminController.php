<?php

/**
 * AdminController
 * 
 * @author
 * @version 
 */
require_once 'System/Controller/AdminAction.php';

class Slider_AdminController extends System_Controller_AdminAction
{

    public $table; 
    public $form; 
    
    
	public function init() {
	    parent::init();
		// TODO: Auto-generated method stub
		$this->table = new Slider_Model_Slider();
		$this->form = new Slider_Form_Manage();
	}

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        $this->view->page = $this->_request->page;
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($this->table->select(true)->order('order ASC'));
        $paginator = new Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($this->_request->page);
        $this->view->paginator = $paginator;
    }
    public function createAction() 
    {
        try {
            switch(true) {
            	case ($this->_request->isPost()) :
            	    if($this->form->isValid($this->_request->getPost())) {
            	        $data = $this->form->getValues($this->_request->getPost());
            	        Zend_Debug::dump($data);
            	        $slide = $this->table->fetchNew();
            	        $slide->setFromArray($data);
            	        $result = $slide->save();
            	        if($result) {
            	            $this->redirect('/admin/slider');
            	        }
            	    }
            	    break;
            }
            $this->view->form = $this->form;
        } catch (Zend_Exception $e) {
            echo $e->getMessage();
        }
        
    }
    public function editAction()
    {
        try {
            switch(true) {
            	case ($this->_request->isPost()) :
            	    if($this->form->isValid($this->_request->getPost())) {
            	        $data = $this->form->getValues($this->_request->getPost());
            	        $slide = $this->table->fetch($data['slideId']);
            	        
            	        $mainPath = $_SERVER['DOCUMENT_ROOT'] . '/modules/slider/';
            	        $thumbPath = $_SERVER['DOCUMENT_ROOT'] . '/modules/slider/_thumbs/';
            	        
            	        if($data['image'] !== null) {
                	        if(is_file($mainPath . $slide->image)) {
                	            unlink($mainPath . $slide->image);
                	        }
                	        if(is_file($thumbPath . 'resized_'.$slider->image)) {
                	            unlink($thumbPath . 'resized_'.$slider->image);
                	        }
            	        }
            	        elseif($data['image'] === null) {
            	            unset($data['image']);
            	        }
            	        
            	        $slide->setFromArray($data);
            	        $result = $slide->save();
            	        if($result) {
            	            $this->redirect('/admin/slider');
            	        }
            	    }
            	    break;
            	case (!$this->_request->isPost()) :
            	    if(isset($this->_request->slideId) && $this->_request->slideId !== null) {
            	        $slide = $this->table->fetch($this->_request->slideId);
            	        $this->view->image = $slide->image;
            	        unset($slide->image);
            	        $this->form->populate($slide->toArray());
            	    }
            	    break;
            }
            $this->view->form = $this->form;
        } catch (Zend_Exception $e) {
            $this->log->warn($e->getMessage());
            if(err)
            echo $e->getMessage();
        }
    }
    public function deleteAction()
    {
        if(isset($this->_request->slideId) && $this->_request->slideId !== null) {
            $slide = $this->table->fetch($this->_request->slideId);
            $result = $slide->delete();
            if($result) {
                $this->redirect('/admin/slider');
            }
        }
    }
}
