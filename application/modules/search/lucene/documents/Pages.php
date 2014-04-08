<?php
class Search_Lucene_Document_Pages extends Zend_Search_Lucene_Document
{
    public $user;
    public $filter;
    public $log;
    public $docData = null;
    
    public function __construct($document)
    {
        $filter = new Zend_Filter_StripTags();
    
        //$this->log = $this->getLog();
        //$this->user = new User_Model_User();
    
        //$this->user = new User_Model_User();
    
        $this->addField(Zend_Search_Lucene_Field::keyword('page_id', $document->id));
        $this->addField(Zend_Search_Lucene_Field::unIndexed('page_url', $document->url));
        //$this->addField(Zend_Search_Lucene_Field::unIndexed('url', $page->url));
        $this->addField(Zend_Search_Lucene_Field::unIndexed('date', $document->createdDate));
        $this->addField(Zend_Search_Lucene_Field::text('title', $document->name));
        //substr($widget->content, 0, 300)
        $this->addField( Zend_Search_Lucene_Field::text('teaser', substr($filter->filter($document->name), 0, 300) ) ) ;
        //$this->addField(Zend_Search_Lucene_Field::text('author', $data->firstName . ' ' . $data->lastName));
        $this->addField(Zend_Search_Lucene_Field::unStored('content', $document->content));
    }
    public function getLog()
    {
        return Zend_Registry::get('log');
    }
}