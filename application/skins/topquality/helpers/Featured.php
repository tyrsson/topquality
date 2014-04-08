<?php
/**
 * System_View_Helper
 * 
 * @author
 * @version 
 */
require_once 'Zend/View/Interface.php';


/**
 * View Helper
 */


class Topquality_View_Helper_Featured extends System_View_Helper_Abstract
{

    public function featured ($wrapperTag = 'div', array $wrapperAttribs = array())
    {
        $useLog = false;
        $content = '';
        if(Zend_Registry::isRegistered('log')) {
            $useLog = true;
            $log = Zend_Registry::get('log');
        }
        
        try {
        	
            $element = new Zend_Form_Decorator_HtmlTag(array_merge(array('tag' => $wrapperTag), $wrapperAttribs));
            $pages = new Page_Model_Page();
            $featuredPages = $pages->fetchAll(
                $pages->select()->where('featured = ?', 1)->limit(3, 0)
            );
            $allPages = $pages->fetchAll(
                $pages->select()->where('visibility', 'public')->order('createdDate DESC')
            );
            
            if(count($featuredPages) < 3) {
                $mergedPages = array_merge($featuredPages, $allPages);
            }
            else {
                $mergedPages = $featuredPages;
            }
            
            foreach($mergedPages as $featured) {
                if(empty($featured->image) || $featured->image === null) {
                    $featured->image = 'feature1.png';
                }
                $content .= $element->render('<p class="featured-title">' . $featured->label . '</p><img src="'.$this->view->baseUrl('/').'modules/featured/_thumbs/resized_'.$featured->image.'" /><p>' . substr($featured->content, 0, 100) . '...</p> <div class="clear"></div> <div class="featured-link"><a href="' . $featured->uri . '">Click here to learn more</a></div>');
            }
            return $content;
            
        } catch (Zend_Exception $e) {
            if($useLog) {
                $log->crit($e);
            } else {
                
            }
            echo $e->getMessage();
        }

    }
}
