<?php
class Slider_Form_Manage extends Zend_Form
{
    public function init() 
    {
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setMethod('post');
        $slideId = new Zend_Form_Element_Hidden('slideId');
        
        $image = new System_Form_Element_Thumbnail('image');
        $image->setLabel('Slider Image');
        $image->setDestination($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'slider');
        $image->addValidator('Count', false, 1);
        $image->addValidator('Size', false, 1002400);
        $image->addValidator('Extension', false, 'jpg,png,gif');
        $image->setOptions(array('thumbMaxHeight' => '350', 'thumbMaxWidth' => '580', 'thumbNamePrefix' => 'resized_'));
        
        
        $order = new Zend_Form_Element_Text('order');
        $order->setRequired(true);
        $order->setLabel('Slide Order');
        
        
        $content = new Zend_Form_Element_Textarea('content');
        $content->setAttrib('class', 'ckeditor');
        
        
        $submit = new Zend_Form_Element_Submit('save', array('ignore' => true));
        
        $this->addElements(array(
                                $slideId, 
                                $order, 
                                $image, 
                                //$content, 
                                $submit));
    }
}