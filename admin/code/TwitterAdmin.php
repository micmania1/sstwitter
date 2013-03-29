<?php

/**
 * Admin interface to mange Twitter integration.
 *
 * @package sstwitter
 * @subpackage admin
**/
class TwitterAdmin extends LeftAndMain {

	static $menu_title = "Twitter Admin";
	
	static $url_segment = "twitter";
	
	static $allowed_actions = array(
		"EditForm"
	);
	
	public function init() {
		parent::init();
		Requirements::css("sstwitter/admin/css/screen.css");
	}
	
	
	public function getEditForm($id = null, $field = null) {
		$form = parent::getEditForm($id, $field);
		$form->addExtraClass("center");
		
		$twitter = $this->getTwitterApp();
		// Setup Fields
		$form->setFields($twitter->getCMSFields());
		
		//Setup Actions
		$actions = $form->Actions();
		$actions->push(
			FormAction::create("save", "Save")->setUseButtonTag(true)
				->addExtraClass('ss-ui-action-constructive')->setAttribute('data-icon', 'accept')
		);
		
		// Add the authorize action if consumer keys are set.
		if($twitter->hasExtension("TwitterUser") && $twitter->TwitterConsumerKey && $twitter->TwitterConsumerSecret) {
			$buttonText = ($twitter->TwitterAccessToken && $twitter->TwitterAccessSecret) ? "Update or Re-Authorize a Twitter Account" : "Authorize a Twitter Account";
			$actions->push(
				FormAction::create("authorize", $buttonText)->setUseButtonTag(true)
					->addExtraClass("sstwitter-ui-action-twitter")->setAttribute("data-icon", "twitter")
			);
		}
		
		$form->loadDataFrom($twitter);
		return $form;
	}
	
	public function getFormActions() {
		
	}
	
	public function save($data, $form) {
		$twitter = $this->getTwitterApp();
		$form->saveInto($twitter, $this->request);
		
		if($twitter->write()) {
			$form->sessionMessage("Twitter Application saved.", "good");
		} else {
			$form->sessionMessage("Unable to save Twitter Application", "bad");
		}
		return $this->getResponseNegotiator()->respond($this->request);
	}
	
	public function getTwitterApp() {
		return TwitterApp::get()->first();
	}
}

?>
