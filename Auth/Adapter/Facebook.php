<?php
namespace TBS\Auth\Adapter;
use \TBS\Auth\Identity\Facebook as Identity;
use \TBS\OAuth2\Consumer;

use \Zend_Auth_Result as Result;
use \Zend_Registry as Registry;

class Facebook implements \Zend_Auth_Adapter_Interface
{
    protected $_accessToken;
    protected $_requestToken;
    protected $_options;

    public function __construct($requestToken = NULL)
    {
        $this->_setOptions();
        $this->_setRequestToken($requestToken);
    }

    public function authenticate()
    {
        $result = array();
        $result['code'] = Result::FAILURE;
        $result['identity'] = NULL;
        $result['messages'] = array();
        $identity = new Identity($this->_accessToken);
        if (NULL !== $identity->getId()) {
            $result['code'] = Result::SUCCESS;
            $result['identity'] = $identity;
        }

        return new Result($result['code'], $result['identity'],
                          $result['messages']);
    }

    public static function getAuthorizationUrl()
    {
        $config = Registry::get('config');
        $options = is_object($config) ? $config->toArray() : $config;
        return Consumer::getAuthorizationUrl($options['facebook']);
    }

    protected function _setRequestToken($requestToken)
    {
        if(NULL === $requestToken) return;
        $this->_options['code'] = $requestToken;

        $accesstoken = Consumer::getAccessToken($this->_options);

        $accesstoken['timestamp'] = time();
        $this->_accessToken = $accesstoken;
    }
    
    public function setAccessToken($token) {
        $accesstoken['timestamp'] = time();
        $accesstoken['access_token'] = $token;
        $this->_accessToken = $token;
    }

    protected function _setOptions($options = null)
    {
        
      $config = Registry::get('config');
      $options = is_object($config) ? $config->toArray() : $config;
        $this->_options = $options['facebook'];
    }
}
