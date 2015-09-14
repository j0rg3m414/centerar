<?php
  defined('_JEXEC') or die('Restricted access');

  GalleryHeader();
  $na = ($ad_na) ? '#dgtop':'';
  $ssa = (!$ad_slideshow_auto) ? ',onOpen:function(currentImage){Shadowbox.play();Shadowbox.pause();}':'';

  if ($ad_shadowbox) {
    $document->addStyleSheet(JURI::base(true).'/components/com_datsogallery/libraries/shadowbox/shadowbox.css');
    $document->addScript(JURI::base(true).'/components/com_datsogallery/libraries/shadowbox/shadowbox.js');
    $sbinit = 'Shadowbox.init({slideshowDelay:'.$ad_slideshow_delay.$ssa.'});';
    $document->addScriptDeclaration($sbinit);
  }
  $db->setQuery('SELECT c.access'
  .' FROM #__datsogallery_catg AS c'
  .' LEFT JOIN #__datsogallery AS a'
  .' ON a.catid = c.cid'
  .' WHERE a.id = '.(int) $id
  .' AND a.published = 1'
  .' AND a.approved = 1'
  .' AND c.approved = 1'
  .' AND c.published = 1'
  .' AND c.access IN ('.$groups.')'
  );
  $access = $db->loadObject();

  if (!$access) {
    $app->redirect(JRoute::_("index.php?option=com_datsogallery&view=datsogallery".$itemid), JText::_('COM_DATSOGALLERY_NOT_ACCESS_THIS_IMAGE'));
  }

  $db->setQuery('SELECT a.*'
  .' FROM #__datsogallery AS a'
  .' WHERE a.id = '.(int) $id
  );
  $obj = $db->loadObject();
  if (count($obj) < 1) {
    $app->redirect(JRoute::_("index.php?option=com_datsogallery&view=datsogallery".$itemid, false), JText::_('COM_DATSOGALLERY_PICSLAD'));
  }

  $document->setTitle($obj->imgtitle);
  if ($ad_metagen) {
    if ($obj->imgtext) {
      $document->setDescription(limit_words($obj->imgtext, 25));
      $document->setMetadata('keywords', metaGen($obj->imgtext));
    }
  }
  $obj->id_cache = array();
  $db->setQuery('SELECT *'
  .' FROM #__datsogallery'
  .' WHERE catid = '.$obj->catid
  .' AND published = 1'
  .' AND approved = 1'
  .' ORDER BY ordering'
  );
  $rows = $db->loadObjectList();
  if (count($rows)) {
    foreach ($rows as $row) {
      $obj->id_cache[] = $row->id;
    }
  }
  $act_key = array_search($obj->id, $obj->id_cache);
  if ($ad_sortby == "ASC") {
    $nid = (isset ($obj->id_cache[$act_key + 1])) ? $obj->id_cache[$act_key + 1] : 0;
    $pid = (isset ($obj->id_cache[$act_key - 1])) ? $obj->id_cache[$act_key - 1] : 0;
  }
  else {
    $nid = (isset ($obj->id_cache[$act_key - 1])) ? $obj->id_cache[$act_key - 1] : 0;
    $pid = (isset ($obj->id_cache[$act_key + 1])) ? $obj->id_cache[$act_key + 1] : 0;
  }
  unset ($obj->id_cache);
  addhit($obj->id);
  if (!$ad_pnthumb) {
  ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="width:50%;text-align:left"><?php if ($pid > 0) {?>
      <a href="<?php echo JRoute::_("index.php?option=com_datsogallery&amp;task=image&amp;catid=".$obj->catid
      ."&amp;id=".$pid.$itemid.$na);?>"><?php echo JText::_('COM_DATSOGALLERY_PREV_IMAGE');?></a>
      <?php } ?>
    </td>
    <td style="width:50%;text-align:right"><?php if ($nid > 0) {?>
      <a href="<?php echo JRoute::_("index.php?option=com_datsogallery&amp;task=image&amp;catid=".$obj->catid
      ."&amp;id=".$nid.$itemid.$na);?>"><?php echo JText::_('COM_DATSOGALLERY_NEXT_IMAGE');?></a>
      <?php } ?>
    </td>
  </tr>
</table>
    <?php
      if (!$ad_shadowbox) {
        echo "<div style=\"position:relative;padding:10px 0;text-align:center\">\n";
        echo "<img src=\"".resize($obj->imgoriginalname, $ad_maxwidth, $ad_maxheight, 0, 0, $ad_showwatermark, $obj->catid)
        ."\" ".get_width_height($obj->imgoriginalname, $ad_maxwidth, $ad_maxheight, $obj->catid)." class=\"dgimg\" alt=\"".$obj->imgtitle."\" />\n";
      }
      else {
        ?>
        <div style="position:relative;padding:10px 0;text-align:center" class="dgtip" id="<?php echo ($ad_slideshow) ? JText::_('COM_DATSOGALLERY_VIEW_ORG_IMAGE') : JText::_('COM_DATSOGALLERY_CLICK_TO_VIEW_BIG'); ?>">
        <?php
        echo "<a rel=\"shadowbox[screenshots];player=img\" href=\"".JURI::root()."index.php?option=com_datsogallery&task=sbox&catid=".$obj->catid
        ."&id=".$obj->id."&format=raw\" title=\"".jsspecialchars($obj->imgtitle)."\">\n";
        echo "<img class=\"dgimg\" src=\"".resize($obj->imgoriginalname, $ad_maxwidth, $ad_maxheight, 0, 0, $ad_showwatermark, $obj->catid)
        ."\" ".get_width_height($obj->imgoriginalname, $ad_maxwidth, $ad_maxheight, $obj->catid)." alt=\"".$obj->imgtitle."\" /></a>";
        if ($ad_slideshow) {
              $whereasc  = ($ad_sortby == 'ASC') ? '> ':'< ';
              $wheredesc = ($ad_sortby == 'DESC') ? '> ':'< ';
              $order = ($ad_sortby == 'DESC') ? 'DESC':'ASC';
              $db->setQuery('SELECT *'
              .' FROM #__datsogallery'
              .' WHERE id != '.$obj->id
              .' AND id '.$whereasc.$obj->id
              .' AND catid = '.$obj->catid
              .' AND published = 1'
              .' AND approved = 1'
              .' ORDER BY ordering '.$order
              );
              $rows = $db->loadObjectList();
              if (count($rows)) {
                foreach ($rows as $row) {
                  echo "<a style=\"visibility:hidden;\" rel=\"shadowbox[screenshots];player=img\"
                  href=\"".JURI::root()."index.php?option=com_datsogallery&task=sbox&catid=".$obj->catid."&id=".$row->id
                  ."&format=raw\" title=\"".jsspecialchars($row->imgtitle)."\"></a>\n";
                }
              }
              $db->setQuery('SELECT *'
              .' FROM #__datsogallery'
              .' WHERE id != '.$obj->id
              .' AND id '.$wheredesc.$obj->id
              .' AND catid = '.$obj->catid
              .' AND published = 1'
              .' AND approved = 1'
              .' ORDER BY ordering '.$order
              );
              $rows = $db->loadObjectList();
              if (count($rows)) {
                foreach ($rows as $row) {
                  echo "<a style=\"visibility:hidden;\" rel=\"shadowbox[screenshots];player=img\"
                  href=\"".JURI::root()."index.php?option=com_datsogallery&task=sbox&catid=".$obj->catid."&id=".$row->id
                  ."&format=raw\" title=\"".jsspecialchars($row->imgtitle)."\"></a>\n";
                }
              }
            }
          }
      echo "</div>";
    }
    else {
      $fader = ($ad_fader) ? ' class="fader"':'';
    ?>

<div style="position:relative;padding:10px 0;"<?php echo $fader; ?>>
        <?php
          if (!$ad_shadowbox) {
            echo "<div align=\"center\">\n";
            echo "<img src=\"".resize($obj->imgoriginalname, $ad_maxwidth, $ad_maxheight, 0, 0, $ad_showwatermark, $obj->catid)
            ."\" ".get_width_height($obj->imgoriginalname, $ad_maxwidth, $ad_maxheight, $obj->catid)." class=\"dgimg\" alt=\"".$obj->imgtitle."\" />\n";
          }
          else {
            ?>
            <div style="position:relative;text-align: center" class="dgtip" id="<?php echo ($ad_slideshow) ? JText::_('COM_DATSOGALLERY_VIEW_ORG_IMAGE') : JText::_('COM_DATSOGALLERY_CLICK_TO_VIEW_BIG'); ?>">
            <?php
            echo "<a rel=\"shadowbox[screenshots];player=img\" href=\"".JURI::root()."index.php?option=com_datsogallery&task=sbox&catid=".$obj->catid
            ."&id=".$obj->id."&format=raw\" title=\"".jsspecialchars($obj->imgtitle)."\">\n";
            echo "<img class=\"dgimg\" src=\"".resize($obj->imgoriginalname, $ad_maxwidth, $ad_maxheight, 0, 0, $ad_showwatermark, $obj->catid)
            ."\" ".get_width_height($obj->imgoriginalname, $ad_maxwidth, $ad_maxheight, $obj->catid)." alt=\"".$obj->imgtitle."\" /></a>";
              if ($ad_slideshow) {
              $whereasc  = ($ad_sortby == 'ASC') ? '> ':'< ';
              $wheredesc = ($ad_sortby == 'DESC') ? '> ':'< ';
              $order = ($ad_sortby == 'DESC') ? 'DESC':'ASC';
              $db->setQuery('SELECT *'
              .' FROM #__datsogallery'
              .' WHERE id != '.$obj->id
              .' AND id '.$whereasc.$obj->id
              .' AND catid = '.$obj->catid
              .' AND published = 1'
              .' AND approved = 1'
              .' ORDER BY ordering '.$order
              );
              $rows = $db->loadObjectList();
              if (count($rows)) {
                foreach ($rows as $row) {
                  echo "<a style=\"visibility:hidden;\" rel=\"shadowbox[screenshots];player=img\"
                  href=\"".JURI::root()."index.php?option=com_datsogallery&task=sbox&catid=".$obj->catid."&id=".$row->id
                  ."&format=raw\" title=\"".jsspecialchars($row->imgtitle)."\"></a>\n";
                }
              }
              $db->setQuery('SELECT *'
              .' FROM #__datsogallery'
              .' WHERE id != '.$obj->id
              .' AND id '.$wheredesc.$obj->id
              .' AND catid = '.$obj->catid
              .' AND published = 1'
              .' AND approved = 1'
              .' ORDER BY ordering '.$order
              );
              $rows = $db->loadObjectList();
              if (count($rows)) {
                foreach ($rows as $row) {
                  echo "<a style=\"visibility:hidden;\" rel=\"shadowbox[screenshots];player=img\"
                  href=\"".JURI::root()."index.php?option=com_datsogallery&task=sbox&catid=".$obj->catid."&id=".$row->id
                  ."&format=raw\" title=\"".jsspecialchars($row->imgtitle)."\"></a>\n";
                }
              }
            }
          }
          $cachefile = getCacheFile($obj->imgoriginalname, $ad_maxwidth, $ad_maxheight, $obj->catid);
          $msize = getimagesize($cachefile);
          $hres = ($msize[1] / 2) - ($ad_thumbwidth1 / 2) + 10;
        ?>
  </div>
  <div style="position:absolute;left:0;top: <?php echo $hres; ?>px">
        <?php
          $db->setQuery("SELECT * FROM #__datsogallery WHERE id = ".$pid);
          $rowsPrev = $db->loadObjectList();
          if (count($rowsPrev)) {
            foreach ($rowsPrev as $rowPrev) {
              echo "<a href='".JRoute::_('index.php?option=com_datsogallery&amp;task=image&amp;catid='.$obj->catid
              .'&amp;id='.$pid.$itemid).$na."'>\n";
              echo "<img src='".resize($rowPrev->imgoriginalname, $ad_thumbwidth1, $ad_thumbheight1, $ad_crop, '1:1', 0, $obj->catid)
              ."' ".get_width_height($rowPrev->imgoriginalname, $ad_thumbwidth1, $ad_thumbheight1, $obj->catid, '1:1')
              ." class='dgimg' alt='' /></a>\n";
            }
          }
        ?>
  </div>
    <div style="position:absolute;right:0;top: <?php echo $hres; ?>px">
        <?php
          $db->setQuery("SELECT * FROM #__datsogallery WHERE id = ".$nid);
          $rowsNext = $db->loadObjectList();
          if (count($rowsNext)) {
            foreach ($rowsNext as $rowNext) {
              echo "<a href='".JRoute::_('index.php?option=com_datsogallery&amp;task=image&amp;catid='.$obj->catid
              .'&amp;id='.$nid.$itemid).$na."'>\n";
              echo "<img src='".resize($rowNext->imgoriginalname, $ad_thumbwidth1, $ad_thumbheight1, $ad_crop, '1:1', 0, $obj->catid)
              ."' ".get_width_height($rowNext->imgoriginalname, $ad_thumbwidth1, $ad_thumbheight1, $obj->catid, '1:1')
              ." class='dgimg' alt='' /></a>\n";
            }
          }
        ?>
  </div>
</div>
  <?php
  }

  if ($dg_theme == 'customtheme') {
      $bg_img_over = '#'.$dg_body_background_td_hover;
      $bg_img_out  = '#'.$dg_body_background;
    }
    else {
      $bg_img_over = ($dg_theme != 'lightteme' && $dg_theme == 'darktheme') ? '#909090' : '#FCFCFC';
      $bg_img_out = ($dg_theme != 'lightteme' && $dg_theme == 'darktheme') ? '#808080' : '#F9F9F9';
    }

  if ($ad_showdetail) {
    $imgsize = filesize(JPATH_SITE.dgPath($ad_pathoriginals).DS.$obj->imgoriginalname);
    $dgfilesize = format_filesize($imgsize);
    $size_pic[0] = "";
    $size_pic[1] = "";
    $size_pic = getimagesize(JPATH_SITE.dgPath($ad_pathoriginals).DS.$obj->imgoriginalname);
    $x = "x";
    $res = "$size_pic[0] $x $size_pic[1]";
    $types = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG');
    $type = $types[$size_pic[2]];

  if ($is_admin || $user->id == $obj->owner_id) {
    echo "<script type=\"text/javascript\">
      datso(document).ready(function(){
       datso('#".$obj->id."-editimg').editInPlace({
        bg_img_over:  'transparent url(".JURI::base(true)."/components/com_datsogallery/images/".$dg_theme."/edit.png) no-repeat 98% center',
        bg_img_out:   'transparent',
        show_buttons: false,
        url:          'index.php?option=com_datsogallery&task=edittitle&format=raw',
        update_value: 'imgtitle',
        element_id:   'id',
        saving_image: '".JURI::base(true)."/components/com_datsogallery/images/".$dg_theme."/loading.gif',
        success: function(response){ datso('#".$obj->id."-edit').html(response); }
        });
      });
    </script>";
  }
?>
<div class="dg_head_background"><div style="position:relative;display:inline;padding:4px 24px 4px 0" id="<?php echo $obj->id; ?>-editimg"><?php echo $obj->imgtitle;?></div>
<div style="position:relative;float:right">
<?php
  canDownload ($obj->id, $obj->catid, $obj->imgprice);
  if ($user->id == $obj->owner_id) {
    echo dgTip(JText::_('COM_DATSOGALLERY_EDIT_IMAGE'), $image = 'edit.png', '',
    JRoute::_("index.php?option=com_datsogallery&amp;task=editpic&amp;uid=".$obj->id.$itemid), '', $link = 1);
  }
  if ($user->id && $ad_favorite) {
  $db->setQuery('SELECT * FROM `#__datsogallery_favorites` WHERE `user_id` = '.(int)$user->id.' AND image_id = '.(int) $obj->id);
  $db->query() or die($db->stderr());
  $favorited = $db->loadObject();
  $ifav = ($favorited) ? 'remove_favorite' : 'add_favorite';
  $tfav = ($favorited) ? JText::_('COM_DATSOGALLERY_REMOVE_FAVORITE') : JText::_('COM_DATSOGALLERY_ADD_FAVORITE');
?>
&nbsp;<div class="favbox">
  <span class="favorite" id="<?php echo $obj->id; ?>">
    <span class="dgtip" id="<?php echo $tfav; ?>">
      <span class="<?php echo $ifav; ?>"></span>
    </span>
  </span>
</div>
<?php
  }
  $db->setQuery('SELECT status'
  .' FROM #__datsogallery_purchases'
  .' WHERE image_id = '.(int) $obj->id
  .' AND user_id = '.(int) $user->id
  );
  $status = $db->loadResult();
  if ($ad_shop && $obj->imgprice != '0.00'){
    if(!$user->id) {
      echo '&nbsp;'.dgTip(JText::_('COM_DATSOGALLERY_TO_START_SHOPPING'),
      $image = 'basket_disable.png', '', ''.$url.'', '', $link = 1).' ';
    }
    elseif ($status == 'Completed'){
      echo '&nbsp;'.dgTip(JText::_('COM_DATSOGALLERY_ALREADY_PURCHASED'),
      $image = 'basket_disable.png', '', '', '', $link = 0).' ';
    }
    else {
      echo '&nbsp;<span class="dgtip" id="'.JText::_('COM_DATSOGALLERY_BASKET_ADD_REMOVE').'">'.dgTip('', $image = 'basket.png', '', '#', 'class="'.$obj->id.'" id="addtobasket"', $link = 1).'</span> ';
      echo '<span>' . currencySymbol($ad_pp_currency) . $obj->imgprice . '</span>';
    }
  } elseif (!$ad_shop && $obj->imgprice != '0.00') {
      echo '&nbsp;'.dgTip(JText::_('COM_DATSOGALLERY_SHOPPING_DISABLED'),
      $image = 'basket_disable.png', '', '#', '', $link = 0).' ';
  }
if ($ad_gmap && $type == 'JPG' && is_callable('exif_read_data')){
$imgmeta = JPath::clean(JPATH_SITE.$ad_pathoriginals.DS.$obj->imgoriginalname);
$exif = @exif_read_data( $imgmeta );
 if ((!empty($exif['GPSLatitude'])) && (!empty($exif['GPSLongitude']))) {
?>
<div id="gmap_icon" class="gmap_icon"><div class="dgtip" id="<?php echo JText::_('COM_DATSOGALLERY_VIEW_MAP'); ?>" style="width:16px;height:16px"></div></div>
<?php
 }
}
?>
</div>
</div>
<div style="clear: both"></div>
<?php
if ($obj->imgtext && $ad_showimgtext) {
  if ($is_admin || $user->id == $obj->owner_id) {
    echo "<script type=\"text/javascript\">
      datso(document).ready(function(){
       datso('#".$obj->id."-editdesc').editInPlace({
        bg_img_over:  '".$bg_img_over." url(".JURI::base(true)."/components/com_datsogallery/images/".$dg_theme."/edit.png) no-repeat bottom right',
        bg_img_out:   '".$bg_img_out."',
        show_buttons: true,
        save_button:  '<button class=\"inplace_save dg_btn\"><span><span>".JText::_('COM_DATSOGALLERY_SAVE')."</span></span></button>',
        cancel_button:'<button class=\"inplace_cancel dg_btn\"><span><span>".JText::_('COM_DATSOGALLERY_CANCEL')."</span></span></button>',
        field_type:	  'textarea',
        url:          'index.php?option=com_datsogallery&task=editdesc&format=raw',
        update_value: 'imgtext',
        element_id:   'id',
        saving_image: '".JURI::base(true)."/components/com_datsogallery/images/".$dg_theme."/loading.gif',
        success: function(response){ datso('#".$obj->id."-edit').html(response); }
        });
      });
    </script>";
  }
?>
	<div class="dg_body_background_description" id="<?php echo $obj->id; ?>-editdesc">
		<?php echo nl2br($obj->imgtext);?>
	</div>
<?php
}

if ($ad_allow_tags) : ?>
<div class="dg_head_background_tags">
  <div style="float: left;"><?php echo JText::_('COM_DATSOGALLERY_TAGS'); ?> </div>
  <script type="text/javascript">
    var imgid = '<?php echo $obj->id; ?>'
  </script>
  <?php
  if ($user->id) {
  $groups = $user->getAuthorisedViewLevels();
  $allowed = in_array($ad_access_tags, $groups);
  $query = 'SELECT COUNT(*)'
    . ' FROM #__datsogallery_tags'
    . ' WHERE image_id = ' . (int) $obj->id
    ;
    $db->setQuery($query);
    $count = $db->loadResult();
    if ($count < $ad_max_tags && $allowed || $is_admin) {
  ?>
  <div style="float: left;"> <span id="tagsform" style="display:none">
    <form action="">
      : <input type="text" name="newtag" id="newtag" />
      <button type="button" id="btnaddtag" class="dg_btn"><span><span><?php echo JText::_('COM_DATSOGALLERY_SEND'); ?></span></span></button><span class="addtag_loading" style="display:none"></span>
    </form>
    </span> </div>
  <div style="position:relative;float:right;top:5px;cursor:pointer" id="dgtag"><img class="dgtip" id="<?php echo JText::_('COM_DATSOGALLERY_ADD_TAG'); ?>" src="<?php echo JURI::base(true); ?>/components/com_datsogallery/images/<?php echo $dg_theme; ?>/tag.png" width="16" height="16" alt="" /></div>
<?php
   }
  }
?>
  <div style="clear:both"></div>
</div>
<div id="tagserror" class="dg_tag_error_message" style="display:none"><?php echo JText::_('COM_DATSOGALLERY_ENTER_TAG'); ?></div><div style="clear:both"></div>
<div id="tagsmin" class="dg_tag_error_message" style="display:none"><?php echo JText::sprintf('COM_DATSOGALLERY_TAG_MINIMUM_CHARS', $ad_min_tag_chars); ?></div><div style="clear:both"></div>
<div id="tagsmax" class="dg_tag_error_message" style="display:none"><?php echo JText::_('COM_DATSOGALLERY_TAG_MAXIMUM_TAGS'); ?></div><div style="clear:both"></div>
<div id="tagsdbl" class="dg_tag_error_message" style="display:none"><?php echo JText::_('COM_DATSOGALLERY_TAG_EXISTS'); ?></div><div style="clear:both"></div>
<div class="dg_body_background_tags">
  <div id="tagchecklist" class="tagchecklist"> <?php echo showTags($obj->id); ?> </div>
  <div style="clear:both"></div>
</div>
<?php
endif;

if ($ad_gmap && $type == 'JPG' && is_callable('exif_read_data')) :
$imgmeta = JPath::clean(JPATH_SITE.$ad_pathoriginals.DS.$obj->imgoriginalname);
$exif = @exif_read_data( $imgmeta );
$lat = (!empty($exif['GPSLatitude'])) ? geo_single_gps($exif["GPSLatitude"], $exif['GPSLatitudeRef']) : '';
$lng = (!empty($exif['GPSLongitude'])) ? geo_single_gps($exif["GPSLongitude"], $exif['GPSLongitudeRef']) : '';
if ($lat && $lng):
$document->setMetaData('viewport', 'initial-scale=1.0, user-scalable=no', false);
$document->addScript('http://maps.googleapis.com/maps/api/js?sensor=false');
$document->addScript('components/com_datsogallery/libraries/datso.gmap.min.js');
?>
<script type="text/javascript">
function initialize() {
   datso("#gmap").gMap({
      markers: [
         {
         latitude: <?php echo $lat; ?> ,
         longitude : <?php echo $lng; ?> ,
         icon : {
            image: "<?php echo JURI::base(true); ?>/components/com_datsogallery/images/location.png",
            iconsize: [23, 32],
            iconanchor: [11, 32]
         }
      }],
      zoom : 15
   });
}
datso(document).ready(function () {
   datso("#gmap_icon").click(function () {
      if (datso("#dg_gmap").is(":hidden")) {
         datso("#dg_gmap").slideToggle('fast');
         initialize();
      } else {
         datso("#dg_gmap").slideToggle('fast');
      }
   });
});
</script>
<div id="dg_gmap" class="dg_body_background_tags">
  <div id="gmap" class="gmap"></div>
</div>
<?php
endif;
endif;
$image_info = 1;
if ($image_info || $ad_bookmarker || $ad_showsubmitter) :
$bc = ($ad_showcomment) ? '':' style="border-bottom:1px solid #DCDCDC"';
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="dg_body_background_details"<?php echo $bc; ?>>
  <tr>
    <?php if ($image_info) : ?>
    <td class="details">
      <?php
        if ($ad_showimgauthor) {
          if ($obj->imgauthor && $obj->imgauthorurl) {
            $link = '<a rel="nofollow" class="dgtip" href="http://'.remove_http($obj->imgauthorurl)
            .'" target="_blank" class="dgtip" id="'.JText::_('COM_DATSOGALLERY_GOTO_AUTHOR_URL').'">'.$obj->imgauthor.'</a>';
          }
          if (!$obj->imgauthorurl) {
            $link = $obj->imgauthor;
          }
          if (!$obj->imgauthor) {
            $link = JText::_('COM_DATSOGALLERY_UNKNOWN_AUTHOR');
          }
          echo '<strong>'.JText::_('COM_DATSOGALLERY_AUTHOR').'</strong>: '.$link.'<br />';
        }
        echo ($ad_showfimgdate) ? '<strong>'.JText::_('COM_DATSOGALLERY_DATE_ADD').'</strong>: '.strftime($ad_datef, $obj->imgdate).'<br />' : '';
        echo ($ad_showimgcounter) ? '<strong>'.JText::_('COM_DATSOGALLERY_HITS').'</strong>: '.$obj->imgcounter.'<br />' : '';
        echo ($ad_showdownloads) ? '<strong>'.JText::_('COM_DATSOGALLERY_DOWNLOADS').'</strong>: '.$obj->imgdownloaded.'<br />' : '';
        echo ($ad_showres) ? '<strong>'.JText::_('COM_DATSOGALLERY_SIZE').'</strong>: '.$res.' / '.$type.'<br />' : '';
        echo ($ad_showfimgsize) ? '<strong>'.JText::_('COM_DATSOGALLERY_FILESIZE').'</strong>: '.$dgfilesize.'<br />' : '';
        echo ($ad_showrating) ? showVote ($obj->id, $obj->imgvotes, $obj->imgvotesum, true) : '';
        echo exifData($obj->imgoriginalname);
      ?>
    </td>
     <?php endif; ?>
    <?php if ($ad_bookmarker) {?>
    <td class="bookmarker"><?php echo DatsoBookmarker((int) $obj->id, $obj->imgtitle, $obj->imgtext);?></td>
    <?php } ?>
    <?php if ($ad_showsubmitter) {?>
    <td class="submitter">
      <?php
      $db->setQuery("select * from #__users where id = ".(int) $obj->owner_id);
      $rows = $db->loadObjectList();
      $row = $rows[0];
      $submitter = ($ad_name_or_user == 'name') ? $row->name : $row->username;

      if($ad_js || $ad_cb) {
        $savatar = ($ad_js || $ad_cb) ? getUserAvatar($row->id) : getGravatar($row->email);
      } else {
        $savatar = ($ad_kunena) ? getUserAvatar($row->id) : getGravatar($row->email);
      }
      echo '<div class="center1">'.$savatar.'</div>';
    ?>
<strong><?php echo $submitter;?></strong><br />
<?php
$db->setQuery("select count(id) from #__datsogallery where owner_id = ".(int) $obj->owner_id);
$images = $db->loadResult();
echo JText::_('COM_DATSOGALLERY_SUBMITTED_IMG').' ';
?>
<a href='<?php echo JRoute::_("index.php?option=com_datsogallery&amp;task=owner&amp;op=".$row->id
.$itemid);?>'><strong><?php echo $images;?></strong></a> <?php echo JText::_('COM_DATSOGALLERY_SUBMITTED_IMAGES'); ?>
</td>
<?php
}
?>
  </tr>
</table>
<?php endif; ?>

<?php if($ad_shop) : ?>
<div class="dg_head_background"<?php echo checkBasket(); ?>><?php echo JText::_('COM_DATSOGALLERY_BASKET_ITEMS');?> <span id="items"><?php countItems(); ?></span> <?php echo dgTip(JText::_('COM_DATSOGALLERY_CHECKOUT'), $image = 'basket_go.png', '', JRoute::_('index.php?option=com_datsogallery&amp;task=checkout'.$itemid), '', $link = 1); ?> <div style="float: right"><?php echo JText::_('COM_DATSOGALLERY_BASKET_SUBTOTAL'); ?>: <?php echo currencySymbol($ad_pp_currency); ?><span id="subtotal"><?php echo subTotal(); ?></span></div></div>
<div id="dg_body_background_basket" class="dg_slider"<?php echo checkBasket(); ?>>
  <?php echo showBasket(); ?>
</div>
<?php
endif;
  }
  if ($ad_showcomment) {
    $cmtip = getIpAddress();
    $and = (array_intersect($is_admin, $userGroups)) ? '':' AND published = 1';
    $db->setQuery('SELECT * FROM #__datsogallery_comments WHERE cmtpic = '.$obj->id.$and.' ORDER BY cmtid ASC');
    $rows = $db->loadObjectList();
    echo "<div class=\"dg_head_background\">".JText::_('COM_DATSOGALLERY_COMMENT1').": ".count($rows)."</div>";
    if (count($rows) != 0) {
      $appruve = '<a class="btn-unapprove">'.JText::_('COM_DATSOGALLERY_UNAPPROVE').'</a>';
      $unappruve = '<a class="btn-approve">'.JText::_('COM_DATSOGALLERY_APPROVE').'</a>';
?>
<div class="comments_core">
    <?php
      echo '<ul id="list">';
      foreach ($rows as $row) {
        $db->setQuery("SELECT id FROM #__users WHERE email = '".$row->cmtmail."'");
        $user_id = $db->loadResult();

        if($ad_js || $ad_cb) {
        $avatar = ($user_id) ? getUserAvatar($user_id) : getUserAvatar(0);
        } elseif($ad_kunena) {
        $avatar = ($user_id) ? getUserAvatar($user_id) : getUserAvatar(0);
        } else {
        $avatar = ($user_id) ? getUserAvatar($user_id) : getUserAvatar(0);
        }
        $action = ($row->published != 0) ? $appruve : $unappruve;
        if (array_intersect($is_admin, $userGroups) || $cmtip == $row->cmtip) {
          echo "<script type=\"text/javascript\">
            datso(document).ready(function(){
             datso('#".$row->cmtid."-edit').editInPlace({
              bg_img_over:  '".$bg_img_over." url('+ siteurl +'/components/com_datsogallery/images/".$dg_theme."/edit.png) no-repeat bottom right',
              bg_img_out:   '".$bg_img_out."',
              show_buttons: true,
              save_button:  '<button class=\"inplace_save dg_btn\"><span><span>".JText::_('COM_DATSOGALLERY_SAVE')."</span></span></button>',
              cancel_button:'<button class=\"inplace_cancel dg_btn\"><span><span>".JText::_('COM_DATSOGALLERY_CANCEL')."</span></span></button>',
              field_type:	'textarea',
              url:          'index.php?option=com_datsogallery&task=editcomment&format=raw',
              update_value: 'cmttext',
              element_id:   'cmtid',
              field_type:   'textarea',
              saving_image: '".JURI::base(true)."/components/com_datsogallery/images/".$dg_theme."/loading.gif',
              success: function(response){ datso('#".$row->cmtid."-edit').html(response); }
              });
            });
          </script>";
        }
        echo "<li id=\"".$row->cmtid."\" class=\"pane\">\n";
        echo "<div class=\"imgblock\">";
        echo $avatar;
        echo "</div>";
        echo '<div style="display:block;min-height:60px;margin-left:70px;padding-bottom:20px;cursor: text;" id="'.$row->cmtid.'-edit">'.nl2br($row->cmttext).'</div>';
        echo '<div class="date">'.sprintf(JText::_('COM_DATSOGALLERY_ON'), $row->cmtname, strftime($ad_datef, (int) $row->cmtdate)).'</div>';
        if (array_intersect($is_admin, $userGroups)) {
          echo '<div class="control"><a class="btn-delete">'.JText::_('COM_DATSOGALLERY_DELETE').'</a> | '.$action.' | <a class="btn-spam">Spam</a></div>';
        }
        echo "</li>\n";
      }
      echo "</ul>\n";
    }
    else {
      echo '<div id="nocom"></div>';
    }
    if ($ad_anoncomment || $user->id) {
      if($ad_js || $ad_cb) {
          $avatar = ($user->id) ? getUserAvatar($user->id) : getUserAvatar(0);
          } elseif($ad_kunena) {
          $avatar = ($user->id) ? getUserAvatar($user->id) : getUserAvatar(0);
          } else {
          $avatar = getGravatar($user->email);
          }
          $brd = ($ad_showsend2friend && $user->id) ? '' : ' style="border-bottom: 1px solid #DCDCDC"';
    ?>
<div class="dg_body_background_comment"<?php echo $brd; ?>>
  <div class="imgblock" style="position:relative;float:right"><?php echo $avatar;?></div>
  <p>
    <label for="cmtname"><?php echo JText::_('COM_DATSOGALLERY_YOUR_NAME');?></label>
    <?php if ($user->id) {?>
    <input class="inputbox" id="cmtname" value="<?php echo $user->name;?>" size="40" disabled="disabled">
    <?php }else {?>
    <input class="inputbox" id="cmtname" value="" size="40">
    <?php }?>
  </p>
  <p>
    <label for="cmtmail"><?php echo JText::_('COM_DATSOGALLERY_YOUR_MAIL');?></label>
    <?php if ($user->id) {?>
    <input class="inputbox" id="cmtmail" value="<?php echo $user->email;?>" size="40" disabled="disabled">
    <?php }else {?>
    <input class="inputbox" id="cmtmail" value="" size="40">
    <?php }?>
  </p>
  <p>
    <label for="cmttext"><?php echo JText::_('COM_DATSOGALLERY_YOUR_COMMENT');?></label>
    <textarea id="cmttext" style="width: 100%" rows="4" cols="10" class="resizable"></textarea>
  </p>
  <p>
    <?php
      $refreshimage = '<img src="'.JURI::base().'components/com_datsogallery/images/'.$dg_theme.'/refresh.png" alt="Refresh Security Image" align="absmiddle" />';
      $out = '';
      $out .= '<img src="'.JURI::base().'index.php?option=com_datsogallery&task=captcha&format=raw" id="dgcaptcha" alt="Security Image" align="absmiddle" />&nbsp;&nbsp;';
      $out .= '<input class="inputbox" type="text" style="font-weight:bold" name="captcha" value="" size="4" id="dgcaptchaval" /><button class="dg_btn" id="btn-submit"><span><span>'.JText::_('COM_DATSOGALLERY_SEND').'</span></span></button>';
      $out .= '<a id="rfc" style="cursor: pointer" onclick="dgCaptcha(\''.JURI::base().'index.php?option=com_datsogallery&task=captcha&format=raw\'); return false;" title="Refresh">'.$refreshimage.'</a>';
      echo $out;
    ?>
  </p>
  <input type="hidden" id="cmtpic" value="<?php echo $obj->id;?>" />
</div>
    <?php
    }
    else {
      $link2login = '<a href="'.$url.'">'.JText::_('COM_DATSOGALLERY_COMMENTS_MESSAGE_LINK_TO_LOGIN').'</a>';
      echo '<div class="dg_body_background_message">'.sprintf(JText::_('COM_DATSOGALLERY_COMMENTS_MESSAGE'), $link2login).'</div>';
    }
  ?>
</div>
  <?php
  }
  if ($ad_showsend2friend) {
    if ($user->username) {
    ?>
<script type="text/javascript">
function validatesend2friend() {
  if ((document.send2friend.send2friendname.value == '') || (document.send2friend.send2friendemail.value == '')) {
    alert('<?php echo JText::_('COM_DATSOGALLERY_ENTER_NAME_EMAIL');?>');
    return false;
  } else {
    document.send2friend.action = 'index.php';
    document.send2friend.submit();
  }
}
</script>
<form method="post" name="send2friend">
  <div class="dg_head_background"><?php echo JText::_('COM_DATSOGALLERY_SEND_FRIEND');?></div>
  <div class="dg_body_background_recomend">
    <label for="send2frienduename"><?php echo JText::_('COM_DATSOGALLERY_YOUR_NAME');?></label>
    <input type="text" name="send2frienduename" size="40" class="inputbox" value="<?php echo $user->name;?>" disabled="disabled" />
    <br />
    <label for="send2frienduemail"><?php echo JText::_('COM_DATSOGALLERY_YOUR_MAIL');?></label>
    <input type="text" name="send2frienduemail" size="40" class="inputbox" value="<?php echo $user->email;?>" disabled="disabled" />
    <br />
    <label for="send2friendname"><?php echo JText::_('COM_DATSOGALLERY_FRIENDS_NAME');?></label>
    <input type="text" name="send2friendname" size="40" class="inputbox" />
    <br />
    <label for="send2friendemail"><?php echo JText::_('COM_DATSOGALLERY_FRIENDS_MAIL');?></label>
    <input type="text" name="send2friendemail" size="40" class="inputbox" />
    <br />
    <br />
    <button class="dg_btn" name="send" onclick="validatesend2friend();return false;"><span><span><?php echo JText::_('COM_DATSOGALLERY_SEND');?></span></span></button>
    <input type="hidden" name="option" value="com_datsogallery" />
    <input type="hidden" name="task" value="send2friend" />
    <input type="hidden" name="catid" value="<?php echo $obj->catid;?>" />
    <input type="hidden" name="id" value="<?php echo $obj->id;?>" />
    <input type="hidden" name="from2friendname" value="<?php echo $user->name;?>" />
    <input type="hidden" name="from2friendemail" value="<?php echo $user->email;?>" />
  </div>
</form>
<?php
  }
}
?>

