<?php

// SVN file version:
// $Id: index.php 241 2007-04-11 07:02:35Z david.kozikowski $

/*

Pixelpost version 1.6

Pixelpost www: http://www.pixelpost.org/

Version 1.6:
Development Team:
Ramin Mehran, Connie Mueller-Goedecke, Will Duncan, Joseph Spurling,
Piotr "GeoS" Galas, Dennis Mooibroek, Karin Uhlig, Jay Williams, David Kozikowski
Version 1.1 to Version 1.3: Linus <http://www.shapestyle.se>

IMPORTANT!!!
Due to the nature of the characterset used in this file it is important to save this
file with an UTF-8 encoding.

Contact: thecrew (at) pixelpost (dot) org
Copyright 2006 Pixelpost.org <http://www.pixelpost.org>

License: http://www.gnu.org/copyleft/gpl.html

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

*/
// fix proposed by tomyeah on the forum
header('Content-Type: text/html; charset=utf-8');

error_reporting(0);
ini_set('arg_separator.output', '&amp;');
session_start();

if (isset($_GET['errors']) && $_SESSION["pixelpost_admin"]){
	error_reporting(E_ALL ^ E_NOTICE);
	
}elseif(isset($_GET['errorsall']) && $_SESSION["pixelpost_admin"]){
	error_reporting(E_ALL);
	
}

$PHP_SELF = "index.php";

// includes
require("includes/pixelpost.php");
require("includes/functions.php");

// Set cookie for visitor counter, re-count a person after 60 mins
setcookie("lastvisit","expires in 60 minutes",time() +60*60);

// save user info if requested
if(isset($_POST['vcookie']))
{
	$vcookiename = addslashes($_POST['name']);
	$vcookieurl = addslashes($_POST['url']);
	// modified for Email
	$vcookieemail = clean($_POST['email']);
	setcookie("visitorinfo","$vcookiename%$vcookieurl%$vcookieemail",time() +60*60*24*30); // save cookie 30 days
}


start_mysql();

if($_GET['showimage'])	$_GET['showimage'] = (int) $_GET['showimage'];

// get config
if($cfgrow = sql_array("SELECT * FROM ".$pixelpost_db_prefix."config"))
{
	$upload_dir = $cfgrow['imagepath'];
}
else
{
	$extra_message= "Coming Soon. Not Installed Yet. Cause #1";
	show_splash($extra_message,"templates");
	//echo "Coming Soon. Not Installed Yet.";
	//exit;
}

if ($cfgrow['markdown'] == 'T')
{
	require("includes/markdown.php");
}

// added token support for use in forms only if it is set on
if ($cfgrow['token'] == 'T'){
	if (!isset($_SESSION['token'])){
		$_SESSION['token'] = md5($_SERVER["HTTP_USER_AGENT"].$_SERVER["HTTP_ACCEPT_LANGUAGE"].$_SERVER["HTTP_ACCEPT_ENCODING"].$_SERVER["HTTP_ACCEPT_CHARSET"].$_SERVER["HTTP_ACCEPT"].$_SERVER["SERVER_SOFTWARE"].session_id().uniqid(rand(), TRUE));
	}
	if(!isset($_GET['x'])&&$_GET['x'] !== "save_comment"){
		$_SESSION['token_time'] = time();
	}
}

// book visitors
if (strtolower($cfgrow['visitorbooking'])!='no')	book_visitor($pixelpost_db_prefix."visitors");


if(isset($mod_rewrite)&&$mod_rewrite == "1")	$showprefix = "";
else	$showprefix = "./index.php?showimage=";

// refresh the addons table
$dir = "addons/";
refresh_addons_table($dir);


$tz = $cfgrow['timezone'];
$datetime = gmdate("Y-m-d H:i:s",time()+(3600 * $tz)); // current date+time
$cdate = $datetime;				// for future posting, current date+time

// ##########################################################################################//
// LANGUAGE SELECTION
// ##########################################################################################//
// Original idea by RobbieMc (http://forum.pixelpost.org/showthread.php?t=3668)

// This is an array of all supported languages in PP. It contains the country abbreviation
// and the native word for the language spoken in that country. This is used to get all
// variables.

$PP_supp_lang = array('dutch'=>array('NL','Nederlands'),
  										'english'=>array('EN','English'),
  										'french'=>array('FR','Français'),
  										'german'=>array('DE','Deutsch'),
											'italian'=>array('IT','Italiano'),
											'norwegian'=>array('NO','Norsk'),
											'persian'=>array('FA','Farsi'),
											'polish'=>array('PL','Polskiego'),
											'portuguese'=>array('PT','Português'),
											'simplified_chinese'=>array('CN','Chinese'),
											'spanish'=>array('ES','Español'),
											'swedish'=>array('SE','Svenska'),
											'danish'=>array('DK','Dansk'),
											'japanese'=>array('JP','Japanese')
									);
// The default language is the language the user has set in the adminpanel
// We have to find the abbreviation
$default_language_abr = strtolower($PP_supp_lang[$cfgrow['langfile']][0]);

// Try to find if another language was selected or not (different ways)
// Set a cookie to the GET arg 'lang' if it exists.
if(isset($_GET['lang']))
{
	setcookie ('lang', substr($_GET['lang'],0,2), false, '/', false, 0);
	$language_abr = substr($_GET['lang'],0,2);
}

// Set the &language variable to session 'lang' - this variable is the one used below
$language_abr = $_COOKIE['lang'];

// Use the default language if none of the previous steps captured a language preference
if(!isset($language_abr))	$language_abr = $default_language_abr;

// override the language if $_GET['lang'] is set.
if(isset($_GET['lang']))	$language_abr = substr($_GET['lang'],0,2);

// convert the two letter $language variable to full name of language file
// (used in language file switch but not template switch (template uses abbreviation))
foreach ($PP_supp_lang as $key => $row)
{
	foreach($row as $cell)
  {
  	if ($cell == strtoupper($language_abr))	$language_full = $key;
  }
}

// ##########################################################################################//
// GET LANGUAGE FILE BASED ON LANGUAGE SELECTION
// ##########################################################################################//
if (file_exists("language/lang-".$language_full.".php"))
{
	if ( !isset($_GET['x'])OR($_GET['x'] != "rss" & $_GET['x'] != "atom"))
      require("language/lang-".$language_full.".php");
}
else
{
	echo '<b>Error:</b><br />No <b>language</b> folder exists or the file <b>"lang-' .$language_full.'.php"</b> is missing in that folder.<br />Make sure that you have uploaded all necessary files with the exact same names as mentioned here.';
	exit;
}

// Double Quotes in <SITE_TITLE> break HTML Code
$pixelpost_site_title = $cfgrow['sitetitle'];
$pixelpost_site_title = pullout($cfgrow['sitetitle']);
$pixelpost_site_title = htmlspecialchars($pixelpost_site_title,ENT_QUOTES);


//  Added ability to use header and footers for templates.  They are not needed but used if included in the template

// Don't show header or footer if viewing comments in a popup:
if(!isset($_GET['popup'])&&$_GET['popup'] != "comment"){
	if(file_exists("templates/".$cfgrow['template']."/header.html"))
		$header = file_get_contents("templates/".$cfgrow['template']."/header.html");
	if(file_exists("templates/".$cfgrow['template']."/footer.html"))
		$footer = file_get_contents("templates/".$cfgrow['template']."/footer.html");
}

// You can now add any template you want by just adding the template and a link to it.  For example,
// ?x=about will load the template about_template.html
if(isset($_GET['x'])&& $_GET['x'] == "ref")
{
	// Maintain backwards compatibility with the referer template
	$_GET['x'] = "referer";
}

// refererlog
if(isset($_GET['x'])&&$_GET['x'] == "referer") {
		header("HTTP/1.0 404 Not Found");
		header("Status: 404 File Not Found!");
    // header("Location: index.php");
echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><HTML><HEAD>\n<TITLE>404 Not Found</TITLE>\n</HEAD><BODY>\n<H1>Not Found</H1>\nThe requested URL /index.php was not found on this server.<P>\n<P>Additionally, a 404 Not Found\nerror was encountered while trying to use an ErrorDocument to handle the request.\n</BODY></HTML>";
    exit;
 } // end refererlog

// ##########################################################################################//
// GET TEMPLATE FILE BASED ON LANGUAGE SELECTION
// ##########################################################################################//

if ($language_full==$cfgrow['langfile'])
	{
		// we have our default language from the PP installation, so we use our default templates
    if( isset($_GET['x'])&&file_exists( "templates/".$cfgrow['template']."/".$_GET['x']."_template.html"))
    {
    	if (eregi("[.]",$_GET['x']))	die("Come on! forget about it...");

    	$tpl = file_get_contents("templates/".$cfgrow['template']."/".$_GET['x']."_template.html");
    }
    else
    {
    	if (!file_exists("templates/".$cfgrow['template']."/image_template.html"))
    	{
    		echo '<b>Error:</b><br />No template folder exists by the name of <b>"' .$cfgrow['template'] .'"</b> or the file <b>image_template.html</b> is missing in that folder.<br />Make sure that you have uploaded all necessary files with the exact same names as mentioned here.';
    		exit;
    	}

	    // if the x=foo does not exist prompt it! don't show the main page anymore!
    	if (isset($_GET['x'])&& $_GET['x']!='atom' && $_GET['x']!='comment_atom' && $_GET['x']!='rss' && $_GET['x']!='comment_rss' && $_GET['x']!='save_comment')
    	{ // if (isset($_GET['x']) and !file_exists( "templates/".$cfgrow['template']."/".$_GET['x']."_template.html"))
    			header("HTTP/1.0 404 Not Found");
    			header("Status: 404 File Not Found!");
    			// header("Location: index.php");
    			echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><HTML><HEAD>\n<TITLE>404 Not Found</TITLE>\n</HEAD><BODY>\n<H1>Not Found</H1>\nThe requested URL /index.php was not found on this server.<P>\n<P>Additionally, a 404 Not Found\nerror was encountered while trying to use an ErrorDocument to handle the request.\n</BODY></HTML>";
    			exit;
    	}

    	$tpl = file_get_contents("templates/".$cfgrow['template']."/image_template.html");
    }
  }
  else
  {
  	// we use our special designed language templates.
  	if( isset($_GET['x'])&&file_exists( "templates/".$cfgrow['template']."/".$_GET['x']."_".$language_abr."_template.html"))
		{
    	if (eregi("[.]",$_GET['x']))
        die("Come on! forget about it...");
      $tpl = file_get_contents("templates/".$cfgrow['template']."/".$_GET['x']."_".$language_abr."_template.html");
		}
		else
		{
	    if (!file_exists("templates/".$cfgrow['template']."/image_".$language_abr."_template.html"))
  	  {
        echo '<b>Error:</b><br />No template folder exists by the name of <b>"' .$cfgrow['template'] .'"</b> or the file <b>image_'.$language_abr .'_template.html</b> is missing in that folder.<br />Make sure that you have uploaded all necessary files with the exact same names as mentioned here.<br /><br /><a href="index.php?lang='.$default_language_abr.'" alt="return to default language">Click here to return to the default language.</a>';
        exit;
    	}
			// if the x=foo does not exist prompt it! don't show the main page anymore!
	    if (isset($_GET['x'])&& $_GET['x']!='atom' && $_GET['x']!='comment_atom' && $_GET['x']!='rss' && $_GET['x']!='comment_rss' && $_GET['x']!='save_comment'){ // if (isset($_GET['x']) and !file_exists( "templates/".$cfgrow['template']."/".$_GET['x']."_template.html"))
            header("HTTP/1.0 404 Not Found");
            header("Status: 404 File Not Found!");
            // header("Location: index.php");
            echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><HTML><HEAD>\n<TITLE>404 Not Found</TITLE>\n</HEAD><BODY>\n<H1>Not Found</H1>\nThe requested URL /index.php was not found on this server.<P>\n<P>Additionally, a 404 Not Found\nerror was encountered while trying to use an ErrorDocument to handle the request.\n</BODY></HTML>";
            exit;
       }
    	$tpl = file_get_contents("templates/".$cfgrow['template']."/image_".$language_abr."_template.html");
		}
	}

if(isset($_GET['popup'])&&$_GET['popup'] == "comment")
{
	// additional language file for comment template
	if ($language_full==$cfgrow['altlangfile'] && file_exists("templates/".$cfgrow['template']."/comment_".$language_abr."_template.html")) $tpl = file_get_contents("templates/".$cfgrow['template']."/comment_".$language_abr."_template.html");
	// if not existing or no additional language chosen, default template file is called without error
	else $tpl = file_get_contents("templates/".$cfgrow['template']."/comment_template.html");
}

// if showimage=badstuff or email to hijack!
if (isset($_GET['showimage']) && !is_numeric($_GET['showimage'])){
// show 404!
			header("HTTP/1.0 404 Not Found");
			header("Status: 404 File Not Found!");
			// header("Location: index.php");
			echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><HTML><HEAD>\n<TITLE>404 Not Found</TITLE>\n</HEAD><BODY>\n<H1>Not Found</H1>\nDon't do that! go back to index.php! \n</BODY></HTML>";
			exit;
}

// Added ability to use header and footers for templates.  They are not needed but used if included in the template

if(isset($header))
	$tpl = $header . $tpl;
if(isset($footer))
	$tpl = $tpl. $footer;

// Get visitor count
$visitors = sql_array("SELECT count(*) as count FROM ".$pixelpost_db_prefix."visitors");
$pixelpost_visitors = $visitors['count'];

// Get number of photos in database
$photonumb = sql_array("SELECT count(*) as count FROM ".$pixelpost_db_prefix."pixelpost WHERE datetime<='$datetime'");
$pixelpost_photonumb = $photonumb['count'];

// added for temp to create banlist table if it is not there TODO: THIS WILL GO INTO THE CREATE_TABLES
create_banlist();

// images/main site
if(!isset($_GET['x']) /*$_GET['x'] == ""*/)
{
  // Get Current Image.
	if(!isset($_SESSION["pixelpost_admin"]))
	{
		if(!isset($_GET['showimage']) /*$_GET['showimage'] == ""*/)
		{
			$row = sql_array("SELECT * FROM ".$pixelpost_db_prefix."pixelpost WHERE datetime<='$cdate' ORDER BY datetime DESC limit 0,1");
		}
		else
		{
			$row = sql_array("SELECT * FROM ".$pixelpost_db_prefix."pixelpost WHERE (id='".$_GET['showimage']."') AND datetime<='$cdate'");
		}
	}
	else
	{
		if($_GET['showimage'] == "")
		{
			$row = sql_array("SELECT * FROM ".$pixelpost_db_prefix."pixelpost ORDER BY datetime DESC limit 0,1");
		}
		else
		{
			$row = sql_array("SELECT * FROM ".$pixelpost_db_prefix."pixelpost WHERE (id='".$_GET['showimage']."')");
		}
	}

	if(!$row['image'])
	{
		echo "$lang_nothing_to_show";
		exit;
	}

	$image_name         = $row['image'];
	if ($language_abr == $default_language_abr) {
  	$image_title        = pullout($row['headline']);
		$image_notes        = ($cfgrow['markdown'] == 'T') ? markdown(pullout($row['body']))	: pullout($row['body']);
	} else {
  	if ($row['alt_headline']=='') $image_title        = pullout($row['headline']);
  	else $image_title        = pullout($row['alt_headline']);
		if ($row['alt_body']=='') $image_notes        = ($cfgrow['markdown'] == 'T') ? markdown(pullout($row['body']))	: pullout($row['body']);
		else $image_notes        = ($cfgrow['markdown'] == 'T') ? markdown(pullout($row['alt_body']))	: pullout($row['alt_body']);
  }
	$image_title = htmlspecialchars($image_title,ENT_QUOTES);
	$image_id           = $row['id'];
	$image_datetime     = $row['datetime'];
	$image_datetime_formatted = strtotime($image_datetime);
	$image_datetime_formatted = date($cfgrow['dateformat'],$image_datetime_formatted);
	$image_date         = substr($row['datetime'],0,10);
	$image_time         = substr($row['datetime'],11,5);
	$image_date_year_full   = substr($row['datetime'],0,4);
	$image_date_year   = substr($row['datetime'],2,2);
	$image_date_month = substr($row['datetime'],5,2);
	$image_date_day = substr($row['datetime'],8,2);
	$thumbnail_extra = getimagesize("thumbnails/thumb_$image_name");
	$image_extra = getimagesize("images/$image_name");
	$image_width = $image_extra['0'];
	$image_height = $image_extra['1'];
	$tpl = str_replace("<IMAGE_WIDTH>",$image_width,$tpl);
	$tpl = str_replace("<IMAGE_HEIGHT>",$image_height,$tpl);
	$local_width = $thumbnail_extra['0'];
	$local_height = $thumbnail_extra['1'];
	$image_exif = $row['exif_info'];

	//$image_title = htmlentities($image_title);
	$image_thumbnail = "<a href='$showprefix$image_id'><img src='thumbnails/thumb_$image_name' alt='$image_title' title='$image_title' width='$local_width' height='$local_height' /></a>";

	// thumnail no link
	$image_thumbnail_no_link = "<img src='thumbnails/thumb_$image_name' alt='$image_title' title='$image_title' width='$local_width' height='$local_height' />";

	$image_permalink = "<a href='$showprefix$image_id'>$lang_permalink</a>"; // permalink automated for fancy url/no fancy

	// get previous image id and name
	if(!isset($_SESSION["pixelpost_admin"]))
	{
		$previous_row = sql_array("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime < '$image_datetime') and (datetime<='$cdate') ORDER BY datetime desc limit 0,1");
	}
	else
	{
		$previous_row = sql_array("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime < '$image_datetime')  ORDER BY datetime desc limit 0,1");
	}
	$image_previous_name = $previous_row['image'];
	$image_previous_id = $previous_row['id'];
	if ($language_abr == $default_language_abr) {
		$image_previous_title = pullout($previous_row['headline']);
	} else {
		$image_previous_title = pullout($previous_row['alt_headline']);
	}
	$image_previous_datetime = $previous_row['datetime'];
	$image_previous_link = "<a href='$showprefix$image_previous_id'>$lang_previous</a>";
	list($local_width,$local_height,$type,$attr) = getimagesize("thumbnails/thumb_$image_previous_name");
	$image_previous_thumbnail = "<a href='$showprefix$image_previous_id'><img src='thumbnails/thumb_$image_previous_name' width='$local_width' height='$local_height' alt='$image_previous_title' title='$image_previous_title' /></a>";

	if($image_previous_id == "")
	{
		$image_previous_id = $image_id;
		$image_previous_title = "$lang_no_previous";
		$image_previous_link = "";
		$image_previous_thumbnail = "";
	}

	// get next image id and name
	if(!isset($_SESSION["pixelpost_admin"]))
	{
		$next_row = sql_array("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime > '$image_datetime') and (datetime<='$cdate') ORDER BY datetime asc limit 0,1");
	}
	else
	{
		$next_row = sql_array("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime > '$image_datetime') ORDER BY datetime asc limit 0,1");
	}
	$image_next_name = $next_row['image'];
	$image_next_id = $next_row['id'];
	if ($language_abr == $default_language_abr) {
		$image_next_title = pullout($next_row['headline']);
	} else {
		$image_next_title = pullout($next_row['alt_headline']);
	}
	$image_next_datetime = $next_row['datetime'];
	$image_next_link = "<a href='$showprefix$image_next_id'>$lang_next</a>";
	list($local_width,$local_height,$type,$attr) = getimagesize("thumbnails/thumb_$image_next_name");
	$image_next_thumbnail = "<a href='$showprefix$image_next_id'><img src='thumbnails/thumb_$image_next_name' alt='$image_next_title' width='$local_width' height='$local_height' title='$image_next_title' /></a>";

	if($image_next_id == "")
	{
		$image_next_id = $image_id;
		$image_next_title = "$lang_no_next";
		$image_next_link = "";
		$image_next_thumbnail = "";
	}
	
	// get first image
	if(!isset($_SESSION["pixelpost_admin"]))
	{
		$first_image_row = sql_array("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime<='$cdate') ORDER BY datetime asc limit 0,1");
	}
	else
	{
		$first_image_row = sql_array("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE ORDER BY datetime asc limit 0,1");
	}
	$first_image_name = $first_image_row['image'];
	$first_image_id = $first_image_row['id'];
	if ($language_abr == $default_language_abr) {
		$first_image_title = pullout($first_image_row['headline']);
	} else {
		$first_image_title = pullout($first_image_row['alt_headline']);
	}
	$first_image_datetime = $first_image_row['datetime'];
	$first_image_link = "<a href='$showprefix$first_image_id'>$lang_first</a>";
	list($local_width,$local_height,$type,$attr) = getimagesize("thumbnails/thumb_$first_image_name");
	$first_image_thumbnail = "<a href='$showprefix$first_image_id'><img src='thumbnails/thumb_$first_image_name' alt='$first_image_title' width='$local_width' height='$local_height' title='$first_image_title' /></a>";

	if($first_image_id == $image_id)
	{
		$first_image_title = null;
		$first_image_link = null;
		$first_image_thumbnail = null;
	}
	
		// get latest image
	if(!isset($_SESSION["pixelpost_admin"]))
	{
		$last_image_row = sql_array("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime<='$cdate') ORDER BY datetime desc limit 0,1");
	}
	else
	{
		$last_image_row = sql_array("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE ORDER BY datetime desc limit 0,1");
	}
	$last_image_name = $last_image_row['image'];
	$last_image_id = $last_image_row['id'];
	if ($language_abr == $default_language_abr) {
		$last_image_title = pullout($last_image_row['headline']);
	} else {
		$last_image_title = pullout($last_image_row['alt_headline']);
	}
	$last_image_datetime = $last_image_row['datetime'];
	$last_image_link = "<a href='$showprefix$last_image_id'>$lang_latest</a>";
	list($local_width,$local_height,$type,$attr) = getimagesize("thumbnails/thumb_$last_image_name");
	$last_image_thumbnail = "<a href='$showprefix$last_image_id'><img src='thumbnails/thumb_$last_image_name' alt='$last_image_title' width='$local_width' height='$local_height' title='$last_image_title' /></a>";

	if($last_image_id == $image_id)
	{
		$last_image_title = null;
		$last_image_link = null;
		$last_image_thumbnail = null;
	}
	
	
	if(function_exists('gd_info'))
	{
		$gd_info = gd_info();

		if($gd_info != "")
		{
		// check that gd is here before this
			$aheadnumb = sql_array("SELECT count(*) as count FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime > '$image_datetime') and (datetime<='$cdate')");
			$aheadnumb = $aheadnumb['count'];
			$behindnumb = sql_array("SELECT count(*) as count FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime < '$image_datetime') and (datetime<='$cdate')");
			$behindnumb = $behindnumb['count'];
			$aheadlimit = round(($cfgrow['thumbnumber']-1)/2);
			$behindlimit = round(($cfgrow['thumbnumber']-1)/2);

			if($aheadnumb <= $aheadlimit)
			{
				$behindlimit = ($cfgrow['thumbnumber']-1)-$aheadnumb;
				$aheadlimit = $aheadnumb;
			}

			if($behindnumb <= $behindlimit)
			{
				$aheadlimit = ($cfgrow['thumbnumber']-1)-$behindnumb;
				$behindlimit = $behindnumb;
			}

			$totalthumbcounter = 1;
			$ahead_thumbs = "";
			$ahead_thumbs_reverse  ="";
			$thumbs_ahead = mysql_query("SELECT id,headline,alt_headline,image FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime > '$image_datetime') and (datetime<='$cdate') ORDER BY datetime asc limit 0,$aheadlimit");

			while(list($id,$headline,$alt_headline,$image) = mysql_fetch_row($thumbs_ahead))
			{
				if ($language_abr == $default_language_abr) {
					$headline = pullout($headline);
				} else {
					$headline = pullout($alt_headline);
				}
				$headline = htmlspecialchars($headline,ENT_QUOTES);
				list($local_width,$local_height,$type,$attr) = getimagesize("thumbnails/thumb_$image");
				$ahead_thumbs .= "<a href='$showprefix$id'><img src='thumbnails/thumb_$image' alt='$headline' title='$headline' class='thumbnails' width='$local_width' height='$local_height' /></a>";
				$ahead_thumbs_reverse = "<a href='$showprefix$id'><img src='thumbnails/thumb_$image' alt='$headline' title='$headline' class='thumbnails' width='$local_width' height='$local_height' /></a>" .$ahead_thumbs_reverse ;
				$totalthumbcounter++;
			}

			$behind_thumbs = "";
			$behind_thumbs_reverse ="";
			$thumbs_behind = mysql_query("SELECT id,headline,alt_headline,image FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime < '$image_datetime') and (datetime<='$cdate') ORDER BY datetime desc limit 0,$behindlimit");

			while(list($id,$headline,$alt_headline,$image) = mysql_fetch_row($thumbs_behind))
			{
				if ($language_abr == $default_language_abr) {
					$headline = pullout($headline);
				} else {
					$headline = pullout($alt_headline);
				}
				$headline = htmlspecialchars($headline,ENT_QUOTES);
				list($local_width,$local_height,$type,$attr) = getimagesize("thumbnails/thumb_$image");
				$behind_thumbs = "<a href='$showprefix$id'><img src='thumbnails/thumb_$image' alt='$headline' title='$headline' class='thumbnails' width='$local_width' height='$local_height' /></a>$behind_thumbs";
				$behind_thumbs_reverse .= "<a href='$showprefix$id'><img src='thumbnails/thumb_$image' alt='$headline' title='$headline' class='thumbnails' width='$local_width' height='$local_height' /></a>";
				$totalthumbcounter++;
			}

			list($local_width,$local_height,$type,$attr) = getimagesize("thumbnails/thumb_$image_name");
			$thumbnail_row = "$behind_thumbs<a href='$showprefix$image_id'><img src='thumbnails/thumb_$image_name' alt='$image_title' title='$image_title' class='current-thumbnail' width='$local_width' height='$local_height' /></a>$ahead_thumbs";
			$thumbnail_row_reverse = "$ahead_thumbs_reverse<a href='$showprefix$image_id'><img src='thumbnails/thumb_$image_name' alt='$image_title' title='$image_title' class='current-thumbnail' width='$local_width' height='$local_height' /></a>$behind_thumbs_reverse";
			$tpl = preg_replace("/<IMAGE_THUMBNAIL_ROW>/",$thumbnail_row,$tpl);
			$tpl = preg_replace("/<IMAGE_THUMBNAIL_ROW_REV>/",$thumbnail_row_reverse,$tpl);
		} // gd_info()
	} // func exist

	// Modified from Mark Lewin's hack for multiple categories
	$querystr = "SELECT t1.cat_id,t2.name,t2.alt_name FROM ".$pixelpost_db_prefix."catassoc as t1 inner join ".$pixelpost_db_prefix."categories t2 on t1.cat_id = t2.id WHERE t1.image_id = '$image_id' ORDER BY t2.name ";
	$query = mysql_query($querystr);
	$image_category_number = 0;

	$image_category_all ="";
	$image_category_all_paged = "";
	while(list($cat_id,$name,$alt_name) = mysql_fetch_row($query))
	{
		if ($language_abr == $default_language_abr) {
			$name = pullout($name);
		} else {
			$name = pullout($alt_name);
		}
		$image_category_all .= "<a href='$PHP_SELF?x=browse&amp;category=$cat_id'>" .$cfgrow['catgluestart'] .$name .$cfgrow['catglueend']."</a> &nbsp;";
		$image_category_all_paged .= "<a href='$PHP_SELF?x=browse&amp;category=$cat_id&amp;pagenum=1'>" .$cfgrow['catgluestart'] .$name .$cfgrow['catglueend']."</a> &nbsp;";
		$image_category_number = $image_category_number +1;
	}

	if ($image_category_number >1)	$image_categoryword = "$lang_category_plural ";
	else	$image_categoryword = "$lang_category_singular ";

	$tpl = preg_replace("/<SITE_TITLE>/",$pixelpost_site_title,$tpl);
	$tpl = preg_replace("/<SITE_URL>/",$cfgrow['siteurl'],$tpl);
	$tpl = preg_replace("/<IMAGE_CATEGORY>/",$image_categoryword." ".$image_category_all,$tpl);
	// for paged_archive addon
	$tpl = preg_replace("/<IMAGE_CATEGORY_PAGED>/",$image_categoryword." ".$image_category_all_paged,$tpl);
	$tpl = preg_replace("/<IMAGE_DATE_YEAR_FULL>/",$image_date_year_full,$tpl);
	$tpl = preg_replace("/<IMAGE_DATE_YEAR>/",$image_date_year,$tpl);
	$tpl = preg_replace("/<IMAGE_DATE_MONTH>/",$image_date_month,$tpl);
	$tpl = preg_replace("/<IMAGE_DATE_DAY>/",$image_date_day,$tpl);
	$tpl = preg_replace("/<IMAGE_THUMBNAIL>/",$image_thumbnail,$tpl);
	// thumbnail no link
	$tpl = preg_replace("/<IMAGE_THUMBNAIL_NO_LINK>/",$image_thumbnail_no_link,$tpl);
	$tpl = preg_replace("/<IMAGE_DATE>/",$image_date,$tpl);
	$tpl = preg_replace("/<IMAGE_TIME>/",$image_time,$tpl);
	$tpl = preg_replace("/<IMAGE_NAME>/",$image_name,$tpl);
	$tpl = preg_replace("/<IMAGE_TITLE>/",$image_title,$tpl);
	$tpl = preg_replace("/<IMAGE_DATETIME>/",$image_datetime_formatted,$tpl);
	$tpl = preg_replace("/<IMAGE_NOTES>/",$image_notes,$tpl);
	// image notes without HTML tags and double quotes
	$image_notes_clean = strip_tags($image_notes);
        $image_notes_clean = htmlspecialchars($image_notes_clean,ENT_QUOTES);
     	$tpl = preg_replace("/<IMAGE_NOTES_CLEAN>/",$image_notes_clean,$tpl);
	// end image notes without HTML tags
	$tpl = preg_replace("/<IMAGE_ID>/",$image_id,$tpl);
	$tpl = preg_replace("/<IMAGE_PERMALINK>/",$image_permalink,$tpl);
	$tpl = preg_replace("/<IMAGE_PREVIOUS_LINK>/",$image_previous_link,$tpl);
	$tpl = preg_replace("/<IMAGE_PREVIOUS_THUMBNAIL>/",$image_previous_thumbnail,$tpl);
	$tpl = preg_replace("/<IMAGE_PREVIOUS_ID>/",$image_previous_id,$tpl);
	$tpl = preg_replace("/<IMAGE_PREVIOUS_TITLE>/",$image_previous_title,$tpl);
	$tpl = preg_replace("/<IMAGE_NEXT_LINK>/",$image_next_link,$tpl);
	$tpl = preg_replace("/<IMAGE_NEXT_ID>/",$image_next_id,$tpl);
	$tpl = preg_replace("/<IMAGE_NEXT_TITLE>/",$image_next_title,$tpl);
	$tpl = preg_replace("/<IMAGE_NEXT_THUMBNAIL>/",$image_next_thumbnail,$tpl);

	$tpl = preg_replace("/<IMAGE_LAST_LINK>/",$last_image_link,$tpl);
	$tpl = preg_replace("/<IMAGE_LAST_THUMBNAIL>/",$last_image_thumbnail,$tpl);
	$tpl = preg_replace("/<IMAGE_LAST_ID>/",$last_image_id,$tpl);
	$tpl = preg_replace("/<IMAGE_LAST_TITLE>/",$last_image_title,$tpl);
	$tpl = preg_replace("/<IMAGE_FIRST_LINK>/",$first_image_link,$tpl);
	$tpl = preg_replace("/<IMAGE_FIRST_ID>/",$first_image_id,$tpl);
	$tpl = preg_replace("/<IMAGE_FIRST_TITLE>/",$first_image_title,$tpl);
	$tpl = preg_replace("/<IMAGE_FIRST_THUMBNAIL>/",$first_image_thumbnail,$tpl);



	// get number of comments
	$cnumb_row = sql_array("SELECT count(*) as count FROM ".$pixelpost_db_prefix."comments WHERE parent_id='$image_id' and publish='yes'");
	$image_comments_number = $cnumb_row['count'];

	// get latest comment
	$latest_comment = sql_array("SELECT parent_id FROM ".$pixelpost_db_prefix."comments WHERE  publish='yes' ORDER BY id desc limit 0,1");
	$latest_comment = $latest_comment['parent_id'];
	$queryrow = sql_array("SELECT headline FROM ".$pixelpost_db_prefix."pixelpost WHERE id='$latest_comment'");
	$latest_comment_name = pullout($queryrow['headline']);

	// ##########################################################################################//
	// EXIF STUFF
	// ##########################################################################################//
	if ($cfgrow['exif']=='T'){
		include_once('includes/functions_exif.php');
		if ($image_exif!==null){
			$tpl = replace_exif_tags ($language_full, $image_exif, $tpl);
		} else {
			$tpl = replace_exif_tags_null($tpl);
		}
	} else {
		include_once('includes/functions_exif.php');
		$tpl = replace_exif_tags_null($tpl);
	}
  /////////////
  // build a string with all comments
	// only perform this code when the user has commenting enabled
  if(isset($_GET['x'])&&($_GET['x'] == "") or (isset($_GET['popup'])&&$_GET['popup'] == "comment"))
  {
		$comments_result = sql_array("SELECT comments FROM ".$pixelpost_db_prefix."pixelpost where id = '".$_POST['parent_id']."'");
		$cmnt_setting = pullout($comments_result['comments']);
		if($cmnt_setting == 'F')	die('Die you SPAMMER!!');
  } // end if comment

 	// visitor information in comments
 	$vinfo_name = "";
 	$vinfo_url = "";
 	$vinfo_email = "";
 	if(isset($_COOKIE['visitorinfo']))	list($vinfo_name,$vinfo_url,$vinfo_email) = split("%",$_COOKIE['visitorinfo']);

 	$tpl = preg_replace("/<VINFO_NAME>/",$vinfo_name,$tpl);
 	$tpl = preg_replace("/<VINFO_URL>/",$vinfo_url,$tpl);
 	$tpl = preg_replace("/<VINFO_EMAIL>/",$vinfo_email,$tpl);
 	if ($cfgrow['token'] == 'T'){
 		$tpl = preg_replace("/<TOKEN>/","<input type='hidden' name='token' value='".$_SESSION['token']."' />",$tpl);
 	} else {
 		$tpl = preg_replace("/<TOKEN>/",null,$tpl);
 	}
	if($_GET['showimage'] == "")	$imageid = $image_id;
	else	$imageid = $_GET['showimage'];

	$image_comments = print_comments($imageid);
	$tpl = preg_replace("/<IMAGE_COMMENTS>/",$image_comments,$tpl);

	if(($_GET['popup'] == "comment") AND (!isset($_GET['x'])OR$_GET['x'] != "save_comment"))
	{
		include_once('includes/addons_lib.php');
		echo $tpl;
		exit;
	}
//} // end if comment
} // end imageprint

$tpl = preg_replace("/<SITE_TITLE>/",$pixelpost_site_title,$tpl);

// ##########################################################################################//
// BROWSE STUFF
// ##########################################################################################//
require("includes/functions_browse.php");


// ##########################################################################################//
// FEED STUFF
// ##########################################################################################//
require("includes/functions_feeds.php");

// ##########################################################################################//
// creating other tags
// ########################################################################################
$tpl = preg_replace("/<SITE_BROWSELINK>/","./index.php?x=browse",$tpl);
$tpl = preg_replace("/<SITE_BROWSELINK_PAGED>/","./index.php?x=browse&amp;pagenum=1",$tpl);
$tpl = preg_replace("/<SITE_PHOTONUMBER>/",$pixelpost_photonumb,$tpl);
$tpl = preg_replace("/<SITE_VISITORNUMBER>/",$pixelpost_visitors,$tpl);
$tpl = preg_replace("/<IMAGE_COMMENTS_NUMBER>/",$image_comments_number,$tpl);
$tpl = preg_replace("/<LATEST_COMMENT_ID>/",$latest_comment,$tpl);
$tpl = preg_replace("/<LATEST_COMMENT_NAME>/",$latest_comment_name,$tpl);
if($image_comments_number != 1){
	$tpl = preg_replace("/<IMAGE_COMMENT_TEXT>/",$lang_comment_plural,$tpl);
}else{
	$tpl = preg_replace("/<IMAGE_COMMENT_TEXT>/",$lang_comment_single,$tpl);	
}
if ($row['comments'] == 'F')
{
	$tpl = preg_replace("/<COMMENT_POPUP>/","<a href='index.php?showimage=$image_id' onclick=\"alert('$lang_comment_popup_disabled');\">$lang_comment_popup</a>",$tpl);
}
else
{
	$tpl = preg_replace("/<COMMENT_POPUP>/","<a href='index.php?showimage=$image_id' onclick=\"window.open('index.php?popup=comment&amp;showimage=$image_id','Comments','width=480,height=540,scrollbars=yes,resizable=yes');\">$lang_comment_popup</a>",$tpl);
}
$tpl = preg_replace("/<BROWSE_CATEGORIES>/",$browse_select,$tpl);
$tpl = str_replace("<BASE_HREF>","<base href='".$cfgrow['siteurl']."' />",$tpl);

// ##########################################################################################//
// COMMENT STUFF
// ##########################################################################################//
require("includes/functions_comments.php");

// ##########################################################################################//
// REPLACE LANGUAGE SPECIFIC TAGS
// ##########################################################################################//

if ($cfgrow['altlangfile'] != 'Off')
{
	$tpl = replace_alt_lang_tags( $tpl, $language_abr, $PP_supp_lang, $cfgrow);
}

// ##########################################################################################//
// SUCK IN ADDONS
// ##########################################################################################//

include_once('includes/addons_lib.php');

// ##########################################################################################//
// END - ECHO TEMPLATE
// ##########################################################################################//

if( (isset($_GET['x'])&&$_GET['x'] != "save_comment") || (!isset($_GET['x'])))	echo $tpl;

?>
