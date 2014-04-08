<?php
/**
 * @author Joey Smith
 * @version 1.1.1
 * @package Aurora
 * @subpackage Page
 */
require_once 'Zend/Controller/Action.php';

class Page_AdminController extends System_Controller_AdminAction
{

    public $page;

    protected $_pService;

    public $name;

    protected $urlFilter;

    protected $nameFilter;

    protected $matches = array(
        '/ /',
        '/_/',
        '/--/',
        '/&/',
        '/\'/',
        '/\"/'
    );

    protected $replacement = array(
        '-',
        '-',
        '-',
        'and',
        '',
        ''
    );

    protected $nameMatches = array(
        '/&/'
    );

    protected $nameReplacement = array(
        'and'
    );

    public function preDispatch()
    {
        $ajax = $this->_helper->getHelper('AjaxContext');
        $ajax->addActionContext('index', 'html')
            ->addActionContext('create', 'html')
            ->addActionContext('edit', 'html')
            ->initContext();
        
        switch ($this->isAjax()) {
            case true:
                
                break;
            case false:
                
                break;
        }
    }
    // public $searchIndexPath;
    public function init()
    {
        parent::init();
        
        // $this->pages = new Page_Model_Page();
        
        //$this->url = $this->_request->getParam('url', null);
        
        // $this->page = $this->pages->fetchByUrl($this->url);
        
        // create the filter chains
        $this->urlFilter = new Zend_Filter();
        $this->nameFilter = new Zend_Filter();
        
        $this->entities = new Zend_Filter_HtmlEntities(array(
            'quotestyle' => ENT_QUOTES
        ));
        $this->trimFilter = new Zend_Filter_StringTrim();
        $this->alnumFilter = new Zend_Filter_Alnum(array(
            'allowwhitespace' => true
        ));
        $this->toLowerFilter = new Zend_Filter_StringToLower();
        
        $this->replaceUrlFilter = new Zend_Filter_PregReplace();
        $this->replaceUrlFilter->setMatchPattern($this->matches);
        $this->replaceUrlFilter->setReplacement($this->replacement);
        
        // build the chain
        $this->urlFilter->addFilter($this->trimFilter);
        $this->urlFilter->addFilter($this->toLowerFilter);
        $this->urlFilter->addFilter($this->replaceUrlFilter);
        
        $this->nameFilter->addFilter($this->trimFilter);
        $this->replaceNameFilter = new Zend_Filter_PregReplace();
        $this->replaceNameFilter->setMatchPattern($this->nameMatches);
        $this->replaceNameFilter->setReplacement($this->nameReplacement);
        $this->nameFilter->addFilter($this->replaceNameFilter);
        $this->nameFilter->addFilter($this->alnumFilter);
        
        $this->roleTable = new User_Model_Roles();
        
        $this->validatePageName = new Zend_Validate_Db_NoRecordExists(array(
            'table' => 'pages',
            'field' => 'label'
        ));
    }

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        $cats = new System_Db_Categories();
        $this->conManager = new System_Db_Categories();
        
       // Zend_Debug::dump($this->conManager->getTree()->toMultiArray());
        $this->view->navigation = $this->conManager->getTree()->toZendArray();
        
        switch (true) {
            // handle ajax request first if any
            case ( $this->isAjax() && $this->_request->isPost() ) :
                
                break;
            case ( $this->isAjax() && $this->_request->isGet() ) :
                
                break;
            // handle none ajax request
            case ( ! $this->isAjax() && $this->_request->isPost() ) :
                
                break;
            case ( ! $this->isAjax() && $this->_request->isGet() ) :

                break;
        }
    }
    public function editCategoryAction()
    {
        try {
            // this must be called or your dojo dijit will not parse when ajaxed in
            Zend_Dojo_View_Helper_Dojo::setUseDeclarative(true);
            $form = new System_Form_EditCategory();
            
            $form->setAction('/admin/page/edit/category/' . $this->_request->caturi);
            
            $table = new System_Db_Categories();
            $pagesTable = new System_Db_Page();
            $row = $table->fetchNode($table->select(true)->where('uri = ?', $this->_request->caturi));
            $childPages = $table->fetchPagesByCategoryUri($this->_request->caturi);
            
//             if ($childPages instanceof Zend_Db_Table_Rowset_Abstract) {
//                 $childPagesData = $childPages->toArray();
//             }
            
            if(isset($this->_request->caturi) && !empty($this->_request->caturi)) {
                switch(true) {
                	case ( !$this->isAjax() && $this->_request->isPost() ) :
            
                	    if( $form->isValid( $this->_request->getPost() ) ) {
                	        //Zend_Debug::dump($form->getValues());
                	        $data = $form->getValues();
                	    }
                	    if (isset($data['categoryName']) && !empty($data['categoryName'])) {
                	        $urlFilter = new Zend_Filter_Word_SeparatorToDash();
                	        $data['uri'] = $urlFilter->filter($data['categoryName']);
                	    }
                	    
                	    $row->setFromArray($data);
                	    
                	    if (count($childPages)) {
                	       $count = count($childPages);
                	       for ($i = 0; $i < $count; $i++) {
                	           $pieces = explode("/", $childPages[$i]->uri);
                	           $childPages[$i]->uri = $data['uri'] . '/' . $pieces[1];
                	           $childPages[$i]->setReadOnly(false);
                	           $childPages[$i]->save();
                	       }
                	    }
                	    
                	    
                	    $row->setReadOnly(false);
                	    
                	    $result = $row->save();
                	    
                	    if ($result > 0) {
                	        //echo 'saved';
                	        $this->redirect('/admin/success');
                	    }
                	    
                	    break;
                	case ( !$this->isAjax() && $this->_request->isGet() ) :
                	    $form->populate($row->toArray());
                	    break;
                }
            }
            else {
                // throw error
            }
            
            
            $this->view->form = $form;
        } catch (Zend_Exception $e) {
            $this->log->warn($e->getMessage() . ' ::Error Location:: File:: ' . $e->getFile() . ' :: Line:: ' . $e->getLine());
        }
        
    }
    public function createCategoryAction()
    { 
            try {
            // this must be called or your dojo dijit will not parse when ajaxed in
            Zend_Dojo_View_Helper_Dojo::setUseDeclarative(true);
            $form = new System_Form_CreateCategory();

                switch(true) {
                	case ( !$this->isAjax() && $this->_request->isPost() ) :
            
                	    if( $form->isValid( $this->_request->getPost() ) ) {
                	        $data = $form->getValues();
                	    }
                	    
                	    if (isset($data['categoryName']) && !empty($data['categoryName'])) {
                	        $urlFilter = new Zend_Filter_Word_SeparatorToDash();
                	        $data['uri'] = $urlFilter->filter($data['categoryName']);
                	    }
                	    
                	    $table = new System_Db_Categories();
                	    $parent = $table->fetchNode($table->select(true)->where('categoryId = ?', $data['parentId']));
                	    
                	    $result = $table->addChild($parent, $data, System_Db_NestedSet::LAST_CHILD);
           
                	    if(isset($result->categoryId) && $result->categoryId > 0) {
                	        //echo 'saved';
                	        $this->redirect('/admin/success');
                	    }
                	    break;
                	case ( !$this->isAjax() && $this->_request->isGet()) :
                	    
                	    break;
                }

            $this->view->form = $form;
        } catch (Zend_Exception $e) {
            echo $e->getMessage();
        }
    }
    public function editAction()
    {
        // this must be called or your dojo dijit will not parse when ajaxed in
        Zend_Dojo_View_Helper_Dojo::setUseDeclarative(true);
    
        $form = new Page_Form_EditPage();
        
        $form->setAction('/admin/page/edit/' . $this->_request->caturi . '/' . $this->_request->uri);
        
        $model = new Page_Model_Page();
        
        if($this->_request->uri === 'home' || $this->_request->uri === 'Home') {
            $wSoObj = $form->getSubForm('wSoObj');
            
            /**
             * Remove these elements to prevent modifying them for the home page
             */
            $content = $wSoObj->getSubForm('contentTab');
            $content->removeElement('featured');
            $content->removeElement('label');
            $content->removeElement('isLanding');
            $content->removeElement('categoryId');
            $content->removeElement('image');
            /**
             * access should never be restricted for the home page
             */
            $wSoObj->removeSubForm('accessTab');

        }
        
        $uri = $this->_request->caturi . '/' . $this->_request->uri;
        $page = $model->fetchForEditByUri($uri);
        
        $this->view->name = $page->label;

        switch (true) {
        	case (! $this->isAjax() && $this->_request->isPost()):
        	    try {
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
        	                    $page->setFromArray($section);
        	                }
        	            }
        	            continue;
        	        }
        	        
        	        if (isset($page->label) && $page->label !== null) {
        	            
        	            $parent = $page->findParentRow('System_Db_Categories');
        	            if(isset($parent->uri) && !empty($parent->uri)) {
        	                // if we got to here we need this as well
        	                $urlFilter = new Zend_Filter_Word_SeparatorToDash();
        	                //$row->uri = $urlFilter->filter($row->label);
        	                $uri = $urlFilter->filter($page->label);
        	                $page->uri = $parent->uri . '/' . $uri;
        	                $page->setReadOnly(false);
        	                $result = $page->save();
        	                // validate result, redirect common
        	            }
        	            else {
        	                // throw error
        	            }
        	        }

        	    } catch (Zend_Exception $e) {
        	        $this->log->crit($e);
        	        echo $e->getMessage();
        	    }
    
        	    break;
        	case (! $this->isAjax() && $this->_request->isGet()): // pre populate the form with the requested page data
        	    $data = array();
    
        	    $data['role'] = $page->role;
        	    $popdata = array_merge($data, $page->toArray());
    
        	    $form->populate($popdata);
        	    $this->view->data = $popdata;
        	    break;
        }
    
        $this->view->form = $form;
    }
    public function createAction()
    {
        // This allows the markup to be parsed in the view file when the form is requsted via ajax
        Zend_Dojo_View_Helper_Dojo::setUseDeclarative(true);
        $form = new Page_Form_CreatePage();
        $page = new Page_Model_Page();
        $row = $page->fetchNew();
//         $pageCount = $page->fetchTotalPageCount();
        try {
            switch (true) {
                case (! $this->isAjax() && $this->_request->isPost() ) :
                    
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
                    
                    if ($this->validatePageName->isValid($row->label)) {
                        // got to have this
                        $parent = $row->findParentRow('System_Db_Categories');
                        if(isset($parent->uri) && !empty($parent->uri)) {
                            // if we got to here we need this as well
                            $urlFilter = new Zend_Filter_Word_SeparatorToDash();
                            //$row->uri = $urlFilter->filter($row->label);
                            $uri = $urlFilter->filter($row->label);
                            $row->uri = $parent->uri . '/' . $uri;
                            $row->setReadOnly(false);
                            $result = $row->save();
                            // validate result, redirect common
                            $this->redirect('/admin/success');
                        }
                        else {
                            // throw error
                        }
                        
                    } else {
                        throw new Zend_Application_Exception(sprintf('A page with that name already exist, please choose another page name.', $row->label));
                    }
                    
                    break;
            }
        } catch (Exception $e) {
            $this->log->alert($e->getMessage() . ' ::Error Location:: File:: ' . $e->getFile() . ' :: Line:: ' . $e->getLine());
            echo $e->getMessage();
        }
        $this->view->form = $form;
    }

    public function orderPagesAction()
    {
        // This code block handles the ordering of pages in the main menu
        // switch ($this->isAjax()) {
        // case true:
        // $model = new Page_Model_Page();
        // if (isset($_POST['order'])) {
        // $i = 1;
        // foreach ($_POST['order'] as $order) {
        // $orderParts = explode('_', $order);
        // $pageToOrderId = $orderParts[1];
        // $row = $model->fetchById($pageToOrderId);
        // $row->pageOrder = $i;
        // $row->save();
        // $i++;
        // continue;
        // }
        // }
        // $pageList = $model->getPagesForOrder();
        // $this->view->orderList = $pageList->toArray();
        // $this->getHelper('viewRenderer')->setNoRender(true);
        // $this->_helper->layout->disableLayout();
        // if (isset($this->_request->url)) {
        // $page = new Page_Model_Page();
        // $child = $page->fetchByUrl($this->_request->url);
        // //$this->_response->setBody(var_dump($_POST));
        // }
        // break;
        // }
    }
    public function deleteCategoryAction()
    {
        //die('ran to here');
        try {
            $this->_helper->viewRenderer->setNoRender(true);
            switch (isset($this->_request->caturi)) {
                case true:
                        $model = new System_Db_Categories();
                        $category = $model->fetchNodeByUri($this->_request->caturi);
                        //Zend_Debug::dump($category);
                        $category->setReadOnly(false);
                        $delete = (int) $category->delete();
                        if ($delete > 0) {
                            //$this->messenger->addMessage("$category->categoryName was deleted successfully!");
                            $this->redirect('/admin/success');
                        } else {
                            throw new Zend_Db_Exception(' unknown error trying to process request!');
                        }
                    break;
                
                case false:
                    break;
            }
        } catch (Exception $e) {
            $this->log->warn($e->getMessage() . ' ::Error Location:: File:: ' . $e->getFile() . ' :: Line:: ' . $e->getLine());
        }
        
    }
    public function deleteAction()
    {
        try {
            $this->_helper->viewRenderer->setNoRender(true);
            switch (isset($this->_request->uri)) {
                case true:
                    if ($this->_request->uri !== 'home') {
                        $model = new Page_Model_Page();
                        $uri = $this->_request->caturi . '/' . $this->_request->uri;
                        $page = $model->fetchForEditByUri($uri);
                        $page->setReadOnly(false);
                        //Zend_Debug::dump($page);
                        $delete = (int) $page->delete();
                        //Zend_Debug::dump($delete);
                        if ($delete > 0) {
                            //$this->messenger->addMessage("$page->label was deleted successfully!");
                            $this->redirect('/admin/success');
                        } else {
                            throw new Zend_Db_Exception(' unknown error trying to process request!');
                        }
                    }
                    break;
                
                case false:
                    break;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->log->warn($e->getMessage() . ' ::Error Location:: File:: ' . $e->getFile() . ' :: Line:: ' . $e->getLine());
        }
    }

    public function successAction()
    {
//         if (isset($this->_request->uri)) {
//             $this->view->name = $this->_request->uri;
//         }
    } // <- void method, here only for loading the view
}