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
class Topquality_View_Helper_ImageSlider extends System_View_Helper_Abstract
{

    public function imageSlider ($wrapperTag = 'div', $wrapperAttribs = array())
    {
        $pages = new Page_Model_Page();
        $rotator = new Rotator_Model_Rotator();
        for ($i = 0; $i < 3; $i++) {         
            $wrapperAttribs = array('class' => 'pane pane' . $i);
            $element = new Zend_Form_Decorator_HtmlTag(array_merge(array('tag' => $wrapperTag), $wrapperAttribs));
            $content = 'public/modules/rotator/';
            $content .= $element->render($content);
            return $content;
        }
    }
}
