<?php
class TBS_Auth_Identity_Google extends TBS_Auth_Identity_Generic
{
   protected $_api;
 
   public function __construct($token)
   {
		$this->_api = new TBS_Resource_Google($token);
		$this->_name = 'google';
		$this->_id = $this->_api->getId();
   }
 
	public function getApi()
	{
		return $this->_api;
	}
}