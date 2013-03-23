<?php

/**
 * Allows the user to setup their app details. This extension is intended for use with SiteConfig.
 * 
 * @package sstwitter
 * @subpackage extension
**/
class TwitterApp extends DataExtension {

	/**
	 * Stores the current instance of Twitter
	 *
	 * @var Twitter
	**/
	static $twitter;

	static $db = array(
		"EnableTwitterLogin" => "Boolean",
		"TwitterConsumerKey" => "Varchar(255)",
		"TwitterConsumerSecret" => "Varchar(255)"
	);

	static $defaults = array(
		"EnableTwitterLogin" => true
	);

	/**
	 * Creates and returns an instance of Twitter if the Consumer key & secret are set.
	 *
	 * @return Twitter or false
	**/    
	static public function curr() {
		if(self::$twitter) {
			return self::$twitter;
		}
	
		$config = SiteConfig::current_site_config();
		if($config->TwitterConsumerKey && $config->TwitterConsumerSecret) {
			return self::$twitter = new Twitter($config->TwitterConsumerKey, $config->TwitterConsumerSecret);
		}
		return false;
	}

	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldsToTab("Root.Twitter", array(
			HeaderField::create("Application Settings", 3),
			TextField::create("TwitterConsumerKey", "Consumer Key"),
			TextField::create("TwitterConsumerSecret", "Consumer Secret"),
			OptionsetField::create("Enable Twitter Login", "Twitter Login", array(
    			0 => "Disabled",
				1 => "Enabled"
			))
		));
	}
}

