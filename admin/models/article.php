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
use Joomla\Utilities\ArrayHelper;

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
	 * @return  \JObject|boolean  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();

		if ($pk > 0)
		{
			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());

				return false;
			}
		}

		// Convert to the \JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = ArrayHelper::toObject($properties, '\JObject');

		if (property_exists($item, 'params'))
		{
			$registry = new Registry($item->params);
			$item->params = $registry->toArray();
		}

		// Set null
		if ($item->facebook == '0000-00-00 00:00:00')
		{
			$item->facebook = null;
		}

		// Set null
		if ($item->twitter == '0000-00-00 00:00:00')
		{
			$item->twitter = null;
		}

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm|boolean  A JForm object on success, false on failure
	 *
	 * @since    Version 1.1.0.1
	 */
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
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since    Version 1.1.0.1
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_dd_socialshare.edit.article.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		// If there are params fieldsets in the form it will fail with a registry object
		if (isset($data->params) && $data->params instanceof Registry)
		{
			$data->params = $data->params->toArray();
		}

		// Get associated content id
		$content_id = (int) $app->input->get('content_id', '0', 'int');

		if ($content_id !== 0)
		{
			JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'ContentModel');
			$model = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));

			$item = $model->getItem((int) $content_id);

			$item->introtext = trim(preg_replace('/\s+/', ' ', strip_tags($item->introtext)));


			$router = JApplicationCms::getInstance('site')->getRouter();
			$url = $router->build('/index.php?option=com_content&view=article&id=' . $item->id);
			$url = rtrim(JUri::root(), '/') . str_replace(JUri::base(true), "", $url->toString());

			$data->set('content_id', $content_id);
			$data->set('title', $item->title);
			$data->set('alias', $item->alias);
			$data->set('facebook_post_title', $item->title);
			$data->set('facebook_post_url', $url);

			if (isset($item->images['image_intro']))
			{
				$data->set('facebook_post_image', $item->images['image_intro']);
			}

			$data->set('facebook_post_text', $item->introtext);
			$data->set('twitter_post_text', substr($item->introtext, 0, 140));
		}

		$this->preprocessData('com_dd_socialshare.article', $data);

		return $data;
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
	 * shareSave
	 *
	 * execute share{SocialPlattform} event AND
	 * execute save model
	 *
	 * @return boolean | array with success data for the view
	 */
	public function shareSave()
	{
		$app    = JFactory::getApplication();
		$input  = $app->input;

		$event      = $input->post->get('event', '', 'STRING');
		$id         = $input->post->get('id',    '', 'INT');
		$content_id = $input->post->get('cid',   '', 'INT');

		switch ($event)
		{
			case 'facebook':
				$state = $this->shareFacebook(
					$input->post->get('title',  '', 'STRING'),
					$input->post->get('url',    '', 'STRING'),
					$input->post->get('image',  '', 'STRING'),
					$input->post->get('url',    '', 'STRING')
				);
				break;

			case 'twitter':
				$state = $this->shareTwitter(
					$input->post->get('desc',   '', 'STRING')
				);
				break;

			default:
				return false;
		}

		$data = array($event => JFactory::getDate()->toSql(), 'id' => $id, 'content_id' => $content_id);

		if (!$state)
		{
			return false;
		}

		if ($this->save($data))
		{
			// Last InserId
			$data['id'] = (int) $this->getState($this->getName() . '.id');

			return array_merge(array('success' => 'true', 'date' => $data[$event]), $data);
		}
		else
		{
			return false;
		}
	}

	/**
	 * shareFacebook
	 *
	 * Posting status messages to a Facebook account
	 * Adapted from: http://talkerscode.com/webtricks/auto-post-on-facebook-using-php.php
	 *
	 * @param   string  $title        title
	 * @param   string  $targetUrl    absoulte url to the article
	 * @param   string  $imgUrl       absoulte url to the article introimage
	 * @param   string  $description  article description
	 *
	 * @return  bool
	 */
	public function shareFacebook($title, $targetUrl, $imgUrl, $description)
	{
		// Testing
		return true;

		// Include facebook-sdk
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/facebook.php';

		$params = JComponentHelper::getParams('com_dd_socialshare');

		$accessToken                     = $params->get('facebook_accessToken');
		$facebookData                    = array();
		$facebookData['consumer_key']    = $params->get('facebook_consumerKey');
		$facebookData['consumer_secret'] = $params->get('facebook_consumerSecret');

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
	 * @param   string  $tweetMessage  tweet status message
	 *
	 * @return  bool
	 */
	public function shareTwitter($tweetMessage)
	{
		return true;

		// Include twitter-oauth
		require_once  JPATH_COMPONENT_ADMINISTRATOR . '/libraries/twitter-oauth/autoload.php';

		$params = JComponentHelper::getParams('com_dd_socialshare');

		// Set keys
		$consumerKey       = $params->get('twitter_consumerKey');
		$consumerSecret    = $params->get('twitter_consumerSecret');
		$accessToken       = $params->get('twitter_accessToken');
		$accessTokenSecret = $params->get('twitter_accessTokenSecret');

		// Create object
		$tweet = new TwitterOAuth\TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

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
