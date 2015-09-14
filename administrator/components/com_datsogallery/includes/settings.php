<?php
defined( '_JEXEC' ) or die;
$app = JFactory::getApplication('administrator');
$uri = JFactory::getURI();
$db = JFactory::getDBO();
jimport('joomla.html.pane');
$tabs = JPane::getInstance('tabs', array('startOffset'=>0));
JToolBarHelper::title(JText::_('COM_DATSOGALLERY_CONFIGURATION'), 'tb-config');
JToolBarHelper::custom('savesettings', 'dg-save.png', 'dg-save.png', JText::_('COM_DATSOGALLERY_SAVE'), false);
JToolBarHelper::spacer();
JToolBarHelper::custom('', 'dg-cancel.png', 'dg-cancel.png', JText::_('COM_DATSOGALLERY_CANCEL'), false);
require (JPATH_COMPONENT.DS.'config.datsogallery.php');
JHTML::_('behavior.modal', 'a.modal-button');
JHTML::_('behavior.tooltip');
$arr_ad_category = explode(",", $ad_category);
$clist1 = CategoryListSettings($ad_category, "ad_category", $extras = "id=\"ucat\" style=\"width:230px\"");
$clist2 = CategoryList($arr_ad_category, "ad_category[]", $extras = "id=\"acat\" multiple size=\"6\" style=\"width:230px\"", $levellimit = "4");
//$clist = ($user_categories) ? $clist1 : $clist2;
?>
<form action="index.php" method="post" name="adminForm" id="dgConfig">
<?php
  $yesno[] = JHTML::_('select.option', '0', JText::_('COM_DATSOGALLERY_NO'));
  $yesno[] = JHTML::_('select.option', '1', JText::_('COM_DATSOGALLERY_YES'));
  if ($ad_protect) {
    dgProtect($ad_pathoriginals);
  }
  else {
    dgUnprotect($ad_pathoriginals);
  }
  if (JFile::exists(JPATH_SITE.DS.JPath::clean($ad_pathoriginals).DS.'.htaccess')) {
    $img = dgTip(JText::_('COM_DATSOGALLERY_PROTECT_YES'), 'dg-lock-icon.png');
  }
  else {
    $img = dgTip(JText::_('COM_DATSOGALLERY_PROTECT_NO'), 'dg-lock-open-icon.png');
  }
  echo $tabs->startPane("settings");
  echo $tabs->startPanel(JText::_('COM_DATSOGALLERY_DIRS'), "tab1");
?>

<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_DIRS_LABEL'); ?>&nbsp;</legend>
  <table class="admintable">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_ORG_PIC_PATH'); ?>:</td>
      <td width="240"><input type="text" name="ad_pathoriginals" value="<?php echo $ad_pathoriginals; ?>" size="42"></td>
      <td><?php echo writDir(JPath::clean(JPATH_SITE.$ad_pathoriginals)); ?></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ORG_PIC_PATH_I')); ?></td>
    </tr>
    <?php
	   jimport('joomla.filesystem.folder');
	   $path = JPATH_ROOT.DS.'zipimport';
	?>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_PATH_TO_ZIPIMPORT'); ?>:</td>
      <td><input type="text" value="/zipimport" size="42" disabled></td>
      <td>
	  <?php
		  if (JFolder::exists($path)) {
			writDir($path);
		  }
		  else {
			echo dgTip(JText::_('COM_DATSOGALLERY_ZIPIMPORT_NOT_EXIST_TIP'), 'dg-error-icon.png', '', '#', 0);
		  }
	  ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_PATH_TO_ZIPIMPORT_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_PROTECT_ORIGINALS'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_protect = JHTML::_('select.genericlist', $yesno, 'ad_protect', 'class="inputbox"', 'value', 'text', $ad_protect);
		  echo $yn_ad_protect;
		  ?>
      </td>
      <td><?php echo $img; ?></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_PROTECT_ORIGINALS_I')); ?></td>
    </tr>
  </table>
</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('COM_DATSOGALLERY_PROCESSING'), "tab2");
?>

<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_PROCESSING_LABEL'); ?>&nbsp;</legend>
  <table class="admintable">
    <?php if (function_exists('exif_read_data')) { ?>
    <tr>
      <td width="270"><?php echo JText::_('COM_DATSOGALLERY_EXIF'); ?>:</td>
      <td width="240">
      <?php
          $yn_ad_exif = JHTML::_('select.genericlist', $yesno, 'ad_exif', 'class="input"', 'value', 'text', $ad_exif);
          echo $yn_ad_exif;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_EXIF_I')); ?></td>
    </tr>
    <?php
        }
        else {
          $ad_exif = 0;
        }
    ?>
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_ORGWIDTH'); ?>:</td>
      <td width="240"><input type="text" name="ad_orgwidth" value="<?php echo $ad_orgwidth; ?>" size="5">&nbsp;px</td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ORGWIDTH_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_ORGHIGHT'); ?>:</td>
      <td><input type="text" name="ad_orgheight" value="<?php echo $ad_orgheight; ?>" size="5">&nbsp;px</td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ORGHIGHT_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_MAX_WIDTH'); ?>:</td>
      <td><input type="text" name="ad_maxwidth" value="<?php echo $ad_maxwidth; ?>" size="5">&nbsp;px</td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_MAX_WIDTH_IMAGE')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_MAX_HIGHT'); ?>:</td>
      <td><input type="text" name="ad_maxheight" value="<?php echo $ad_maxheight; ?>" size="5">&nbsp;px</td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_MAX_HIGHT_IMAGE')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THUMBNAIL1_WIDTH'); ?>:</td>
      <td><input type="text" name="ad_thumbwidth1" value="<?php echo $ad_thumbwidth1; ?>" size="5">&nbsp;px</td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_WIDTH_THUMBNAIL1_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THUMBNAIL1_HEIGHT'); ?>:</td>
      <td><input type="text" name="ad_thumbheight1" value="<?php echo $ad_thumbheight1; ?>" size="5">&nbsp;px</td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_HEIGHT_THUMBNAIL1_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THUMBNAIL_WIDTH'); ?>:</td>
      <td><input type="text" name="ad_thumbwidth" value="<?php echo $ad_thumbwidth; ?>" size="5">
        &nbsp;px</td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_WIDTH_THUMBNAIL_CREAT')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THUMBNAIL_HEIGHT'); ?>:</td>
      <td><input type="text" name="ad_thumbheight" value="<?php echo $ad_thumbheight; ?>" size="5">
        &nbsp;px</td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_HEIGHT_THUMBNAIL_CREAT')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SKETCHING_METHOD'); ?></td>
      <td>
      <?php
          $crop[] = JHTML::_('select.option', '0', JText::_('COM_DATSOGALLERY_SCALE_METHOD'));
          $crop[] = JHTML::_('select.option', '1', JText::_('COM_DATSOGALLERY_CROP_METHOD'));
          $cs_ad_crop = JHTML::_('select.genericlist', $crop, 'ad_crop', 'class="inputbox"', 'value', 'text', $ad_crop);
          echo $cs_ad_crop;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SKETCHING_METHOD_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_ASPECT_RATIOS'); ?></td>
      <td>
      <?php
          $cropratio[] = JHTML::_('select.option', '1:1', '1:1');
          $cropratio[] = JHTML::_('select.option', '3:2', '3:2');
          $cropratio[] = JHTML::_('select.option', '4:3', '4:3');
          $cropratio[] = JHTML::_('select.option', '16:9', '16:9');
          $cropratio[] = JHTML::_('select.option', '2.39:1', '2.39:1');
          $cropratio[] = JHTML::_('select.option', '2.75:1', '2.75:1');
          $cropratio[] = JHTML::_('select.option', '4.00:1', '4.00:1');
          $cropratio[] = JHTML::_('select.option', '2:3', '2:3');
          $cropratio[] = JHTML::_('select.option', '3:4', '3:4');
          $cs_ad_cropratio = JHTML::_('select.genericlist', $cropratio, 'ad_cropratio', 'class="inputbox"', 'value', 'text', $ad_cropratio);
          echo $cs_ad_cropratio;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ASPECT_RATIOS_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THUMBNAIL_QUALIT'); ?>:</td>
      <td><input type="text" name="ad_thumbquality" value="<?php echo $ad_thumbquality; ?>" size="5">
        &nbsp;%</td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_THUMBNAIL_QUALIT_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_ORIGINAL_IMAGE_NAME'); ?></td>
      <td>
      <?php
          $fname[] = JHTML::_('select.option', '0', JText::_('COM_DATSOGALLERY_UNIQUE_NAME'));
          $fname[] = JHTML::_('select.option', '1', JText::_('COM_DATSOGALLERY_ORIGINAL_NAME'));
          $cs_ad_fname = JHTML::_('select.genericlist', $fname, 'ad_fname', 'class="inputbox"', 'value', 'text', $ad_fname);
          echo $cs_ad_fname;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ORIGINAL_IMAGE_NAME_TIP')); ?></td>
    </tr>
  </table>
</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('COM_DATSOGALLERY_VIEW'), "tab3");
?>

<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_VIEW_LABEL'); ?>&nbsp;</legend>
  <table class="admintable">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_SHOW_DETAILS'); ?></td>
      <td width="240">
	  <?php
		  $yn_ad_showdetail = JHTML::_('select.genericlist', $yesno, 'ad_showdetail', 'class="inputbox"', 'value', 'text', $ad_showdetail);
		  echo $yn_ad_showdetail;
	  ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_DETAILS_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_IMAGE_TITLE'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showimgtitle = JHTML::_('select.genericlist', $yesno, 'ad_showimgtitle', 'class="inputbox"', 'value', 'text', $ad_showimgtitle);
		  echo $yn_ad_showimgtitle;
      ?></td>
      <td>
	  <?php echo dgTip(JText::_('COM_DATSOGALLERY_IMAGE_TITLE_TIP')); ?>
      </td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_DESCRIPTION'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showimgtext = JHTML::_('select.genericlist', $yesno, 'ad_showimgtext', 'class="inputbox"', 'value', 'text', $ad_showimgtext);
		  echo $yn_ad_showimgtext;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_DESCRIPTION_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_DATE_ADD'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showfimgdate = JHTML::_('select.genericlist', $yesno, 'ad_showfimgdate', 'class="inputbox"', 'value', 'text', $ad_showfimgdate);
		  echo $yn_ad_showfimgdate;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_DATE_ADD_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_HITS'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showimgcounter = JHTML::_('select.genericlist', $yesno, 'ad_showimgcounter', 'class="inputbox"', 'value', 'text', $ad_showimgcounter);
		  echo $yn_ad_showimgcounter;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_HITS_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_DOWNLOADS'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showdownloads = JHTML::_('select.genericlist', $yesno, 'ad_showdownloads', 'class="inputbox"', 'value', 'text', $ad_showdownloads);
		  echo $yn_ad_showdownloads;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_DOWNLOADS_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_RATING'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showfrating = JHTML::_('select.genericlist', $yesno, 'ad_showfrating', 'class="inputbox"', 'value', 'text', $ad_showfrating);
		  echo $yn_ad_showfrating;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_RATING_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_COMMENTS'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showcomments = JHTML::_('select.genericlist', $yesno, 'ad_showcomments', 'class="inputbox"', 'value', 'text', $ad_showcomments);
		  echo $yn_ad_showcomments;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_COMMENTS_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SIZE'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showres = JHTML::_('select.genericlist', $yesno, 'ad_showres', 'class="inputbox"', 'value', 'text', $ad_showres);
		  echo $yn_ad_showres;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SIZE_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_FILESIZE'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showfimgsize = JHTML::_('select.genericlist', $yesno, 'ad_showfimgsize', 'class="inputbox"', 'value', 'text', $ad_showfimgsize);
		  echo $yn_ad_showfimgsize;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_FILESIZE_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_AUTHOR'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showimgauthor = JHTML::_('select.genericlist', $yesno, 'ad_showimgauthor', 'class="inputbox"', 'value', 'text', $ad_showimgauthor);
		  echo $yn_ad_showimgauthor;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_AUTHOR_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_SUBMITTER'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showsubmitter = JHTML::_('select.genericlist', $yesno, 'ad_showsubmitter', 'class="inputbox"', 'value', 'text', $ad_showsubmitter);
		  echo $yn_ad_showsubmitter;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_SUBMITTER_TIP')); ?></td>
    </tr>
   </table>
</fieldset>

<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_VIEW_NAV_PANEL'); ?>&nbsp;</legend>
  <table class="admintable" cellspacing="1">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_SHOW_PANEL'); ?></td>
      <td width="240">
	  <?php
		  $yn_ad_showpanel = JHTML::_('select.genericlist', $yesno, 'ad_showpanel', 'class="inputbox"', 'value', 'text', $ad_showpanel);
		  echo $yn_ad_showpanel;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_PANEL_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_USERPANEL'); ?></td>
      <td>
	  <?php
		  $yn_ad_userpannel = JHTML::_('select.genericlist', $yesno, 'ad_userpannel', 'class="inputbox"', 'value', 'text', $ad_userpannel);
		  echo $yn_ad_userpannel;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_USERPANEL_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_SPECIALPANEL'); ?></td>
      <td>
	  <?php
		  $yn_ad_special = JHTML::_('select.genericlist', $yesno, 'ad_special', 'class="inputbox"', 'value', 'text', $ad_special);
		  echo $yn_ad_special;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_SPECIALPANEL_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_RATINGPANEL'); ?></td>
      <td>
	  <?php
		  $yn_ad_rating = JHTML::_('select.genericlist', $yesno, 'ad_rating', 'class="inputbox"', 'value', 'text', $ad_rating);
		  echo $yn_ad_rating;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_RATINGPANEL_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_MOST_DOWNLOADED_MENU'); ?></td>
      <td>
	  <?php
		  $yn_most_downloaded_menu = JHTML::_('select.genericlist', $yesno, 'most_downloaded_menu', 'class="inputbox"', 'value', 'text', $most_downloaded_menu);
		  echo $yn_most_downloaded_menu;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_MOST_DOWNLOADED_MENU_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_NEWPANEL'); ?></td>
      <td>
	  <?php
		  $yn_ad_lastadd = JHTML::_('select.genericlist', $yesno, 'ad_lastadd', 'class="inputbox"', 'value', 'text', $ad_lastadd);
		  echo $yn_ad_lastadd;
      ?></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_NEWPANEL_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_LASTCOMMENTPANEL'); ?></td>
      <td>
	  <?php
		  $yn_ad_lastcomment = JHTML::_('select.genericlist', $yesno, 'ad_lastcomment', 'class="inputbox"', 'value', 'text', $ad_lastcomment);
		  echo $yn_ad_lastcomment;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_LASTCOMMENTPANEL_I')); ?></td>
    </tr>
   </table>
</fieldset>

<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_VIEW_OPTIONAL'); ?>&nbsp;</legend>
  <table class="admintable" cellspacing="1">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_SHOW_TITLE_COM'); ?>:</td>
      <td width="240">
	  <?php
		  $yn_ad_comtitle = JHTML::_('select.genericlist', $yesno, 'ad_comtitle', 'class="inputbox"', 'value', 'text', $ad_comtitle);
		  echo $yn_ad_comtitle;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_TITLE_COM_I')); ?></td>
    </tr>
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_SHOW_GRID'); ?>:</td>
      <td width="240">
	  <?php
		  $yn_show_grid = JHTML::_('select.genericlist', $yesno, 'show_grid', 'class="inputbox"', 'value', 'text', $show_grid);
		  echo $yn_show_grid;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_GRID_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_USE_NAMED_ANCHORS'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_na = JHTML::_('select.genericlist', $yesno, 'ad_na', 'class="inputbox"', 'value', 'text', $ad_na);
		  echo $yn_ad_na;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_USE_NAMED_ANCHORS_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_USE_PNTHUMB'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_pnthumb = JHTML::_('select.genericlist', $yesno, 'ad_pnthumb', 'class="inputbox"', 'value', 'text', $ad_pnthumb);
		  echo $yn_ad_pnthumb;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_USE_PNTHUMB_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_POWERED'); ?></td>
      <td>
	  <?php
		  $yn_ad_powered = JHTML::_('select.genericlist', $yesno, 'ad_powered', 'class="inputbox"', 'value', 'text', $ad_powered);
		  echo $yn_ad_powered;
      ?></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_POWERED_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_DATE_FORMAT'); ?>:</td>
      <td><input type="text" name="ad_datef" value="<?php echo $ad_datef; ?>" size="20"></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_DATE_FORMAT_TIP'), 'dg-info-icon.png', '', 'http://php.net/manual/en/function.strftime.php', 1, 1); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_PICINCAT'); ?></td>
      <td>
	  <?php
		  $yn_ad_picincat = JHTML::_('select.genericlist', $yesno, 'ad_picincat', 'class="inputbox"', 'value', 'text', $ad_picincat);
		  echo $yn_ad_picincat;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_PICINCAT_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_COLUMNS_IN_SUBCAT'); ?></td>
      <td><input type="text" name="ad_ncsc" value="<?php echo $ad_ncsc; ?>" size="5"></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_COLUMNS_IN_SUBCAT_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_COLUMNS_IN_SUBCAT_TH'); ?></td>
      <td><input type="text" name="ad_cp" value="<?php echo $ad_cp; ?>" size="5"></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_COLUMNS_IN_SUBCAT_TH_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_CATS_PERPAGE'); ?></td>
      <td><input type="text" name="ad_catsperpage" value="<?php echo $ad_catsperpage; ?>" size="5"></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_CATS_PERPAGE_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_DISPLAY_PIC'); ?></td>
      <td><input type="text" name="ad_perpage" value="<?php echo $ad_perpage; ?>" size="5"></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_DISPLAY_PIC_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SORTBY'); ?>:</td>
      <td>
	  <?php
		  $sortby[] = JHTML::_('select.option', 'ASC', JText::_('COM_DATSOGALLERY_SORTBYASC'));
		  $sortby[] = JHTML::_('select.option', 'DESC', JText::_('COM_DATSOGALLERY_SORTBYDESC'));
		  $sb_ad_sortby = JHTML::_('select.genericlist', $sortby, 'ad_sortby', 'class="inputbox"', 'value', 'text', $ad_sortby);
		  echo $sb_ad_sortby;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SORTBY_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_CATEGORY_IMAGE'); ?>:</td>
      <td>
	  <?php
		  $catimg[] = JHTML::_('select.option', 'ASC', JText::_('COM_DATSOGALLERY_CATEGORY_IMAGE_ASC'));
		  $catimg[] = JHTML::_('select.option', 'DESC', JText::_('COM_DATSOGALLERY_CATEGORY_IMAGE_DESC'));
          $catimg[] = JHTML::_('select.option', 'RAND()', JText::_('COM_DATSOGALLERY_CATEGORY_IMAGE_RANDOM'));
		  $sb_ad_catimg = JHTML::_('select.genericlist', $catimg, 'ad_catimg', 'class="inputbox"', 'value', 'text', @$ad_catimg);
		  echo $sb_ad_catimg;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_CATEGORY_IMAGE_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_DISPLAY_TOP'); ?></td>
      <td><input type="text" name="ad_toplist" value="<?php echo $ad_toplist; ?>" size="5"></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_DISPLAY_TOP_I')); ?></td>
    </tr>
  </table>
</fieldset>

<?php
  echo $tabs->endPanel();
  echo $tabs->startPanel(JText::_('COM_DATSOGALLERY_THEME_TAB'), "tab6");
  JHTML::script('jscolor.js', 'administrator/components/com_datsogallery/js/jscolor/');
  $custom = ($dg_theme == 'customtheme') ? '' : ' style="display:none"';
?>
<script type="text/javascript">
  function displayColors(obj){
    var theme=document.getElementById('customtheme');
    theme.style.display=obj=="customtheme"?"block":"none";
  }
</script>
<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_THEME_LEGEND'); ?>&nbsp;</legend>
  <table class="admintable">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_THEME'); ?>:</td>
      <td width="240">
	  <?php
		  $theme[] = JHTML::_('select.option', 'lighttheme', JText::_('COM_DATSOGALLERY_THEME_LIGHT'));
		  $theme[] = JHTML::_('select.option', 'darktheme', JText::_('COM_DATSOGALLERY_THEME_DARK'));
          $theme[] = JHTML::_('select.option', 'customtheme', JText::_('COM_DATSOGALLERY_THEME_CUSTOM'));
		  $ad_theme = JHTML::_('select.genericlist', $theme, 'dg_theme', 'class="inputbox" onchange="displayColors(this.value);"', 'value', 'text', @$dg_theme);
		  echo $ad_theme;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_THEME_I')); ?></td>
    </tr>
  </table>
  <table class="admintable" id="customtheme"<?php echo $custom; ?>>
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR1'); ?></td>
      <td width="240"><input type="text" class="color" name="dg_border" value="<?php echo @$dg_border; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color1.png" title="Color 1" class="modal-button">View</a></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR2'); ?></td>
      <td><input type="text" class="color" name="dg_input_background" value="<?php echo @$dg_input_background; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color2.png" title="Color 2" class="modal-button">View</a></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR3'); ?></td>
      <td><input type="text" class="color" name="dg_input_hover" value="<?php echo @$dg_input_hover; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color3.png" title="Color 3" class="modal-button">View</a></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR4'); ?></td>
      <td><input type="text" class="color" name="dg_head_background" value="<?php echo @$dg_head_background; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color4.png" title="Color 4" class="modal-button">View</a></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR5'); ?></td>
      <td><input type="text" class="color" name="dg_head_color" value="<?php echo @$dg_head_color; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color5.png" title="Color 5" class="modal-button">View</a></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR6'); ?></td>
      <td><input type="text" class="color" name="dg_body_background" value="<?php echo @$dg_body_background; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color6.png" title="Color 6" class="modal-button">View</a></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR7'); ?></td>
      <td><input type="text" class="color" name="dg_body_background_td" value="<?php echo @$dg_body_background_td; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color7.png" title="Color 7" class="modal-button">View</a></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR8'); ?></td>
      <td><input type="text" class="color" name="dg_body_background_td_hover" value="<?php echo @$dg_body_background_td_hover; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color8.png" title="Color 8" class="modal-button">View</a></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR9'); ?></td>
      <td><input type="text" class="color" name="dg_body_color" value="<?php echo @$dg_body_color; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color9.png" title="Color 9" class="modal-button">View</a></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR10'); ?></td>
      <td><input type="text" class="color" name="dg_link_color" value="<?php echo @$dg_link_color; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color2.png" title="Color 10" class="modal-button">View</a></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_THEME_COLOR11'); ?></td>
      <td><input type="text" class="color" name="dg_captcha_color" value="<?php echo @$dg_captcha_color; ?>" size="8" /></td>
      <td><a href="./components/com_datsogallery/images/color10.png" title="Color 11" class="modal-button">View</a></td>
    </tr>
  </table>
</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('COM_DATSOGALLERY_UPBYUSER'), "tab4");
?>
<script type="text/javascript">
  datso(document).ready(function () {
    <?php if ($user_categories) { ?>
    datso('#acat').attr("disabled", true);
    <?php } ?>
    datso("#user_categories").change(function () {
        if (datso(this).val() == 1) {
           datso('#acategories').hide();
           datso('#acat').attr("disabled", true);
           datso('#ucat').attr("disabled", false);
           datso('#ucategories').show();
        } else {
           datso('#acategories').show();
           datso('#acat').attr("disabled", false);
           datso('#ucat').attr("disabled", true);
           datso('#ucategories').hide();
        }
     });
  });
</script>
<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_CATEGORIES_SETTING'); ?>&nbsp;</legend>
  <table class="admintable">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_ALLOW_CREATE_CATEGORIES'); ?>:</td>
      <td width="240">
	  <?php
		  $yn_user_categories = JHTML::_('select.genericlist', $yesno, 'user_categories', '', 'value', 'text', @$user_categories);
		  echo $yn_user_categories;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ALLOW_CREATE_CATEGORIES_TIP')); ?></td>
    </tr>
    </table>
    <?php
        $ucategories = (@$user_categories) ? '' : ' style="display:none"';
        $acategories = (@$user_categories) ? ' style="display:none"' : '';
        $info_icon = '<img src="'.$uri->root().'components/com_datsogallery/images/customtheme/dg-info-icon.png" />';
        $hierarchy = '<img src="'.$uri->base().'components/com_datsogallery/images/hierarchy.png" />';
        $tip = "<span class='dgtip' id='".JText::sprintf("COM_DATSOGALLERY_MEMBERS_CATEGORY_TIP",$hierarchy)."'>".$info_icon."</span>";
    ?>
    <table id="ucategories"<?php echo $ucategories; ?> class="admintable">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_MEMBERS_CATEGORY'); ?>:</td>
      <td width="240"><?php echo $clist1; ?></td>
      <td><?php echo $tip; ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_MAX_USER_CATEGORIES'); ?>:</td>
      <td>
        <input type="text" name="ad_max_categories" value="<?php echo @$ad_max_categories; ?>" size="10">
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_MAX_USER_CATEGORIES_DESC')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_USER_CATEGORIES_APPROVAL'); ?>:</td>
      <td>
	  <?php
		  $yn_user_categories_approval = JHTML::_('select.genericlist', $yesno, 'user_categories_approval', 'class="inputbox"', 'value', 'text', @$user_categories_approval);
		  echo $yn_user_categories_approval;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_USER_CATEGORIES_APPROVAL_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_ADMIN_CATEGORY_NOTIFY'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_category_notify = JHTML::_('select.genericlist', $yesno, 'ad_category_notify', 'class="inputbox"', 'value', 'text', @$ad_category_notify);
		  echo $yn_ad_category_notify;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ADMIN_CATEGORY_NOTIFY_TIP')); ?></td>
    </tr>
    </table>
    <table id="acategories"<?php echo $acategories; ?> class="admintable">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_ALLOWED_CATEGORIES'); ?>:</td>
      <td width="240"><?php echo $clist2; ?></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ALLOWED_CATEGORIES_TIP')); ?></td>
    </tr>
  </table>
</fieldset>
<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_USER_UPLOAD_SETTING'); ?>&nbsp;</legend>
  <table class="admintable">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_ADMIN_APPRO_NEEDED'); ?>:</td>
      <td width="240">
	  <?php
		  $yn_ad_approve = JHTML::_('select.genericlist', $yesno, 'ad_approve', 'class="inputbox"', 'value', 'text', $ad_approve);
		  echo $yn_ad_approve;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_USER_UPLOAD_NEDD_APPROVAL')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_ADMIN_UPLOAD_NOTIFY'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_upload_notify = JHTML::_('select.genericlist', $yesno, 'ad_upload_notify', 'class="inputbox"', 'value', 'text', @$ad_upload_notify);
		  echo $yn_ad_upload_notify;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ADMIN_UPLOAD_NOTIFY_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_MAX_NR_IMAGES'); ?>:</td>
      <td>
        <input type="text" name="ad_acl_registered" value="<?php echo $ad_acl_registered; ?>" size="10"> <span style="color:#669933">Registered</span><br style="padding-bottom: 8px">
        <input type="text" name="ad_acl_author" value="<?php echo $ad_acl_author; ?>" size="10"> <span style="color:#FF6600">Author</span><br style="padding-bottom: 8px">
        <input type="text" name="ad_acl_editor" value="<?php echo $ad_acl_editor; ?>" size="10"> <span style="color:#006699">Editor</span><br style="padding-bottom: 8px">
        <input type="text" name="ad_acl_publisher" value="<?php echo $ad_acl_publisher; ?>" size="10"> <span>Publisher</span><br />
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_MAX_ALLOWED_PICS')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_MAX_SIZE_IMAGE'); ?>:</td>
      <td><input type="text" name="ad_maxfilesize" value="<?php echo $ad_maxfilesize; ?>" size="10">
        <span><a href="http://www.bit-calculator.com/" title="Open Bit Calculator in Modal window" class="modal-button" rel="{handler: 'iframe', size: {x: 725, y: 520}}">Bit Calculator</a></span></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_MAX_ALLOWED_FILESIZE')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_ALLOW_ZIP_UPLOAD'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_allow_zip = JHTML::_('select.genericlist', $yesno, 'ad_allow_zip', 'class="inputbox"', 'value', 'text', @$ad_allow_zip);
		  echo $yn_ad_allow_zip;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ALLOW_ZIP_UPLOAD_TIP')); ?></td>
    </tr>
    <script type="text/javascript">
       function displayField(obj) {
         var resize = document.getElementById('hide');
         resize.style.display = obj == 1 ? "" : "none";
       }
    </script>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_USER_UPLOAD_RESIZE'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_max_wh = JHTML::_('select.genericlist', $yesno, 'ad_max_wh', 'class="inputbox" onchange="displayField(this.value);"', 'value', 'text', $ad_max_wh);
		  echo $yn_ad_max_wh;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_USER_UPLOAD_RESIZE_TIP')); ?></td>
    </tr>
    <?php
        $md = ($ad_max_wh) ? '' : ' style="display:none"';
    ?>
    <tr id="hide"<?php echo $md; ?>>
      <td><?php echo JText::_('COM_DATSOGALLERY_MAX_WIDTH_AND_HEIGHT'); ?>:</td>
      <td><input type="text" name="ad_max_wu" value="<?php echo $ad_max_wu; ?>" size="5">
        <span><?php echo JText::_('COM_DATSOGALLERY_WIDTH'); ?></span><br />
        <input type="text" name="ad_max_hu" value="<?php echo $ad_max_hu; ?>" size="5">
        <span><?php echo JText::_('COM_DATSOGALLERY_HEIGHT'); ?></span></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_MAX_WIDTH_AND_HEIGHT_TIP')); ?></td>
    </tr>
    <script type="text/javascript">
       function displayTerms(obj) {
         var terms = document.getElementById('terms');
         terms.style.display = obj == 1 ? "" : "none";
       }
    </script>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_TERMS_OF_USE'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_terms = JHTML::_('select.genericlist', $yesno, 'ad_terms', 'class="inputbox" onchange="displayTerms(this.value);"', 'value', 'text', @$ad_terms);
		  echo $yn_ad_terms;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_TERMS_OF_USE_TIP')); ?></td>
    </tr>
    <?php
        $terms = (@$ad_terms) ? '' : ' style="display:none"';
    ?>
    <script type="text/javascript">
    	function jSelectArticle(id, title, object) {
    		document.getElementById('terms_name').value = title;
    		document.getElementById('terms_id').value = id;
    		SqueezeBox.close();
    	}
    </script>
    <tr id="terms"<?php echo $terms; ?>>
      <td><?php echo JText::_('COM_DATSOGALLERY_TERMS_OF_USE'); ?>:</td>
      <td>
      <input id="terms_name" name="ad_terms_name" value="<?php echo @$ad_terms_name; ?>" type="text" />
      <a rel="{handler: 'iframe', size: {x: 650, y: 375}}" href="index.php?option=com_content&view=articles&layout=modal&tmpl=component" title="<?php echo JText::_('Please select an Article', true ); ?>" class="modal-button"><?php echo JText::_('Select an Article'); ?></a>
      <input id="terms_id" name="ad_terms_id" value="<?php echo @$ad_terms_id; ?>" type="hidden" />
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_TERMS_OF_USE_TIP')); ?></td>
    </tr>
  </table>
</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('COM_DATSOGALLERY_RATE'), "tab5");
?>

<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_RATE_SETTING'); ?>&nbsp;</legend>
  <table class="admintable" cellspacing="1">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_ALLOW_RATING'); ?>:</td>
      <td width="240">
	  <?php
          $yn_ad_showrating = JHTML::_('select.genericlist', $yesno, 'ad_showrating', 'class="inputbox"', 'value', 'text', $ad_showrating);
          echo $yn_ad_showrating;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ALLOW_RATING_I')); ?></td>
    </tr>
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_QUICK_RATING'); ?>:</td>
      <td width="240">
	  <?php
          $yn_quick_rating = JHTML::_('select.genericlist', $yesno, 'quick_rating', 'class="inputbox"', 'value', 'text', @$quick_rating);
          echo $yn_quick_rating;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_QUICK_RATING_TIP')); ?></td>
    </tr>
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_ACCESS_RATING'); ?>:</td>
      <td width="240">
	  <?php
          $arating[] = JHTML::_('select.option', 1, JText::_('Public'));
		  $arating[] = JHTML::_('select.option', 2, JText::_('Registered'));
		  $arating[] = JHTML::_('select.option', 3, JText::_('Special'));
		  $ps_access_rating = JHTML::_('select.genericlist', $arating, 'ad_access_rating', 'class="inputbox"', 'value', 'text', $ad_access_rating);
		  echo $ps_access_rating;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ACCESS_RATING_I')); ?></td>
    </tr>
  </table>
</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('COM_DATSOGALLERY_TAGS'), "tab5");
?>

<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_TAGS_SETTING'); ?>&nbsp;</legend>
  <table class="admintable" cellspacing="1">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_ALLOW_TAGS'); ?>:</td>
      <td width="240">
	  <?php
          $yn_ad_allow_tags = JHTML::_('select.genericlist', $yesno, 'ad_allow_tags', 'class="inputbox"', 'value', 'text', @$ad_allow_tags);
          echo $yn_ad_allow_tags;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ALLOW_TAGS_I')); ?></td>
    </tr>
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_ACCESS_TAGS'); ?>:</td>
      <td width="240">
	  <?php
		  $atags[] = JHTML::_('select.option', 2, JText::_('Registered'));
		  $atags[] = JHTML::_('select.option', 3, JText::_('Special'));
		  $ps_access_tags = JHTML::_('select.genericlist', $atags, 'ad_access_tags', 'class="inputbox"', 'value', 'text', @$ad_access_tags);
		  echo $ps_access_tags;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ACCESS_TAGS_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_MAX_TAGS'); ?>:</td>
      <td><input type="text" name="ad_max_tags" value="<?php echo @$ad_max_tags; ?>" size="5"></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_MAX_TAGS_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_MIN_TAG_CHARS'); ?>:</td>
      <td><input type="text" name="ad_min_tag_chars" value="<?php echo @$ad_min_tag_chars; ?>" size="5"></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_MIN_TAG_CHARS_TIP')); ?></td>
    </tr>
  </table>
</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('COM_DATSOGALLERY_COMMENT1'), "tab6");
?>

<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_COMMENT_SETTING'); ?>&nbsp;</legend>
  <table class="admintable" cellspacing="1">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_ALLOW_COMM'); ?>:</td>
      <td width="240">
      <?php
          $yn_ad_showcomment = JHTML::_('select.genericlist', $yesno, 'ad_showcomment', 'class="inputbox"', 'value', 'text', $ad_showcomment);
          echo $yn_ad_showcomment;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ALLOW_COMM_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_ANONYM_COMM'); ?>:</td>
      <td>
      <?php
          $yn_ad_anoncomment = JHTML::_('select.genericlist', $yesno, 'ad_anoncomment', 'class="inputbox"', 'value', 'text', $ad_anoncomment);
          echo $yn_ad_anoncomment;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ANONYM_COMM_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_ALLOW_EMAIL_NOTIFICATIONS'); ?>:</td>
      <td>
      <?php
          $yn_ad_comment_notify = JHTML::_('select.genericlist', $yesno, 'ad_comment_notify', 'class="inputbox" onchange="displayNotifyParameters();"', 'value', 'text', @$ad_comment_notify);
          echo $yn_ad_comment_notify;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_ALLOW_EMAIL_NOTIFICATIONS_TIP')); ?></td>
    </tr>
    <?php
        $ntf = ($ad_comment_notify) ? '' : ' style="display:none"';
    ?>
    <tr id="notify"<?php echo $ntf; ?>>
      <td><?php echo JText::_('COM_DATSOGALLERY_COMMENT_WORD_LIMITER'); ?>:</td>
      <td><input type="text" name="ad_comment_wl" value="<?php echo @$ad_comment_wl; ?>" size="5"></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_COMMENT_WORD_LIMITER_TIP')); ?></td>
    </tr>
    <script type="text/javascript">
            function displayNotifyParameters() {
            if(document.getElementsByName('ad_comment_notify')[0].checked)
            document.getElementById('notify').style.display="none";

            if(document.getElementsByName('ad_comment_notify')[1].checked)
            document.getElementById('notify').style.display="";
            }
    </script>
    <?php
        $db->setQuery("SELECT extension_id FROM `#__extensions` WHERE `element` = 'com_community' AND `enabled` = 1");
        $result = $db->loadResult();
        if ($result) {
    ?>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_JS_AVATAR'); ?>:</td>
      <td>
      <?php
          $yn_ad_js = JHTML::_('select.genericlist', $yesno, 'ad_js', 'class="inputbox"', 'value', 'text', $ad_js);
          echo $yn_ad_js;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_JS_AVATAR_I')); ?></td>
    </tr>
    <?php
        }
        else {
          $ad_js = 0;
        }
        $db->setQuery("SELECT extension_id FROM `#__extensions` WHERE `element` = 'com_comprofiler' AND `enabled` = 1");
        $result = $db->loadResult();
        if ($result) {
    ?>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_CB_AVATAR'); ?>:</td>
      <td>
      <?php
          $yn_ad_cb = JHTML::_('select.genericlist', $yesno, 'ad_cb', 'class="inputbox"', 'value', 'text', @$ad_cb);
          echo $yn_ad_cb;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_CB_AVATAR_I')); ?></td>
    </tr>
    <?php
        }
        else {
          $ad_cb = 0;
        }
        $db->setQuery("SELECT extension_id FROM `#__extensions` WHERE `element` = 'com_kunena' AND `enabled` = 1");
        $result = $db->loadResult();
        if ($result) {
    ?>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_KUNENA_AVATAR'); ?>:</td>
      <td>
      <?php
          $yn_ad_kunena = JHTML::_('select.genericlist', $yesno, 'ad_kunena', 'class="inputbox"', 'value', 'text', $ad_kunena);
          echo $yn_ad_kunena;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_KUNENA_AVATAR_I')); ?></td>
    </tr>
    <?php
        }
        else {
          $ad_kunena = 0;
        }
    ?>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_NAME_OR_USER'); ?>:</td>
      <td>
      <?php
          $name_or_user[] = JHTML::_('select.option', 'user', JText::_('COM_DATSOGALLERY_AS_USER'));
          $name_or_user[] = JHTML::_('select.option', 'name', JText::_('COM_DATSOGALLERY_AS_NAME'));
          $un_ad_name_or_user = JHTML::_('select.genericlist', $name_or_user, 'ad_name_or_user', 'class="inputbox"', 'value', 'text', $ad_name_or_user);
          echo $un_ad_name_or_user;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_NAME_OR_USER_I')); ?></td>
    </tr>
  </table>
</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('COM_DATSOGALLERY_OPTION'), "tab7");
?>

<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_OPTION2'); ?>&nbsp;</legend>
  <table class="admintable" cellspacing="1">
    <tr>
      <td width="260"><?php echo JText::_('COM_DATSOGALLERY_SB_CATEGORY'); ?>:</td>
      <td width="240">
	  <?php
		  $yn_ad_sbcat = JHTML::_('select.genericlist', $yesno, 'ad_sbcat', 'class="inputbox"', 'value', 'text', $ad_sbcat);
		  echo $yn_ad_sbcat;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SB_CATEGORY_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_USE_LIGHTBOX'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_shadowbox = JHTML::_('select.genericlist', $yesno, 'ad_shadowbox', 'class="inputbox"', 'value', 'text', $ad_shadowbox);
		  echo $yn_ad_shadowbox;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_USE_LIGHTBOX_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_LIGHTBOX_FOR_ALL'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_shadowbox_fa = JHTML::_('select.genericlist', $yesno, 'ad_shadowbox_fa', 'class="inputbox"', 'value', 'text', $ad_shadowbox_fa);
		  echo $yn_ad_shadowbox_fa;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_LIGHTBOX_FOR_ALL_I')); ?></td>
    </tr>
    <script type="text/javascript">
       function showWmUpload(obj) {
         var wm = document.getElementById('wm');
         wm.style.display = obj == 1 ? "" : "none";
       }
    </script>
    <?php
        $wm = (@$ad_showwatermark) ? '' : ' style="display:none"';
    ?>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_USE_WATERMARK'); ?></td>
      <td>
	  <?php
		  $yn_ad_showwatermark = JHTML::_('select.genericlist', $yesno, 'ad_showwatermark', 'class="inputbox" onchange="showWmUpload(this.value);"', 'value', 'text', $ad_showwatermark);
		  echo $yn_ad_showwatermark;
          echo '<iframe id="wm"'.$wm.' src="index.php?option=com_datsogallery&task=wmupload&format=raw" class="dg_wmframe"></iframe>';
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_USE_WATERMARK_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_WM_POSITION'); ?>:</td>
      <td>
	  <?php
		  $wmposs[] = JHTML::_('select.option', 'topleft', JText::_('COM_DATSOGALLERY_WMP_TL'));
		  $wmposs[] = JHTML::_('select.option', 'topright', JText::_('COM_DATSOGALLERY_WMP_TR'));
		  $wmposs[] = JHTML::_('select.option', 'bottomleft', JText::_('COM_DATSOGALLERY_WMP_BL'));
		  $wmposs[] = JHTML::_('select.option', 'bottomright', JText::_('COM_DATSOGALLERY_WMP_BR'));
		  $wmposs[] = JHTML::_('select.option', 'center', JText::_('COM_DATSOGALLERY_WMP_C'));
		  $ps_ad_wmpos = JHTML::_('select.genericlist', $wmposs, 'ad_wmpos', 'class="inputbox"', 'value', 'text', $ad_wmpos);
		  echo $ps_ad_wmpos;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_WM_POSITION')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_USE_DOWNLOAD'); ?></td>
      <td>
	  <?php
		  $yn_ad_showdownload = JHTML::_('select.genericlist', $yesno, 'ad_showdownload', 'class="inputbox"', 'value', 'text', $ad_showdownload);
		  echo $yn_ad_showdownload;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_USE_DOWNLOAD_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_DOWNLOAD_OPTIONS'); ?></td>
      <td>
	  <?php
		  $yn_ad_download_options = JHTML::_('select.genericlist', $yesno, 'ad_download_options', 'class="inputbox"', 'value', 'text', $ad_download_options);
		  echo $yn_ad_download_options;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_DOWNLOAD_OPTIONS_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_DOWNLOAD_WITH_WM'); ?></td>
      <td>
	  <?php
		  $yn_ad_download_wm = JHTML::_('select.genericlist', $yesno, 'ad_download_wm', 'class="inputbox"', 'value', 'text', $ad_download_wm);
		  echo $yn_ad_download_wm;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_DOWNLOAD_WITH_WM_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_DOWNLOAD_RESOLUTIONS'); ?>:</td>
      <td>
	  <?php
          $ad_download_resolutions = explode(",", $ad_download_resolutions);
		  $resolutions[] = JHTML::_('select.option', '1024', '1024X768');
		  $resolutions[] = JHTML::_('select.option', '1152', '1152X864');
		  $resolutions[] = JHTML::_('select.option', '1600', '1600X1200');
		  $resolutions[] = JHTML::_('select.option', '1920', '1920x1280');
          $resolutions[] = JHTML::_('select.option', '360', 'iPhone 3G/3GS');
          $resolutions[] = JHTML::_('select.option', '640', 'iPhone 4');
          $resolutions[] = JHTML::_('select.option', 'org', 'Original');
		  $ps_ad_download_resolutions = JHTML::_('select.genericlist', $resolutions, 'ad_download_resolutions[]', 'class="inputbox" multiple="multiple"', 'value', 'text', $ad_download_resolutions);
		  echo $ps_ad_download_resolutions;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_DOWNLOAD_RESOLUTIONS_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_PUB_DOWNLOAD'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_downpub = JHTML::_('select.genericlist', $yesno, 'ad_downpub', 'class="inputbox"', 'value', 'text', $ad_downpub);
		  echo $yn_ad_downpub;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_PUB_DOWNLOAD_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_FAVORITES'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_favorite = JHTML::_('select.genericlist', $yesno, 'ad_favorite', 'class="inputbox"', 'value', 'text', $ad_favorite);
		  echo $yn_ad_favorite;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_FAVORITES_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_GMAP'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_gmap = JHTML::_('select.genericlist', $yesno, 'ad_gmap', 'class="inputbox"', 'value', 'text', @$ad_gmap);
		  echo $yn_ad_gmap;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_GMAP_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOP'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_shop = JHTML::_('select.genericlist', $yesno, 'ad_shop', 'class="inputbox"', 'value', 'text', @ $ad_shop);
		  echo $yn_ad_shop;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOP_DESC')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_ALLOW_SLIDESHOW'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_slideshow = JHTML::_('select.genericlist', $yesno, 'ad_slideshow', 'class="inputbox"', 'value', 'text', $ad_slideshow);
		  echo $yn_ad_slideshow;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_SLIDESHOW_BUTON_USER')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SLIDESHOW_AUTO_START'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_slideshow_auto = JHTML::_('select.genericlist', $yesno, 'ad_slideshow_auto', 'class="inputbox"', 'value', 'text', $ad_slideshow_auto);
		  echo $yn_ad_slideshow_auto;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SLIDESHOW_AUTO_START_TIP')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SLIDESHOW_DELAY'); ?>:</td>
      <td><input type="text" name="ad_slideshow_delay" value="<?php echo $ad_slideshow_delay; ?>" size="5"></td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SLIDESHOW_DELAY_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_FADER'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_fader = JHTML::_('select.genericlist', $yesno, 'ad_fader', 'class="inputbox"', 'value', 'text', $ad_fader);
		  echo $yn_ad_fader;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_USE_FADER')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_SEARCH'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_search = JHTML::_('select.genericlist', $yesno, 'ad_search', 'class="inputbox"', 'value', 'text', $ad_search);
		  echo $yn_ad_search;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_SEARCH_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_SEND2FRIEND'); ?></td>
      <td>
	  <?php
		  $yn_ad_showsend2friend = JHTML::_('select.genericlist', $yesno, 'ad_showsend2friend', 'class="inputbox"', 'value', 'text', $ad_showsend2friend);
		  echo $yn_ad_showsend2friend;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_SEND2FRIEND_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_INFORMER'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_showinformer = JHTML::_('select.genericlist', $yesno, 'ad_showinformer', 'class="inputbox"', 'value', 'text', $ad_showinformer);
		  echo $yn_ad_showinformer;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_SHOW_INFORMER_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_LIST_OF_PERIODS'); ?>:</td>
      <td>
	  <?php
		  $periods[] = JHTML::_('select.option', '1', JText::_('COM_DATSOGALLERY_PS_SECOND'));
		  $periods[] = JHTML::_('select.option', '60', JText::_('COM_DATSOGALLERY_PS_MINUTE'));
		  $periods[] = JHTML::_('select.option', '3600', JText::_('COM_DATSOGALLERY_PS_HOUR'));
		  $periods[] = JHTML::_('select.option', '86400', JText::_('COM_DATSOGALLERY_PS_DAY'));
		  $periods[] = JHTML::_('select.option', '604800', JText::_('COM_DATSOGALLERY_PS_WEEK'));
		  $periods[] = JHTML::_('select.option', '2629744', JText::_('COM_DATSOGALLERY_PS_MONTH'));
		  $periods[] = JHTML::_('select.option', '31556926', JText::_('COM_DATSOGALLERY_PS_YEAR'));
		  $ps_ad_periods = JHTML::_('select.genericlist', $periods, 'ad_periods', 'class="inputbox"', 'value', 'text', $ad_periods);
		  echo $ps_ad_periods;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_LIST_OF_PERIODS_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_META_GENERATOR'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_metagen = JHTML::_('select.genericlist', $yesno, 'ad_metagen', 'class="inputbox"', 'value', 'text', $ad_metagen);
		  echo $yn_ad_metagen;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_META_GENERATOR_I')); ?></td>
    </tr>
    <tr>
      <td valign="top"><?php echo JText::_('COM_DATSOGALLERY_WORDS2IGNORE'); ?></td>
      <td><?php echo getWords(); ?></td>
      <td valign="top"><?php echo dgTip(JText::_('COM_DATSOGALLERY_WORDS2IGNORE_I')); ?></td>
    </tr>
    <tr>
      <td><?php echo JText::_('COM_DATSOGALLERY_BOOKMARKER'); ?>:</td>
      <td>
	  <?php
		  $yn_ad_bookmarker = JHTML::_('select.genericlist', $yesno, 'ad_bookmarker', 'class="inputbox"', 'value', 'text', $ad_bookmarker);
		  echo $yn_ad_bookmarker;
      ?>
      </td>
      <td><?php echo dgTip(JText::_('COM_DATSOGALLERY_BOOKMARKER_TIP')); ?></td>
    </tr>
    <tr>
      <td valign="top"><?php echo JText::_('COM_DATSOGALLERY_BOOKMARKER_SERVICES'); ?>:</td>
      <td>
      <table width="100%" class="admintable">
          <tr>
            <td width="50%">
            <fieldset class="radio">
              <label for="ad_google">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/google.png" title="Google" alt="" />
              <?php
              $yn_ad_google = JHTML::_('select.booleanlist', 'ad_google', '', $ad_google);
              echo $yn_ad_google;
              ?>
              </label>
              <label for="ad_facebook">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/facebook.png" title="Facebook" alt="" />
              <?php
              $yn_ad_facebook = JHTML::_('select.booleanlist', 'ad_facebook', '', $ad_facebook);
              echo $yn_ad_facebook;
              ?>
              </label>
              <label for="ad_twitter">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/twitter.png" title="Twitter" alt="" />
              <?php
              $yn_ad_twitter = JHTML::_('select.booleanlist', 'ad_twitter', '', $ad_twitter);
              echo $yn_ad_twitter;
              ?>
              </label>
              <label for="ad_myspace">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/myspace.png" title="Myspace" alt="" />
              <?php
              $yn_ad_myspace = JHTML::_('select.booleanlist', 'ad_myspace', '', $ad_myspace);
              echo $yn_ad_myspace;
              ?>
              </label>
              <label for="ad_linkedin">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/linkedin.png" title="Linkedin" alt="" />
              <?php
              $yn_ad_linkedin = JHTML::_('select.booleanlist', 'ad_linkedin', '', $ad_linkedin);
              echo $yn_ad_linkedin;
              ?>
              </label>
              <label for="ad_yahoo">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/yahoo.png" title="Yahoo" alt="" />
              <?php
              $yn_ad_yahoo = JHTML::_('select.booleanlist', 'ad_yahoo', '', $ad_yahoo);
              echo $yn_ad_yahoo;
              ?>
              </label>
              </fieldset>
              </td>
              <td width="50%">
              <fieldset class="radio">
              <label for="ad_digg">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/digg.png" title="Digg" alt="" />
              <?php
              $yn_ad_digg = JHTML::_('select.booleanlist', 'ad_digg', '', $ad_digg);
              echo $yn_ad_digg;
              ?>
              </label>
              <label for="ad_del">
              <img style="vertical-align:bottom;padding:1px;" align="baseline" src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/delicious.png" title="Del.icoi.us" alt="" />
              <?php
              $yn_ad_del = JHTML::_('select.booleanlist', 'ad_del', '', $ad_del);
              echo $yn_ad_del;
              ?>
              </label>
              <label for="ad_live">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/windows.png" title="Windows Live" alt="" />
              <?php
              $yn_ad_live = JHTML::_('select.booleanlist', 'ad_live', '', $ad_live);
              echo $yn_ad_live;
              ?>
              </label>
              <label for="ad_furl">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/furl.png" title="Furl" alt="" />
              <?php
              $yn_ad_furl = JHTML::_('select.booleanlist', 'ad_furl', '', $ad_furl);
              echo $yn_ad_furl;
              ?>
              </label>
              <label for="ad_reddit">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/reddit.png" title="Reddit" alt="" />
              <?php
              $yn_ad_reddit = JHTML::_('select.booleanlist', 'ad_reddit', '', $ad_reddit);
              echo $yn_ad_reddit;
              ?>
              </label>
              <label for="ad_technorati">
              <img src="<?php echo JURI::root(); ?>components/com_datsogallery/images/customtheme/bookmarker/technorati.png" title="Technorati" alt="" />
              <?php
              $yn_ad_technorati = JHTML::_('select.booleanlist', 'ad_technorati', '', $ad_technorati);
              echo $yn_ad_technorati;
              ?>
              </label>
              </fieldset>
             </td>
          </tr>
        </table></td>
      <td valign="top"><?php echo dgTip(JText::_('COM_DATSOGALLERY_BOOKMARKER_SERVICES_TIP')); ?></td>
    </tr>
  </table>
</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('COM_DATSOGALLERY_COMPONENT_INFO'), "tab9");
?>

<fieldset class="adminform">
  <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_COMPONENT_INFO1');?>&nbsp;</legend>
  <table class="admintable" cellspacing="1">
    <tr>
      <td width="260" align="left" valign="top"><a href="http://www.datso.fr" target="_blank">
        <img src="components/com_datsogallery/images/datsogallery-box.png" alt="" title="DATSOGALLERY BOX" width="200" height="212" align="middle" border="0" hspace="5" vspace="5" /></a>
      </td>
      <td width="300" valign="top">
      <p>
      <font color='#808080'>
      <?php
        if (ini_get('allow_url_fopen')) {
          $v = getDatso('DGCJ17', 'VER');
        }
        else {
          $v = '';
        }
        $c = DG_VER;
        if (empty ($v)) {
          $latest_version = '<div style="font-size:12px">Automatic checking for the latest versions of Datso products is impossible, since one of the required PHP functions (allow_url_fopen) is disabled in your PHP.INI file</div>';
        }
        else {
          $pot = array(1000000000, 10000000, 100000, 1);
          $wv = explode('.', $v);
          $wvd = 0;
          foreach ($wv as $i => $d) {
            $wvd = $wvd + $d * $pot[$i];
          }
          $cv = explode('.', $c);
          $cvd = 0;
          foreach ($cv as $i => $d) {
            $cvd = $cvd + $d * $pot[$i];
          }
          if ($wvd > $cvd) {
            $latest_version = "<span style=\"font-size:14px\">".JText::_('COM_DATSOGALLERY_VERSION_AVAILABLE')."<a href=\"http://www.datso.fr/\"><span style=\"color:#006699\">$v</span></a></span>";
          }
          else {
            $latest_version = "<span style=\"font-size:14px\">".JText::_('COM_DATSOGALLERY_VERSION_INSTALLED')."<span style=\"color:#669933\">".DG_VER."</span></span>";
          }
        }
        echo $latest_version;
      ?>
      </font>
      </p>
        <ul style="list-style: none;font-size:12px">
          <li><a href="http://www.datso.fr/news.html"><?php echo JText::_('COM_DATSOGALLERY_NEWS'); ?></a></li>
          <li><a href="http://www.datso.fr/video-guidelines.html"><?php echo JText::_('COM_DATSOGALLERY_FLASH_DEMO'); ?></a></li>
        </ul>
        <p>
        <?php
            $pathorig = getDirectorySize(JPath::clean(JPATH_SITE.$ad_pathoriginals));
            echo "<span style=\"font-size:14px;color:#808080\">Details for Originals Picture Path</span>";
            echo "<ul style=\"list-style:none;font-size:12px\">\n";
            echo "<li>Total size : ".sizeFormat($pathorig['size'])."</li>\n";
            echo "<li>No. of files : ".$pathorig['count']."</li>\n";
            echo "</ul>";
        ?>
        </p>
      </td>
    </tr>
  </table>
</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel('PayPal', "tab10");
?>

<fieldset class="adminform">
	<legend><?php echo JText::_('COM_DATSOGALLERY_PAYPAL_PARAMETERS');?></legend>
	<table class="admintable" cellspacing="1">

		<tbody>
		<tr>
			<td width="185" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DATSOGALLERY_PAYPAL_EMAIL');?>::<?php echo JText::_('COM_DATSOGALLERY_PAYPAL_EMAIL_DESC');?>">
					<?php echo JText::_('COM_DATSOGALLERY_PAYPAL_EMAIL');?>
				</span>
			</td>
			<td>
				<input type="text" name="ad_pp_email" value="<?php echo $ad_pp_email;?>" size="42" />
			</td>
		</tr>
        <tr>
			<td width="185" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DATSOGALLERY_PP_MODE');?>::<?php echo JText::_('COM_DATSOGALLERY_PP_MODE_DESC');?>">
					<?php echo JText::_('COM_DATSOGALLERY_PP_MODE');?>
				</span>
			</td>
			<td>
				  <?php
				    $pp_mode = array();
				    $pp_mode[] = JHTML::_('select.option', '0', JText::_('COM_DATSOGALLERY_PP_TEST'));
				    $pp_mode[] = JHTML::_('select.option', '1', JText::_('COM_DATSOGALLERY_PP_LIVE'));
				    echo JHTML::_('select.genericlist', $pp_mode, 'ad_pp_mode', 'class="inputbox"', 'value', 'text', @$ad_pp_mode);
				  ?>
			</td>
		</tr>
        <tr>
			<td width="185" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DATSOGALLERY_PAYPAL_CURRENCY');?>::<?php echo JText::_('COM_DATSOGALLERY_PAYPAL_CURRENCY_DESC');?>">
					<?php echo JText::_('COM_DATSOGALLERY_PAYPAL_CURRENCY');?>
				</span>
			</td>
			<td>
				  <?php
				    $pp_currency = array();
				    $pp_currency[] = JHTML::_('select.option', 'EUR', 'EUR');
				    $pp_currency[] = JHTML::_('select.option', 'USD', 'USD');
				    $pp_currency[] = JHTML::_('select.option', 'AUD', 'AUD');
				    $pp_currency[] = JHTML::_('select.option', 'GBP', 'GBP');
				    $pp_currency[] = JHTML::_('select.option', 'CAD', 'CAD');
				    $pp_currency[] = JHTML::_('select.option', 'CZK', 'CZK');
				    $pp_currency[] = JHTML::_('select.option', 'DKK', 'DKK');
				    $pp_currency[] = JHTML::_('select.option', 'HKD', 'HKD');
				    $pp_currency[] = JHTML::_('select.option', 'HUF', 'HUF');
				    $pp_currency[] = JHTML::_('select.option', 'ILS', 'ILS');
				    $pp_currency[] = JHTML::_('select.option', 'JPY', 'JPY');
				    $pp_currency[] = JHTML::_('select.option', 'MXN', 'MXN');
				    $pp_currency[] = JHTML::_('select.option', 'NZD', 'NZD');
				    $pp_currency[] = JHTML::_('select.option', 'NOK', 'NOK');
				    $pp_currency[] = JHTML::_('select.option', 'PLN', 'PLN');
				    $pp_currency[] = JHTML::_('select.option', 'SGD', 'SGD');
				    $pp_currency[] = JHTML::_('select.option', 'SEK', 'SEK');
				    $pp_currency[] = JHTML::_('select.option', 'CHF', 'CHF');
				    echo JHTML::_('select.genericlist', $pp_currency, 'ad_pp_currency', 'class="inputbox"', 'value', 'text', @$ad_pp_currency);
				  ?>
			</td>
		</tr>
		<tr>
			<td width="185" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DATSOGALLERY_TAX_TYPE');?>::<?php echo JText::_('COM_DATSOGALLERY_TAX_TYPE_DESC');?>">
					<?php echo JText::_('COM_DATSOGALLERY_TAX_TYPE');?>
				</span>
			</td>
			<td>
				  <?php
				    $pp_tax_type = array();
				    $pp_tax_type[] = JHTML::_('select.option', '0', JText::_('COM_DATSOGALLERY_TAX_PERCENT'));
				    $pp_tax_type[] = JHTML::_('select.option', '1', JText::_('COM_DATSOGALLERY_TAX_FIXED'));
				    echo JHTML::_('select.genericlist', $pp_tax_type, 'ad_pp_tax_type', 'class="inputbox"', 'value', 'text', @$ad_pp_tax_type);
				  ?>
			</td>
		</tr>
        <tr>
			<td width="185" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DATSOGALLERY_TAX_VALUE');?>::<?php echo JText::_('COM_DATSOGALLERY_TAX_VALUE_DESC');?>">
					<?php echo JText::_('COM_DATSOGALLERY_TAX_VALUE');?>
				</span>
			</td>
			<td>
				<input type="text" name="ad_pp_tax_value" value="<?php echo @$ad_pp_tax_value;?>" size="10" />
			</td>
		</tr>
	  </tbody>
	</table>
</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->endPane();
?>
<input type="hidden" name="option" value="com_datsogallery" />
<input type="hidden" name="task" value="savesettings" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>