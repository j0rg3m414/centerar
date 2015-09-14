<?php
 defined('_JEXEC') or die('Restricted access');
 jimport('joomla.html.pagination');

 $limit = $ad_toplist;
 $limitstart = JRequest::getVar('limitstart', 0, 'int');
 $page_nav_links = '';
 GalleryHeader();
 if (!($user->id && $ad_favorite)) {
   $mainframe->redirect(JRoute::_('index.php?option=com_datsogallery'.$itemid, false));
 }
 $db->setQuery('SELECT count(*) FROM #__datsogallery_favorites WHERE user_id = '.$user->id);
 $total = $db->loadResult();
 $pageNav = new JPagination($total, $limitstart, $limit);
 $page_nav_links = $pageNav->getPagesLinks();
 $db->setQuery('SELECT a.*, f.image_id'
 .' FROM #__datsogallery AS a,'
 .' #__datsogallery_catg AS c,'
 .' #__datsogallery_favorites AS f'
 .' WHERE a.catid = c.cid'
 .' AND a.published = 1'
 .' AND a.id = f.image_id'
 .' AND f.user_id = '.$user->id
 .' AND a.approved = 1'
 .' AND c.approved = 1'
 .' AND c.published = 1'
 .' AND c.access IN ('.$groups.')'
 .' GROUP BY a.id'
 .' ORDER BY f.id DESC'
 .' LIMIT '.$pageNav->limitstart.', '.$pageNav->limit);

 $pw_title = JText::_('COM_DATSOGALLERY_MY_FAVORITES');
 $rows = $db->loadObjectList();
 $document->setTitle($pw_title);
?>

<div class="datso_pgn">
<?php echo $page_nav_links;?>
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
                url: '<?php echo JURI::base();?>index.php?option=com_datsogallery&task=addtofavorites&format=raw',
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
<div class="dg_head_background"><?php echo $pw_title;?></div>
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
   $ld = "<a href='".JRoute::_("index.php?option=com_datsogallery&amp;task=image&amp;catid=".$row->catid
   ."&amp;id=".$row->id.$itemid)."' title='".$row->imgtitle."'>";
   echo $ld;
   echo "<img src=\"".resize($row->imgoriginalname, $ad_thumbwidth, $ad_thumbheight, $ad_crop, $ad_cropratio, 0, $row->catid)
   ."\" ".get_width_height($row->imgoriginalname, $ad_thumbwidth, $ad_thumbheight, $row->catid, $ad_cropratio)
   ." class=\"dgimg\" title=\"".$row->imgtitle."\" alt=\"".$row->imgtitle."\" /></a>";
   if ($ad_showdetail) {
     echo "<div style=\"width:".$ad_thumbwidth."px;margin:10px auto 0 auto;text-align:left;text-transform: uppercase;\">";
     echo ($ad_showimgtitle) ? $ld.'<span>'.$row->imgtitle.'</span></a><br />' : '';
    ?>
    <span>
              <?php
               echo ($ad_showfimgdate) ? '<strong>'.JText::_('COM_DATSOGALLERY_DATE_ADD').'</strong>: '.strftime($ad_datef, $row->imgdate).'<br />' : '';
               echo ($task == 'purchases') ? '<strong>'.JText::_('COM_DATSOGALLERY_DATE_PURCHASED').'</strong>: '.strftime($ad_datef, strtotime($row->date)).'<br />' : '';
               echo ($task == 'purchases') ? '<strong>'.JText::_('COM_DATSOGALLERY_PRICE').'</strong>: '.currencySymbol($ad_pp_currency).$row->imgprice.'<br />' : '';
               echo ($ad_showimgcounter) ? '<strong>'.JText::_('COM_DATSOGALLERY_HITS').'</strong>: '.$row->imgcounter.'<br />' : '';
               echo ($ad_showdownloads) ? '<strong>'.JText::_('COM_DATSOGALLERY_DOWNLOADS').'</strong>: '.$row->imgdownloaded.'<br />' : '';
               echo ($ad_showrating) ? showVote ($row->id, $row->imgvotes, $row->imgvotesum) : '';
               if ($ad_showcomments) {
                 $db->setQuery('SELECT COUNT(cmtid) FROM #__datsogallery_comments WHERE cmtpic = '.$row->id.' AND published = 1');
                 $comments = $db->loadResult();
                 $comments = ($comments) ? $comments : JText::_('COM_DATSOGALLERY_NO');
                 echo "<strong>".JText::_('COM_DATSOGALLERY_COMMENTS')."</strong>: $comments";
               }

                ?>
      <div class="favbox" style="float:right">
        <span class="favorites" id="<?php echo $row->id;?>">
            <span class="dgtip" id="<?php echo JText::_('COM_DATSOGALLERY_REMOVE_FAVORITE');?>"><span class="remove_favorite"></span></span>
        </span>
      </div>
    </span>
            <?php
             echo '</div>';
           }
           echo '</td>';
           $rowcounter++;
         }
         if ($rowcounter % $ad_cp <> 0) {
           for ($i = 1; $i <= ($ad_cp - ($rowcounter % $ad_cp)); $i++) {
             echo '<td width="'.$cw.'" class="dg_body_background_td" valign="top"> </td>';
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
<?php echo $page_nav_links;?>
</div>
<?php
GalleryFooter();
?>