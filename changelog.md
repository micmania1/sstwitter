SSTwitter Change Log
============================

1.1
----------------------------
* Changed TwitterApp from a DataExtension to a DataObject and used this for its base rather than SiteConfig.
* Added TwitterAdmin to the CMS.
* Deprecated TwitterApp::curr() in favour of TwitterApp::get()->first()
* Removed _config.php in favour of the new Config API. Config is now in _config/_config.yml
* TwitterUser no longer extends SiteConfig. Instead, this extends TwitterApp.
