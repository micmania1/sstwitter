<?php

/**
 * Allows the user to setup their app details. This extension is intended for use with SiteConfig.
 * 
 * @package sstwitter
 * @subpackage extension
**/
class TwitterApp extends DataExtension implements TemplateGlobalProvider
{

    /**
     * Stores the current instance of Twitter
     *
     * @var Twitter
    **/
    public static $twitter;

    public static $db = array(
        "EnableTwitterLogin" => "Boolean",
        "TwitterConsumerKey" => "Varchar(255)",
        "TwitterConsumerSecret" => "Varchar(255)"
    );

    public static $defaults = array(
        "EnableTwitterLogin" => true
    );

    /**
     * Creates and returns an instance of Twitter if the Consumer key & secret are set.
     *
     * @return Twitter or false
    **/
    public static function curr()
    {
        if (self::$twitter) {
            return self::$twitter;
        }
    
        $config = SiteConfig::current_site_config();
        if ($config->TwitterConsumerKey && $config->TwitterConsumerSecret) {
            return self::$twitter = new Twitter($config->TwitterConsumerKey, $config->TwitterConsumerSecret);
        }
        return false;
    }
    
    /**
     * Return Global variables for use in templates.
     *
     * @return array
    **/
    public static function get_template_global_variables()
    {
        return array(
            "TwitterConnectURL" => "connect_url",
            "TwitterLoginURL" => "login_url",
            "TwitterDisconnectURL" => "disconnect_url"
        );
    }
    
    /**
     * Returns a URL for logged in users to connect their Twitter accounts
     *
     * @return string URL
    **/
    public static function connect_url()
    {
        return Controller::join_links("twitter", "connect");
    }
    
    /**
     * Returns a URL for users to login with their twitter accounts.
     *
     * @return string URL or false when Login is disabled
    **/
    public static function login_url()
    {
        if (SiteConfig::current_site_config()->EnableTwitterLogin == 1) {
            return Controller::join_links("twitter", "login");
        }
        return false;
    }
    
    
    /**
     * Return a URL to allow users to disassociate their Twitter accounts.
     *
     * @return string URL
    **/
    public static function disconnect_url()
    {
        return Controller::join_links("twitter", "disconnect");
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab("Root.Twitter", array(
            HeaderField::create("Application Settings", 3),
            TextField::create("TwitterConsumerKey", "Consumer Key"),
            TextField::create("TwitterConsumerSecret", "Consumer Secret"),
            OptionsetField::create("EnableTwitterLogin", "Twitter Login", array(
                0 => "Disabled",
                1 => "Enabled"
            ))
        ));
    }
}
