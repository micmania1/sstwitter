<?php

/**
 * Provides the extra fields needed to store a user
 *
 * @package sstwitter
 * @subpackage extension
**/
class TwitterUser extends DataExtension {

	/**
	 * Stores the users' instance of Twitter
	 *
	 * @var Twitter
	**/
	static $twitter;

	/**
	 * Whether or not to allow duplicate Twitter accounts on the same DataObject type.
	 *
	 * @var boolean
	**/
	protected $allowDuplicateTwitterAccounts = false;

	/**
	 * Whether or not to overwrite existing Twitter account data on the current DataObject.
	 *
	 * @var boolean
	**/
	protected $overwriteExistingTwitterAccount = false;
    

	static $db = array(
		"TwitterUserID" => "Varchar(255)", // Use a string to support 64-bit integers on 32-bit systems
		"TwitterScreenName" => "Varchar(255)",
		"TwitterAccessToken" => "Varchar(255)",
		"TwitterAccessSecret" => "Varchar(255)"
	);
    

	/**
	 * This method holds the functionality to complete the oauth flow through the CMS
	 *
	 * @param $fields FieldList
	**/
	public function updateCMSFields(FieldList $fields) {
		$fields->push(HeaderField::create("Access", 3));
		$fields->push(TextField::create("TwitterAccessToken", "Access Token"));
		$fields->push(PasswordField::create("TwitterAccessSecret", "Access Secret"));
		$fields->push(HeaderField::create("User Details", 3));
		$fields->push(TextField::create("TwitterUserID", "User ID"));
		$fields->push(TextField::create("TwitterScreenName", "Screen Name"));
	}
    
	/**
	 * Set whether or not to allow duplicate twitter accounts on this type of DataObject
	 *
	 * @param $bool boolean
	**/
	public function setAllowDuplicateTwitterAccounts($bool) {
		$this->allowDuplicateTwitterAccounts = (bool) $bool;
	}

	/**
	 * Fetch whether or not we're allowing duplicate twitter accounts on this dataobject type.
	 *
	 * @return boolean
	**/
	public function getAllowDuplicateTwitterAccounts() {
		return (bool) $this->allowDuplicateTwitterAccounts;
	}

	/**
	 * Set whether or not we should overwrite existing records.
	 *
	 * @param $bool boolean
	**/
	public function setOverwriteExistingTwitterAccount($bool) {
		$this->overwriteExistingTwitterAccount = (bool) $bool;
	}

	/**
	 * Get whether or not we can overwrite existing twitter account data.
	 *
	 * @return boolean
	**/
	public function getOverwriteExistingTwitterAccount() {
		return (bool) $this->overwriteExistingTwitterAccount;
	}


	/**
	 * Validate writing of the Twitter Account
	 *
	 * @param $validation ValidationResult
	**/
	public function validate(ValidationResult $validation) {
		if($this->owner->TwitterUserID && $this->owner->TwitterScreenName && $this->owner->TwitterAccessToken && $this->owner->TwitterAccessSecret) {
		    // Check to see if the user is already connected to an account & whether it matters.
		    if($this->getOverwriteExistingTwitterAccount() == false) {
		    	$changed = $this->owner->getChangedFields();
		        if($this->owner->isChanged("TwitterUserID") && trim($changed['TwitterUserID']['before']) != "") {
		           	$validation->error("You already have a Twitter account connected.");
		        }
		    }
		    
		    // Check to see if there are other types of this DataObject also connected to this Twitter account (if it matters)
		    if($this->getAllowDuplicateTwitterAccounts() == false) {
		        $duplicate = DataList::create($this->owner->ClassName)
		            ->filter("TwitterUserID", $this->owner->TwitterUserID);
		        
		        // Exclude the current user from the search.
		        if($this->owner->ID)
		            $duplicate->exclude("ID", $this->owner->ID);
		            
		        if($duplicate->first())
		            $validation->error("Your Twitter account is already connected to another ". $this->owner->singular_name().".");
		    }
		}
	}


	/**
	 * This connects the given twitter account to the current DataObject
	 *
	 * @param $id string - Twitter id passed as a string (64-bit int)
	 * @param $screen_name string - Twitter Handle
	 * @param $access_token string - users' access token
	 * @param $access_secret string - users' access secret
	 *
	 * @return boolean
	**/
	public function connectTwitterAccount($id, $screen_name, $access_token, $access_secret) {
		// Write our values to the DataObject
		$this->owner->TwitterUserID = $id;
		$this->owner->TwitterScreenName = $screen_name;
		$this->owner->TwitterAccessToken = $access_token;
		$this->owner->TwitterAccessSecret = $access_secret;
		
		return (bool) $this->owner->write();
	}


	/**
	 * This will disconnect a DataObject from its associated Twitter account.
	 *
	 * @return boolean
	**/
	public function disconnectTwitterAccount() {
		// Write our values to the DataObject
		$this->owner->TwitterUserID = "";
		$this->owner->TwitterScreenName = "";
		$this->owner->TwitterAccessToken = "";
		$this->owner->TwitterAccessSecret = "";
		
		return (bool) $this->owner->write();
	}
}

