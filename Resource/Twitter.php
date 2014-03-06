<?php

namespace TBS\Resource;
class Twitter
{
	protected $_accessToken;
	protected $_options;
	
	protected $data = array();

	public function __construct($accessToken,$options)
	{
		$this->_accessToken = $accessToken;
		$this->_options = $options;
	}

	public function getStatus() {
	    $endpoint = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		return json_decode($this->_getData('status', $endpoint));
	}

	public function getId() {
	    $profile = $this->getProfile();
		return $profile['id_str'];
	}

	public function getProfile() {
	    $endpoint = 'https://api.twitter.com/1.1/users/show.json';
		return (array)json_decode($this->_getData('profile', $endpoint));
	}
	
	public function getPicture() {
	    $profile = $this->getProfile();
		return $profile['profile_image_url_https'];
	}
	
	protected function _getData($label, $url)
	{
	    if (!$this->_hasData($label)) {
    	    $client = $this->_accessToken->getHttpClient($this->_options);
    	    $client->setUri($url);
    	    // Set the request method to GET
    	    // Thanks to http://thebestsolution.org/zend-authentication-with-facebook-twitter-and-google/#comment-43496
    	    $client->setMethod(\Zend_Http_Client::GET);
    	    $client->setParameterGet('user_id', $this->_accessToken->user_id);
    	    $this->_setData($label, $client->request()->getBody());
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
