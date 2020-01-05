<?php
echo "<ul>";

// Converts the password from the 1.3 base64encoded to MD5 hash
// Do not do this unless we are upgrading

// function ConvertPassword( $db, $prefix)
// {
// 	$result = mysqli_query(  $db,  $db, "SELECT password FROM {$prefix}config LIMIT 1") or die("Error: ". mysqli_error(  $db ) );
// 	if( $row = mysqli_fetch_array( $result))
// 	{
// 		$adm_pass = base64_decode($row[0]);
// 		mysqli_query(  $db,  $db, "UPDATE {$prefix}config SET password=MD5('$adm_pass') LIMIT 1") or die("Error: ". mysqli_error(  $db ) );
// 		echo "<li style=\"list-style-type:none;\">Password updated from 1.3 to 1.4 hash ...</li>";
// 	}
// }

function Show_username_password(){
	// get post data
	$admin_user = addslashes($_POST['admin_user']);
	$admin_password = $_POST['admin_password'];
	echo "<b>Remember your data:</b><br />
	Username: <b>$admin_user</b><br />
	Password: <b>$admin_password</b><p />";
}

function Create13Tables( $db, $prefix)
{
	echo "<li style=\"list-style-type:none;\"><strong>Automatic creation of tables</strong></li><p />";
	// Config table
	mysqli_query(  $db, "
	CREATE TABLE IF NOT EXISTS {$prefix}config (
		admin varchar(20) NOT NULL default '',
		password varchar(90) NOT NULL default '',
		email varchar(90) NOT NULL default '',
		commentemail varchar(3) NOT NULL default '',
		template varchar(150) NOT NULL default '',
		imagepath varchar(150) NOT NULL default '',
		siteurl varchar(100) NOT NULL default '',
		sitetitle varchar(100) NOT NULL default '',
		langfile varchar(100) NOT NULL default '',
		calendar varchar(30) NOT NULL default '',
		crop varchar(3) NOT NULL default '',
		thumbwidth int(11) NOT NULL,
		thumbheight int(11) NOT NULL,
		thumbnumber int(11) NOT NULL,
		compression int(11) NOT NULL,
		dateformat varchar(30) NOT NULL default ''
	)
	") or die( "Error: ". mysqli_error( $db ));
	echo "<li style=\"list-style-type:none;\">Table {$prefix}config created ...</li>";

	// Categories Table
	mysqli_query(  $db, "
	CREATE TABLE IF NOT EXISTS {$prefix}categories (
		id int(11) NOT NULL auto_increment,
		name varchar(100) NOT NULL default '',
		KEY id (id)
	)
	") or die("Error: ". mysqli_error( $db ));

	mysqli_query(  $db, "
	INSERT INTO {$prefix}categories VALUES (0, 'default')
	") or die("Error: ". mysqli_error(  $db ) );
	echo "<li style=\"list-style-type:none;\">Table {$prefix}categories created ...</li>";

	// Pixelpost table
	mysqli_query(  $db, "
	CREATE TABLE IF NOT EXISTS {$prefix}pixelpost (
		id int(11) NOT NULL auto_increment,
		datetime datetime default NULL,
		headline varchar(150) NOT NULL default '',
		body text NOT NULL,
		image text NOT NULL,
		category varchar(150) NOT NULL default '',
		KEY id (id)
	)
	") or die("Error: ". mysqli_error(  $db ) );
	echo "<li style=\"list-style-type:none;\">Table {$prefix}pixelpost created ...</li>";

	// Comments table
	mysqli_query(  $db, "
	CREATE TABLE IF NOT EXISTS {$prefix}comments (
		id int(11) NOT NULL auto_increment,
		parent_id int(11) NOT NULL default '0',
		datetime datetime default NULL,
		ip varchar(20) NOT NULL default '',
		message text NOT NULL,
		name varchar(20) NOT NULL default '',
		url varchar(40) NOT NULL default '',
		KEY id (id)
	)
	") or die("Error: ". mysqli_error(  $db ) );
	echo "<li style=\"list-style-type:none;\">Table {$prefix}comments created ...</li>";

	// Visitors table
	mysqli_query(  $db, "
	CREATE TABLE IF NOT EXISTS {$prefix}visitors (
		id int(11) NOT NULL auto_increment,
		datetime datetime default NULL,
		host varchar(100) NOT NULL default '',
		referer varchar(255) NOT NULL default '',
		ua varchar(255) NOT NULL default '',
		ip varchar(255) NOT NULL default '',
		ruri varchar(150) NOT NULL default '',
		PRIMARY KEY  (id)
	)
	") or die("Error: ". mysqli_error(  $db ) );
	echo "<li style=\"list-style-type:none;\">Table {$prefix}visitors created ...</li>";
}

function Set_Configuration($db, $prefix)
{
	// guess environment
	$site_url = $_SERVER['SERVER_NAME'];
	$site_url .= $_SERVER['PHP_SELF'];
	$site_url = pathinfo($site_url);
	$site_url = $site_url['dirname'];
	$site_url = str_replace("admin","",$site_url);
	$site_url = "http://$site_url";
	$images_path = str_replace("admin","images/",$images_path);

	$images_path  = "../images/";

	// get post data
	$admin_user = addslashes($_POST['admin_user']);
	$admin_password = $_POST['admin_password'];

	$query = mysqli_query(  $db, "
	INSERT INTO {$prefix}config
	(`admin`, `password`, `email`, `commentemail`, `template`, `imagepath`, `siteurl`, `sitetitle`, `langfile`, `calendar`, `crop`, `thumbwidth`, `thumbheight`, `thumbnumber`, `compression`, `dateformat`)
	VALUES ( '$admin_user', MD5('$admin_password'),'','no', 'simple', '$images_path', '$site_url', 'pixelpost','english','No Calendar','yes','100','75','5','75','Y-m-d H:i:s')
	") or die("Error: ". mysqli_error(  $db ) );
	echo "<li style=\"list-style-type:none;\">Table {$prefix}config populated ...</li>";
}

// // Upgrade the database from the 1.3 schema to the 1.4 schema
// function UpgradeTo14( $prefix)
// {
// 	// Version 1.4
// 	// Make future upgrade scripts easier by adding a version table
// 	mysqli_query(  $db, "
// 	CREATE TABLE IF NOT EXISTS {$prefix}version (
// 		`id` int(10) unsigned NOT NULL auto_increment,
// 		`upgrade_date` timestamp(14) NOT NULL,
// 		`version` float NOT NULL default '0',
// 		PRIMARY KEY  (`id`),
// 		KEY `version` (`version`)
// 	)
// 	") or die("Error: ". mysqli_error(  $db ) );

// 	mysqli_query(  $db, "
// 	INSERT INTO `{$prefix}version` (version) VALUES (1.4)
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	echo "<li style=\"list-style-type:none;\">Table ".$prefix."version created ...</li>";

// 	// Multiple Categories support
// 	mysqli_query(  $db, "
// 	CREATE TABLE IF NOT EXISTS {$prefix}catassoc (
// 		id int(11) NOT NULL auto_increment,
// 		cat_id int(11) NOT NULL default '0',
// 		image_id int(11) NOT NULL default '0',
// 		PRIMARY KEY  (id),
// 		KEY cat_id (cat_id),
// 		KEY image_id (image_id)
// 	)
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	echo "<li style=\"list-style-type:none;\">Table ".$prefix."catassoc created ...</li>";

// 	// Timezone support, the 0 will be included automatically, so no need to insert
// 	$tz = date("Z")/3600; // set the default timezone value equal to the server timezone
// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}config` ADD `timezone` FLOAT DEFAULT '".$tz."' NOT NULL
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	echo "<li style=\"list-style-type:none;\">Added timezone support ...</li>";

// 	// Customizable category links
// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}config` ADD `catgluestart` varchar(5) DEFAULT '[' NOT NULL
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}config` ADD `catglueend` varchar(5) DEFAULT ']' NOT NULL
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	echo "<li style=\"list-style-type:none;\">Added customizable category links support ...</li>";


// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}config` ADD `htmlemailnote` CHAR(3) DEFAULT 'yes'
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	echo "<li style=\"list-style-type:none;\">Added HTML notification email support ...</li>";


// 	if(!mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}comments` ADD `email` varchar(100)
// 	")) echo("comments.email already exists: ". mysqli_error(  $db ) );
// 	echo "<li style=\"list-style-type:none;\">Added email in comments support ...</li>";


// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}comments` MODIFY  `name` varchar(30)
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	echo "<li style=\"list-style-type:none;\">Longer name field in comments support ...</li>";


// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}comments` MODIFY  `url` varchar(70)
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	echo "<li style=\"list-style-type:none;\">Longer url field in comments support ...</li>";


// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}config` ADD `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	echo "<li style=\"list-style-type:none;\">Added indexes to {$prefix}config ...</li>";

// 	// Indexes
// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}categories` DROP INDEX `id`, ADD PRIMARY KEY ( `id`)
// 	");
// 	echo "<li style=\"list-style-type:none;\">Added indexes to {$prefix}categories ...</li>";
// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}comments` DROP INDEX `id`, ADD PRIMARY KEY ( `id`), ADD INDEX ( `parent_id`)
// 	");
// 	echo "<li style=\"list-style-type:none;\">Added indexes to {$prefix}comments ...</li>";
// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}pixelpost` DROP INDEX `id`, ADD PRIMARY KEY ( `id`), ADD INDEX ( `datetime`)
// 	");
// 	echo "<li style=\"list-style-type:none;\">Added indexes to {$prefix}pixelpost ...</li>";
// 	mysqli_query(  $db, "
// 	ALTER TABLE `{$prefix}visitors` ADD INDEX ( `datetime`), ADD INDEX ( `referer`), ADD INDEX ( `ip`)
// 	");
// 	echo "<li style=\"list-style-type:none;\">Added indexes to {$prefix}visitors ...</li>";

// 	// Move any existing categories into the new category association table
// 	$result = mysqli_query(  $db, "SELECT id, category FROM {$prefix}pixelpost") or die("Error: ". mysqli_error(  $db ) );
// 	while( $row = mysqli_fetch_array( $result))
// 	{
// 		mysqli_query(  $db, "INSERT INTO {$prefix}catassoc VALUES ( 0, '{$row[1]}', '{$row[0]}')") or die("Error: ". mysqli_error(  $db ) );
// 	}
// }

// // Upgrade the version table from the 1.4 to the 141
// function UpgradeTo141( $prefix)
// {
// 	mysqli_query(  $db, "
// 	INSERT INTO `{$prefix}version` (version) VALUES (1.41)
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	//echo "table ".$prefix."version updated to 1.4.1 ...<p />";
// }



// // Upgrade the version table to 1.499 (means 1.5alpha)
// function UpgradeTo1501( $prefix)
// {
// global $pixelpost_db_prefix;
// 	if (!is_field_exists ('moderate_comments','config'))
// 	{
// 		// add moterate_comments field to config table
// 		$table = $prefix."config";
// 		mysqli_query(  $db, "ALTER TABLE $table ADD `moderate_comments` VARCHAR( 3) DEFAULT 'no' NOT NULL ")
// 			or die("Error: ". mysqli_error(  $db ) );

// 		// add publish field to comments table
// 		$table = $prefix ."comments";
// 		mysqli_query(  $db, "ALTER TABLE $table ADD `publish` VARCHAR( 3) DEFAULT 'yes' NOT NULL ")
// 			or die("Error: ". mysqli_error(  $db ) );

// 		echo "<li style=\"list-style-type:none;\">Comment moderation feature is added ...</li>";
// 	}

// 	// create addons table
// 	$query = "CREATE TABLE {$pixelpost_db_prefix}addons (
// 		id INT(11) NOT NULL auto_increment,
// 		addon_name VARCHAR(66) NOT NULL default '',
// 		status VARCHAR(3) NOT NULL default 'on',
// 		type VARCHAR(15) NOT NULL default 'normal',
// 		PRIMARY KEY  (id)
// 	)";
// 	mysqli_query(  $db,  $query) or die("Error: ". mysqli_error(  $db ) );;

// 	// populate the addons table
// 	$dir = "../addons/";
// 	refresh_addons_table($dir);
// 	echo "<li style=\"list-style-type:none;\">Addon ON/OFF switchs are added.</li>";

// 	// update version
// 	mysqli_query(  $db, "
// 	INSERT INTO `{$prefix}version` (version) VALUES (1.49931)
// 	") or die("Error: ". mysqli_error(  $db ) );
// 	echo "<li style=\"list-style-type:none;\">Table ".$prefix."version updated to 1.5alpha_a03 ...</li>";
// }

// function UpgradeTo15011( $prefix)
// {
// 	global $pixelpost_db_prefix;

// 	if (is_field_exists ('clean_url','config'))
// 	{
// 		// del clean_url field from config table
// 		$table = $prefix."config";
// 		mysqli_query(  $db, "ALTER TABLE $table DROP `clean_url` ")
// 			or die("Error: ". mysqli_error(  $db ) );

// 		// del clean_url field from pixelpost table
// 		$table = $prefix."pixelpost";
// 		mysqli_query(  $db, "ALTER TABLE $table DROP `clean_url` ")
// 			or die("Error: ". mysqli_error(  $db ) );

// 		// update version
// 		mysqli_query(  $db, "
// 		INSERT INTO `{$prefix}version` (version) VALUES (1.4995)
// 		") or die("Error: ". mysqli_error(  $db ) );
// 		echo "<li style=\"list-style-type:none;\">Table ".$prefix."version updated to 1.5alpha_a04 ...</li>";
// 	}
// }

// function UpgradeTo15012($prefix)
// {
// 	global $pixelpost_db_prefix;
// 	if (!is_field_exists ('timestamp','config'))
// 	{
// 		// add clean_url field to config table
// 		$table = $prefix."config";
// 		mysqli_query(  $db, "ALTER TABLE $table ADD `timestamp` VARCHAR( 4) DEFAULT 'yes' NOT NULL ")
// 			or die("Error: ". mysqli_error(  $db ) );

// 		echo "<li style=\"list-style-type:none;\">Switch ON/OFF for time stamps is added ...</li>";

// 		// update version
// 		mysqli_query(  $db, "
// 		INSERT INTO `{$prefix}version` (version) VALUES (1.4995)
// 		") or die("Error: ". mysqli_error(  $db ) );
// 		echo "<li style=\"list-style-type:none;\">Table ".$prefix."version updated to 1.5alpha_a04_1.</li><p />";
// 	}
// }




// // upgrade to 1.5Beta
// function UpgradeTo15beta($prefix,$newversion)
// {
// global $pixelpost_db_prefix;

// // add comment moderation
// 	if (!is_field_exists ('moderate_comments','config'))
// 	{
// 		// add moterate_comments field to config table
// 		$table = $prefix."config";
// 		mysqli_query(  $db, "ALTER TABLE $table ADD `moderate_comments` VARCHAR( 3) DEFAULT 'no' NOT NULL ")
// 			or die("Error: ". mysqli_error(  $db ) );

// 		// add publish field to comments table
// 		$table = $prefix ."comments";
// 		mysqli_query(  $db, "ALTER TABLE $table ADD `publish` VARCHAR( 3) DEFAULT 'yes' NOT NULL ")
// 			or die("Error: ". mysqli_error(  $db ) );

// 		echo "<li style=\"list-style-type:none;\">Comment moderation feature is added ...</li>";
// 	} // end if

// // create addons table if necessary
// 	if(!is_table_created('addons'))
// 	{
// 		// create addons table
// 		$query = "CREATE TABLE {$pixelpost_db_prefix}addons (
// 			id INT(11) NOT NULL auto_increment,
// 			addon_name VARCHAR(66) NOT NULL default '',		
// 			status VARCHAR(3) NOT NULL default 'on',		
// 			type VARCHAR(15) NOT NULL default 'normal',
// 			PRIMARY KEY  (id)
// 		)";
// 		mysqli_query(  $db,  $query) or die("Error: ". mysqli_error(  $db ) );;

// 		// populate the addons table
// 		$dir = "../addons/";
// 		refresh_addons_table($dir);
// 		echo "<li style=\"list-style-type:none;\">Addon ON/OFF switchs are added ...</li>";
// 	}

// // timestamp
// 	if (!is_field_exists ('timestamp','config'))
// 	{
// 			// add clean_url field to config table
// 			$table = $prefix."config";
// 			mysqli_query(  $db, "ALTER TABLE $table ADD `timestamp` VARCHAR( 4) DEFAULT 'yes' NOT NULL ")
// 				or die("Error: ". mysqli_error(  $db ) );

// 			echo "<li style=\"list-style-type:none;\">Switch ON/OFF for time stamps is added ...</li>";

// 	}	// end if

// // visitor booking ON/OFF switch
// 	if (!is_field_exists ('visitorbooking','config'))
// 	{
// 		// add clean_url field to config table
// 		$table = $prefix."config";
// 		mysqli_query(  $db, "ALTER TABLE $table ADD `visitorbooking` VARCHAR( 4) DEFAULT 'yes' NOT NULL ")
// 			or die("Error: ". mysqli_error(  $db ) );

// 		echo "<li style=\"list-style-type:none;\">Switch ON/OFF for visitor booking is added ...</li>";

// 		// update version
// 		mysqli_query(  $db, "
// 		INSERT INTO `{$prefix}version` (version) VALUES (".$newversion.")
// 		") or die("Error: ". mysqli_error(  $db ) );
// 		echo "<li style=\"list-style-type:none;\">Table ".$prefix."version updated to 1.5Beta.</li>";
// 	}	// end if
	
// } // end function UpgradeTo15beta($prefix)

// function UpgradeTo15final( $prefix,$newversion)
// {
// 	global $pixelpost_db_prefix;
// 	if (is_field_exists ('clean_url','config'))
// 	{
// 		// del clean_url field from config table
// 		$table = $prefix."config";
// 		mysqli_query(  $db, "ALTER TABLE $table DROP `clean_url` ")
// 			or die("Error: ". mysqli_error(  $db ) );

// 		// del clean_url field from pixelpost table
// 		$table = $prefix."pixelpost";
// 		mysqli_query(  $db, "ALTER TABLE $table DROP `clean_url` ")
// 			or die("Error: ". mysqli_error(  $db ) );

// 		// update version
// 		mysqli_query(  $db, "
// 		INSERT INTO `{$prefix}version` (version) VALUES (1.5)
// 		") or die("Error: ". mysqli_error(  $db ) );
// 		echo "<li style=\"list-style-type:none;\">Table ".$prefix."version updated to 1.5 Final ...</li>";
// 	}
// }

// function UpgradeTo16beta( $prefix, $newversion)
// {
// 	global $pixelpost_db_prefix;

// 	$table = $prefix."tags";
// 	mysqli_query(  $db, "CREATE TABLE `$table` (
// 		`img_id` INT NOT NULL ,
// 		`tag` TINYTEXT NOT NULL,
// 		`alt_tag` TINYTEXT NOT NULL
// 		);")or die("Error: ". mysqli_error(  $db ) );

// 	// Language stuff
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `altlangfile` VARCHAR( 100) DEFAULT 'Off' NOT NULL")
// 	or die("Error: ". mysqli_error(  $db ) );
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."pixelpost ADD `alt_headline` VARCHAR( 150) DEFAULT '' NOT NULL,
// 		ADD `alt_body` TEXT DEFAULT '' NOT NULL ") or die("Error: ". mysqli_error(  $db ) );
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."categories ADD `alt_name` VARCHAR( 100) DEFAULT 'default' NOT NULL")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// new field used to disable Markdown
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `markdown` VARCHAR(1) DEFAULT 'f' NOT NULL")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// creation of primary key on tags table
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."tags ADD PRIMARY KEY ( `img_id` , `tag` ( 128), `alt_tag` (128)) ")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// Drop field moderate comments
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config DROP `moderate_comments`;");

// // global settings disabling comments (default for new picture)
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `global_comments` ENUM( 'A', 'M', 'F') NOT NULL DEFAULT 'A'")
// 	or die("Error: ". mysqli_error(  $db ) );
// 	// picture based disabling comments
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."pixelpost ADD `comments` ENUM( 'A', 'M', 'F') NOT NULL DEFAULT 'A'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// Drop field markdown by GeoS
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config DROP `markdown`;")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// new markdown field
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `markdown` ENUM( 'F', 'T') NOT NULL DEFAULT 'F'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// new exif
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `exif` ENUM( 'F', 'T') NOT NULL DEFAULT 'T'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// picture based exif
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."pixelpost ADD `exif_info` TEXT NULL DEFAULT NULL")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// token field
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `token` ENUM( 'F', 'T') NOT NULL DEFAULT 'F'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// token_time
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `token_time` VARCHAR( 2) NOT NULL DEFAULT '5'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// comment field
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `comment_dsbl` ENUM( 'F', 'T') NOT NULL DEFAULT 'F'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// comment_timebetween
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `comment_timebetween` VARCHAR( 3) NOT NULL DEFAULT '30'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// rsstype field
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `rsstype` ENUM( 'F', 'T', 'O' ,'N') NOT NULL DEFAULT 'T'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// feeditems
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `feeditems` VARCHAR( 3) NOT NULL DEFAULT '10'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// no_uri_comments
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `max_uri_comments` VARCHAR( 3) NOT NULL DEFAULT '5'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// update version
// 	mysqli_query(  $db, "
// 	INSERT INTO `{$prefix}version` (version) VALUES ($newversion)
// 	")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	echo "<li style=\"list-style-type:none;\">Table ".$prefix."version updated to $newversion ...</li>";
// }

// function UpgradeTo16final( $db, $prefix, $newversion)
// {
// 	global $pixelpost_db_prefix;
	
// 	// ********************************************************************
// 	// NOTE: the "rsstype" will need to be placed in the v1.6 Beta upgrade.
// 	// ********************************************************************
	
// 	// Drop rsstype field
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config DROP `rsstype`;");
	
// 	// rsstype field
// 	mysqli_query(  $db, "ALTER TABLE ".$pixelpost_db_prefix."config ADD `rsstype` ENUM( 'F', 'T', 'O' ,'N') NOT NULL DEFAULT 'T'")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	// update version
// 	mysqli_query(  $db, "
// 	INSERT INTO `{$prefix}version` (version) VALUES ($newversion)
// 	")
// 	or die("Error: ". mysqli_error(  $db ) );

// 	echo "<li style=\"list-style-type:none;\">Table ".$prefix."version updated to $newversion ...</li>";
// }
?>