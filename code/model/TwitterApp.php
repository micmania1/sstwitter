<?php

/**
 * Twitter Application
 * 
 * @package sstwitter
 * @subpackage model
**/
class TwitterApp extends DataObject implements TemplateGlobalProvider
{

    /**
     * Stores the current instance of Twitter
     *
     * @var Twitter
    **/
    protected $twitter;

    public static $db = array(
        "EnableTwitterLogin" => "Boolean",
        "TwitterConsumerKey" => "Varchar(255)",
        "TwitterConsumerSecret" => "Varchar(255)"
    );

    public static $defaults = array(
        "EnableTwitterLogin" => true
    );
    
    
    /**
     * Returns the current calsses instance of Twitter.
     *
     * @deprecated 1.1
     * @return Twitter
    **/
    public static function curr()
    {
        Deprecation::notice("1.1", "Twitter::curr() is deprecated.", Deprecation::SCOPE_GLOBAL);
        return TwitterApp::get()->first()->getTwitter();
    }

    /**
     * Creates and returns an instance of Twitter if the Consumer key & secret are set.
     *
     * @return Twitter or false
    **/
    public function getTwitter()
    {
        if ($this->twitter) {
            return $this->twitter;
        }
    
        if ($this->TwitterConsumerKey && $this->TwitterConsumerSecret) {
            return $this->twitter = new Twitter($this->TwitterConsumerKey, $this->TwitterConsumerSecret);
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
        $twitter = TwitterApp::get()->first();
        if ($twitter && $twitter->EnableTwitterLogin == 1) {
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

    public function getCMSFields()
    {
        $fields = new FieldList();
        $fields->push(HeaderField::create("Application Settings", 3));
        $fields->push(TextField::create("TwitterConsumerKey", "Consumer Key"));
        $fields->push(PasswordField::create("TwitterConsumerSecret", "Consumer Secret"));
        $fields->push(OptionsetField::create("EnableTwitterLogin", "Twitter Login", array(
            0 => "Disabled",
            1 => "Enabled"
        )));
        $this->extend("updateCMSFields", $fields);
        return $fields;
    }
    
    /**
     * Setup a default Twitter app
    **/
    public function requireDefaultRecords()
    {
        $twitter = TwitterApp::get()->count();
        if (!$twitter) {
            $twitter = TwitterApp::create();
            $twitter->write();
        }
    }
}
