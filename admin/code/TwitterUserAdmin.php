<?php

/**
 * TwitterAdmin extension for user-specific actions.
 *
 * @package sstwitter
 * @subpackage extension
**/
class TwitterUserAdmin extends DataExtension {
	
	static $allowed_actions = array(
		"authorize"
	);
	
	public function onAfterInit() {
		Requirements::css("sstwitter/admin/css/screen.css");
		Requirements::javascript("sstwitter/admin/javascript/TwitterAdmin.js");
	}
	
	public function updateCMSActions(FieldList $fields) {
		$twitterApp = $this->getTwitterApp();
		$twitter = $twitterApp->getTwitter();
		
		if($twitter) {
			$twitter->setOAuthCallback(Director::absoluteURL(Controller::curr()->Link("authorize")));
			Session::set("TwitterRequest", $twitter->getRequestToken());
			
			// Add the authorize action if consumer keys are set.
			if($twitterApp->TwitterConsumerKey && $twitterApp->TwitterConsumerSecret) {
				if($url = $twitter->getLoginURL()) {
					$buttonText = ($twitterApp->TwitterAccessToken && $twitterApp->TwitterAccessSecret) ? "Update Twitter Account" : "Authorize a Twitter Account";
					$fields->push(
						FormAction::create("authorize", $buttonText)
							->setUseButtonTag(true)
							->addExtraClass("sstwitter-ui-action-twitter")
							->setAttribute("data-icon", "twitter")
							->setAttribute("data-role", "authorize")
							->setAttribute("data-url", $url)
					);
				}
			}
		}
		return $fields;
	}
	
	/**
	 * Authorizes the current users' Twitter account and connects to it
	 * with the given DataObject.
	**/
	public function authorize() {
		$twitterApp = $this->getTwitterApp();
		$twitter = $twitterApp->getTwitter();
		$request = Session::get("TwitterRequest");
		if($request && $twitter->getOAuthVerifier()) {
			$twitter->setRequest($request);
			if($access = $twitter->getAccessToken()) {
				if($user = $twitter->getUser()) {
	            	$twitterApp->setOverwriteExistingTwitterAccount(true);
	                $write = $twitterApp->connectTwitterAccount(
	                	$user['id_str'], 
	                	$user['screen_name'], 
	                	$twitter->access()->token, 
	                	$twitter->access()->secret
	            	);
	            	if($write) {
	            		$this->owner->getEditForm()
	            			->sessionMessage("Your Twitter account has been connected.", "good");
            		} else {
            			$this->owner->getEditForm()
            				->sessionMessage("Unable to connect your Twitter account.", "bad");
        			}
	            }
			}
		}
		return $this->owner->redirect(Controller::curr()->Link());
	}
	
	/**
	 * Fetches the sites Twitter Applications.
	 *
	 * @return TwitterApp
	**/
	public function getTwitterApp() {
		return TwitterApp::get()->first();
	}
}

?>
