<?php
   class XHtmlSimpleElement {
      var $_element;
      var $_siblings = array();
      var $_htmlcode;   
      var $_attributes = array();
      
      function XHtmlSimpleElement($element = null) {
         $this->_element = $this->is_element();
      }
   
      function set_style($style) {
         $this->set_attribute("style", $style);
      }
      
      function set_class($class) {
         $this->set_attribute("class", $class);
      }
   
      function is_element() {
         return str_replace("xhtml_", "", get_class($this));
      }
   
      function _html() {
         $this->_htmlcode = "<";
         foreach ($this->_attributeCollection as $attribute => $value) {
            if (!empty($value)) $this->_htmlcode .= " {$attribute}=\"{$value}\"";
         }
         $this->_htmlcode .= "/>";
         return $this->_htmlcode;
      }
      
      function fetch() {
         return $this->_html();
      }

      function show()  {
         echo $this->fetch();
      }
   
      function set_attribute($attr, $value) {
         $this->_attributes[$attr] = $value;
      }
   }
   
   class XHtmlElement extends XHtmlSimpleElement {
      var $_text     = null;   
      var $_htmlcode = "";
      var $_siblings = array();
   
      function XHtmlElement($text = null) {
         XHtmlSimpleElement::XHtmlSimpleElement();
         if ($text) $this->set_text($text);
      }
   
      function add(&$object) {
         array_push($this->_siblings, $object);
      }
   
      function set_text($text) {
         if ($text) $this->_text = htmlspecialchars($text);   
      }
   
      function fetch() {
         return $this->_html();
      }
   
      function _html() {
         $this->_htmlcode = "<{$this->_element}";
         foreach ($this->_attributes as $attribute =>$value) {
            if (!empty($value))   $this->_htmlcode .= " {$attribute} =\"{$value}\"";
         }
         $this->_htmlcode .= ">";
         if ($this->_text) { 
            $this->_htmlcode .= $this->_text;
         }
         foreach ($this->_siblings as $obj) {
            $this->_htmlcode .= $obj->fetch();
         }      
         $this->_htmlcode .= "</{$this->_element}>";
         return $this->_htmlcode;
      }
   
      function get_siblings() {
         return $this->_siblings;
      }
      
      function has_siblings() {
         return (count($this->_siblings) != 0);
      }
   }
   
   class XHTML_Button extends XHtmlElement {
      function XHTML_Button ($name, $text = null) {
         parent::XHtmlElement();
         $this->set_attribute("name", $name);
         if ($text) $this->set_text($text);
      }
   }
   
   class XHTML_Option extends XHtmlElement {
      function XHTML_Option($text, $value = null) {
         XHtmlElement::XHtmlElement(null);         
         $this->set_text($text);
      }
   }
   
   class XHTML_Select extends XHTMLElement {
      var $_data;
      function XHTML_Select ($name, $multiple = false, $size = null) {
         XHtmlElement::XHtmlElement();               
         $this->set_attribute("name", $name);
         if ($multiple) $this->set_attribute("multiple","multiple");
         if ($size) $this->set_attribute("size",$size);
      }
      
      function set_data(&$data, $delim = ",") {
         switch (gettype($data)) {
            case "string":
               $this->_data = explode($delim, $data);
               break;
            case "array":
               $this->_data = $data;
               break;
               
            default:
               break;
         }
      }
      
      function fetch() {
         if (isset($this->_data) && $this->_data) {
            foreach ($this->_data as $value) { $this->add(new XHTML_Option($value)); }
         }
         return parent::fetch();
      }
   }
?>
