<?php
/**
 * initialise language handling
 * see {@link  http://www.zen-cart.com/wiki/index.php/Developers_API_Tutorials#InitSystem wikitutorials} for more details.
 *
 * @package initSystem
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @todo ICW(SECURITY) is it worth having a sanitizer for $_GET['language'] ?
 * @license http://www.zen-cart-pro.at/license/2_0.txt GNU Public License V2.0
 * @version $Id: init_languages.php 729 2011-08-09 15:49:16Z hugo13 $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
if (!isset($_SESSION['language']) || isset($_GET['language'])) {
  $lng = new language();
  if (isset($_GET['language']) && zen_not_null($_GET['language'])) {
    $lng->set_language($_GET['language']);
  } else {
    if (LANGUAGE_DEFAULT_SELECTOR=='Browser') {
      $lng->get_browser_language();
    } else {
      $lng->set_language(DEFAULT_LANGUAGE);
    }
  }
  $_SESSION['language'] = (zen_not_null($lng->language['directory']) ? $lng->language['directory'] : 'english');
  $_SESSION['languages_id'] = (zen_not_null($lng->language['id']) ? $lng->language['id'] : 1);
  $_SESSION['languages_code'] = (zen_not_null($lng->language['code']) ? $lng->language['code'] : 'en');
}
?>