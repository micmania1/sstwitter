<?php

class TwitterUserTest extends SapphireTest {
		
	public function testConnectTwitterAccount() {
		$connect = false;
		$member = Member::get()->first();
		if($member) {
			$connect = $member->connectTwitterAccount("12345", "twitter", "000000", "111111");
		}
		$this->assertEquals(true, $connect, "Connect Twitter Account");
	}
	
	public function testDisconnectTwitterAccount() {
		$disconnect = false;
		$member = Member::get()->first();
		if($member) {
			$disconnect = $member->disconnectTwitterAccount();
		}
		$this->assertEquals(true, $disconnect, "Connect Twitter Account");
	}
}

