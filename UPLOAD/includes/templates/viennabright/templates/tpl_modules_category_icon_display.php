<?php
/**
* Template designed by 12leaves.com
* 12leaves.com - Free ecommerce templates and design services

 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart-pro.at/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_category_icon_display.php 746 2011-08-12 07:58:36Z hugo13 $
 */
  require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_CATEGORY_ICON_DISPLAY));

?>

<!--div align="<?php echo $align; ?>" id="categoryIcon" class="categoryIcon"><?php echo '<a href="' . zen_href_link(FILENAME_DEFAULT, 'cPath=' . $_GET['cPath'], 'NONSSL') . '">' . $category_icon_display_image . $category_icon_display_name .  '</a>'; ?></div-->