<?php

// Add SiteConfig extensions
DataObject::add_extension("TwitterApp", "TwitterUser");


// Add Member extensions
DataObject::add_extension("Member", "TwitterUser");

Director::set_environment_type("dev");
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
