<?php

/**
 * @author Joey Smith
 * @version 1.1.1
 * @package Aurora
 * @subpackage Page
 */

class Page_Model_Row_SubCategory extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'Page_Model_SubCategory';
    
    public function getChildren()
    {
        $this->children = $this->findDependentRowset('Page_Model_SubCategories', 'SubCategories');
        $table = $this->_getTable();
        
        return $this->toArray();
    }
}