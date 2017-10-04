<?php
/**
 * @package    DD_SocialShare
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Abraham\TwitterOAuth;

/**
 * Class DD_SocialShareModelArticle
 *
 * @since  Version 1.0.0.0
 */
class DD_SocialShareModelArticle extends JModelAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 *
	 * @since    Version 1.0.0.0
	 */
	protected $text_prefix = 'COM_DD_SOCIALSHARE';

	/**
	 * The type alias for this content type (for example, 'com_dd_gmaps_locations.location').
	 *
	 * @var    string
	 *
	 * @since    Version 1.0.0.0
	 */
	public $typeAlias = 'com_dd_socialshare.article';

	/**
	 * The context used for the associations table
	 *
	 * @var    string
	 *
	 * @since    Version 1.0.0.0
	 */
	protected $associationsContext = 'com_dd_socialshare.item';

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since    Version 1.1.0.1
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing article.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_dd_gmaps_locations.location.' . (int) $record->id);
		}

		// New article, so check against the category.
		if (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_dd_gmaps_locations.category.' . (int) $record->catid);
		}

		// Default to component settings if neither article nor category known.
		return parent::canEditState();
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 *
	 * @since    Version 1.0.0.0
	 */
	public function getTable($type = 'Article', $prefix = 'DD_SocialShareTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		return parent::getItem($pk);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_dd_socialshare.article', 'article', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since    Version 1.1.0.1
	 */
	public function save($data)
	{
		if (parent::save($data))
		{
			return true;
		}

		return false;
	}

	/**
	 * shareFacebook
	 *
	 * Posting status messages to a Facebook account
	 * Adapted from: http://talkerscode.com/webtricks/auto-post-on-facebook-using-php.php
	 *
	 * @return bool
	 */
	public function shareFacebook()
	{
		// Include facebook-sdk
		require_once('../libraries/facebook.php');

		$accessToken                    = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';
		$facebookData                    = array();
		$facebookData['consumer_key']    = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';
		$facebookData['consumer_secret'] = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';

		$title       = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';
		$targetUrl   = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';
		$imgUrl      = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';
		$description = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';

		$facebook = new FacebookApi($facebookData);

		if ($facebook->share($title, $targetUrl, $imgUrl, $description, $accessToken))
		{
			return true;
		}

		return false;
	}

	/**
	 * shareTwitter
	 *
	 * Posting status messages to a Twitter account
	 * Adapted from: http://www.tech-faq.com/send-tweets-to-your-twitter-account-via-php.html
	 *
	 * @return bool
	 */
	public function shareTwitter()
	{
		// Include twitter-oauth
		require_once('../libraries/twitter-oauth/src/TwitterOAuth.php');

		// Set keys
		$consumerKey       = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';
		$consumerSecret    = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';
		$accessToken       = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';
		$accessTokenSecret = 'XXXXXXXXXXXXXXXXXXXXXXXXXXX';

		// Create object
		$tweet = new TwitterOAuth\TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

		// Set status message
		$tweetMessage = 'This is a tweet to my Twitter account via PHP.';

		// Check for 140 characters
		if (strlen($tweetMessage) <= 140)
		{
			// Post the status message
			$tweet->post('statuses/update', array('status' => $tweetMessage));

			return true;
		}

		return false;
	}
}
