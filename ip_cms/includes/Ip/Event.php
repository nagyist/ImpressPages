<?php 
/**
 * @package	ImpressPages
 * @copyright	Copyright (C) 2011 ImpressPages LTD.
 * @license	GNU/GPL, see ip_license.html
 */

namespace Ip;


if (!defined('CMS')) exit;  

/**
 * 
 * Event dispatcher class
 * 
 */ 
class Event{
    
    private $object;
    private $name;
    private $values;
    private $processed;

    public function __construct($object, $name, $values) {
        $this->object = $object;
        $this->name = $name;
        $this->values = $values;
        $this->processed = 0;
    }
    
    public function getObject () {
        return $this->object;    
    }
    
    public function getName () {
        return $this->name;
    }
    
    public function getValues () {
        return $this->data;    
    }
    
    public function setValue ($key, $value) {
        $this->values[$key] = $value;        
    }
    
    public function issetValue ($key) {    
        return isset($this->values[$key]);
    }

    public function unsetValue ($key) {
        unset($this->values[$key]);        
    }
    
    public function addProcessed () {
        $this->processed++;
    }
    
    public function getProcessed () {
        return $this->processed;        
    }
    
    public function getValue ($key) {
        if (isset($this->values[$key])) {
            return $this->values[$key];   
        }
        
        $trace = debug_backtrace();
        throw new \Exception(
            'Undefined data variable via getData(): ' . $key .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
    }
    
    public function valueExist($valueKey) {
        return isset($this->data[$valueKey]);
    }
    
}
