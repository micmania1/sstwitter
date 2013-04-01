<?php

/**
 * Admin interface to mange Twitter integration.
 *
 * @package sstwitter
 * @subpackage admin
**/
class TwitterAdmin extends LeftAndMain {

	static $menu_title = "Twitter Integration";
	
	static $url_segment = "twitter";
	
	static $allowed_actions = array(
		"EditForm"
	);
	
	static $menu_icon = "sstwitter/admin/images/menu-icons/twitter.png";
	
	protected $twitter;
	
	public function init() {
		parent::init();
		
		// Load Twitter App
		$this->getTwitterApp();
	}
	
	
	public function getEditForm($id = null, $field = null) {
		$form = parent::getEditForm($id, $field);
		$form->addExtraClass("center");
		
		// Setup Fields
		$form->setFields($this->twitter->getCMSFields());
		// Setup Actions
		$form->setActions($this->getCMSActions());
		// Populate Form
		$form->loadDataFrom($this->twitter);
		
		return $form;
	}
	
	public function getCMSActions() {
		//Setup Actions
		$actions = new FieldList();
		$actions->push(
			FormAction::create("save", "Save")->setUseButtonTag(true)
				->addExtraClass('ss-ui-action-constructive')->setAttribute('data-icon', 'accept')
		);
		$this->extend("updateCMSActions", $actions);
		return $actions;
	}
	
	/**
	 * Save the form in its current state.
	 * 
	 * @param $data array - Form data
	 * @param $form Form - Current Form
	 * @return SS_HTTPResponse
	**/
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
	
	
	/**
	 * Returns the Twitter App for the current site.
	 *
	 * @return TwitterApp
	**/
	public function getTwitterApp() {
		if($this->twitter) return $this->twitter;
		return $this->twitter = TwitterApp::get()->first();
	}
}

?>
