<?php

// SVN file version:
// $Id: pixelpost.php 241 2007-04-11 07:02:35Z david.kozikowski $

/*

Pixelpost version 1.6

Pixelpost www: http://www.pixelpost.org/

Version 1.6:
Development Team:
Ramin Mehran, Connie Mueller-Goedecke, Will Duncan, Joseph Spurling,
Piotr "GeoS" Galas, Dennis Mooibroek, Karin Uhlig, Jay Williams, David Kozikowski
Version 1.1 to Version 1.3: Linus <http://www.shapestyle.se>

Contact: thecrew (at) pixelpost (dot) org
Copyright 2006 Pixelpost.org <http://www.pixelpost.org>


License: http://www.gnu.org/copyleft/gpl.html

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

*/

// database variables - this is info you've received from you hosting service
// those are needed for the script to connect and use MySQL

$pixelpost_db_host      = "localhost"; // database host, often "localhost"
$pixelpost_db_user      = "salmara"; // database user
$pixelpost_db_pass      = "q93sqz"; // database user password
$pixelpost_db_pixelpost = "salmara"; // database

$pixelpost_db_prefix = "pixelpost_"; // table prefix, leave as is unless you want to install multiple blogs on the same database

// Pixelpost Version
// $version = "MS42LjAgKGluIHRoZSByaWdodCBkaXJlY3Rpb24pIC0gQXByaWwgMjAwNw=="; Original: "1.6.0 (in the right direction) - April 2007"
// print( base64_encode("2.0.0 (Update to php7/Mysql5) - January 2020 ") ); 

$version = "Mi4wLjAgKFVwZGF0ZSB0byBwaHA3L015c3FsNSkgLSBKYW51YXJ5IDIwMjAg";
?>