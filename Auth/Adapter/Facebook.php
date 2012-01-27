<?php
class TBS_Auth_Adapter_Facebook implements Zend_Auth_Adapter_Interface
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
        $result['code'] = Zend_Auth_Result::FAILURE;
        $result['identity'] = NULL;
        $result['messages'] = array();
        $identity = new TBS_Auth_Identity_Facebook($this->_accessToken);
        if (NULL !== $identity->getId()) {
            $result['code'] = Zend_Auth_Result::SUCCESS;
            $result['identity'] = $identity;
        }

        return new Zend_Auth_Result($result['code'], $result['identity'],
                          $result['messages']);
    }

    public static function getAuthorizationUrl()
    {
        $options = Zend_Registry::get('config');
        return TBS_OAuth2_Consumer::getAuthorizationUrl($options['facebook']);
    }

    protected function _setRequestToken($requestToken)
    {
        if(NULL === $requestToken) return;
        $this->_options['code'] = $requestToken;

        $accesstoken = TBS_OAuth2_Consumer::getAccessToken($this->_options);

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
        $options = Zend_Registry::get('config');
        $this->_options = $options['facebook'];
    }
}