<?php

// SVN file version:
// $Id: functions.php 242 2007-04-11 15:54:46Z schonhose $

// Will work in despite of Windows ACLs bug
// NOTE: use a trailing slash for folders!!!
// see http://bugs.php.net/bug.php?id=27609
// see http://bugs.php.net/bug.php?id=30931

// Source: <http://www.php.net/is_writable#73596>

function is__writable($path) {
    if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
        return is__writable($path.uniqid(mt_rand()).'.tmp');
    else if (is_dir($path))
        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
    // check tmp file for read/write capabilities
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f===false)
        return false;
    fclose($f);
    if (!$rm)
        unlink($path);
    return true;
}


// Check if directory exists;
// If it doesn't, attempt to create it.

function check_and_set($directory){
    if(@file_exists($directory)){
        if(@is__writable($directory)){
            return "ok";
        }else{
            if(@chmod($directory, 0777)){
                return "ok";
            }else{    
                return "chmod";
            }
        }
    }else{
        if(@mkdir($directory)){
            return check_and_set($directory);
        }else{
            return "create";
        }
    }    
}


// Returns the Pixelpost version by looking at the database.  Returns 0 if not installed.
// This is used when performing the initial install/upgrade

function Get_Pixelpost_Version( $db, $prefix){
	// First, check to see if we are 1.4 or better
	$querystr = "SELECT version FROM {$prefix}version ORDER BY version DESC LIMIT 1;";
	$query = mysqli_query( $db, $querystr);
	if($query){
		if( $row = mysqli_fetch_array( $query)){
			if( $row[0] > 1.3) return $row[0];
		}
	}
	// Are we even installed?
	$query = mysqli_query( $db, "SELECT COUNT(admin) FROM {$prefix}config");
	if($query){
		if( $row = mysqli_fetch_array( $query )){
			if( $row[0] > 0) return 1.3;	// This could also be 1.2, but that is okay
		}
	}
	return 0;	// Everything failed, must not be installed
}


function print_comments($imageid)
{
	global $pixelpost_db_prefix;
	global $lang_no_comments_yet;
	global $lang_visit_homepage;
	global $cfgrow;

	$comment_count = 0;
	$image_comments = "<ul>"; // comments stored in this string
	$cquery = mysqli_query("select datetime, message, name, url, email  from ".$pixelpost_db_prefix."comments where parent_id='".$imageid."' and publish='yes' order by id asc");
	while(list($comment_datetime, $comment_message, $comment_name, $comment_url, $comment_email) = mysqli_fetch_row($cquery))
	{
		$comment_message = pullout($comment_message);
		$comment_name = pullout($comment_name);
		$comment_email = pullout($comment_email);

		if($comment_url != "")
		{
	  	if( preg_match( '/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$comment_url))
			{
  			$comment_name = "<a href=\"$comment_url\" title=\"$lang_visit_homepage\" target=\"new\" rel=\"nofollow\">$comment_name</a>";
			}
			else
			{
				unset($comment_url);
				$comment_name = "$comment_name";
			}
		}

		$comment_datetime = strtotime($comment_datetime);
		$comment_datetime = date($cfgrow['dateformat'],$comment_datetime);
		if ($comment_email == $cfgrow['email']){
			// admin comment
			$image_comments .= "<li class=\"admin_comment\">$comment_message<br />$comment_name @ $comment_datetime</li>";
		} else {
			$image_comments .= "<li>$comment_message<br />$comment_name @ $comment_datetime</li>";
		}
		$comment_count++;

	}

	if($comment_count == 0)	$image_comments .= "<li>$lang_no_comments_yet</li>";

	$image_comments .= "</ul>";

	return $image_comments;
}



function check_upload($string)
{
	global $admin_lang_pp_up_error_0, $admin_lang_pp_up_error_1, $admin_lang_pp_up_error_2, $admin_lang_pp_up_error_3, $admin_lang_pp_up_error_4;

	$error_explained = array(
		"0" => "$admin_lang_pp_up_error_0",
		"1" => "$admin_lang_pp_up_error_1",
		"2" => "$admin_lang_pp_up_error_2",
		"3" => "$admin_lang_pp_up_error_3",
		"4" => "$admin_lang_pp_up_error_4",
		"6" => "$admin_lang_pp_up_error_6",
		"7" => "$admin_lang_pp_up_error_7"
		);

 	$result = $error_explained[$string];
	return($result);
	}

function createthumbnail($db, $file){
  	global $pixelpost_db_prefix;
  	$cfgquery = mysqli_query($db, "select * from ".$pixelpost_db_prefix."config");
  	$cfgrow = mysqli_fetch_array($cfgquery);
  	// credit to codewalkers.com - there is 90% a tutorial there
  	$max_width = $cfgrow['thumbwidth'];
  	$max_height = $cfgrow['thumbheight'];
  	define(IMAGE_BASE, "../images");
  	$image_path = IMAGE_BASE . "/$file";
  	$img = null;
  	$image_path_exp = explode('.', $image_path);
  	$image_path_end = end($image_path_exp);
  	$ext = strtolower($image_path_end);
  	if ($ext == 'jpg' || $ext == 'jpeg'){
    	$img = @imagecreatefromjpeg($image_path);
  	}elseif($ext == 'png'){
    	$img = @imagecreatefrompng($image_path);
  	}elseif($ext == 'gif'){
    	$img = @imagecreatefromgif($image_path);
  	}

  	if($img){
    	$width = imagesx($img);
    	$height = imagesy($img);
    	$scale = max($max_width/$width, $max_height/$height);

    	if($scale < 1){
      		$new_width = floor($scale*$width);
      		$new_height = floor($scale*$height);
      		$tmp_img = imagecreatetruecolor($new_width,$new_height);
      		// gd 2.0.1 or later: imagecopyresampled
      		// gd less than 2.0: imagecopyresized
      		if(function_exists(imagecopyresampled)){
        		imagecopyresampled($tmp_img, $img, 0,0,0,0,$new_width,$new_height,$width,$height);
      		}else{
        		imagecopyresized($tmp_img, $img, 0,0,0,0,$new_width,$new_height,$width,$height);
      		}
	    	imagedestroy($img);
	    	$img = $tmp_img;
    	}

    	if($cfgrow['crop'] == "yes" | $cfgrow['crop'] == "12c"){
      	// 	// crop
      		$tmp_img = imagecreatetruecolor($max_width,$max_height);
      		if(function_exists(imagecopyresampled)){
        		imagecopyresampled($tmp_img, $img, 0,0,0,0,$max_width,$max_height,$max_width,$max_height);
      		}else{
        		imagecopyresized($tmp_img, $img, 0,0,0,0,$max_width,$max_height,$max_width,$max_height);
      		}
      		imagedestroy($img);
      		$img = $tmp_img;
    	} // end crop yes
  	}
  	touch("../thumbnails/thumb_$file");
  	imagejpeg($img,"../thumbnails/thumb_$file",$cfgrow['compression']);
  	$thumbimage = "../thumbnails/thumb_$file";
  	chmod($thumbimage,0644);
}

function sql_query($db, $str)
{
	$query = "$str";
	$result = mysqli_query($db, $query) || die(mysqli_error($db));
}

function clean($str)
{
		//$str = UTF8_encode($str);
	$str = addslashes($str);
	return $str;
}

function pullout($str)
{
	$str = stripslashes($str);
	//$str = UTF8_decode($str);
	return $str;
}

function clean_url($str)
{
	$url = EscapeShellCmd($str);
	return $str;
}

function book_visitor($str)
{
	global $cfgrow;
	// book a visitor
	$datetime = gmdate("Y-m-d H:i:s",gmdate("U")+(3600 * $cfgrow['timezone']));
	$host = $_SERVER['HTTP_HOST'];
	$referer = addslashes($_SERVER['HTTP_REFERER']);

	// don't book a referer from self
	$refererhost = parse_url($referer);
	$refererhost = $refererhost['host'];
	if($refererhost == $host)
	{
   	$referer = "";
	}
	$ua = addslashes($_SERVER['HTTP_USER_AGENT']);
	$ip = $_SERVER['REMOTE_ADDR'];
	$ruri = addslashes($_SERVER['REQUEST_URI']);
	// ### if cookie lastvisit not set, count the people!
	if(!isset($_COOKIE['lastvisit']))
	{
		$query = "insert into $str(id,datetime,host,referer,ua,ip,ruri)
		VALUES(NULL,'$datetime','$host','$referer','$ua','$ip','$ruri')";
    	$result = mysqli_query($query);
	}
}

function sql_array( $db, $str)
{
	$query = mysqli_query($db, $str);
	$row = mysqli_fetch_array($query);
	return $row;
}

function start_mysql()
{
	global $pixelpost_db_host, $pixelpost_db_user, $pixelpost_db_pass, $pixelpost_db_pixelpost;
	$dir = 'templates';
	if (!file_exists($dir ."/splash_page.html"))
		$dir = '../templates';

		$db = mysqli_connect($pixelpost_db_host, $pixelpost_db_user, $pixelpost_db_pass, $pixelpost_db_pixelpost );
		if (!$db) {
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
			echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			show_splash("Connect DB Error: ". mysqli_error($db)." Cause #2",$dir);
			exit;
		}
		return $db;
		// if(!mysqli_connect($pixelpost_db_host, $pixelpost_db_user, $pixelpost_db_pass))
		// show_splash("Connect DB Error: ". mysqli_error()." Cause #2",$dir);

	// if(!mysqli_select_db($pixelpost_db_pixelpost))
	// 	show_splash("Select DB Error: ". mysqli_error()." Cause #2",$dir);
}

// function show_splash
function show_splash($extra_message,$splash_dir)
{
if (file_exists($splash_dir."/splash_page.html")){
		$splash = file_get_contents($splash_dir."/splash_page.html");
		$splash = preg_replace("/<ERROR_MESSAGE>/",$extra_message,$splash);
		die($splash) ;
	} else {
	die($extra_message);
	}
}

function &reduceExif($exifvalue)
{
	$vals = split("/",$exifvalue);
	if(count($vals) == 2)
	{
		// MJS 29092005 - Code to deal with exposure times of > 1 sec
		if ( $vals[1] == 0)	$exposure = round($vals[0].$vals[1],2);
		else
		{
			$exposure = round($vals[0]/$vals[1],2);
			if ( $exposure < 1) $exposure = '1/'.round($vals[1]/$vals[0],0);
		}
	}
	else	$exposure = round($vals[0]/$vals[1], 2);

	return $exposure;
}


// categories as table
function category_list_as_table($db, $categories, $cfgrow)
{
	global $pixelpost_db_prefix;

	if(!is_array($categories))	$categories = array();
  // get the id and name of the first entered category, default category.
  $query = mysqli_query($db, "select * from ".$pixelpost_db_prefix."categories order by id asc LIMIT 0,1");
  list($firstid,$firstname) = mysqli_fetch_row($query);
  $getid = $_GET['id'];
 // begin of category-list as a table
	$query = mysqli_query($db, "select t1.id, name, alt_name, image_id from ".$pixelpost_db_prefix."categories as t1 left join ".$pixelpost_db_prefix."catassoc t2 on t2.cat_id = t1.id and t2.image_id='$getid' order by t1.name");
	while(list($id,$name) = mysqli_fetch_row($query))
	{
		echo "<table id='cattable'><tr>";
		$query = mysqli_query($db, "select t1.id, name, alt_name, image_id from ".$pixelpost_db_prefix."categories as t1 left join ".$pixelpost_db_prefix."catassoc t2 on t2.cat_id = t1.id and t2.image_id='$getid' order by t1.name");
		while(list($id,$name,$alt_name,$image_id) = mysqli_fetch_row($query))
		{
			$name = pullout($name);
			// Check if the secondary language is enabled. If not there is no need to show these fields
			if ($cfgrow['altlangfile'] != 'Off')
			{
				$alt_name = " (".pullout($alt_name).")";
			}
			else
			{
				// replace with empty string.
				$alt_name = null;
			}
			$id = pullout($id);
			$catcounter++;
			$inarow = 4;
			if (($image_id != "" AND $_GET['view']=='images') || in_array($id,$categories))
			{
				echo "<td><input type='checkbox' CHECKED name='category[]' value='".$id."' />&nbsp;".$name.$alt_name."</td>";
			}
			else
			{
				if ($firstid==$id && $_GET['view']!='images') // if it is the first defualt category in the new_image page
					echo "<td><input type='checkbox' name='category[]' value='".$id."' />&nbsp;".$name.$alt_name."</td>";
				else // if it is other categories in the new image page
					echo "<td><input type='checkbox' name='category[]' value='".$id."' />&nbsp;".$name.$alt_name."</td>";
			}

			if ($catcounter % $inarow == 0)	echo "\n</tr><tr>\n";
			else	echo "\n";
		}
	}

	if ($catcounter % $inarow > 0)	echo "</tr>";

	echo "</table>\n\n";
}

// function refresh addon table
function refresh_addons_table($dir)
{
	global $pixelpost_db_prefix;
	add_new_addons_2table($dir);
	delete_obsolute_addon($dir,$pixelpost_db_prefix);
}

// add new addon 2 addons table
function add_new_addons_2table($dir)
{
	global $pixelpost_db_prefix;
	global $pixelpost_db_pixelpost;
	//start_mysql();
	// Check to see if the ban table exists, if not, create it
	//$query = "show tables from ".$pixelpost_db_pixelpost." like '".$pixelpost_db_prefix."addons'";
	$query = "SHOW TABLES FROM `".$pixelpost_db_pixelpost."` LIKE '".$pixelpost_db_prefix."addons'";
	$query = mysqli_query( $query);
	$query = mysqli_fetch_array($query);
	if ($query !='')
	{// addons table does exist
		$str = '';

		if($handle = opendir($dir))
		{
			while (false !== ($file = readdir($handle)))
			{
				if($file != "." && $file != "..")
				{
					$farry = explode('.', $file);
					reset($farry);
					$filename = current($farry);
					$filename_exp = explode('_', $filename);

					if (is_array($filename_exp))	$filename_crnt = current($filename_exp);

					$addontype = strtolower($filename_crnt);
					$farry_end = end($farry);
					$ftype = strtolower($farry_end);
					if($ftype == "php" AND !check_addon_exists($filename,$pixelpost_db_prefix))
					{
						switch (strtolower($addontype)){
							case "admin":
								$query = "INSERT INTO {$pixelpost_db_prefix}addons VALUES ( NULL, '$filename',  'on', '".strtolower($addontype)."')";
								break;	
							case "front":
								$query = "INSERT INTO {$pixelpost_db_prefix}addons VALUES ( NULL, '$filename',  'on', '".strtolower($addontype)."')";
								break;	
							default:
								$query = "INSERT INTO {$pixelpost_db_prefix}addons VALUES ( NULL, '$filename', 'on', 'normal')";
								break; 	
						}
						mysqli_query( $query);
						if (mysqli_error())	echo 'Failed to insert addon: ' .$filename .'.php';
					}//end if
				}//end if
			}// end while

			closedir($handle);
		}// end if
	} // end if addon table exist
}

// delete the row of the deleted addon
function delete_obsolute_addon($dir,$db_prefix)
{
	global $pixelpost_db_prefix;
	global $pixelpost_db_pixelpost;
	//start_mysql();
	// Check to see if the ban table exists, if not, create it
	//$query = "show tables from `".$pixelpost_db_pixelpost."` like '".$pixelpost_db_prefix."addons'";
	$query = "SHOW TABLES FROM `".$pixelpost_db_pixelpost."` LIKE '".$pixelpost_db_prefix."addons'";
	$query = mysqli_query( $query);
	$query = mysqli_fetch_array($query);
	if ($query !='')
	{     // addons table does exist
		$query = "select id,addon_name from {$db_prefix}addons ";
		$query = mysqli_query($query);
		while (list($id,$addon_name)= mysqli_fetch_row($query))
		{
			if (!file_exists($dir.$addon_name.".php"))
			{
				$querydel = "delete from {$db_prefix}addons where id='$id' ";
				$resquerydel = mysqli_query($querydel);
				if (mysqli_error())	echo 'Failed to delete the addon_name: ' .$addon_name;
			} // end if file not exists
		}	/// end while
	}// end if addon exists
}

// check existence of an addon in the addons table
function check_addon_exists($name,$db_prefix)
{
	$returnvalue = FALSE;
	$query = "select id from {$db_prefix}addons where addon_name='$name'";
	$query = mysqli_query($query);
	while (list($id)= mysqli_fetch_row($query))
	{
		if (is_numeric($id))	$returnvalue = TRUE;
	}
	return $returnvalue;
}
// check if a table exists inside PP DB
function is_table_created($table_name)
{
	global $pixelpost_db_prefix;

  // Check to see if the ban table exists, if not, create it
  $query = "SELECT id FROM {$pixelpost_db_prefix}".$table_name." LIMIT 1";
  if( mysqli_query($query))	return true;
  else	return false;
}

// check if a field exist inside a table
function is_field_exists ($fieldname,$table)
{
	global $pixelpost_db_prefix, $pixelpost_db_pixelpost;
	// FIELD_EXISTS
	// Checks whether specified field exists in current or specified table.
	//$fieldname = "maxpthumb";
	$table = $pixelpost_db_prefix .$table;
	$fieldexists = FALSE;
	$t = 0;
	$attention_call = "";
	if ($table != "")
	{
		if ($table_name != "") $current_table = $table;
		$result_id = mysqli_list_fields( $pixelpost_db_pixelpost, $table);
		for ($t = 0; $t < mysqli_num_fields($result_id); $t++)
		{
			$msql_fname = mysqli_field_name($result_id, $t);

			if (strtolower( $fieldname) == strtolower($msql_fname))
			{
				$fieldexists = TRUE;
				break;
			}
		}
	}
	return $fieldexists;
}

//----------- for addons in admin panel

//
function add_admin_functions($function_name, $function_workspace,$function_menu ='' ,$function_submenu ='')
{
	global $addon_admin_functions;
	$wrkspc_fcn = array('function_name' => $function_name,
											'workspace' => $function_workspace,
											'menu_name' => $function_menu,
											'submenu_name' => $function_submenu);
	$c = count($addon_admin_functions);
	$end = array($c => $wrkspc_fcn);
	$addon_admin_functions = array_merge($addon_admin_functions, $end);
}

// evaluates the admin functions
function eval_addon_admin_workspace_menu ($workspace,$menu_name ='')
{
	global $addon_admin_functions;
	for ($i = 0 ; $i < count($addon_admin_functions) ; $i++)
	{
		$funcs = $addon_admin_functions[$i];
//	foreach ($addon_admin_functions as $key => $funcs)
//	{
		$view_menu = $menu_name ."view";

		// if action is needed
		if ($funcs['workspace']== strtolower($workspace))
		{
			// if main menu
			if ($funcs['workspace']=='admin_main_menu')
			{
				echo "<a href='".$_SERVER['PHP_SELF']."?view=".strtolower($funcs['menu_name'])."'>".$funcs['menu_name']."</a>";
				continue;
			}
	
			// no menu
			if ($menu_name=='')
			{
				if ($funcs['workspace']=='admin_main_menu_contents' & $_GET['view']!=strtolower($funcs['menu_name']))	continue;
				call_user_func ($funcs['function_name']);
			}	// end if menu is empty
			else
			{
			// if menu is needed
				if ($_GET['view']==strtolower($menu_name) && $_GET[$view_menu] == strtolower($funcs['submenu_name']))
				{
					// add style (show that the button is pushed!)
					// BANNED! AND REPLACED WITH SOMETHING ELSE!
					//$style_of_selected = "<style> #".$_GET['view'].$_GET[$view_menu] ."{background:#FFA500;}	</style>";
					//echo $style_of_selected ;
					call_user_func ($funcs['function_name']);
				}// end if menu is need
			} // end else
		} // end if workspace
	}// end foreach
} // end function

// create array
function create_admin_addon_array()
{
	global $addon_admin_functions;
	global $pixelpost_db_prefix;
	$acounter = 0;
	$dir = "../addons/";

	if( $_GET['view'] != "addons")
	{
		$query_ad_s = "select * from {$pixelpost_db_prefix}addons where status='on' and type='admin'";
		$query_ad_s = mysqli_query($query_ad_s);

		while (list($id,$filename,$status,$addon_type)= mysqli_fetch_row($query_ad_s))
		{
			//$addontype = strtolower(current(explode('_', $filename)));
			$dir = "../addons/";
			include($dir.$filename.".php");
		} // end while
	} // end if not addons
//return $addon_admin_functions;
}


// echos the submenu title
function echo_addon_admin_menus ($addon_admin_menus,$menu_name,$additional = '')
{
	for ($i = 0 ; $i < count($addon_admin_menus) ; $i++)
	{
	$submenus = $addon_admin_menus[$i];
//	foreach ($addon_admin_menus as $key => $submenus)
//	{

		if ($submenus['menu_name'] == $menu_name)
		{
			$submenu_name = $submenus['submenu_name'];
			// echo  html content of that sub menu
			$menuitem = strtolower($menu_name).'view';
			$submenuitem = strtolower($submenu_name);
			$selecteclass = '';
			if (isset($_GET[$menuitem]) && ($_GET[$menuitem] == $submenuitem))
				$selecteclass='selectedsubmenu';
				$toecho ="<a class='".$selecteclass."' href='?view=".strtolower($menu_name) ."&amp;".$menuitem ."=".$submenuitem.$additional."' id='".$menu_name.$submenu_name."'>" .strtoupper($submenu_name) ."</a>";
			echo $toecho;
		}
	}
}

function count_addon_admin_menus ($addon_admin_menus,$menu_name,$additional = '')
{
	$menu_items=0;
	for ($i = 0 ; $i < count($addon_admin_menus) ; $i++)
	{
	$submenus = $addon_admin_menus[$i];
		if ($submenus['menu_name'] == $menu_name)
		{
			$menu_items=$menu_items+1;
		}
	}
	return $menu_items;
}
//============================= CONTROL SPAM SECTION BEGINS ========================

function banlist_exist($db){
	global $pixelpost_db_prefix;
	// Check to see if the banlist table exists, if not, create it
	$ret = TRUE;
	$query = "SELECT id FROM {$pixelpost_db_prefix}banlist LIMIT 1";
	if( !mysqli_query($db, $query))
	 $ret = FALSE;
	return $ret;
}

// function create banlist table
function create_banlist($db){
	global $pixelpost_db_prefix;
	$result = '';
	if (!banlist_exist($db)){
		$query = "CREATE TABLE {$pixelpost_db_prefix}banlist (
		id INT(11) NOT NULL auto_increment,
		moderation_list MEDIUMTEXT,
		blacklist MEDIUMTEXT,
		ref_ban_list MEDIUMTEXT,
		acceptable_num_links int(3) NOT NULL default '2',
		PRIMARY KEY  (id)
		)";
	  	mysqli_query( $db, $query);
	  	$query = "INSERT INTO {$pixelpost_db_prefix}banlist VALUES ( NULL,'','','tramadol\n-online\nadipex\nadvicer\nambien\nbllogspot\ncarisoprodol\ncasino\ncasinos\nbaccarrat\ncialis\ncwas\ncyclen\ncyclobenzaprine\nday-trading\ndiscreetordering\ndutyfree\nduty-free\nfioricet\nfreenet-shopping\nincest\nlevitra\nmacinstruct\nmeridia\nonline-gambling\npaxil\nphentermine\nplatinum-celebs\npoker-chip\npoze\nprescription\nsoma\nslot-machine\ntaboo\nteen\ntramadol\ntrim-spa\nultram\nviagra\nxanax\nbooker\nzolus\nchatroom\npoker\ncasino\ntexas\nholdem','2')";
	  	mysqli_query($db, $query);
	  	if (mysqli_error($db))
	  		$result = "$admin_lang_spam_err_1".mysqli_error($db);
	  	else
	  		$result = "$admin_lang_spam_tableadd";
	}// end if
	return $result;
}

// Update the ban list if the form is called
function update_banlist($db){
	global $pixelpost_db_prefix;
	if( isset( $_POST['banlistupdate'])){
		// moderation list
		if (isset( $_POST['moderation_list'])){
			$banlist = str_replace( "\r\n", "\n", $_POST['moderation_list']);
			$banlist = str_replace( "\r", "\n", $banlist);
			if (version_compare(phpversion(),"4.3.0")=="-1")	$banlist = mysqli_escape_string($db, $banlist);
			else	$banlist = mysqli_real_escape_string($db, $banlist);
			$query = "UPDATE {$pixelpost_db_prefix}banlist SET moderation_list='$banlist' LIMIT 1";
			mysqli_query($db, $query) ;
			if ( mysqli_error($db))	$result .= "$admin_lang_spam_err_2".mysqli_error($db)."<br/>";
		}// end if

		// black list
		if (isset( $_POST['blacklist'])){
			$banlist = str_replace( "\r\n", "\n", $_POST['blacklist']);
			$banlist = str_replace( "\r", "\n", $banlist);
			if (version_compare(phpversion(),"4.3.0")=="-1")	$banlist = mysqli_escape_string($db, $banlist);
			else	$banlist = mysqli_real_escape_string($db, $banlist);
			$query = "UPDATE {$pixelpost_db_prefix}banlist SET blacklist='$banlist' LIMIT 1";
			mysqli_query($db, $query) ;
			if ( mysqli_error($db))	$result .= "$admin_lang_spam_err_3".mysqli_error($db)."<br/>";
		}// end if

		// referer ban list
		if (isset( $_POST['ref_ban_list'])){
			$banlist = str_replace( "\r\n", "\n", $_POST['ref_ban_list']);
			$banlist = str_replace( "\r", "\n", $banlist);
			if(version_compare(phpversion(),"4.3.0")=="-1")	$banlist = mysqli_escape_string($db, $banlist);
			else	$banlist = mysqli_real_escape_string($db, $banlist);
			$query = "UPDATE {$pixelpost_db_prefix}banlist SET ref_ban_list='$banlist' LIMIT 1";
			mysqli_query($db, $query) ;
			if ( mysqli_error($db))	$result .= "$admin_lang_spam_err_4 ".mysqli_error($db)."<br/>";
		}// end if

		// acceptable_num_links
		if (isset( $_POST['acceptable_num_links'])){
			$acceptable_num_links= $_POST['acceptable_num_links'];
			$query = "UPDATE {$pixelpost_db_prefix}banlist SET acceptable_num_links='$acceptable_num_links' LIMIT 1";
			mysqli_query($db, $query) ;
			if ( mysqli_error($db))	$result .= "$admin_lang_spam_err_5 ".mysqli_error($db)."<br/>";
		}
		if (!isset($result))	$result = "$admin_lang_spam_upd";
		$result = $result."<br/>";
	} // end if isset( $_POST['banlistupdate'])
	return $result;
}

// Get the moderation_list
function get_moderation_banlist($db)
{
	global $pixelpost_db_prefix;
	$query = "SELECT moderation_list FROM {$pixelpost_db_prefix}banlist LIMIT 1";
	$result = mysqli_query($db, $query) or die( mysqli_error($db));
	if( $row = mysqli_fetch_row($result))	$banlist = $row[0];
	return $banlist;
}

// Get the blacklist
function get_blacklist($db)
{
	global $pixelpost_db_prefix;
	$query = "SELECT blacklist FROM {$pixelpost_db_prefix}banlist LIMIT 1";
	$result = mysqli_query($db, $query) or die( mysqli_error($db));
	if( $row = mysqli_fetch_row($result))	$banlist = $row[0];
	return $banlist;
}

// Get the ref_ban_list
function get_ref_ban_list($db)
{
  	global $pixelpost_db_prefix;
	$query = "SELECT ref_ban_list FROM {$pixelpost_db_prefix}banlist LIMIT 1";
	$result = mysqli_query($db, $query) or die( mysqli_error($db));
	if( $row = mysqli_fetch_row($result))	$banlist = $row[0];
	return $banlist;
}

// prevent bad comments!
function check_moderation_blacklist($cmnt_message,$cmnt_ip,$cmnt_name,$field)
{
	global $pixelpost_db_prefix;

// help from wordpress codes
  $query = "select ".$field." from {$pixelpost_db_prefix}banlist LIMIT 1";
  $result = mysqli_query($query);
  $bad_keys = mysqli_fetch_array($result);

	$words = explode("\n", $bad_keys[$field]);

	foreach ($words as $word)
	{
		$word = trim($word);

		// Skip empty lines
		if ( empty($word))	continue;

		// Do some escaping magic so that '#' chars in the
		// spam words don't break things:
		$word = preg_quote($word, '#');

		$pattern = "#$word#i";
		if ( preg_match($pattern, $cmnt_message)) return true;
		if ( preg_match($pattern, $cmnt_ip)) return true;
		if ( preg_match($pattern, $cmnt_name)) return true;
		/*
		if ( preg_match($pattern, $comment)) return true;
		if ( preg_match($pattern, $user_ip)) return true;
		if ( preg_match($pattern, $user_agent)) return true;
		*/
	}
	return false;
}
// is it in blacklist
function is_comment_in_blacklist($cmnt_message,$cmnt_ip,$cmnt_name)
{
	$field = 'blacklist';
	return check_moderation_blacklist($cmnt_message,$cmnt_ip,$cmnt_name,$field);
}

// is it in blacklist
function is_comment_in_moderation_list($cmnt_message,$cmnt_ip,$cmnt_name)
{
	$field = 'moderation_list';
	return check_moderation_blacklist($cmnt_message,$cmnt_ip,$cmnt_name,$field);
}

// clean the reflist entry. no http
function clean_reflist ($entry)
{
	$entry = explode('http://',$entry);
	$entry = end($entry);
	$entry = end(explode('https://',$entry));
	return $entry;
}

// is ref list entry an IP?
function is_entry_ip ($entry)
{
	$entry = explode('.',$entry);
	$entry = current($entry);
	$entry = trim($entry);
	return is_numeric($entry);
}

// create the .htaccess for copy paste
function create_htaccess_banlist($db)
{
	$badreflist = "SetEnvIfNoCase Referer \".*(";
	$ref_banlist = get_ref_ban_list($db);
	$ref_banlist = explode("\n",$ref_banlist);
	if (is_array($ref_banlist)){
		foreach ($ref_banlist as $entry){
			if ($entry=='')	continue;
			$entry = trim($entry);
			$entry = clean_reflist($entry);
			if (is_entry_ip($entry))	$denylist .= "deny from " .$entry."\n";
			else	$badreflist .= $entry."|";
		}// end for each
	}else{
		$entry = trim($ref_banlist);
		$entry = clean_reflist($entry);
		if (is_entry_ip($entry))	$denylist .= "deny from " .$entry."\n";
		else	$badreflist .= $entry."|";
	}
	$badreflist .="baccarat.host-c.com).*\" BadReferrer\norder deny,allow\n";
	$badreflist .="deny from env=BadReferrer";
	$to_htaccess = $denylist.$badreflist;
	return $to_htaccess;
}

// compare the moderation list with comments
function moderate_past_with_list($db){
	global $pixelpost_db_prefix;
	$where ='';
	if ($_GET['antispamaction']=='moderation')
	{
		$banlist= get_moderation_banlist($db);
		$banlist = str_replace( "\r\n", "\n",$banlist);
		$banlist = str_replace( "\r", "\n", $banlist);
		$banlist = explode("\n",$banlist);
		if (is_array($banlist)){
			foreach ($banlist as $entry){
				if ($entry=='')	continue;
				$entry = trim($entry);
				$where .= " message LIKE '%{$entry}%' OR name LIKE '%{$entry}%' OR ip LIKE '%{$entry}%' OR ";
			}// end for each
		}else{
			$entry = trim($ref_banlist);
			$where .= " message LIKE '%{$entry}%' OR name LIKE '%{$entry}%' OR ip LIKE '%{$entry}%' OR ";
		}

		$where .= ' 0 ';
		$query = "UPDATE {$pixelpost_db_prefix}comments SET publish='no' WHERE $where ";
		mysqli_query($db, $query);
		if (mysqli_error($db))	$additional_msg = "$admin_lang_spam_err_6 ".mysqli_error($db)."<br/>";
		else	$additional_msg = "$admin_lang_spam_com_upd"."<br/>";
	}// end if moderation
	$additional_msg = $additional_msg;
	return $additional_msg;
}


// delete comments which contains words from the blacklist
function delete_past_with_list($db){
	global $pixelpost_db_prefix;
	$where ='';
	if ($_GET['antispamaction']=='deletecmnt'){
		$banlist= get_blacklist($db);
		$banlist = str_replace( "\r\n", "\n",$banlist);
		$banlist = str_replace( "\r", "\n", $banlist);
		$banlist = explode("\n",$banlist);

		if (is_array($banlist))	{
			foreach ($banlist as $entry){
				if ($entry=='')	continue;
				$entry = trim($entry);
				$where .= " message LIKE '%{$entry}%' OR name LIKE '%{$entry}%' OR ip LIKE '%{$entry}%' OR ";
			}// end for each
		}else{
			$entry = trim($ref_banlist);
			$where .= " message LIKE '%{$entry}%' OR name LIKE '%{$entry}%' OR ip LIKE '%{$entry}%' OR ";
		}
		$where .= ' 0 ';
		$query = "delete from {$pixelpost_db_prefix}comments WHERE $where ";
		mysqli_query($db, $query);
		if (mysqli_error($db))	$additional_msg = "$admin_lang_spam_err_7 ".mysqli_error($db)."<br/>";
		else	$additional_msg = "$admin_lang_spam_com_del"."<br/>";
	}// end if moderation
	$additional_msg = $additional_msg;
	return $additional_msg;
}

// delete refs that are listed in the ref ban list
function delete_from_badreferer_list($db)
{
	global $pixelpost_db_prefix;
	$where ='';
	//if moderation of past comments pressed
	if ($_GET['antispamaction']=='deleterefs')
	{
		$banlist= get_ref_ban_list($db);
		$banlist = str_replace( "\r\n", "\n",$banlist);
		$banlist = str_replace( "\r", "\n", $banlist);
		$banlist = explode("\n",$banlist);
		if (is_array($banlist)){
			foreach ($banlist as $entry){
				if ($entry=='')	continue;
				$entry = trim($entry);
				$where .= " referer LIKE '%{$entry}%' OR ";
			}// end for each
		}else{
			$entry = trim($ref_banlist);
			$where .= " referer LIKE '%{$entry}%' OR ";
		}
		$where .= ' 0 ';
		$query = "delete from {$pixelpost_db_prefix}visitors WHERE $where ";
		mysqli_query($db, $query);
		if (mysqli_error($db))
			$additional_msg = "$admin_lang_spam_err_8".mysqli_error($db)."<br/>";
		else
			$additional_msg = "$admin_lang_spam_visit_del"."<br/>";
	}// end if moderation
	$additional_msg = $additional_msg;
	return $additional_msg;
}
//
//============================= ANTI SPAM SECTION ENDS   ========================

function clean_comment($string)
{
	$string = strip_tags($string);
	$string = htmlspecialchars($string,ENT_QUOTES);
	$string = addslashes($string);
	return $string;
}

//=============================== TAGS SECTION BEGINS ===========================

function save_tags_new($tags_str,$theid)
{
	global $pixelpost_db_prefix;

	if(strlen($tags_str) > 0)
	{
		$strtr_arr = array(',' => ' ', ';' => ' ');
		$tags = strtr($tags_str, $strtr_arr);
		$pat1 = '/([^a-zA-Z 0-9_-]+)/';
		$tags = preg_replace( $pat1, '_', $tags);
		$pat2 = array('/ _ /', '/ _/', '/(_){2,}/','/ - /', '/ -/', '/(-){2,}/');
		$rep2 = array('', '', '_', '', '', '-');
		$tags = preg_replace( $pat2, $rep2, $tags);
		$tags_arr = preg_split('/[ ]{1,}/',$tags,-1,PREG_SPLIT_NO_EMPTY);

		for($i = 0; $i < count($tags_arr); $i++)
		{
			$sql_tag = "INSERT INTO " . $pixelpost_db_prefix. "tags (img_id, tag) VALUES ( " . $theid . ",'" . addslashes($tags_arr[$i]) . "');";
			//$sql_tag = "INSERT INTO " . $pixelpost_db_prefix. "tags VALUES ( " . $theid . ",'" . addslashes($tags_arr[$i]) . "');";
			mysqli_query($sql_tag);
		}
	}
}

function list_tags_edit($db, $id)
{
	global $pixelpost_db_prefix;
	$tags = '';

	$sql_tag = "SELECT tag FROM " . $pixelpost_db_prefix . "tags WHERE img_id = " . $id . " AND alt_tag LIKE '' ORDER BY tag ASC";
	$query = mysqli_query($db, $sql_tag);

	while(list($tag) = mysqli_fetch_row($query))	$tags .= ' '.$tag;

	return trim($tags);
}

function del_tags_edit($db, $id)
{
	global $pixelpost_db_prefix;

	$sql_tag = "DELETE FROM " . $pixelpost_db_prefix . "tags WHERE img_id = " . $id . " AND tag NOT LIKE ''" ;
	mysqli_query($db, $sql_tag);
}

function save_tags_edit($db, $tags_str,$id)
{
	global $pixelpost_db_prefix;
	del_tags_edit($db, $id);
	save_tags_new($db, $tags_str,$id);
}

//
//=============================== TAGS SECTION ENDS =============================

//============================= LANGUAGE SECTION BEGINS =========================

// alt_tag functions

function save_alt_tags_new($db, $tags_str,$theid)
{
	global $pixelpost_db_prefix;

	if(strlen($tags_str) > 0)
	{
		$strtr_arr = array(',' => ' ', ';' => ' ');
		$tags = strtr($tags_str, $strtr_arr);
		$pat1 = '/([^a-zA-Z 0-9_-]+)/';
		$tags = preg_replace( $pat1, '_', $tags);
		$pat2 = array('/ _ /', '/ _/', '/(_){2,}/','/ - /', '/ -/', '/(-){2,}/');
		$rep2 = array('', '', '_', '', '', '-');
		$tags = preg_replace( $pat2, $rep2, $tags);
		$tags_arr = preg_split('/[ ]{1,}/',$tags,-1,PREG_SPLIT_NO_EMPTY);

		for($i = 0; $i < count($tags_arr); $i++)
		{
			$sql_tag = "INSERT INTO " . $pixelpost_db_prefix. "tags (img_id, alt_tag) VALUES ( " . $theid . ",'" . addslashes($tags_arr[$i]) . "');";
			mysqli_query($db, $sql_tag);
		}
	}
}

function list_alt_tags_edit($db, $id)
{
	global $pixelpost_db_prefix;
	$tags = '';

	$sql_tag = "SELECT alt_tag FROM " . $pixelpost_db_prefix . "tags WHERE img_id = " . $id . " AND tag LIKE '' ORDER BY tag ASC";
	$query = mysqli_query($db, $sql_tag);

	while(list($alt_tag) = mysqli_fetch_row($query))
	{
		$tags .= ' '.$alt_tag;
	}
	return trim($tags);
}

function del_alt_tags_edit($db, $id)
{
	global $pixelpost_db_prefix;

	$sql_tag = "DELETE FROM " . $pixelpost_db_prefix . "tags WHERE img_id = " . $id . " AND alt_tag NOT LIKE ''" ;
	mysqli_query($db, $sql_tag);
}


function save_alt_tags_edit($db, $tags_str,$id)
{
	global $pixelpost_db_prefix;
	del_alt_tags_edit($db, $id);
	save_alt_tags_new($db, $tags_str,$id);
}
//

function create_language_url_from_tag( $language_link_abr, $language_link_full)
{
	// changing $_SERVER['argv'] to $_SERVER['QUERY_STRING'], because argv may be not "on"
	if (($_SERVER['QUERY_STRING'] == "") OR (substr($_SERVER['QUERY_STRING'],0,5)=="lang="))
	{
		$language_link="<a href='".$_SERVER['PHP_SELF']."?lang=".strtolower( $language_link_abr)."'>".$language_link_full."</a>";
	}
	else
	{
		// removed &lang=XX from query string, otherways it is added which each language change
		$arguments=preg_replace('/\&lang=\w{2}/', '',$_SERVER['QUERY_STRING']);
		$arguments=str_replace("&","&amp;", $arguments);
		$language_link="<a href='".$_SERVER['PHP_SELF']."?".$arguments."&amp;lang=".strtolower( $language_link_abr)."'>".$language_link_full."</a>";
	}
	return $language_link;
}

function replace_alt_lang_tags( $tpl, $language_abr, $PP_supp_lang, $cfgrow)
{
	global $lang_alt_lang_dutch,$lang_alt_lang_english,$lang_alt_lang_french,$lang_alt_lang_german;
	global $lang_alt_lang_italian,$lang_alt_lang_norwegian,$lang_alt_lang_persian,$lang_alt_lang_polish;
  global $lang_alt_lang_portuguese,$lang_alt_lang_simplified_chinese,$lang_alt_lang_spanish,$lang_alt_lang_swedish;
	
  $default_language_abr = strtolower($PP_supp_lang[$cfgrow['langfile']][0]);
  $alt_language_abr = strtolower($PP_supp_lang[$cfgrow['altlangfile']][0]);
  if ($language_abr == $default_language_abr)
  {
  	$link_language_abr = $alt_language_abr;
  }
  else
  {
  	$link_language_abr = $default_language_abr;
  }
  // Determine the full name of the link_language
  foreach ($PP_supp_lang as $key => $row)
  {
  	foreach($row as $cell)
  	{
   		if ($cell == strtoupper($link_language_abr))	$language_link_key = $key;
    }
  }
  $language_link_name="lang_alt_lang_".$language_link_key;
  $language_link_full=${$language_link_name};
  
  $language_link	=	create_language_url_from_tag( $link_language_abr, $language_link_full);
  // Create one template tag for all templates and both languages
  $tpl = str_replace("<ALTERNATIVE_LANGUAGE>",$language_link,$tpl);

  // support for <LANGUAGE=XX> TAG
  preg_match_all("/<(\s*language\s*=\s*([^<>\s]*)\s*)>/iU", $tpl, $matches);
  for($i = 0; $i < count($matches[0]); $i++)
  {
  	foreach ($PP_supp_lang as $key => $row)
  	{
  		foreach($row as $cell)
  		{
   			if ($cell == strtoupper($matches[2][$i]))	$language_link_key = $key;
  	  }
  	}
  	$alt_language_link=create_language_url_from_tag( $matches[2][$i],$PP_supp_lang[$language_link_key][1]);
  	$tpl = str_replace("<LANGUAGE=".$matches[2][$i].">",$alt_language_link,$tpl);
  }
	// return the template
	return $tpl;
}

function translation_data()
{
	global $admin_lang_pp_lng_fname, $admin_lang_pp_lng_author, $admin_lang_pp_lng_ver, $admin_lang_pp_lng_email;
	$d = dir("../language");
	$dir_con = array();
	while (false !== ($entry = $d->read()))
	{
		($entry != '.' && $entry != '..')	? $dir_con[] = $entry : '';
	}
	$d->close();

	sort($dir_con);

	$out = '<table border="0" cellspacing="5">
<tr>
	<td><b>'.$admin_lang_pp_lng_fname.'</b></td>
	<td><b>'.$admin_lang_pp_lng_author.'</b></td>
	<td><b>'.$admin_lang_pp_lng_ver.'</b></td>
	<td><b>'.$admin_lang_pp_lng_email.'</b></td>
</tr>';

	foreach($dir_con as $k => $v)
	{
		if($v != "index.html" && is_file('../language/'.$v))
		{
			$_lang_file_translator = '';
			$_lang_file_rev = '';
			$_lang_file_email = '';
			include('../language/'.$v);
			$out .= '
<tr>
	<td>'.$dir_con[$k].'</td>
	<td>'.$_lang_file_translator.'</td>
	<td>'.$_lang_file_rev.'</td>
	<td><a href="mailto:'.$_lang_file_email.'?subject=Pixelpost translation">'.$_lang_file_email.'</a></td>
</tr>';
		}
	}

	$out .= '
</table>';

	return $out;
}
//
//============================= LANGUAGE SECTION ENDS ===========================
?>