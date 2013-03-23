<?php

// Add SiteConfig extensions
DataObject::add_extension("SiteConfig", "TwitterUser");
DataObject::add_extension("SiteConfig", "TwitterApp");


// Add Member extensions
DataObject::add_extension("Member", "TwitterUser");

