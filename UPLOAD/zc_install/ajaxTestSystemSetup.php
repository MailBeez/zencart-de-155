<?php
/**
 * ajaxTestSystemSetup.php
 * @package Installer
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @license http://www.zen-cart-pro.at/license/2_0.txt GNU Public License V2.0
 * @version $Id: ajaxTestSystemSetup.php 1 2016-02-28 21:59:53Z webchills $
 */
define('IS_ADMIN_FLAG', false);
if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
define('DIR_FS_INSTALL', __DIR__ . '/');
define('DIR_FS_ROOT', realpath(__DIR__ . '/../') . '/');

require(DIR_FS_INSTALL . 'includes/application_top.php');

$error = FALSE;
$errorList = array();

//physical path tests

if (!file_exists($_POST['physical_path']. '/includes/vers' . 'ion.php'))
{
  $error = TRUE;
  $errorList[] = TEXT_SYSTEM_SETUP_ERROR_CATALOG_PHYSICAL_PATH;
}

echo json_encode(array('error'=>$error, 'errorList'=>$errorList));
