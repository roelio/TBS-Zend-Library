<?php
class TBS_Resource_Google
{
    protected $_accessToken;

    protected $data = array();

    public function __construct($accessToken)
    {
        $this->_accessToken = $accessToken;
    }

    public function getId()
    {
        $profile = $this->getProfile();
        return $profile['id'];
    }

    public function getProfile()
    {
        $endpoint = 'https://www.googleapis.com/oauth2/v1/userinfo';
        return (array) json_decode($this->_getData('profile', $endpoint));
    }

    protected function _getData($label, $url, $redirects = true)
    {
        if (!$this->_hasData($label)) {
            $value = TBS_OAuth2_Consumer::getData($url,
                                       $this->_accessToken['access_token'],
                                       $redirects);
            $this->_setData($label, $value);
        }
        return $this->data[$label];
    }

    protected function _setData($label, $value)
    {
        $this->data[$label] = $value;
    }

    protected function _hasData($label)
    {
        return isset($this->data[$label]) && (NULL !== $this->data[$label]);
    }
}