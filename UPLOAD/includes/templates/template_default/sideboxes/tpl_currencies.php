<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart-pro.at/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_currencies.php 729 2011-08-09 15:49:16Z hugo13 $
 */
    $content = "";
    $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent centeredContent">';
    $content .= zen_draw_form('currencies_form', zen_href_link(basename(preg_replace('/.php/','', $PHP_SELF)), '', $request_type, false), 'get');
    $content .= zen_draw_pull_down_menu('currency', $currencies_array, $_SESSION['currency'], 'onchange="this.form.submit();"') . $hidden_get_variables . zen_hide_session_id();
    $content .= '</form>';
    $content .= '</div>';
?>