<?php
/**
 * @package    DD_SocialShare
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2019 HR-IT-Solutions GmbH
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

/**
 * Class DD_SocialShareModelDashboard
 *
 * @since  Version 1.0.0.0
 */
class DD_SocialShareModelDashboard extends JModelList
{
	/**
	 * @return bool
	 * @throws Exception
	 */
	public function facebookOAuth(){

		$app = JFactory::getApplication();
		$input = $app->input;

		// Access via: &facebook_oauth=true  OR
		// Access via  &code={TOKEN}
		if (!$input->get('facebook_oauth') && !$input->get('code'))
		{
			return false;
		}

		// Component Params
		$comparams  = JComponentHelper::getParams('com_dd_socialshare');

		// Facebook
		$oauth = new JFacebookOAuth;

		// OAuth
		$oauth->setOption('sendheaders', true)
			->setOption('clientid', $comparams->get('facebook_consumerKey'))
			->setOption('clientsecret', $comparams->get('facebook_consumerSecret'))
			->setOption('redirecturi', JUri::base() . 'index.php?option=com_dd_socialshare')
			->setOption('authmethod', 'get')
			->setScope('publish_pages,publish_actions');

		if ($oauth->authenticate())
		{
			$app->enqueueMessage('Facebook OAuth Successfull');

			// Bind accessToken to component params
			$comparams->set('facebook_accessToken', $oauth->getToken()['access_token']);
			$comparams->set('facebook_accessToken_type', $oauth->getToken()['token_type']);
			$comparams->set('facebook_accessToken_created', $oauth->getToken()['created']);

			$componentid = JComponentHelper::getComponent('com_dd_socialshare')->id;

			// DB Store
			$table = JTable::getInstance('extension');
			$table->load($componentid);
			$table->bind(array('params' => $comparams->toString()));

			if ($table->store())
			{
				$app->redirect('index.php?option=com_config&view=component&component=com_dd_socialshare#facebook_api_settings',
					'Access token has been saved in the component configuration');
			}
		}

	}

	public function facebookTestPost(){

		$app = JFactory::getApplication();
		$input = $app->input;

		if (!$input->get('facebook_testpost'))
		{
			return false;
		}

		// TEST PARAMS
		$title = 'Test';
		$description = 'Lorem ipsum dolor sit amet';
		$imgUrl = null;
		$targetUrl = JUri::root();


		// Prepare Description
		$description = "$title\n$description";

		// Prepare Image URL
		if ($imgUrl != '')
		{
			$imgUrl = JUri::root() . $imgUrl;
		}
		else
		{
			$imgUrl = null;
		}

		// Get Component Params
		$comparams  = JComponentHelper::getParams('com_dd_socialshare');

		$oauth = new JFacebookOAuth;

		$token = array(
			'access_token' => $comparams->get('facebook_accessToken'),
			'token_type' => $comparams->get('facebook_accessToken_type'),
			'created' => $comparams->get('facebook_accessToken_created'));

		$oauth->setToken($token);

		$facebook = new JFacebook($oauth);

		$accounts = $facebook->user->get('me/accounts');

		// Loop through accounts
		foreach ($accounts->data as $account)
		{
			if ($account->id == $comparams->get('facebook_accountID'))
			{
				$facebook->oauth->setToken(array('access_token' => $account->access_token));
				break;
			}
		}

		return $facebook->user->createPost(
			$comparams->get('facebook_accountID'),
			$description,
			$targetUrl
		);
	}
}
