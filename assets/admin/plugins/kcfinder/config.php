<?php

/** This file is part of KCFinder project
  *
  *      @desc Base configuration file
  *   @package KCFinder
  *   @version 2.51
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

// IMPORTANT!!! Do not remove uncommented settings in this file even if
// you are using session configuration.
// See http://kcfinder.sunhater.com/install for setting descriptions

ob_start();
$ds = DIRECTORY_SEPARATOR;
define('BASEPATH', dirname(dirname(dirname(__FILE__))));
define('APPPATH', BASEPATH . $ds . 'application' . $ds);
define('LIBBATH', BASEPATH . "{$ds}system{$ds}libraries{$ds}Session{$ds}");

require_once LIBBATH . 'Session_driver.php';
require_once LIBBATH . "drivers{$ds}Session_files_driver.php";
require_once BASEPATH . "{$ds}system{$ds}core{$ds}Common.php";

$config = get_config();

if (empty($config['sess_save_path'])) {
	$config['sess_save_path'] = rtrim(ini_get('session.save_path'), '/\\');
}

$config = array(
	'cookie_lifetime'   => $config['sess_expiration'],
	'cookie_name'       => $config['sess_cookie_name'],
	'cookie_path'       => $config['cookie_path'],
	'cookie_domain'     => $config['cookie_domain'],
	'cookie_secure'     => $config['cookie_secure'],
	'expiration'        => $config['sess_expiration'],
	'match_ip'          => $config['sess_match_ip'],
	'save_path'         => $config['sess_save_path'],
	'_sid_regexp'       => '[0-9a-v]{32}',
);

$class = new CI_Session_files_driver($config);

if (is_php('5.4')) {
	session_set_save_handler($class, TRUE);
} else {
	session_set_save_handler(
		array($class, 'open'),
		array($class, 'close'),
		array($class, 'read'),
		array($class, 'write'),
		array($class, 'destroy'),
		array($class, 'gc')
	);
	register_shutdown_function('session_write_close');
}
session_name($config['cookie_name']);
ob_end_clean();

$_CONFIG = array(

    'disabled' => true,
    'denyZipDownload' => false,
    'denyUpdateCheck' => false,
    'denyExtensionRename' => false,

    'theme' => "oxygen",

    'uploadURL' => "upload",
    'uploadDir' => "",

    'dirPerms' => 0755,
    'filePerms' => 0644,

    'access' => array(

        'files' => array(
            'upload' => true,
            'delete' => true,
            'copy' => true,
            'move' => true,
            'rename' => true
        ),

        'dirs' => array(
            'create' => true,
            'delete' => true,
            'rename' => true
        )
    ),

    'deniedExts' => "exe com msi bat php phps phtml php3 php4 cgi pl",

    'types' => array(

        // CKEditor & FCKEditor types
        'files'   =>  "",
        'flash'   =>  "swf",
        'images'  =>  "*img",

        // TinyMCE types
        'file'    =>  "",
        'media'   =>  "swf flv avi mpg mpeg qt mov wmv asf rm",
        'image'   =>  "*img",
    ),

    'filenameChangeChars' => array(/*
        ' ' => "_",
        ':' => "."
    */),

    'dirnameChangeChars' => array(/*
        ' ' => "_",
        ':' => "."
    */),

    'mime_magic' => "",

    'maxImageWidth' => 0,
    'maxImageHeight' => 0,

    'thumbWidth' => 100,
    'thumbHeight' => 100,

    'thumbsDir' => ".thumbs",

    'jpegQuality' => 90,

    'cookieDomain' => "",
    'cookiePath' => "",
    'cookiePrefix' => 'KCFINDER_',

    // THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION CONFIGURATION
    '_check4htaccess' => true,
    //'_tinyMCEPath' => "/tiny_mce",

    '_sessionVar' => &$_SESSION['KCFINDER'],
    //'_sessionLifetime' => 30,
    //'_sessionDir' => "/full/directory/path",

    //'_sessionDomain' => ".mysite.com",
    //'_sessionPath' => "/my/path",
);

?>
