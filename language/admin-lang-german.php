<?php
/*

admin-lang-german.php : german language file for Pixelpost-Admin-Section
========================================================================
Pixelpost version 1.6

SVN file version:
$Id: admin-lang-german.php 213 2007-04-02 11:39:42Z austriaka $

Language file: german (D)
Author:  Connie Mueller-Goedecke
Contact: connie@Pixelpost.org
WWW: http:www.zweiterblick.de + http://www.avantart.com

Version 1.5:
Development Team:
Ramin Mehran, Connie Mueller-Goedecke, Will Duncan, Joseph Spurling, Piotr "GeoS" Galas
Version 1.1 to Version 1.3: Linus <http://www.shapestyle.se>

Copyright  2006 Pixelpost.org <http://www.pixelpost.org>

Pixelpost www:  	http://www.pixelpost.org/
Pixelpost wiki: 	http://wiki.pixelpost.org/
Pixelpost forum: 	http://forum.pixelpost.org
______________________________________________________________________________
Die Variablen fr die ADMINISTRATIONS-Oberflche:

Linke Seite: Nichts aendern!  ||    rechte Seite: aendern, wenn gewuenscht */

$_lang_file_translator        = 'Connie M&uuml;ller-G&ouml;decke - <a href="http://www.zweiterblick.de/" target="_blank">www.zweiterblick.de</a>';
$_lang_file_email             = 'connie@pixelpost.org';
$_lang_file_rev               = '1.6';

$admin_lang_isrtl             = "no"; // 'yes' fuer Sprachen, die von rechts nach links geschrieben werden
                                     // und  'no'  fuer Sprachen, die von links nach rechts geschrieben werden
$admin_lang_update            = "Aktualisieren";
$admin_lang_reload            = "<br />Laden Sie die Seite neu, falls Sie die &Auml;nderungen nicht gleich sehen.";
$admin_lang_error             = "Achtung!";
$admin_lang_post              = "Eintr&auml;ge";
$admin_lang_page              = "Seite";
$admin_lang_of                = "von";
$admin_lang_next              = "weiter";
$admin_lang_prev              = "zur&uuml;ck";
$admin_lang_show              = "Zeige";
$admin_lang_go                = "und Los!";
$admin_lang_done              = "Erledigt:";

// Admin Start
$admin_start_1                = "Das <b>Sprachdateien-Verzeichnis</b> ist nicht vorhanden oder es gibt keine ";
$admin_start_2                = "in diesem Verzeichnis.<br />Pr&uuml;fen Sie, ob alle notwendigen Sprachdateien hochgeladen haben, die namentlich mit den hier genannten &uuml;bereinstimmen.";
$admin_start_userpw           = "Username oder Passwort falsch eingegeben.";
$admin_start_pw_forgot        = "Haben Sie das Passwort vergessen?";
$admin_start_browser_title    = "Verwaltungs-Bereich";
$admin_start_welcome          = "Willkommen im Pixelpost Verwaltungsbereich - Melden Sie sich bitte an.";
$admin_start_pp_name          = "Link zu Ihrer Pixelpost-Installation:";
$admin_start_pp_tit           = "Klicken Sie hier, um Ihr PhotoBlog aufzurufen";
$admin_start_cookie           = "Beim LogIn wird ein Cookie gesetzt";
$admin_start_username         = "Username";
$admin_start_pw               = "Passwort";
$admin_start_pw_button        = "neues Passwort anfordern";
$admin_start_pw_recover       = "Das Passwort kann nicht wiederhergestellt werden, aber Pixelpost kann
                                 ein neues Passwort f&uuml;r Sie generieren.<br />\nTragen Sie hier die eMail-Adresse ein, die Sie auch im Verwaltungsmen&uuml;eingegeben haben,
                                 und Sie erhalten das neue Passwort sofort zugesandt.";
$admin_start_email            = "Ihre eMail-Adresse";
$admin_start_email_button     = "Ihre eMail-Adresse";
$admin_start_admin_1          = "Administrations-Men&uuml;";
$admin_start_admin_2          = "f&uuml;r";
$admin_start_remember         =	"Anmeldung merken, automatisch einloggen:";

// Password Recovery
$admin_lang_pw_title            = "Pixelpost Passwort Wiederherstellung";
$admin_lang_pw_wronguser	= "Der eingegebene Username ist nicht der Username des Pixelpost-Administrators.";
$admin_lang_pw_back             =  "Zur&uuml;ck zur Verwaltungs-Seite";
$admin_lang_pw_noemail          = "Sie haben noch keine email-Adresse eingegeben! Ein neues Passwort kann Ihnen nicht zugesandt werden! \n<br />
				   Wenn Sie sich gar nicht mehr an Ihr Passwort erinnern k&ouml;nnen und nicht weiterkommen, schreiben Sie eine Nachricht im <a href='http://forum.pixelpost.org/'>Pixelpost Forum.</a>\n<br />";
$admin_lang_pw_notsent          = "Nichts gesendet! \n<br /> Die eingegebene email-Adresse entspricht nicht der eMail-Adresse, die Sie im Verwaltungssystem eingetragen haben!<br />";
$admin_lang_pw_subject		= "Pixelpost Passwort: Neues Passwort wurde angelegt";
$admin_lang_pw_usertext		= "Ihr User-Name:";
$admin_lang_pw_mailtext		= "Ihre email-Adresse:";
$admin_lang_pw_newpw		= "Ihr neues Passwort:";
$admin_lang_pw_text_1		= "Pixelpost Passwort-Wiederherstellung";
$admin_lang_pw_text_2		= "From: Pixelpost Administration";
$admin_lang_pw_text_3		= "Eine eMail wurde an Ihre email-Adresse geschickt. Diese enthlt Ihren User-Namen und ein neues Passwort. \n<br />
				   Pr&uuml;fen Sie Ihren Posteingang:  ";
$admin_lang_pw_text_4 		= "<span style='color:red;'>Fehler! Ein Fehler ist aufgetreten! \n<br />
				   Die email-Adresse und der User-Name, den Sie eingegeben haben, ist ok, aber es konnte keine Mail verschickt werden. \n<br />
                                   Wenden Sie sich an den Support Ihres Hosters.</span>";
$admin_lang_pw_text_5 		= "Datenbank-Fehler:";
$admin_lang_pw_text_6		= "<br />Das neue Passwort konnte nicht eingetragen werden.";
$admin_lang_pw_text_7           = "Diese Mail wurde automatisch vom Log-In-Bereich Ihres Pixelpost-Photoblogs versandt.\nEs wurde ein neues Passwort fr den Verwaltungsbereich angefordert.\n\nSie sollten jetzt Ihren Photoblog aufrufen \n\nauf ";
$admin_lang_pw_text_8           = "\n\nund sich anmelden mit dem neuen Passwort, um das automatisch generierte Passwort sofort zu berschreiben!\n\nDas ist notwendig, zu Ihrer Sicherheit!";


// Admin Menu
$admin_lang_new_image         = "Neues Bild";
$admin_lang_images            = "Bilder";
$admin_lang_categories        = "Kategorien";
$admin_lang_comments          = "Kommentare";
$admin_lang_options           = "Optionen";
$admin_lang_general_info      = "Allgemeine Info";
$admin_lang_addons            = "Addons";
$admin_lang_logout            = "Abmelden";

// New Image
$admin_lang_ni_post_a_new_image   = "neues Bild posten";
$admin_lang_ni_image              = "Bild";
$admin_lang_ni_image_title        = "Bild-Titel";
$admin_lang_ni_select_cat         = "W&auml;hlen Sie Kategorie / Schl&uuml;sselworte";
$admin_lang_ni_description        = "Bild-Beschreibung / Text";
$admin_lang_ni_datetime           = "Datum und Zeitangaben";
$admin_lang_ni_post_now           = "Jetzt posten";
$admin_lang_ni_post_one_day_after = "Einen Tag nach dem aktuellsten Eintrag posten";
$admin_lang_ni_post_spec_date     = "Zu einem bestimmten Zeitpunkt posten. Tragen Sie das Datum hier ein:";
$admin_lang_ni_post_entry         = "Eintrag ver&ouml;ffentlichen";
$admin_lang_ni_upload             = "Hochladen";
$admin_lang_ni_upload_error       = "Beim Hochladen der Datei kam es zu einem Fehler!";
$admin_lang_ni_year               = "Jahr";
$admin_lang_ni_month              = "Monat";
$admin_lang_ni_day                = "Tag";
$admin_lang_ni_hour               = "Stunde";
$admin_lang_ni_min                = "Minute";
$admin_lang_ni_markdown_text      = "Zum Formatieren im Textfeld k&ouml;nnen Sie MARKDOWN oder HTML benutzen.";
$admin_lang_ni_markdown_hp        = "Markdown Homepage";
$admin_lang_ni_markdown_element   = "Einf&uuml;hrung";
$admin_lang_ni_markdown_syntax    = "Syntax Referenz";
$admin_lang_ni_posted             = "HOCHGELADEN";
$admin_lang_ni_missing_data       = "Daten fehlen!<br />\nGeben Sie zumindest einen Titel und ein Bild an.
                                     Es wurde noch kein Bild hochgeladen, da die Informationen nicht ausreichten.";
$admin_lang_ni_crop_nextstep      = "Bearbeiten Sie nun den Thumbnail-Ausschnitt:";
$admin_lang_ni_crop_background    = "Dies ist der Hintergrund des Bildes, aus dem der Ausschnitt gew&auml;hlt wird.";
$admin_lang_ni_db_error           = "Es konnte nicht in die Datenbank geschrieben werden.";
$admin_lang_ni_tags               = "Tags";
$admin_lang_ni_tags_desc          = "(Komma, Semikolon und Leerzeichen sind Trennzeichen f&uuml;r Tags; Unterstrich und Bindestrich verbinden W&ouml;rter)";
$admin_lang_ni_alt_language				= "Geben Sie einen Titel und eine Beschreibung in der alternativen Sprache an!";

// Images
$admin_lang_imgedit_edit            = "Bearbeiten";
$admin_lang_imgedit_title           = "Titel:";
$admin_lang_imgedit_file_name       = "Datei-Name:";
$admin_lang_imgedit_dimensions      = "Abmessungen:";
$admin_lang_imgedit_tbpublished     = "Ver&ouml;ffentlichungsdatum:";
$admin_lang_imgedit_category_plural = "Kategorien";
$admin_lang_imgedit_delete          = "L&ouml;schen";
$admin_lang_imgedit_deleted         = "Eintrag entfernen / Bild l&ouml;schen / Thumbnail l&ouml;schen";
$admin_lang_imgedit_deleted1        = "Eintrag gel&ouml;scht.";
$admin_lang_imgedit_deleted2        = "Bild gel&ouml;scht.";
$admin_lang_imgedit_delete_error    = "Bilddatei konnte nicht gel&ouml;scht werden.\n
                                       Versuchen Sie, die Datei anders zu l&ouml;schen, beispielsweise mit einem FTP-Programm";
$admin_lang_imgedit_deleted3        = "Thumbnail gel&ouml;scht.";
$admin_lang_imgedit_delete_error2   = "Thumbnail konnte nicht gel&ouml;scht werden.\n
                                       Versuchen Sie, die Datei anders zu l&ouml;schen, beispielsweise mit einem FTP-Programm";
$admin_lang_imgedit_reupimg         = "Bild erneut hochladen:";
$admin_lang_imgedit_file            = "Datei: ";
$admin_lang_imgedit_file_isuploaded = "ist neu hochgeladen!";
$admin_lang_imgedit_update          = "Bild aktualisieren";
$admin_lang_imgedit_updated         = "Aktualisiertes Bild: ";
$admin_lang_imgedit_txt_desc        = "Text/Beschreibung:";
$admin_lang_imgedit_dtime           = "Zeit/Datum:";
$admin_lang_imgedit_img             = "Bild:";
$admin_lang_imgedit_fsize           = "Dateigr&ouml;&szlig;e:";
$admin_lang_imgedit_12cropimg       = "CropImage Werkzeug:";
$admin_lang_imgedit_12cropimg_txt   = "Ziehen Sie das Crop-Window mit der Maus &uuml;ber das Bild, um den Bildausschnitt f&uuml;r den Thumbnail zu w&auml;hlen,
                                       oder &auml;ndern Sie die Ma&szlig;e mit den '+'/'-' Buttons:";
$admin_lang_imgedit_uthmb_button    = "Thumbnail aktualisieren";
$admin_lang_imgedit_u_post_button   = "Eintrag aktualisieren";
$admin_lang_imgedit_title1          = "Bild(er) erneut hochladen - Bearbeiten - L&ouml;schen || ";
$admin_lang_imgedit_title2          = " Photos insgesamt in der Datenbank ||\n<br />Es werden ";
$admin_lang_imgedit_title3          = " Bilder angezeigt, Seite ";
$admin_lang_imgedit_title4          = " von ";
$admin_lang_imgedit_12crop_opt      = "<strong>Achtung:</strong> Die <i>12CropImage</i>-Option ist nicht aktiviert.
                                       &nbsp;Thumbnails sind deshalb nicht &auml;nderbar.";
$admin_lang_imgedit_edit_post       = "Eintrag bearbeiten";
$admin_lang_imgedit_img_page        = "Bilder pro Seite";
$admin_lang_imgedit_cropbg          = "Hintergrundtext f&uuml;r 12cropimage";
$admin_lang_imgedit_js_del_im       = "Wollen Sie das Bild wirklich l&ouml;schen?";
$admin_lang_imgedit_db_error        = "<br />Stellen Sie sicher, dass dieser Permalink noch nicht genutzt wurde!";
$admin_lang_imgedit_tags_edit       = "Tags (Komma, Semikolon und Leerzeichen sind Trennzeichen f&uuml;r Tags; Unterstrich und Bindestrich verbinden W&ouml;rter):";
$admin_lang_imgedit_alt_language    = "&Auml;ndern von Titel und Beschreibung in der alternativen Sprache";
$admin_lang_imgedit_masstag         = "Ausgew&auml;hltes Bild: Tags Hinzuf&uuml;gen/Entfernen";
$admin_lang_imgedit_masstag_set     = "Tag(s) hinzuf&uuml;gen";
$admin_lang_imgedit_masstag_set2    = "Tag(s) hinzuf&uuml;gen f&uuml;r alternative Sprache";
$admin_lang_imgedit_masstag_unset   = "Tag(s) entfernen";
$admin_lang_imgedit_published          = "Ver&ouml;ffentlicht";
$admin_lang_imgedit_unpublished_cmnts  = "zuvor moderierte Bilder.";

// Mass-Edit Categories
$admin_lang_imgedit_mass_1          = "Kategorien mehrfach editieren";
$admin_lang_imgedit_mass_2          = "Zuweisen";
$admin_lang_imgedit_mass_3          = "Entfernen";
$admin_lang_imgedit_mass_4          = "Mehrfachupdate";
$admin_lang_imgedit_mass_5          = "Die gew&auml;hlte Kategorie wurde bei";
$admin_lang_imgedit_mass_6          = "Bild(ern) entfernt.";
$admin_lang_imgedit_mass_7          = "Die gew&auml;hlte Kategorie wurde";
$admin_lang_imgedit_mass_8          = "Bild(ern) hinzugef&uuml;gt.";

// Categories
$admin_lang_cats_add_cat            = "Kategorie hinzuf&uuml;gen";
$admin_lang_cats_added              = "Kategorie wurde hinzugef&uuml;gt.";
$admin_lang_cats_add_cat_txt        = "F&uuml;gen Sie eine Bild-Kategorie hinzu.";
$admin_lang_cats_edit_cat           = "Kategorien bearbeiten";
$admin_lang_cats_edit_cat_txt       = "Kategorie bearbeiten";
$admin_lang_cats_edit_cat_button    = "Kategorie bearbeiten";
$admin_lang_cats_edit_tip           = "";
$admin_lang_cats_delete_cat         = "Kategorien l&ouml;schen";
$admin_lang_cats_delete_cat_txt     = "Kategorie l&ouml;schen";
$admin_lang_cats_delete_cat2        = "L&ouml;schen:";
$admin_lang_cats_delete_cats_button = "Kategorie l&ouml;schen";
$admin_lang_cats_deleted            = "Kategorie wurde gel&ouml;scht.";
$admin_lang_cats_update             = "";
$admin_lang_cats_update_cat_button  = "Kategorie &auml;ndern";
$admin_lang_cats_updated            = "Kategorie wurde ge&auml;ndert.";

// Comments
$admin_lang_cmnt_commentlist        = "Kommentar-Administration - Bearbeiten oder &Auml;ndern&nbsp;|| Anzeige pro Seite:";
$admin_lang_cmnt_name               = "Name:";
$admin_lang_cmnt_email              = "Email:";
$admin_lang_cmnt_comment            = "Kommentar:";
$admin_lang_cmnt_image              = "Photo";
$admin_lang_cmnt_delete             = "L&ouml;schen";
$admin_lang_cmnt_deleted            = "Kommentar wurde gel&ouml;scht.";
$admin_lang_cmnt_delete1            = "Es wurde(n)";
$admin_lang_cmnt_delete2            = "ausgew&auml;hlte Kommentar(e) gel&ouml;scht.";
$admin_lang_cmnt_edit               = "Bearbeiten";
$admin_lang_cmnt_edited             = "Kommentar wurde ge&auml;ndert.";
$admin_lang_cmnt_check_all          = "Alle ausw&auml;hlen";
$admin_lang_cmnt_clear_all          = "Auswahl l&ouml;schen";
$admin_lang_cmnt_invert_checks      = "Auswahl umkehren";
$admin_lang_cmnt_del_selected       = "Ausgew&auml;hlte Kommentare l&ouml;schen";
$admin_lang_cmnt_page               = "Kommentare pro Seite";
$admin_lang_cmnt_commenter          = "Kommentar-Datum:";
$admin_lang_cmnt_ip                 = "von  IP-Adresse ";
$admin_lang_cmnt_save               = "speichern";
$admin_lang_cmgt_edit2              = "OK";
$admin_lang_cmnt_massdelete_text    = "(Kreuzen Sie alle Kommentare an, die Sie l&ouml;schen m&ouml;chten.)";
$admin_lang_cmnt_js_del_comm        = "Soll dieser Kommentar wirklich gel&ouml;scht werden?";
$admin_lang_cmnt_publish_sel        = "Selektierte Kommentare freigeben";
$admin_lang_cmnt_unpublish_sel      = "Auswahl zur&uuml;cksetzen";
$admin_lang_cmnt_published          = "freigegeben";
$admin_lang_cmnt_unpublished_cmnts  = "vorher gew&auml;hlte Kommentare.";
$admin_lang_cmnt_unpublished        = "Moderiert";
$admin_lang_cmnt_published_cmnts    = "vorher freigegebene Kommentare.";
$admin_lang_cmnt_error_blacklist    = "Die Blacklist konnte nicht aktualisiert werden: ";
$admin_lang_cmnt_error_banlist      = "Die Referer-Block-Liste konnte nicht aktualisiert werden: ";

// Option
$admin_lang_optn_general             = "ALLGEMEIN";
$admin_lang_optn_template            = "TEMPLATE";
$admin_lang_optn_thumbnails          = "THUMBNAILS";
$admin_lang_optn_tip                 = "Achten Sie darauf, dass der Slash <b>'/'</b> gesetzt ist. Beispiel: <i>http://www.pixelpost.org/</i>";
$admin_lang_optn_update              = "Aktualisieren";
$admin_lang_optn_yes                 = "Ja";
$admin_lang_optn_no                  = "Nein";

$admin_lang_optn_title_url          = "Seiten-Titel und URL";
$admin_lang_optn_title              = "Titel:";
$admin_lang_optn_url                = "URL:";
$admin_lang_optn_usr_pss            = "ADMIN USER &amp; PASSWORT";
$admin_lang_optn_usr_pss_txt        = "Usernamen oder Password &auml;ndern?";
$admin_lang_optn_usr                = "User:";
$admin_lang_optn_pss                = "Passwort:";
$admin_lang_optn_pss_re             = "Passwort best&auml;tigen:";
$admin_lang_optn_email              = "ADMIN EMAIL";
$admin_lang_optn_fillit             = "Tragen Sie die eMail-Adresse unbedingt hier ein. Sie wird f&uuml;r eine
                                      eventuelle Password-Wiederherstellung ben&ouml;tigt.";
$admin_lang_optn_img_path           = "PFAD ZU DEN BILDERN";
$admin_lang_optn_tz                 = "Zeitzone";
$admin_lang_optn_tz_txt             = "W&auml;hlen Sie den Zeitversatz Ihrer Zeitzone.";
$admin_lang_optn_sendemail          = "BEI KOMMENTAREN EMAIL VERSENDEN";
$admin_lang_optn_sendemail_txt      = "Benachrichtigen, wenn Kommentare eingetragen wurden?";
$admin_lang_optn_sendemail_html_txt = "Benachrichtigungs-Mails im HTML-Format?";
$admin_lang_optn_comment_setting 		= "ALLGEMEINE EINSTELLUNGEN F&Uuml;R KOMMENTARE";
$admin_lang_optn_comment_setting2		= "Kommentareinstellungen";
$admin_lang_optn_cmnt_mod_txt       = "Voreingestelltes Kommentarverhalten:";
$admin_lang_optn_cmnt_mod_txt2      = "Verhalten f&uuml;r Kommentare:";
$admin_lang_optn_cmnt_mod_allowed		=	"Sofort ver&ouml;ffentlichen";
$admin_lang_optn_cmnt_mod_moderation=	"Zur Freigabe-Queue";
$admin_lang_optn_cmnt_mod_forbidden	=	"Kommentare verbieten";

$admin_lang_optn_switch_template     = "TEMPLATE-AUSWAHL";
$admin_lang_optn_lang_file           = "SPRACHDATEI";
$admin_lang_optn_dateformat          = "DATUMSFORMAT";
$admin_lang_optn_dateformat_txt      = "Datumsformat f&uuml;r die Anzeige der Bilder und der Kommentare.<br />
                                        Die Syntax stimmt mit der PHP <a href='http://www.php.net/date' target='_blank'>date()</a> Funktion &uuml;berein.<br />\n
                                        Ein paar Beispiele f&uuml;r &uuml;bliche Ersetzungen: Y:Jahr m:Monat d:Tag H:Stunde i:Minute s:Sekunde";
$admin_lang_optn_gmt                 = "Beachten Sie, dass Sommerzeit-&Auml;nderungen nicht ber&uuml;cksichtigt werden.\nDas m&uuml;ssen Sie manuell &auml;ndern.<br />Pr&uuml;fen Sie die <a href='http://www.worldtimeserver.com/current_time_in_UTC.aspx' target='_blank'> aktuelle GMT-Zeit,</a> wenn Sie nicht sicher sind, welchen Wert Sie hier als Differenz zur GMT-ZEit eintragen m&uuml;ssen.<br />";
$admin_lang_optn_cat_link_format     = "FORMAT DER KATEGORIE-LINKS";
$admin_lang_optn_catlinkformat_select = "Format der Links ausw&auml;hlen";
$admin_lang_optn_cat_link_format_txt = "Das Format der Links zu allen Eintr&auml;gen einer Kategorie im Template.";
$admin_lang_optn_catlinkformat_custom  = "Frei definiertes Format";
$admin_lang_optn_catlinkformat_custom_start = "Begrenzungszeichen am Anfang: ";
$admin_lang_optn_catlinkformat_custom_end = "Begrenzungszeichen am Ende: ";

$admin_lang_optn_calendar_type       = "KALENDER-TYP";

$admin_lang_optn_thumb_row       = "THUMBNAIL REIHE";
$admin_lang_optn_thumb_row_txt   = "Wie viele Thumbnails sollen in einer Reihe angezeigt werden?<br />\nTragen Sie eine ungerade Zahl ein, z.B. 5, 7 oder 9, nicht 6 oder 8.";
$admin_lang_optn_crop_thumbs     = "THUMBNAILS BESCHNEIDEN?";
$admin_lang_optn_crop_thumbs_txt = "Wenn Thumbnails eine festgelegte Gr&ouml;&szlig;e erhalten sollen: w&auml;hlen Sie <b>Yes.</b><br/>\n
                                    Wenn Sie das Ursprungs-Seiten-Verh&auml;ltnis beibehalten m&ouml;chten, w&auml;hlen Sie <b>No.</b><br/>\n
                                    M&ouml;chten Sie die Thumbnails nach dem Hochladen einzeln bearbeiten,  w&auml;hlen Sie <b>12CropImage.</b>";
$admin_lang_optn_thumb_size      = "THUMBNAIL MA&szlig;E";
$admin_lang_optn_thumb_size_txt     = "Geben Sie die Thumbnail-Ma&szlig;e an: Breite x H&ouml;he";
$admin_lang_optn_thumb_size_new   = "Ma&szlig;e festlegen";
$admin_lang_optn_reg_thumbs_button  = "Thumbnails neu erstellen";
$admin_lang_optn_regen_thumbs_txt   = "Alle Thumbnails aller vorhandenen Photos werden neu berechnet.\n
                                      Alle manuell ver&auml;nderten Thumbnails werden &uuml;berschrieben.\n
                                      Sie k&ouml;nnen Sie aber erneut mit 12CropImage erstellen..";
$admin_lang_optn_img_compression     = "THUMBNAIL KOMPRIMIERUNG";
$admin_lang_optn_img_compression_txt = "Wie stark soll die JPG-Komprimierung sein? 10 = niedrige Qualit&auml;t, 100 = maximale Qualit&auml;t (kein Verlust)";
$admin_lang_optn_pass_chngd_txt      = "Passwort wurde ge&auml;ndert.";
$admin_lang_optn_pass_notchngd_txt   = "Passwort wurde nicht ge&auml;ndert.Geben Sie dass Passwort zur Best&auml;tigung erneut im 2. Feld ein.";
$admin_lang_optn_thumb_updated       = "Thumbnails aktualisiert!";
$admin_lang_optn_updated             = "thumbnails aktualisiert.";

// Info
$admin_lang_info_gd                  = "Die GD-LIB ist nicht installiert, bitten Sie Ihren Hoster, sie f&uuml;r Sie zu installieren!";
$admin_lang_info_gd_jpg              = "mit Unterst&uuml;tzung des JPEG-Formates";
$admin_lang_pp_version1              = "Pr&uuml;fen Sie, ob Sie die aktuellste Pixelpost-Version installiert haben =>";
$admin_lang_pp_forum                 = "Informationen und Tips zu Pixelpost erhalten Sie im Pixelpost-Forum";
$admin_lang_pp_min_php               = "Pixelpost ist lauff&auml;hig ab PHP-Version";
$admin_lang_pp_min_mysql             = "Pixelpost ben&ouml;tigt mindestens die MySQL-Version";
$admin_lang_pp_exif1                 = "<b>EXIF</b> Pixelpost verwendet";
$admin_lang_pp_exif2                 = "f&uuml;r die EXIF-information";
$admin_lang_pp_path                  = "Pfad-Angaben";
$admin_lang_pp_imagepath             = "Erwarteter Pfad zum Image-Verzeichnis:";
$admin_lang_pp_imagepath_conf        = "Aktueller Pfad zum Image-Verzeichnis";
$admin_lang_pp_img_chmod1            = "Keine Schreibrechte f&uuml;r das Image-Verzeichnis gesetzt!";
$admin_lang_pp_img_chmod2            = "Vergeben Sie die richtigen Rechte f&uuml;r dieses Verzeichnis, sonst k&ouml;nnen Sie keine Bilder hochladen.";
$admin_lang_pp_img_chmod3            = "<br />Vergeben Sie <b>777</b> f&uuml;r diesen Folder, <i>chmod 777</i> (read, write und execute f&uuml;r owner, group und world).";
$admin_lang_pp_img_chmod4            = "Schreibrechte gesetzt? JA.";
$admin_lang_pp_img_chmod5            = "Keine Schreibrechte f&uuml;r das Image-Verzeichnis gesetzt!";
$admin_lang_pp_imgfolder             = "Image-Verzeichnis:";
$admin_lang_pp_thumbfolder           = "Thumbnails-Verzeichnis:";
$admin_lang_pp_langfolder            = "Sprachdatei-Verzeichnis:";
$admin_lang_pp_addfolder             = "Addons-Verzeichnis:";
$admin_lang_pp_incfolder             = "Includes-Verzeichnis:";
$admin_lang_pp_tempfolder            = "Templates-Verzeichnis:";
$admin_lang_pp_folder_missing        = "Nicht vorhanden (erwartet wird";
$admin_lang_pp_ref_log_title         = "Referer der letzten 7 Tage";
$admin_lang_hostinfo		     = "Host Information";
$admin_lang_fileuploads		     = "Das <b>Hochladen von Dateien</b> zur Ihrer Pixelpost Installation ist&nbsp;";
$admin_lang_serversoft		     = "Server Software:&nbsp;";
$admin_lang_Pixelpostinfo	     = "Pixelpost Information";
$admin_lang_pp_currversion	     = "Es ist folgende Pixelpost Version installiert:";
$admin_lang_pp_check                 = "Check";
$admin_lang_pp_sess_path             = "Session Speicherpfad";
$admin_lang_pp_sess_path_emp         = "ist leer";
$admin_lang_pp_fileupload_np         = 'NICHT m&ouml;glich! &Uuml;berpr&uuml;fen Sie die Variable file_upload in der php.ini Datei!';
$admin_lang_pp_fileupload_p          = 'm&ouml;glich.';
$admin_lang_pp_langs                 = 'Pixelpost Sprach&uuml;bersetzungen';
$admin_lang_pp_lng_fname             = 'Dateiname';
$admin_lang_pp_lng_author            = 'Author';
$admin_lang_pp_lng_ver               = 'Version';
$admin_lang_pp_lng_email             = 'Email';
$admin_lang_pp_newest_ver            = 'Sie verwenden die neueste Version von Pixelpost!';

// AddOns
$admin_lang_addon_title              = "INSTALLIERTE ADDONS";
$admin_lang_failed_addonstatus	     = "Ein Fehler trat auf beim Updaten des AddOns: ";
$admin_lang_addon_off		     = "Hier klicken zum De-Aktivieren!";
$admin_lang_addon_on		     = "Hier klicken zum Aktivieren!";

// Error Messages
$admin_lang_pp_up_error_0           = "Upload hat problemlos geklappt.";
$admin_lang_pp_up_error_1           = "Die vom Webserver erlaubte Dateigr&ouml;sse wurde &uuml;berschritten.";
$admin_lang_pp_up_error_2           = "Maximal m&ouml;gliche Dateigr&ouml;sse wurde &uuml;berschritten.";
$admin_lang_pp_up_error_3           = "Datei wurde nicht komplett &uuml;bertragen.";
$admin_lang_pp_up_error_4           = "Es wurde keine Datei hochgeladen.";
$admin_lang_pp_up_error_6           = "Tempor&auml;rer Ordner fehlt.";
$admin_lang_pp_up_error_7           = "Datei konnte nicht geschrieben werden.";

$admin_lang_cmnt_moderation_que     = "Freigabe-Queue";


// options >> time stamps
$admin_lang_optn_timestamps_title  = "Zeitstempel (Timestamp)";
$admin_lang_optn_timestamps_desc   = "Mit Timestamps verhindern Sie, dass beim Hochladen gleichnamige Bilder &uuml;berschrieben werden.<br/>
                                     Zeitstempel verwenden? ";

// options >> fight spam
$admin_lang_spam             = "SPAM KONTROLLE";
$admin_lang_spam_err_1      = "Die Tabelle konnte nicht angelegt werden: ";
$admin_lang_spam_tableadd   = "Banlist-Tabelle wurde angelegt, um den SPAM von Grund auf zu bek&auml;mpfen";
$admin_lang_spam_err_2      = "Moderations-Liste konnte nicht aktualisiert werden:";
$admin_lang_spam_err_3      = "Blacklist konnte nicht aktualisiert werden: ";
$admin_lang_spam_err_4      = "Referer-Ban-Liste konnte nicht aktualisiert werden: ";
$admin_lang_spam_err_5      = "Die &Auml;nderung der Anzahl der aktzeptierten Links pro Kommentar konnte nicht geschrieben werden:";
$admin_lang_spam_upd        = "Alle Ban-Listen sind erfolgreich aktualisiert worden";
$admin_lang_spam_err_6      = "Die Kommentare konnten nicht aktualisiert werden, der Fehler trat beim Abgleich  mit der Moderations-Liste auf. ";
$admin_lang_spam_com_upd    = "OK: Kommentare wurden mit der Moderationsliste abgeglichen. ";
$admin_lang_spam_err_7      = "Fehler: Kommentare konnten beim Abgleich mit der Blacklist nicht gel&ouml;scht werden: ";
$admin_lang_spam_com_del    = "OK: Kommentare, die Worte oder IPs aus der Blacklist enthielten, wurden gel&ouml;scht.";
$admin_lang_spam_err_8      = "Fehler: Besucher konnten beim Abgleich mit der Bad-Referer-Liste nicht gel&ouml;scht werden:";
$admin_lang_spam_visit_del  = "OK: Besucher mit Worten/IPs wurden gel&ouml;scht.";

$admin_lang_spam_ban        = "Sperr-Listen";
$admin_lang_spam_content    = "Geben Sie Wortlisten/IP-Adressen/Namen in untenstehenden Eingabefelder ein, pro Zeile ein Eintrag.<br/>\n
				Jeder Kommentar, der ein Wort, eine IP-Adresse, einen Namen der <i>Moderation List</i> enth&auml;lt, wird in die Kommentar-Freigabeliste eingetragen und nicht sofort freigegeben.<br/>\n
  				Jeder Kommentar, der ein Wort, eine IP-Adresse, einen Namen der <i>black list</i> enth&auml;lt, wird nicht als Kommentar gespeichert.<br/>
  				Kein Besucher mit einer IP-Adresse aus der <b>Referer Banned List</b> oder mit einer Adresse, die Worte enth&auml;lt, die in einer der obigen Listen stehen,\n
				erh&auml;lt Zugang zu Ihrem Photoblog.( Sie m&uuml;ssen den generierten Code in Ihre .htaccess eintragen, damit der Schutz wirkt!)";
$admin_lang_spam_modlist        = "Moderation List";
$admin_lang_spam_blacklist      = "Black List";
$admin_lang_spam_reflist        = "Referer Banned List";
$admin_lang_spam_blacklist_text = "Kopieren Sie den nachstehenden Code (CTRL+A und CTRL+C in Windows) und f&uuml;gen Sie ihn in die Datei htaccess auf Ihrer Webseite ein, um SPAM IPs und Referers zu blockieren.";
$admin_lang_spam_htaccess_text  = ".htaccess Code erstellen";
$admin_lang_spam_check_comm     = "Kommentare pr&uuml;fen";
$admin_lang_spam_del_bad_comm   = "Unerw&uuml;nschte Kommentare l&ouml;schen";
$admin_lang_spam_del_bad_ref    = "Unerw&uuml;nschte Referer l&ouml;schen";
$admin_lang_spam_updateblacklist = "alle Sperr-Listen aktualisieren";


$admin_lang_ni_post_exif_date = "Exif-Datum verwenden";

$admin_lang_optn_upd_done    = "Update durchgef&uuml;hrt.";
$admin_lang_optn_upd_error            = "Update Fehler.";
$admin_lang_optn_upd_lang_error			  = "Die gew&auml;hlte alternative Sprache ist dieselbe wie die Standardsprache.<br />Das macht keinen Sinn, w&auml;hlen Sie bitte eine andere alternative Sprache oder deaktivieren Sie die alternative Sprachunterst&uuml;tzung.";
$admin_lang_imgedit_preview  = "Vorschau";
$admin_lang_optn_markdown             = "Markdown verwenden";
$admin_lang_optn_markdown_desc        = "Soll Pixelpost die Markdown-Funktion in der Bildbeschreibung verwenden?";
$admin_lang_optn_exif			            = "Exif verwenden";
$admin_lang_optn_exif_desc		        = "Soll Pixelpost die Exif-Funktion in der Bildseite verwenden?";
$admin_lang_optn_token			          = "Token in Formularen aktivieren";
$admin_lang_optn_token_desc		        = "Using a token will reduce the probability of <a href=\"http://en.wikipedia.org/wiki/Cross-site_request_forgery\">Cross-Site Request Forgeries</a>.<br/><br/>\n
																				 If this setting is on comments will only be saved when the token of the form corresponds to the one in the user session. To implement this you need to add <strong>&lt;TOKEN&gt;</strong> to the comments template file somewhere between the <strong><i>&lt;form&gt;...&lt;/form&gt;</i></strong> tags.
																				 If you forget the <strong>&lt;TOKEN&gt;</strong> tag commenting will not work anymore and the user is presented with an error message.<br /><br/>\n
																				 Should this setting be enabled?";
$admin_lang_optn_token_time						= "Maximum time in minutes between opening the comment window and submit a comment: ";
$admin_lang_optn_token_error					= "Attention: values smaller then 1 minute for the Token time are not possible. The Token time has been reset to 1 minute.";
$admin_lang_optn_dsbl_list 						= "Distributed Sender Blackhole List setting (http://www.dsbl.org)";
$admin_lang_optn_dsbl_list_desc 			= "The <a href=\"http://www.dsbl.org\" target=\"_blank\">Distributed Sender Blackhole List</a> contains the IP addresses of servers who are an open relay, an open proxy or have other vulnerabilities. These servers are often misused by SPAMMERS to send e-mails but are also know for posting comments.<br /> <br />
																				 Should the comment IP address be checked against the Distributed Sender Blackhole List?";
$admin_lang_optn_time_between_comments = "Prevent SPAM flood";
$admin_lang_optn_time_between_comments_desc = "Number of seconds before a new comment can be posted (to prevent floods): ";
$admin_lang_optn_max_uri_comment			= "MAXIMUM NUMBER OF URI";
$admin_lang_optn_max_uri_comment_desc = "Number of URI allowed in one comment: ";
$admin_lang_optn_rss_setting					= "RSS/ATOM feed settings";
$admin_lang_optn_rsstype_desc					= "Select the style of the RSS/ATOM feed:";
$admin_lang_optn_rss_full							= "Show full size pictures";
$admin_lang_optn_rss_thumbs						= "Show thumbnails";
$admin_lang_optn_rss_thumbs_only					= "Show thumbnails only";
$admin_lang_optn_rss_text							= "Show text only";
$admin_lang_optn_feeditems_desc				= "Number of items in the feedlist: ";
$admin_lang_optn_alt_lang             = "Alternative language settings: ";
$admin_lang_optn_alt_lang_dis         = "disabled";
$admin_lang_optn_alt_lang_no          = "disabled";



$admin_lang_cmnt_rep_spam = "Spam melden!!";


$admin_lang_optn_visitorbooking_title = "Seitenbesuche";
$admin_lang_optn_visitorbooking_desc  = "Soll Pixelpost Seitenbesuche / Informationen in einer Tabelle speichern?";

?>