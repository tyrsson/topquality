<?php

/**
 * @author Joey Smith
 * @version 1.1.1
 * @package Aurora
 * @subpackage Page
 */

class Page_Model_Rowset_SubCategories extends Zend_Db_Table_Rowset_Abstract 
{
    protected $_rowClass = 'Page_Model_Row_SubCategory'; 
    protected $_tableClass = 'Page_Model_SubCategories'; 
    
    //protected $_children;
    
//     public function getChildren()
//     {
//         while($this->valid()) {
//             $row = $this->current();
//             //$row->children = $row->findDependentRowset($this->_tableClass, 'SubCategories');
//             //$row->getChildren();
//             //Zend_Debug::dump($row->getChildren());
//             $this->next();
//         }
//         $this->rewind();
//         return $this;
//     }
    
}

