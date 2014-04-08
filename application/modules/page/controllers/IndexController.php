<?php
/**
 * Page_IndexController
 *
 * @author Joey Smith
 * @version 1.1.1
 * @package Aurora
 * @subpackage Page
 */
require_once 'System/Controller/Action.php';

class Page_IndexController extends System_Controller_Action
{

    public $files;

    public $page;

    public $isHome = false;

    public $model = 'Page_Model_Page';

    public $pages;
    
    protected $uri;
    
    protected $conManager;
    
    protected $subCat; 
    
    protected $subCats;
    
    protected $catUri; 
    
    protected $subCatUri; 
    
    protected $defaultCategory = 'site';
    
    protected $defaultSubCategory = 'page';
    
    protected $defaultUri = 'Home';
    
    protected $tree;
    
    protected $_nodePath = '';
    

    public function preDispatch()
    {
        // if this is removed it could cause issues with ajax in the admin area
        if ($this->isAjax()) {
            exit();
        }
    }

    public function init()
    {
        parent::init();
        
        try {
            // get an instance of the content manager
            $this->conManager = new System_Db_Categories();
        } catch (Zend_Exception $e) {
            $this->log->crit($e);
        }
         
    }

    /**
     * The default action - show the page if the user has access to it, if its not found then of course we get a 404
     */
    public function indexAction()
    {      

            $loaded = false;
            $results = array();
            $hasLanding = false;
            $isHome = false;
            
            if(!isset($this->_request->cat) && !isset($this->_request->page)) 
            {
                // are we home? There is no place like home ;)
                $this->page = $this->conManager->getHomeContent();
                $isHome = true;
            }
            elseif(isset($this->_request->cat) && !isset($this->_request->page)) {
                $this->page = $this->conManager->getLandingContent($this->_request->cat);
                
            }
            elseif(isset($this->_request->cat) && isset($this->_request->page)) {
                // note that for the uri, we have a full path to the node
                $this->_nodePath = $this->_request->cat . '/' . $this->_request->page;
                $this->conManager->setMode(System_Db_Categories::RETURN_OBJECT);
    	        $this->page = $this->conManager->fetchPageByUri($this->_nodePath);
            }
            // if this is null we have no db result object so we 404
            if($this->page == null) {
                throw new Zend_Controller_Action_Exception('Page not found', 404);
            }
            else {

                $role = (string) $this->user->role;
                
                $pageRole = (string) $this->page->role;
                switch ($this->acl->isAllowed($role, $this->module, 'page.manage')) {
                	case true:
                	    $this->view->allowEdit = true;
                	    break;
                	default:
                	    $this->view->allowEdit = false;
                	    break;
                }
                // Is the current requesting user allowed access to this page?
                switch ($this->acl->isAllowed($role, $this->module, "page.$pageRole.view")) {
                	case true:
                	    break;
                	default:
                	    throw new Zend_Controller_Action_Exception('Access Denied', 550);
                	    break;
                }
                //Zend_Debug::dump($this->cat);
                if(isset($this->_request->cat)) {
                    $this->view->headTitle(ucfirst(str_replace('-', ' ', $this->_request->cat)));
                }
                
                $this->view->headTitle($this->page->label);
                
                $this->view->headMeta()
                ->appendName('description', $this->page->description)
                ->appendName('keywords', $this->page->keyWords);
                
                $this->view->isHome = $isHome;
                $this->view->catUri = $this->_request->cat;
                
                // Finally assign the page object to the view after everything is loaded
                $this->view->page = $this->page;
            }
    }
}