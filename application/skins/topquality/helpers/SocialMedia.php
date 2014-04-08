<?php
/**
 *
 * @author Randall
 * @version 
 */
require_once 'System/View/Helper/Abstract.php';

/**
 * 
 *
 * @uses viewHelper System_View_Helper_Abstract
 */
class Topquality_View_Helper_SocialMedia extends System_View_Helper_Abstract
{
    public function socialMedia($wrapperTag = 'ul', $itemTag = 'li', array $wrapperAttribs = array( 'class' => 'social-media' ), $itemAttribs = array( 'class' => 'social social-mobile' ))
    {
        $element = new Zend_Form_Decorator_HtmlTag(array_merge(array('tag' => $wrapperTag), $wrapperAttribs));
        $item = new Zend_Form_Decorator_HtmlTag(array_merge(array('tag' => $itemTag), $itemAttribs));
        $social = '';
        if (!empty($this->appSettings->twitterUrl)) {
            $twitter = $item->render('<a href=" ' . $this->appSettings->twitterUrl . ' "><img src="/skins/topquality/images/twitter.png" /></a>');
        }
        if (!empty($this->appSettings->facebookUrl)) {
            $facebook = $item->render('<a href=" ' . $this->appSettings->facebookUrl . ' "><img src="/skins/topquality/images/facebook.png" /></a>');
        }
        $social = $twitter . ' ' . $facebook;
        return $element->render($social);
    }
}