<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart-pro.at/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_best_sellers.php 730 2016-02-17 18:49:16Z webchills $
 */
  $content = '';
  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">' . "\n";
  $content .= '<div class="wrapper">' . "\n" . '<ol>' . "\n";
  for ($i=1; $i<=sizeof($bestsellers_list); $i++) {
// BOF Easy Google Analytics module

    $content .= '<li><a href="' . zen_href_link(zen_get_info_page($bestsellers_list[$i]['id']), 'products_id=' . $bestsellers_list[$i]['id']) . '">' . zen_trunc_string($bestsellers_list[$i]['name'], BEST_SELLERS_TRUNCATE, BEST_SELLERS_TRUNCATE_MORE) . '</a>';

    $content .= "<script type=\"text/javascript\">\n";
    $content .= "ga('ec:addImpression', {\n";                                                               // Provide product details in an impressionFieldObject.
    $content .= "   'id': '"       . $bestsellers_list[$i]['id']   . "',\n";                                // Product ID (string).
    $content .= "   'name': '"     . addslashes($bestsellers_list[$i]['name']) . "',\n";                    // Product name (string).
    $content .= "   'list':        'Side Box BestSeller',\n";                                               // Product list (string).
    $content .= "   'position': "   . $i . ",\n";                                                           // Product position (number).
    $content .= "});\n";
    $content .= "</script>\n";

    $content .= '</li>' . "\n";

// EOF Easy Google Analytics module
  }
  $content .= '</ol>' . "\n";
  $content .= '</div>' . "\n";
  $content .= '</div>';
?>