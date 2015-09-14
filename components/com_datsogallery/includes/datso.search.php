<?php
  defined('_JEXEC') or die('Restricted access');
  jimport('joomla.html.pagination');
  $limit = $ad_toplist;
  $limitstart = JRequest::getVar('limitstart', 0, 'int');
  $page_nav_links = '';
  GalleryHeader();
  if (!$ad_search) {
    $app->redirect(JRoute::_('index.php?option=com_datsogallery'.$itemid, false), JText::_('COM_DATSOGALLERY_SEARCH_DISABLED'));
  }
  $sstring = $app->getUserStateFromRequest('datsogallery.sstring', 'sstring', '', 'string');
  if ($sstring) {
    $searchEscaped = $db->Quote('%'.$db->getEscaped($sstring, true).'%', false);
    $where[] = '( imgtitle LIKE '.$searchEscaped.' OR imgtext LIKE '.$searchEscaped.' OR imgauthor LIKE '.$searchEscaped.' )';
  }
  $db->setQuery('SELECT count(*) FROM #__datsogallery AS a '.(count($where) ? ' WHERE '.implode(' AND ', $where) : ''));
  $total = $db->loadResult();
  $pageNav = new JPagination($total, $limitstart, $limit);
  $page_nav_links = $pageNav->getPagesLinks('index.php?option=com_datsogallery&task=special&sorting=find'.$itemid);
  $where[] = 'a.catid = cc.cid';
  $where[] = 'a.published = 1';
  $where[] = 'a.approved = 1';
  $where[] = 'cc.approved = 1';
  $where[] = 'cc.published = 1';
  $where[] = 'cc.access IN ('.$groups.')';
  $images = count($where) ? ' WHERE '.implode(' AND ', $where) : '';
  $db->setQuery('SELECT a.*'
  .' FROM #__datsogallery AS a,'
  .' #__datsogallery_catg AS cc'
  . $images
  .' ORDER BY a.id DESC'
  .' LIMIT '.$pageNav->limitstart.', '.$pageNav->limit
  );
  if ($db->getErrorNum()) {
    echo $db->stderr();
    return false;
  }

  $pw_title = JText::_('COM_DATSOGALLERY_SEARCH_RESULTS').' '.$sstring;
  $rows = $db->loadObjectList();
  $document->setTitle($pw_title);
?>

<div class="datso_pgn">
<?php echo $page_nav_links; ?>
</div>
<div style="clear:both"></div>
<script type="text/javascript">
    datso(function () {
        datso(".favorites").click(function () {
            var id = datso(this).attr("id");
            var dataString = 'id=' + id;
            var parent = datso(this).parent().parent().parent().parent();

            datso(this).css({opacity: '1.0'}, 50);
            datso.ajax({
                type: "post",
                url: '<?php echo JURI::base(); ?>index2.php?option=com_datsogallery&task=favorites',
                data: dataString,
                cache: false,

                success: function () {
                    parent.animate({opacity: '0.4'}, 300);
                }
            });
            return false;
        });
    });
</script>
<div class="dg_head_background"><?php echo $pw_title; ?></div>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="dg_body_background">
<?php
$rowcounter = 0;
if (count($rows) > 0) {
      foreach ($rows as $row) {
      echo "<script type=\"text/javascript\">
        datso(document).ready(function () {
        datso('span.remove_favorite').click(function () {
            datso(this).removeClass('remove_favorite');
        });

    });
    </script>";
        if ($ad_ncsc)
          $cw = 100 / $ad_cp."%";
        if ($rowcounter % $ad_cp == 0)
        echo '<tr>';
        echo '<td width="'.$cw.'" class="dg_body_background_td" align="center" valign="top">';
        $ld = "<a href='".JRoute::_("index.php?option=com_datsogallery&amp;task=image&amp;catid=".$row->catid."&amp;id=".$row->id.$itemid)."' title='".$row->imgtitle."'>";
        echo $ld;
        echo "<img src=\"".resize($row->imgoriginalname, $ad_thumbwidth, $ad_thumbheight, $ad_crop, $ad_cropratio, 0, $row->catid)."\" ".get_width_height($row->imgoriginalname, $ad_thumbwidth, $ad_thumbheight, $row->catid, $ad_cropratio)." class=\"dgimg\" title=\"".$row->imgtitle."\" alt=\"".$row->imgtitle."\" /></a>";
        if ($ad_showdetail){
        echo "<div style=\"width:".$ad_thumbwidth."px;margin:10px auto 0 auto;text-align:left;text-transform: uppercase;\">";
        echo ($ad_showimgtitle) ? $ld.'<span>'.$row->imgtitle.'</span></a><br />':'';
        ?>
    <span>
      <?php
        echo ($ad_showfimgdate) ? '<strong>'.JText::_('COM_DATSOGALLERY_DATE_ADD').'</strong>: '.strftime($ad_datef,$row->imgdate).'<br />':'';
        echo ($task == 'purchases') ? '<strong>'.JText::_('COM_DATSOGALLERY_DATE_PURCHASED').'</strong>: '.strftime($ad_datef, strtotime($row->date)).'<br />':'';
        echo ($task == 'purchases') ? '<strong>'.PRICE.'</strong>: '.currencySymbol($ad_pp_currency).$row->imgprice.'<br />':'';
        echo ($ad_showimgcounter) ? '<strong>'.JText::_('COM_DATSOGALLERY_HITS').'</strong>: '.$row->imgcounter.'<br />':'';
        echo ($ad_showdownloads) ? '<strong>'.JText::_('COM_DATSOGALLERY_DOWNLOADS').'</strong>: '.$row->imgdownloaded.'<br />':'';
        echo ($ad_showrating) ? showVote ($row->id, $row->imgvotes, $row->imgvotesum) : '';
        if($ad_showcomments) {
          $db->setQuery('SELECT COUNT(cmtid) FROM #__datsogallery_comments WHERE cmtpic = '.$row->id.' AND published = 1');
          $comments = $db->loadResult();
          $comments = ($comments) ? $comments : JText::_('COM_DATSOGALLERY_NO');
          echo "<strong>".JText::_('COM_DATSOGALLERY_COMMENTS')."</strong>: $comments";
        }
        if($task == 'favorites'){
      ?>
      <div class="favbox" style="float:right">
        <span class="favorites" id="<?php echo $row->id; ?>">
            <span class="remove_favorite" title="<?php echo JText::_('COM_DATSOGALLERY_REMOVE_FAVORITE'); ?>"></span>
        </span>
      </div>
      <?php } ?>
    </span>
    <?php
        echo '</div>';
        }
        echo '</td>';
        $rowcounter++;
      }
      if ($rowcounter % $ad_cp <> 0) {
        for ($i = 1; $i <= ($ad_cp - ($rowcounter % $ad_cp)); $i++) {
          echo '<td width="'.$cw.'" class="dg_body_background_td" align="center" valign="top">&nbsp;</td>';
        }
      }
      echo '</tr>';
      }
         else {
           echo '<tr><td class="dg_body_background_td">'.JText::_('COM_DATSOGALLERY_NO_RESULTS').'</td></tr>';
         }
    ?>
</table>

<div class="datso_pgn">
<?php echo $page_nav_links; ?>
</div>
<?php
GalleryFooter();
?>