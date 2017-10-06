<?php
/**
 * @package    DD_SocialShare
 */

defined('_JEXEC') or die;

/**
 * FacebookApi
 *
 * Posting status messages to a Facebook account
 * Adapted from: http://talkerscode.com/webtricks/auto-post-on-facebook-using-php.php
 */

require_once("facebook-sdk/src/facebook.php");

class FacebookApi
{

	var $consumer;
	var $token;
	var $method;
	var $http_status;
	var $last_api_call;
	var $callback;
	var $connection;
	var $access_token;

	function __construct($data)
	{
		$config = array();
		$config['appId'] = $data['consumer_key'];
		$config['secret'] = $data['consumer_secret'];

		$this->connection = new Facebook($config);

	}

	function share($title, $targetUrl, $imgUrl, $description, $access_token)
	{
		$this->connection->setAccessToken($access_token);
		$params["access_token"] = $access_token;

		if(!empty($title))
		{
			$params["message"] = $title;
			$params["name"] = $title;
		}

		if(!empty($targetUrl))
		{
			$params["link"] = $targetUrl;
		}

		if(!empty($imgUrl))
		{
			$params["picture"] = $imgUrl;
		}

		if(!empty($description))
		{
			$params["description"] = $description;
		}

		// post to Facebook
		try
		{
			$ret = $this->connection->api('/me/feed', 'POST', $params);
		}
		catch(Exception $e)
		{
			$e->getMessage();
		}

		return true;
	}

	function getLoginUrl($params)
	{
		return $this->connection->getLoginUrl($params);
	}

	function getContent($url)
	{
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_URL, $url);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ci, CURLOPT_HEADER, false);
		curl_setopt( $ci, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($ci);
		curl_close ($ci);
		return $response;
	}

}
