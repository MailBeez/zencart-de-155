<?php
/**
 * @package admin
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart-pro.at/license/2_0.txt GNU Public License V2.0
 * @version $Id: countries.php 789 2016-03-10 21:13:51Z webchills $
 */

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

// BOF mehrsprachige Lšndernamen 1 of 9
  $languages = zen_get_languages();
// EOF mehrsprachige Lšndernamen 1 of 9
  if (zen_not_null($action)) {
    switch ($action) {
      case 'insert':
        // BOF mehrsprachige Lšndernamen 2 of 9
        //$countries_name = zen_db_prepare_input($_POST['countries_name']);
        // EOF mehrsprachige Lšndernamen 2 of 9
        $countries_iso_code_2 = strtoupper(zen_db_prepare_input($_POST['countries_iso_code_2']));
        $countries_iso_code_3 = strtoupper(zen_db_prepare_input($_POST['countries_iso_code_3']));
        $address_format_id = zen_db_prepare_input($_POST['address_format_id']);
        $status = $_POST['status'] == 'on' ? 1 : 0;

// BOF mehrsprachige Lšndernamen 3 of 9
        $db->Execute("insert into " . TABLE_COUNTRIES . "
                    (countries_iso_code_2, countries_iso_code_3, status, address_format_id)
                    values ('" . zen_db_input($countries_iso_code_2) . "',
                            '" . zen_db_input($countries_iso_code_3) . "',
                            '" . (int)$status . "',
                            '" . (int)$address_format_id . "')");

        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $countries_name_array = $_POST['countries_name'];
          $language_id = $languages[$i]['id'];
          $sql_data_array = array('countries_name' => zen_db_prepare_input($countries_name_array[$language_id]));
          $countries_id_lookup = $db->Execute("SELECT countries_id
                                        FROM " . TABLE_COUNTRIES . "
                                        WHERE countries_iso_code_2 = '" . zen_db_input($countries_iso_code_2) . "'");
          $countries_id = $countries_id_lookup->fields['countries_id'];
          $insert_sql_data = array('countries_id' => $countries_id,
                                   'language_id' => $language_id);

          $sql_data_array_merged = array_merge($sql_data_array, $insert_sql_data);
          zen_db_perform(TABLE_COUNTRIES_NAME, $sql_data_array_merged);
        }
// EOF mehrsprachige Lšndernamen 3 of 9
        zen_record_admin_activity('Country added: ' . $countries_iso_code_3, 'info');
        zen_redirect(zen_href_link(FILENAME_COUNTRIES));
        break;
      case 'save':
        $countries_id = zen_db_prepare_input($_GET['cID']);
        // BOF mehrsprachige Lšndernamen 4 of 9
        //$countries_name = zen_db_prepare_input($_POST['countries_name']);
        // EOF mehrsprachige Lšndernamen 4 of 9
        $countries_iso_code_2 = strtoupper(zen_db_prepare_input($_POST['countries_iso_code_2']));
        $countries_iso_code_3 = strtoupper(zen_db_prepare_input($_POST['countries_iso_code_3']));
        $address_format_id = zen_db_prepare_input($_POST['address_format_id']);
        $status = $_POST['status'] == 'on' ? 1 : 0;

// BOF mehrsprachige Lšndernamen 5 of 9
        $db->Execute("update " . TABLE_COUNTRIES . "
                      set countries_iso_code_2 = '" . zen_db_input($countries_iso_code_2) . "',
                          countries_iso_code_3 = '" . zen_db_input($countries_iso_code_3) . "',
                          address_format_id = '" . (int)$address_format_id . "',
                          status = " . (int)$status . "
                      where countries_id = '" . (int)$countries_id . "'");

        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $countries_name_array = $_POST['countries_name'];
          $language_id = $languages[$i]['id'];
          $sql_data_array = array('countries_name' => zen_db_prepare_input($countries_name_array[$language_id]));

          zen_db_perform(TABLE_COUNTRIES_NAME, $sql_data_array, 'update', "countries_id = '" . (int)$countries_id . "' AND language_id = '" . (int)$language_id . "'");
        }
// EOF mehrsprachige Lšndernamen 5 of 9
        zen_record_admin_activity('Country updated: ' . $countries_iso_code_3, 'info');
        zen_redirect(zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&cID=' . $countries_id));
        break;
      case 'deleteconfirm':
        // demo active test
        if (zen_admin_demo()) {
          $_GET['action']= '';
          $messageStack->add_session(ERROR_ADMIN_DEMO, 'caution');
          zen_redirect(zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page']));
        }
        $countries_id = zen_db_prepare_input($_POST['cID']);
        $sql = "select entry_country_id from " . TABLE_ADDRESS_BOOK . " where entry_country_id = :countryID: LIMIT 1";
        $sql = $db->bindVars($sql, ':countryID:', $countries_id, 'integer');
        $result = $db->Execute($sql);
        if ($result->recordCount() == 0) {
          $db->Execute("delete from " . TABLE_COUNTRIES . "
                        where countries_id = '" . (int)$countries_id . "'");
          zen_record_admin_activity('Country deleted: ' . $countries_id, 'warning');
        } else {
          $messageStack->add_session(ERROR_COUNTRY_IN_USE, 'error');
        }
        zen_redirect(zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page']));
        break;
      case 'setstatus':
        $countries_id = zen_db_prepare_input($_GET['cID']);
        if (isset($_POST['current_status']) && ($_POST['current_status'] == '0' || $_POST['current_status'] == '1')) {
          $sql = "update " . TABLE_COUNTRIES . " set status='" . ($_POST['current_status'] == 0 ? 1 : 0) . "' where countries_id='" . (int)$countries_id . "'";
          $db->Execute($sql);
          zen_record_admin_activity('Country with ID number: ' . $countries_id . ' changed status to ' . ($_POST['current_status'] == 0 ? 1 : 0), 'info');
          zen_redirect(zen_href_link(FILENAME_COUNTRIES, 'cID=' . (int)$countries_id . '&page=' . $_GET['page']));
        }
        $action = '';
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>
</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="smallText" align="right" width="350" valign="top"><?php echo ISO_COUNTRY_CODES_LINK; ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center" colspan="2"><?php echo TABLE_HEADING_COUNTRY_CODES; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_COUNTRY_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  // BOF mehrsprachige Lšndernamen 6 of 9
  $countries_query_raw = "SELECT c.countries_id, cn.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, status
                          FROM " . TABLE_COUNTRIES . " c, " . TABLE_COUNTRIES_NAME . " cn
                          WHERE cn.countries_id = c.countries_id
                          AND cn.language_id = '" . (int)$_SESSION['languages_id'] . "'
                          ORDER BY cn.countries_name";
  // EOF mehrsprachige Lšndernamen 6 of 9
  $countries_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $countries_query_raw, $countries_query_numrows);
  $countries = $db->Execute($countries_query_raw);
  while (!$countries->EOF) {
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $countries->fields['countries_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cInfo = new objectInfo($countries->fields);
    }

    if (isset($cInfo) && is_object($cInfo) && ($countries->fields['countries_id'] == $cInfo->countries_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&cID=' . $countries->fields['countries_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo zen_output_string_protected($countries->fields['countries_name']); ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $countries->fields['countries_iso_code_2']; ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $countries->fields['countries_iso_code_3']; ?></td>
                <td class="dataTableContent" align="center" width="40">
<?php
    echo zen_draw_form('setstatus', FILENAME_COUNTRIES, 'action=setstatus&cID=' . $countries->fields['countries_id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . (isset($_GET['search']) ? '&search=' . $_GET['search'] : ''));
    if ($countries->fields['status'] == '0') {
      $formSRC   = 'icon_red_on.gif';
      $formTITLE = IMAGE_ICON_STATUS_OFF;
    } else {
      $formSRC   = 'icon_green_on.gif';
      $formTITLE = IMAGE_ICON_STATUS_ON;
    }
?>
                    <input type="image" src="<?php echo DIR_WS_IMAGES . $formSRC; ?>" alt="<?php echo $formTITLE; ?>" />
                    <input type="hidden" name="current_status" value="<?php echo $countries->fields['status']; ?>" />
                  </form>
                </td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($countries->fields['countries_id'] == $cInfo->countries_id) ) { echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&cID=' . $countries->fields['countries_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    $countries->MoveNext();
  }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $countries_split->display_count($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $countries_split->display_links($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&action=new') . '">' . zen_image_button('button_new_country.gif', IMAGE_NEW_COUNTRY) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_COUNTRY . '</b>');
      $contents = array('form' => zen_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      // BOF mehrsprachige Lšndernamen 7 of 9
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME);
      for ($i=0, $n=sizeof($languages); $i<$n; $i++){
        $contents[] = array('text' => '<br>' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . zen_draw_input_field('countries_name[' . $languages[$i]['id'] . ']'));
      }
      // EOF mehrsprachige Lšndernamen 7of 9
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . '<br>' . zen_draw_input_field('countries_iso_code_2'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . '<br>' . zen_draw_input_field('countries_iso_code_3'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . '<br>' . zen_draw_pull_down_menu('address_format_id', zen_get_address_formats()));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_STATUS . '<br>' . zen_draw_checkbox_field('status', '', true));
      $contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_COUNTRY . '</b>');

      $contents = array('form' => zen_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      // BOF mehrsprachige Lšndernamen 8 of 9
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME);
      for ($i=0, $n=sizeof($languages); $i<$n; $i++){
        $contents[] = array('text' => '<br>' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . zen_draw_input_field('countries_name[' . $languages[$i]['id'] . ']', htmlspecialchars(zen_get_country_name($cInfo->countries_id, $languages[$i]['id']), ENT_COMPAT, CHARSET, TRUE)));
      }
      // EOF mehrsprachige Lšndernamen 8 of 9
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . '<br>' . zen_draw_input_field('countries_iso_code_2', $cInfo->countries_iso_code_2));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . '<br>' . zen_draw_input_field('countries_iso_code_3', $cInfo->countries_iso_code_3));
      $contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . '<br>' . zen_draw_pull_down_menu('address_format_id', zen_get_address_formats(), $cInfo->address_format_id));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_STATUS . '<br>' . zen_draw_checkbox_field('status', '', $cInfo->status));
      $contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_COUNTRY . '</b>');
      $contents = array('form' => zen_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&action=deleteconfirm') . zen_draw_hidden_field('cID', $cInfo->countries_id));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . zen_output_string_protected($cInfo->countries_name) . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . zen_output_string_protected($cInfo->countries_name) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id . '&action=edit') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id . '&action=delete') . '">' . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        // BOF mehrsprachige Lšndernamen 9 of 9
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME);
        for ($i=0, $n=sizeof($languages); $i<$n; $i++){
          $contents[] = array('text' => '<br>' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . zen_output_string_protected(zen_get_country_name($cInfo->countries_id, $languages[$i]['id'])));
        }
        // EOF mehrsprachige Lšndernamen 9 of 9
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . ' ' . $cInfo->countries_iso_code_2);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . ' ' . $cInfo->countries_iso_code_3);
        $contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . ' ' . $cInfo->address_format_id);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_STATUS . ' ' . ($cInfo->status == 0 ? TEXT_NO : TEXT_YES));
      }
      break;
  }

  if ( (zen_not_null($heading)) && (zen_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>