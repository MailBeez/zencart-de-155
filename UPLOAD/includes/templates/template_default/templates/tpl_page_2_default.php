<?php
/**
 * tpl_page_2_default.php
 *
 * @package templateSystem
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart-pro.at/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_page_2_default.php 729 2011-08-09 15:49:16Z hugo13 $
 */
?>
<div class="centerColumn" id="pageTwo">
<h1 id="pageTwoHeading"><?php echo HEADING_TITLE; ?></h1>

<?php if (DEFINE_PAGE_2_STATUS >= 1 and DEFINE_PAGE_2_STATUS <= 2) { ?>
<div id="pageTwoMainContent" class="content">
<?php
/**
 * load the html_define for the page_2 default
 */
  require($define_page);
?>
</div>
<?php } ?>

<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
</div>