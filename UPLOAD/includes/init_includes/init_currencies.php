<?php
/**
 * initialise currencies
 * see {@link  http://www.zen-cart.com/wiki/index.php/Developers_API_Tutorials#InitSystem wikitutorials} for more details.
 *
 * @package initSystem
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart-pro.at/license/2_0.txt GNU Public License V2.0
 * @version $Id: init_currencies.php 729 2011-08-09 15:49:16Z hugo13 $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

// If no currency is set, use appropriate default
if (!isset($_SESSION['currency']) && !isset($_GET['currency']) ) $_SESSION['currency'] = DEFAULT_CURRENCY;

// Validate selected new currency, if any. Is false if valid not found.
$new_currency = (isset($_GET['currency'])) ? zen_currency_exists($_GET['currency']) : zen_currency_exists($_SESSION['currency']);

// Validate language-currency and default-currency if relevant. Is false if valid not found.
if ($new_currency == false || isset($_GET['language'])) $new_currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? zen_currency_exists(LANGUAGE_CURRENCY) : $new_currency;

// Final check -- if selected currency is bad and the "default" is bad, default to the first-found currency in order of exch rate.
if ($new_currency == false) $new_currency = zen_currency_exists(DEFAULT_CURRENCY, true);
//echo '<br />NEW = ' . $new_currency . '<br />';

// Now apply currency update
if (
   // Has new currency been selected?
  (isset($_GET['currency'])) || 

  // Does language change require currency update?
  (isset($_GET['language']) && USE_DEFAULT_LANGUAGE_CURRENCY == 'true' && LANGUAGE_CURRENCY != $_SESSION['currency']  )

) {
  $_SESSION['currency'] = $new_currency;
  // redraw the page without the currency/language info in the URL
  if (isset($_GET['currency']) || isset($_GET['language'])) zen_redirect(zen_href_link($current_page_base, zen_get_all_get_params(array('currency','language'))));
}
?>