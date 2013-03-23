<?php

/**
 * Twitter Application tests.
 *
 * @package sstwitter
 * @subpackage tests
**/
class TwitterAppTest extends SapphireTest {
	
	/**
	 * Asserts that no app should be returned because no siteconfig has been setup with consumer keys.
	**/
	public function testGetNoApp() {
		// No SiteConfig has been setup yet so this should return false.
		$twitter = TwitterApp::curr();
		$this->assertEquals(false, $twitter, "Returned Twitter app without key entries.");
	}
	
	/**
	 * Sets up consumer keys and asserts that Twitter::curr() should return a valid instance of Twitter
	**/
	public function testGetApp() { 
		$siteConfig = SiteConfig::current_site_config();
		$siteConfig->TwitterConsumerKey = "testkey";
		$siteConfig->TwitterConsumerSecret = "testsecret";
		$siteConfig->write();
		
		$twitter = TwitterApp::curr();
		$this->assertInstanceOf("Twitter", $twitter, "Unable to fetch Twitter App.");
	}
}

