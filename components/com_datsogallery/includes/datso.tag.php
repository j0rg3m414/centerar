<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pagination');
$limit = $ad_toplist;
$limitstart = JRequest::getVar('limitstart', 0, 'int');
$page_nav_links = '';
GalleryHeader();
if (!$ad_allow_tags) {
  $app->redirect(JRoute::_('index.php?option=com_datsogallery'.$itemid, false),
  JText::_('COM_DATSOGALLERY_TAGS_DISABLED'));
}
$tag = JRequest::getVar('tagval', '', 'string');
if (!empty($tag)) {
  $db->setQuery('UPDATE #__datsogallery_tags'
  .' SET count = count + 1'
  .' WHERE tag = '.$db->Quote($tag)
  );
  $db->query() or die($db->stderr());
  $searchEscaped = $db->Quote('%'.$db->getEscaped($tag, true).'%', false);
  $where[] = '( t.tag LIKE '.$searchEscaped.' )';
}

$db->setQuery('SELECT count(*)'
  .' FROM #__datsogallery AS a'
  .' JOIN #__datsogallery_tags AS t'
  .' ON t.image_id = a.id'
  .(count($where) ? ' WHERE '.implode(' AND ', $where) : '')
  .' AND a.published = 1'
  .' AND a.approved = 1'
  );

$total = $db->loadResult();
$pageNav = new JPagination($total, $limitstart, $limit);
$page_nav_links = $pageNav->getPagesLinks();
$where[] = 'a.catid = cc.cid';
$where[] = 'a.id = t.image_id';
$where[] = 'a.published = 1';
$where[] = 'a.approved = 1';
$where[] = 'cc.approved = 1';
$where[] = 'cc.published = 1';
$where[] = 'cc.access IN ('.$groups.')';
$images = count($where) ? ' WHERE '.implode(' AND ', $where) : '';
$db->setQuery('SELECT a.*'
.' FROM #__datsogallery AS a,'
.' #__datsogallery_catg AS cc,'
.' #__datsogallery_tags AS t'
.$images
.' GROUP BY a.id'
.' ORDER BY t.tag DESC'
.' LIMIT '.$pageNav->limitstart.', '.$pageNav->limit
);
if ($db->getErrorNum()) {
  echo $db->stderr();
  return false;
}
$title = JText::sprintf('COM_DATSOGALLERY_TAG_RESULTS',$tag);
$rows = $db->loadObjectList();
$document->setTitle($title);
?>
<div class="datso_pgn">
<?php echo $page_nav_links;?>
</div>
<div style="clear:both"></div>
<div class="dg_head_background"><?php echo $title;?></div>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="dg_body_background">
<?php
 $rowcounter = 0;
 if (count($rows) > 0) {
 foreach ($rows as $row) {
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
    </span>
            <?php
             echo '</div>';
           }
           echo '</td>';
           $rowcounter++;
         }
         if ($rowcounter % $ad_cp <> 0) {
           for ($i = 1; $i <= ($ad_cp - ($rowcounter % $ad_cp)); $i++) {
             echo '<td width="'.$cw.'" class="dg_body_background_td"> </td>';
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