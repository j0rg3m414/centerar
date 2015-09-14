<?php
 defined('_JEXEC') or die('Restricted access');
 function GalleryHeader() {
   require (JPATH_COMPONENT_ADMINISTRATOR . DS . 'config.datsogallery.php');
   $app = JFactory::getApplication();
   $document = JFactory::getDocument();
   $menu = $app->getMenu();
   $active = $menu->getActive();
   $params = new JRegistry();
   $db = JFactory::getDBO();
   $user = JFactory::getUser();
   $id = JRequest::getVar('id', 0, 'get', 'int');
   $catid = JRequest::getVar('catid', 0, 'get', 'int');
   $task = JRequest::getCmd('task');
   $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
   $itemid = isset ($ids[0]) ? '&Itemid=' . $ids[0]->id : '';
   $title = null;
   echo "<div id=\"datso\">";
   echo ($ad_na) ? '<div style="padding-top:20px" name="dgtop" id="dgtop"></div>' : '';
   echo "<div class=\"dg_header\" style=\"display:block;height:26px\">";
   if ($active) {
     $params->loadJSON($active->params);
   }
   if ($active) {
     $params->def('page_heading', $params->get('page_title', $active->title));
   }
   else {
     $params->def('page_heading', JText::_('COM_DATSOGALLERY_GALLERY'));
   }
   $title = $params->get('page_title', '');
   if (empty ($title)) {
     $title = $app->getCfg('sitename');
   }
   elseif ($app->getCfg('sitename_pagetitles', 0)) {
     $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
   }
   $document->setTitle($title);
   if ($params->get('menu-meta_description')) {
     $document->setDescription($params->get('menu-meta_description'));
   }
   if ($params->get('menu-meta_keywords')) {
     $document->setMetadata('keywords', $params->get('menu-meta_keywords'));
   }
   if ($params->get('robots')) {
     $document->setMetadata('robots', $params->get('robots'));
   }
   if ($ad_comtitle) {
?>
<div style="float:left;font-size:16px">
  <?php echo JText::_('COM_DATSOGALLERY_GALLERY'); ?>
</div>
<?php
    }
    echo (($ad_search) || ($ad_showpanel)) ? '<div style="float:right">' : '';
    if ($ad_search) {
?>
<div style="float:left">
  <form action="<?php echo JRoute::_('index.php?option=com_datsogallery&amp;task=search');?>" name="searchgalform" method="post">
    <input type="hidden" name="task" value="search" />
    <input class="inputbox" type="text" name="sstring" onblur="if(this.value=='') this.value='<?php echo JText::_('COM_DATSOGALLERY_SEARCH'); ?>';" onfocus="if(this.value=='<?php echo JText::_('COM_DATSOGALLERY_SEARCH'); ?>') this.value='';" value="<?php echo JText::_('COM_DATSOGALLERY_SEARCH'); ?>" />&nbsp;
   <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>
<?php
    }
    if ($ad_showpanel) {
      echo "<div style=\"float:left\">";
      echo "   <form action=\"../\">\n";
      echo "    <select class=\"inputbox\" onchange=\"window.open(this.options[this.selectedIndex].value,'_self')\">\n";
      echo "     <option value=\"\"> - ".JText::_('COM_DATSOGALLERY_CHOOSE_OPTION')." - </option>\n";
      if ($user->id && $ad_userpannel) {
        echo "   <option value=\"".JRoute::_("index.php?option=com_datsogallery&amp;task=userpanel".$itemid)."\">".JText::_('COM_DATSOGALLERY_USER_PANEL')."</option>\n";
      }
      if ($user->id && $ad_userpannel) {
        echo "   <option value=\"".JRoute::_("index.php?option=com_datsogallery&amp;task=showupload".$itemid)."\">".JText::_('COM_DATSOGALLERY_NEW_PICTURE')."</option>\n";
      }
      if ($user->id && $ad_favorite) {
        echo "   <option value=\"".JRoute::_("index.php?option=com_datsogallery&amp;task=favorites".$itemid)."\">".JText::_('COM_DATSOGALLERY_MY_FAVORITES')."</option>\n";
      }
      if ($user->id && $ad_shop) {
        echo "   <option value=\"".JRoute::_("index.php?option=com_datsogallery&amp;task=purchases".$itemid)."\">".JText::_('COM_DATSOGALLERY_MY_PURCHASES')."</option>\n";
      }
      if ($ad_special) {
        echo "   <option value=\"".JRoute::_("index.php?option=com_datsogallery&amp;task=popular".$itemid)."\">".JText::_('COM_DATSOGALLERY_MOST_VIEWED')."</option>\n";
      }
      if ($ad_rating) {
        echo "   <option value=\"".JRoute::_("index.php?option=com_datsogallery&amp;task=rating".$itemid)."\">".JText::_('COM_DATSOGALLERY_TOP_RATED')."</option>\n";
      }
      if($most_downloaded_menu){
        echo "   <option value=\"".JRoute::_("index.php?option=com_datsogallery&amp;task=downloads".$itemid)."\">".JText::_('COM_DATSOGALLERY_MOST_DOWNLOADED')."</option>\n";
      }
      if ($ad_lastadd) {
        echo "   <option value=\"".JRoute::_("index.php?option=com_datsogallery&amp;task=lastadded".$itemid)."\">".JText::_('COM_DATSOGALLERY_LAST_ADDED')."</option>\n";
      }
      if ($ad_lastcomment) {
        echo "   <option value=\"".JRoute::_("index.php?option=com_datsogallery&amp;task=lastcommented".$itemid)."\">".JText::_('COM_DATSOGALLERY_LAST_COMMENTED')."</option>\n";
      }
      echo "    </select>\n";
      echo JHTML::_( 'form.token' );
      echo "   </form>\n";
      echo "  </div>\n";
    }
    echo (($ad_search) || ($ad_showpanel)) ? '</div>' : '';
    echo "</div>";
?>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
 <tr>
  <td>
   <?php
      }

      function GalleryFooter() {
        require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
   ?>
  </td>
 </tr>
   <?php
      if ($ad_powered) {
   ?>
 <tr>
  <td class="dg_footer"><?php echo $ad_cr;?></td>
 </tr>
   <?php
      }
      else {
        $flink = array('<br />', '<a href="http://www.datso.fr">Andrey Datso</a>');
        $rlink = array(' ', 'Datso.fr');
        $ad_cr = str_replace($flink, $rlink, $ad_cr);
   ?>
 <tr>
  <td style="display:none"><?php echo $ad_cr;?></td>
 </tr>
 <?php } ?>
</table>
</div>
<?php
   return;
 }
     function viewCategory(){
        $app = JFactory::getApplication('site');
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $document = JFactory::getDocument();
        $filter_order = $app->getUserStateFromRequest('com_datsogallery.filter_order', 'filter_order', 'a.ordering', 'cmd');
        $filter_order_Dir = $app->getUserStateFromRequest('com_datsogallery.filter_order_Dir', 'filter_order_Dir', '', 'word');
        $catid = JRequest::getVar('catid', 0, '', 'int');
        $menu = JSite::getMenu();
        $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
        $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
        $is_editor = (strtolower($user->usertype) == 'editor'
        || strtolower($user->usertype) == 'administrator'
        || strtolower($user->usertype) == 'super administrator'
        );
        GalleryHeader();
        $db->setQuery("select count(*) from #__datsogallery_catg where cid = ".$catid." AND access IN (".$groups.")");
        $is_allowed = $db->loadResult();
        if (!$is_allowed) {
          $app->redirect(JRoute::_('index.php?option=com_datsogallery'.$itemid, false), JText::_('COM_DATSOGALLERY_NOT_ACCESS_THIS_CATEGORY'), 'notice');
        }
        //echo dgCategories($catid);
        require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
        if ($ad_sbcat) {
          $ssa = (!$ad_slideshow_auto) ? ',onOpen:function(currentImage){Shadowbox.play();Shadowbox.pause();}':'';
          $document->addStyleSheet(JURI::base(true).'/components/com_datsogallery/libraries/shadowbox/shadowbox.css');
          $document->addScript(JURI::base(true).'/components/com_datsogallery/libraries/shadowbox/shadowbox.js');
          $sbinit = 'Shadowbox.init({slideshowDelay:'.$ad_slideshow_delay.$ssa.'});';
          $document->addScriptDeclaration($sbinit);
        }
        $db->setQuery("SELECT COUNT(*)"
        . " FROM #__datsogallery AS a"
        . " LEFT JOIN #__datsogallery_catg AS c"
        . " ON c.cid = a.catid"
        . " WHERE a.published = 1"
        . " AND a.catid = ".$catid
        . " AND a.approved = 1"
        . " AND c.access IN (".$groups.")"
        );
        $count = $db->loadResult();


        if (!in_array($filter_order, array('a.imgcounter', 'a.imgdownloaded', 'a.imgtitle', 'a.imgdate', 'a.ordering'))) {
        $filter_order = 'a.ordering';
        }
        if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
          $filter_order_Dir = $ad_sortby;
        }
        $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

        if ($ad_picincat && $count > 0) {
            echo JText::sprintf('COM_DATSOGALLERY_CATEGORY_IMAGES', $count);
        }
        ?>
        <form method="post" id="adminForm">
        <?php
        $query = "SELECT count(*) AS count"
        . " FROM #__datsogallery"
        . " WHERE catid = ".$catid
        . " AND published = 1"
        . " AND approved = 1"
        ;
		$db->setQuery($query);
		$row = $db->LoadObject();
		$total = $row->count;

        jimport('joomla.html.pagination');
        $limit        = JRequest::getVar( 'limit', $ad_perpage, '', 'int' );
        $limitstart    = JRequest::getVar( 'limitstart', 0, '', 'int' );
        $pagination = new JPagination( $total, $limitstart, $limit );
        if ($count > $ad_perpage) {
        $page_nav_links = $pagination->getPagesLinks();
        ?>
        <div class="datso_pgn"><?php echo $page_nav_links; ?></div>
        <div style="clear:both"></div>
        <?php
        }

        $db->setQuery("SELECT * FROM #__datsogallery_catg WHERE cid = ".$catid);
        $rows = $db->loadObjectList();
        $catname = $rows[0]->name;
        if ($show_grid):
        echo '<div class="dg_head_background">'.str_replace('Joomla.tableOrdering','tableOrdering',
        JHTML::_('grid.sort', $catname.' ', 'a.imgtitle', $filter_order_Dir, $filter_order ));
        echo '<span class="grid_txt"><div class="grid_border">'.str_replace('Joomla.tableOrdering','tableOrdering',
        JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_ORDER').' ', 'a.ordering', $filter_order_Dir, $filter_order )).'</div>';
        echo '<div class="grid_border">'.str_replace('Joomla.tableOrdering','tableOrdering',
        JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_DATE_ADD').' ', 'a.imgdate', $filter_order_Dir, $filter_order )).'</div>';
        echo '<div class="grid_border">'.str_replace('Joomla.tableOrdering','tableOrdering',
        JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_HITS').' ', 'a.imgcounter', $filter_order_Dir, $filter_order )).'</div>';
        echo '<div class="grid_border">'.str_replace('Joomla.tableOrdering','tableOrdering',
        JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_DOWNLOADS').' ', 'a.imgdownloaded', $filter_order_Dir, $filter_order )).'</div>';
        echo '</span></div>';
        else:
        echo '<div class="dg_head_background">'.$catname.'</div>';
        endif;
        echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" class=\"dg_body_background\">\n";
        if($count > $ad_perpage){
          $addspace = ' - ';
          } else {
          $addspace = '';
          }
          $pages = $pagination->getPagesCounter();
        $document->setTitle($catname.$addspace.$pages);
        if ($ad_metagen) {
          if ($rows[0]->description) {
            $document->setTitle($catname.$addspace.$pages);
            $document->setDescription(limit_words($rows[0]->description, 25));
            $document->setMetadata('keywords', metaGen($rows[0]->description));
          }
        }
        $query = 'SELECT a.*'
        . ' FROM #__datsogallery AS a'
        . ' LEFT JOIN #__datsogallery_catg AS c'
        . ' ON c.cid = a.catid'
        . ' WHERE a.published = 1'
        . ' AND a.catid = '.$catid
        . ' AND a.approved = 1'
        . ' AND c.access IN ('.$groups.')'
        . $orderby
        ;
        $db->setQuery($query, $pagination->limitstart, $pagination->limit);
        $rows = $db->loadObjectList();
        $rowcounter = 0;
        if (count($rows) > 0) {
        foreach ($rows as $row1) {
          if ($ad_ncsc)
            $cw = 100 / $ad_cp."%";
          if ($rowcounter % $ad_cp == 0)
          echo " <tr>\n";
          echo "  <td width=\"".$cw."\" class=\"dg_body_background_td\" align=\"center\" valign=\"top\">\n";
          $tle = jsspecialchars($row1->imgtitle);
          if ($ad_showdetail)
          $picdate = strftime($ad_datef, $row1->imgdate);
          $na = ($ad_na) ? '#dgtop':'';
          $ld = "<a href='".JRoute::_("index.php?option=com_datsogallery&task=image&catid=".$catid."&id=".$row1->id.$itemid).$na."' title='".$tle."'>";
          if ($ad_sbcat){
          echo "   <a rel='shadowbox[screenshots];player=img' href='".JURI::root()."index.php?option=com_datsogallery&task=sbox&catid=".$catid."&id=".$row1->id."&format=raw' title='".$tle."'>";
          } else {
          echo $ld;
          }
          echo "   <img src=\"".resize($row1->imgoriginalname, $ad_thumbwidth, $ad_thumbheight, $ad_crop, $ad_cropratio, 0, $row1->catid)."\" ".get_width_height($row1->imgoriginalname, $ad_thumbwidth, $ad_thumbheight, $catid, $ad_cropratio)." class=\"dgimg\" title=\"".$tle."\" alt=\"".$tle."\" /></a>";
          if ($ad_showdetail){
          echo "   <div style=\"width:".$ad_thumbwidth."px;margin:10px auto 0 auto;text-align:left;text-transform: uppercase;\">";
          echo ($ad_showimgtitle) ? $ld.'<span>'.$row1->imgtitle.'</span></a><br />':'';
          echo "   <span>";
          echo ($ad_showfimgdate) ? '<strong>'.JText::_('COM_DATSOGALLERY_DATE_ADD').'</strong>: '.$picdate.'<br />':'';
          echo ($ad_showimgcounter) ? '<strong>'.JText::_('COM_DATSOGALLERY_HITS').'</strong>: '.$row1->imgcounter.'<br />':'';
          echo ($ad_showdownloads) ? '<strong>'.JText::_('COM_DATSOGALLERY_DOWNLOADS').'</strong>: '.$row1->imgdownloaded.'<br />':'';
          echo ($ad_showrating) ? showVote ($row1->id, $row1->imgvotes, $row1->imgvotesum) : '';
          if ($ad_showcomments) {
            $and = ($is_editor) ? '' : ' AND published = 1';
            $db->setQuery('SELECT COUNT(cmtid) FROM #__datsogallery_comments WHERE cmtpic = '.$row1->id.$and);
            $comments = $db->loadResult();
            echo "<strong>".JText::_('COM_DATSOGALLERY_COMMENTS')."</strong>: $comments";
          }
            echo "   </span>";
            echo "   </div>";
            }
            echo "  </td>\n";
            $rowcounter++;
          }
          }
          else {
            echo "  <td width=\"".@$cw."\" class=\"dg_body_background_td\">".JText::_('COM_DATSOGALLERY_NO_RESULTS')."</td>\n";
          }
          if ($rowcounter % $ad_cp <> 0) {
            for ($i = 1; $i <= ($ad_cp - ($rowcounter % $ad_cp)); $i++) {
              echo "  <td width=\"".@$cw."\" class=\"dg_body_background_td\"> </td>\n";
            }
          }
          echo " </tr>\n";
          echo "</table>\n";
          if ($count > $ad_perpage) {
            $page_nav_links = $pagination->getPagesLinks();
          ?>
          <div class="datso_pgn"><?php echo $page_nav_links; ?></div>
      <?php } ?>
      <input type="hidden" name="filter_order" value="<?php echo $filter_order; ?>" />
      <input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir; ?>" />
      <?php echo JHTML::_( 'form.token' ); ?>
      </form>
      <?php
          echo dgCategories($catid);
          GalleryFooter();
    }

    function send2friend(){
      $app = JFactory::getApplication('site');
      $db = JFactory::getDBO();
      $send2friendname = JRequest::getVar('send2friendname', '', 'post', 'string');
      $send2friendemail = JRequest::getVar('send2friendemail', '', 'post', 'string');
      $from2friendname = JRequest::getVar('from2friendname', '', 'post', 'string');
      $from2friendemail = JRequest::getVar('from2friendemail', '', 'post', 'string');
      $id = JRequest::getVar('id', 0, 'post', 'int');
      $catid = JRequest::getVar('catid', 0, 'post', 'int');
      $menu = JSite::getMenu();
      $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
      $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
      $text = $from2friendname." (".$from2friendemail.")"." ".JText::_('COM_DATSOGALLERY_INVITE_VIEW_PIC')."\r\n";
      $link = JURI::base()."index.php?option=com_datsogallery&task=image&catid=".$catid."&id=".$id.$itemid;
      $text .= $link."\r\n";
      $subject = $app->getCfg('sitename').' - '.JText::_('COM_DATSOGALLERY_RECCOMEND_PIC_FROM_FREND');
      JUtility::sendMail($from2friendemail, $from2friendname, $send2friendemail, $subject, $text);
      $app->redirect(JRoute::_("index.php?option=com_datsogallery&task=image&catid=".$catid."&id=".$id.$itemid, false), JText::_('COM_DATSOGALLERY_MAIL_SENT'));
    }

     function createNewMemberAlbum() {
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $is_admin = array(7,8);
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $userGroups = JAccess::getGroupsByUser($user->id, true);
      $row = new DatsoCategories($db);
      if (!array_intersect($is_admin, $userGroups) && $user_categories) {
        $db->setQuery('SELECT cid'
        .' FROM #__datsogallery_catg'
        .' WHERE user_id = '.$user->id
        .' AND parent = '.$ad_category
        );
        $result = $db->loadResult();
        if (!$result){
          $row->name = $user->name;
          $row->description = JText::sprintf('COM_DATSOGALLERY_NEW_ALBUM_DESC', $user->name);
          $row->parent = $ad_category;
          $row->ordering = $row->getNextOrder('parent = ' . $row->parent);
          $row->user_id = $user->id;
          $row->approved = 1;
          $row->published = 1;
          jimport('joomla.utilities.date');
          $dtz  = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
          $date = new JDate($row->date);
          $date->setTimezone($dtz);
          $row->date = $date->toMySQL(true);
          if (!$row->check()) {
            JError::raiseError(500, $row->getError());
          }
          if (!$row->store()) {
            JError::raiseError(500, $row->getError());
          }
        }
      }
    }

     function showUpload() {
       $app = JFactory::getApplication('site');
       require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
       $db = &JFactory::getDBO();
       $user = JFactory::getUser();
       jimport('joomla.access.access');
       $userGroups = JAccess::getGroupsByUser($user->id, true);
       $document = JFactory::getDocument();
       $menu = JSite::getMenu();
       $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
       $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
       $is_admin = array(7,8);
       $document->setTitle(JText::_('COM_DATSOGALLERY_NEW_PICTURE'));
       if (JFolder::exists(JPATH_ROOT.DS.'tmp'.DS.$user->id)) {
         JFolder::delete(JPATH_ROOT.DS.'tmp'.DS.$user->id);
       }
       $document->addStyleSheet(JURI::root(true).'/components/com_datsogallery/libraries/plupload/css/plupload.queue.css');
       $document->addStyleSheet(JURI::base(true).'/components/com_datsogallery/libraries/shadowbox/shadowbox.css');
       $document->addScript(JURI::root(true).'/components/com_datsogallery/libraries/plupload/js/plupload.full.js');
       $document->addScript(JURI::root(true).'/components/com_datsogallery/libraries/plupload/js/jquery.plupload.queue.js');
       $document->addScript(JURI::base(true).'/components/com_datsogallery/libraries/shadowbox/shadowbox.js');
       $sbinit = 'Shadowbox.init();';
       $document->addScriptDeclaration($sbinit);
       createNewMemberAlbum();
       $db->setQuery('SELECT COUNT(id) FROM #__datsogallery WHERE owner_id = '.(int) $user->id);
       $count_pic = $db->loadResult();
       if(in_array(2,$userGroups)){
         $uplimit = $ad_acl_registered - $count_pic;
         if($count_pic >= $ad_acl_registered)
         $app->redirect(JRoute::_("index.php?option=com_datsogallery&amp;task=userpanel".$itemid, false),
         JText::_('COM_DATSOGALLERY_MAY_ADD_MAX_OFF').' '.$ad_acl_registered.' '.JText::_('COM_DATSOGALLERY_PICTURES'));
       } else if(in_array(3,$userGroups)){
         $uplimit = $ad_acl_author - $count_pic;
         if ($count_pic >= $ad_acl_author)
         $app->redirect(JRoute::_("index.php?option=com_datsogallery&amp;task=userpanel".$itemid, false),
         JText::_('COM_DATSOGALLERY_MAY_ADD_MAX_OFF').' '.$ad_acl_author.' '.JText::_('COM_DATSOGALLERY_PICTURES'));
       } else if(in_array(4,$userGroups)){
         $uplimit = $ad_acl_editor - $count_pic;
         if ($count_pic >= $ad_acl_editor)
         $app->redirect(JRoute::_("index.php?option=com_datsogallery&amp;task=userpanel".$itemid, false),
         JText::_('COM_DATSOGALLERY_MAY_ADD_MAX_OFF').' '.$ad_acl_editor.' '.JText::_('COM_DATSOGALLERY_PICTURES'));
       } else if(in_array(5,$userGroups)){
         $uplimit = $ad_acl_publisher - $count_pic;
         if ($count_pic >= $ad_acl_publisher)
         $app->redirect(JRoute::_("index.php?option=com_datsogallery&amp;task=userpanel".$itemid, false),
         JText::_('COM_DATSOGALLERY_MAY_ADD_MAX_OFF').' '.$ad_acl_publisher.' '.JText::_('COM_DATSOGALLERY_PICTURES'));
       } else if (array_intersect($is_admin, $userGroups)) {
         $uplimit = 0;
         $count_pic = false;
       } else {
         $app->redirect(JRoute::_("index.php?option=com_datsogallery&task=userpanel".$itemid, false), "Contact Administrator");
       }
       $format = '%s %s <br />%s';
       $finfo = sprintf($format, JText::_('COM_DATSOGALLERY_SHOWUPLOAD_5'), format_filesize($ad_maxfilesize), JText::_('COM_DATSOGALLERY_SHOWUPLOAD_7'));

       $document->addCustomTag("<script type=\"text/javascript\">var uplimit = ".$uplimit.";</script>");
       $flimit = '%s %s <span style="color:#CC0000">%s</span> %s';
       if (!array_intersect($is_admin, $userGroups)) {
         $add_files = sprintf($flimit, JText::_('COM_DATSOGALLERY_ADD_FILES_TO_UPLOAD'),
         JText::_('COM_DATSOGALLERY_ADD_FILES_LIMIT'), $uplimit, JText::_('COM_DATSOGALLERY_FILES'));
       } else {
         $add_files = JText::_('COM_DATSOGALLERY_ADD_FILES_TO_UPLOAD');
       }
       $resize = ($ad_max_wh) ? 'resize: { width: '.$ad_max_wu.', height: '.$ad_max_hu.', quality: 100 },':'';
       $msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
       $chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;
       if ($msie) {
         $runtimes = "runtimes: 'flash,silverlight',\n";
       }
       elseif ($chrome) {
         $runtimes = "runtimes: 'flash,gears,html5',\n";
       }
       else {
         $runtimes = "runtimes: 'flash,html5,silverlight',\n";
       }
       $extensions = ($ad_allow_zip) ? 'jpg,jpeg,gif,png,zip':'jpg,jpeg,gif,png';
?>
<style type="text/css">
    fieldset { border:none; width:100%;}
    label { display:block; margin:15px 0 5px;}
    input.error { background: #f8dbdb; border-color: #e77776; }
</style>

<script type="text/javascript">
    plupload.addI18n({
        'Select files': '<?php echo JText::_('COM_DATSOGALLERY_SELECT_FILES'); ?>',
        'Add files to the upload queue and click the start button.': '<div id="up_methods"><?php echo JText::_('COM_DATSOGALLERY_UPLOAD_STEP_ONE_FILES_TYPE');?></div><?php echo $add_files; ?>',
        'Filename': '<?php echo JText::_('COM_DATSOGALLERY_FILENAME'); ?>',
        'Status': '<?php echo JText::_('COM_DATSOGALLERY_STATUS'); ?>',
        'Size': '<?php echo JText::_('COM_DATSOGALLERY_FILESIZE'); ?>',
        'Add files': '<?php echo JText::_('COM_DATSOGALLERY_ADD_FILES'); ?>',
        'Add files.': '<?php echo JText::_('COM_DATSOGALLERY_ADD_FILES'); ?>.',
        'Stop current upload': '<?php echo JText::_('COM_DATSOGALLERY_STOP_UPLOAD'); ?>',
        'Start uploading queue': '<?php echo JText::_('COM_DATSOGALLERY_START_UPLOADING'); ?>',
        'Start upload': '<?php echo JText::_('COM_DATSOGALLERY_START_UPLOAD'); ?>',
        'Drag files here.': '<?php echo JText::_('COM_DATSOGALLERY_DRAG_FILES_HERE'); ?>',
        'N/A': '<?php echo JText::_('COM_DATSOGALLERY_NA'); ?>',
        'Uploaded %d/%d files': '<?php echo JText::_('COM_DATSOGALLERY_UPLOADED'); ?> %d/%d <?php echo JText::_('COM_DATSOGALLERY_FILES'); ?>'
    });

    datso(function () {
        uploader = datso("#stepone").pluploadQueue({
            <?php echo $runtimes; ?>
            url: '<?php echo JURI::root(); ?>index.php?option=com_datsogallery&task=uploading',
            flash_swf_url: '<?php echo JURI::root(true); ?>/components/com_datsogallery/libraries/plupload/js/plupload.flash.swf',
            silverlight_xap_url : '<?php echo JURI::root(true); ?>/components/com_datsogallery/libraries/plupload/js/plupload.silverlight.xap',
            max_file_size : '<?php echo bytes($ad_maxfilesize); ?>',
	     	chunk_size : '500kb',
		    unique_names : false,
            dragdrop : true,
            <?php echo $resize; ?>
            filters: [
            {title: '<?php echo JText::sprintf('COM_DATSOGALLERY_ALLOWED_EXTENSIONS',$extensions); ?>', extensions: '<?php echo $extensions; ?>'}
            ]
        });
        var uploader = datso('#stepone').pluploadQueue();
        <?php if (!array_intersect($is_admin, $userGroups)) { ?>
        uploader.bind('QueueChanged', function (up, files) {
            if (up.files.length > uplimit) {
                up.splice(0, 1);
            }
        });
        <?php } ?>

        uploader.bind('FileUploaded', function (up, file, res) {

            if (this.total.queued == 0) {
                datso('#stepone').delay(1000).fadeOut().slideUp(300);
                datso('#steptwo').delay(1400).fadeIn().slideDown(300);
            }
        });
    });

    function checkForm() {
        var form = document.upForm;
        if (form.catid.value == '0') {
            alert("<?php echo JText::_('COM_DATSOGALLERY_MUST_SELECT_CATEGORY');?>");
            return false;
        } else if (form.gentitle.value == '') {
            alert("<?php echo JText::_('COM_DATSOGALLERY_MUST_HAVE_TITLE');?>");
            return false;
        } else {
            datso('#catname').attr('disabled', false);
            document.upForm.action = 'index.php';
            document.upForm.submit();
        }
    }
</script>
<div class="dg_body_background_upload" style="min-height: 480px">
<form method="post" name="upForm">
      <div class="container">
        <fieldset id="stepone" class="plupload_header_title">
          <p><?php echo JText::_('COM_DATSOGALLERY_BROWSER_SUPPORT'); ?></p>
        </fieldset>

        <fieldset id="steptwo">
        <div class="plupload_header_title"><?php echo JText::_('COM_DATSOGALLERY_STEP_TWO'); ?></div>
        <div class="plupload_header_text"><?php echo JText::_('COM_DATSOGALLERY_STEP_TWO_TEXT'); ?></div>
            <span id="res">
            <?php
                echo '<label for="catid">'.JText::_('COM_DATSOGALLERY_SELECT_CATEGORY').' *</label>';
                $catid = $app->getUserStateFromRequest("catid.com_datsogallery", 'catid', 0, 'int');
                echo ShowDropDownCategoryList('', 'catid', 'id="catnames" onchange="userCat(this.value);" style="width:340px"');
                echo dgTip(JText::_('COM_DATSOGALLERY_ASSIGN_TO_TIP'));
              ?>
            </span>
            <div id="usercat">
            <label for="name"><?php echo JText::_('COM_DATSOGALLERY_CATEGORY_NAME'); ?> *</label>
            <input type="text" id="name" name="name" style="width:330px" /> <span id="ucattip"> <?php echo dgTip(JText::_('COM_DATSOGALLERY_CATEGORY_NAME_TIP'));?> </span><span id="nameerr" style="display:none"> <?php echo dgTip(JText::_('COM_DATSOGALLERY_CATEGORY_ALREADY_EXIST'), 'dg-exclamation-icon.png');?> </span><span id="maxcaterr" style="display:none"> <?php echo dgTip(JText::_('COM_DATSOGALLERY_MAX_CAT_EXHAUSTED'), 'dg-exclamation-icon.png');?> </span>
            <label for="description"><?php echo JText::_('COM_DATSOGALLERY_DESCRIPTION'); ?></label>
            <textarea class="inputbox" cols="35" rows="4" id="description" name="description" style="width:338px"></textarea> <?php echo dgTip(JText::_('COM_DATSOGALLERY_CATEGORY_DESCRIPTION_TIP'));?>
              <p><span id="catnameResult"></span> <button id="create_dg_btn" class="dg_btn" type="button"><span><span><?php echo JText::_('COM_DATSOGALLERY_CREATE'); ?></span></span></button></p>
            </div>
            <div id="imagefields">
            <label for="gentitle"><?php echo JText::_('COM_DATSOGALLERY_GENERIC_TITLE');?> *</label>
            <input type="text" name="gentitle" style="width:330px" />
                <?php echo dgTip(JText::_('COM_DATSOGALLERY_GENERIC_TITLE_TIP'));?>
            <label for="gendesc"><?php echo JText::_('COM_DATSOGALLERY_GENERIC_DESCRIPTION');?></label>
            <textarea class="inputbox" cols="35" rows="4" name="gendesc" style="width:338px"></textarea>
                <?php echo dgTip(JText::_('COM_DATSOGALLERY_GENERIC_DESCRIPTION_TIP'));?>
            <label for="genimgauthor"><?php echo JText::_('COM_DATSOGALLERY_AUTHOR');?></label>
            <input type="text" name="genimgauthor" style="width:330px" />
                <?php echo dgTip(JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR_TIP'));?>
            <label for="genimgauthorurl"><?php echo JText::_('COM_DATSOGALLERY_AUTHOR_URL');?></label>
            <input type="text" name="genimgauthorurl" style="width:330px" />
            <?php  echo dgTip(JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR_URL_TIP')); ?>
            <p>
            <?php
            $disabled = ($ad_terms) ? 'disabled="disabled" ' : '';
               if ($ad_terms) {
            ?>
                  <a rel="shadowbox;width=460;height=600" href="index.php?option=com_content&view=article&id=<?php echo @$ad_terms_id; ?>&tmpl=component&print=1&layout=default" title="<?php echo @$ad_terms_name; ?>"><?php echo @$ad_terms_name; ?></a>
                  <input style="vertical-align: middle" type="checkbox" id="toggleElement" onchange="toggleStatus()"; />
                  <?php } ?>
                  <button id="enable_dg_btn" <?php echo $disabled; ?>class="dg_btn" type="submit" onclick="checkForm();return false;"><span><span><?php echo JText::_('COM_DATSOGALLERY_SEND'); ?></span></span></button></p></div>
        </fieldset>

</div>
<input type="hidden" name="option" value="com_datsogallery" />
<input type="hidden" name="task" value="upload" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
<?php
}

function editPic($uid) {
  $app = JFactory::getApplication('site');
  require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
  $db = JFactory::getDBO();
  $user = JFactory::getUser();
  $is_admin = array(7,8);
  $userGroups = JAccess::getGroupsByUser($user->id, true);
  $doc = JFactory::getDocument();
  $uri = JFactory::getURI();
  $return	= $uri->toString();
  $url = 'index.php?option=com_users&view=login';
  $url .= '&return='.base64_encode($return);
  if (!$user->id) {
    $app->redirect($url, JText::_('You must login first') );
  }
  $menu = JSite::getMenu();
  $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
  $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
  $doc->setTitle(JText::_('COM_DATSOGALLERY_EDIT'));
  $row = new DatsoImages($db);
  $row->load($uid);
  if ($row->owner_id != $user->id) {
    $app->redirect(JRoute::_("index.php?option=com_datsogallery&task=userpanel".$itemid, false), JText::_('COM_DATSOGALLERY_NOT_ALOWED_EDIT_IMAGE'));
  }
  $clist = ShowDropDownCategoryList($row->catid, "catid", ' size="1" style="width:266px"');
?>
<script type="text/javascript">
    function checkMe() {
        var form = document.adminForm;
        if (form.imgtitle.value == '') {
            alert("<?php echo JText::_('COM_DATSOGALLERY_MUST_HAVE_TITLE');?>");
            return false;
        } else if (form.catid.value == 0) {
            alert("<?php echo JText::_('COM_DATSOGALLERY_MUST_SELECT_CATEGORY');?>");
            return false;
        } else {
            form.submit();
        }
    }
</script>
    <div class="dg_head_background"><?php echo JText::_('COM_DATSOGALLERY_EDIT_PICTURE'); ?></div>
    <form action="<?php echo JRoute::_("index.php?option=com_datsogallery&task=savepic".$itemid); ?>" method="post" name="adminForm" onsubmit="checkform();">
      <table cellpadding="0" cellspacing="8" border="0" width="100%" class="dg_body_background_edit_image">
        <tr>
          <td width="20%" align="right"><?php echo JText::_('COM_DATSOGALLERY_TITLE'); ?>:</td>
          <td width="80%"><input class="inputbox" type="text" name="imgtitle" style="width:266px" value="<?php echo trim($row->imgtitle); ?>" /></td>
        </tr>
        <tr>
          <td valign="top" align="right"><?php echo JText::_('COM_DATSOGALLERY_CATEGORY'); ?>:</td>
          <td><?php echo $clist; ?></td>
        </tr>
        <tr>
          <td valign="top" align="right"><?php echo JText::_('COM_DATSOGALLERY_DESCRIPTION'); ?>:</td>
          <td><textarea cols="35" rows="10" name="imgtext" style="width:266px"><?php echo trim($row->imgtext); ?></textarea></td>
        </tr>
        <tr>
          <td valign="top" align="right"><?php echo JText::_('COM_DATSOGALLERY_AUTHOR'); ?>:</td>
          <td><input class="inputbox" type="text" name="imgauthor" value="<?php echo trim($row->imgauthor); ?>" style="width:266px" /></td>
        </tr>
        <tr>
          <td valign="top" align="right"><?php echo JText::_('COM_DATSOGALLERY_AUTHOR_URL'); ?>:</td>
          <td><input class="inputbox" type="text" name="imgauthorurl" value="<?php echo trim($row->imgauthorurl); ?>" style="width:266px" /></td>
        </tr>
        <?php
          if ($ad_comment_notify):
          $yesno = array(
            JHTML::_('select.option', '0', JText::_('COM_DATSOGALLERY_NO')),
            JHTML::_('select.option', '1', JText::_('COM_DATSOGALLERY_YES'))
          );
          $yn_notify = JHTML::_('select.radiolist', $yesno, 'notify', 'class="inputbox"', 'value', 'text', $row->notify);
          $regex = '/(\s|\\\\[rntv]{1})/';
          $yn_notify = preg_replace("/\<label(.*?)\>/si", '', $yn_notify);
          $yn_notify = preg_replace("/<\/label\>/si", '', $yn_notify);
        ?>
        <tr>
          <td valign="top" align="right"><?php echo JText::_('COM_DATSOGALLERY_COMMENT_NOTIFY_ME'); ?>:</td>
          <td style="display: inline;vertical-align: middle"><?php echo preg_replace($regex, ' ', $yn_notify); ?>  <input type="checkbox" name="notify_all" value="1" /> <?php echo JText::_('COM_DATSOGALLERY_ALL'); ?> <?php echo dgTip(JText::_('COM_DATSOGALLERY_COMMENT_NOTIFY_ME_TIP')); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
          <td valign="top" align="right"><?php echo JText::_('COM_DATSOGALLERY_PICTURE'); ?>:</td>
          <td><img src="<?php echo resize($row->imgoriginalname, $ad_thumbwidth, $ad_thumbheight, $ad_crop, $ad_cropratio, 0, $row->catid); ?>" <?php echo get_width_height($row->imgoriginalname, $ad_thumbwidth, $ad_thumbheight, $row->catid, $ad_cropratio); ?> class="dgimg" title="<?php echo JText::_('COM_DATSOGALLERY_PICTURE'); ?>" alt="" /></td>
        </tr>
        <tr>
          <td colspan="2" align="center">
              <button class="dg_btn" onclick="checkMe();return false;"><span><span><?php echo JText::_('COM_DATSOGALLERY_SAVE'); ?></span></span></button>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="dg_btn" onclick="javascript:history.go(-1);return false;"><span><span><?php echo JText::_('COM_DATSOGALLERY_CANCEL'); ?></span></span></button>
          </td>
        </tr>
      </table>
      <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
      <?php echo JHTML::_( 'form.token' ); ?>
    </form>
<?php
    }

    function userPanel() {
      $app = JFactory::getApplication('site');
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $doc = JFactory::getDocument();
      $menu = JSite::getMenu();
      $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
      $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
      $uri = JFactory::getURI();
	  $return = $uri->toString();
      $url = 'index.php?option=com_users&view=login';
	  $url .= '&return='.base64_encode($return);
      if (!$user->id) {
        $app->redirect($url, JText::_('You must login first') );
      }
      $doc->setTitle(JText::_('COM_DATSOGALLERY_USER_PANEL'));
      $limit        = JRequest::getVar( 'limit', 15, '', 'int' );
      $limitstart    = JRequest::getVar( 'limitstart', 0, '', 'int' );
      $filter_order = $app->getUserStateFromRequest('com_datsogallery.filter_order', 'filter_order', 'a.id', 'cmd');
      $filter_order_Dir = $app->getUserStateFromRequest('com_datsogallery.filter_order_Dir', 'filter_order_Dir', '', 'word');

      $where = array();

      if (!in_array($filter_order, array(
      'a.id', 'a.imgtitle', 'a.imgdate', 'a.approved', 'a.notify', 'category'))) {
        $filter_order = 'a.id';
      }

      if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
        $filter_order_Dir = '';
      }

      $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

      $db->setQuery('SELECT count(*) FROM #__datsogallery as a WHERE a.owner_id = '.$user->id);
      $total = $db->loadResult();

      jimport('joomla.html.pagination');
      $pagination = new JPagination($total, $limitstart, $limit);


      GalleryHeader();
      echo "<div class=\"datso_pgn\">";
      echo $pagination->getPagesLinks();
      echo "</div><div style=\"clear:both\"></div>";
      echo "<form method=\"post\" name=\"adminForm\">";
      echo "<table class=\"dguserpanel\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n";
      echo " <thead><tr>";
      echo "  <th width=\"1%\" nowrap=\"nowrap\">".str_replace('Joomla.tableOrdering','tableOrdering',
      JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_ID'), 'a.id', $filter_order_Dir, $filter_order ))."</th>\n";
      echo "  <th class=\"title\">".str_replace('Joomla.tableOrdering','tableOrdering',
      JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_PIC_NAME').' ', 'a.imgtitle', $filter_order_Dir, $filter_order ))."</th>\n";
      echo "  <th class=\"title\">".str_replace('Joomla.tableOrdering','tableOrdering',
      JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_CATEGORY').' ', 'category', $filter_order_Dir, $filter_order ))."</th>\n";
      echo "  <th width=\"16%\" nowrap=\"nowrap\">".str_replace('Joomla.tableOrdering','tableOrdering',
      JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_DATE_ADD').' ', 'a.imgdate', $filter_order_Dir, $filter_order ))."</th>\n";
      if ($ad_approve) {
        echo "  <th width=\"6%\" nowrap=\"nowrap\">".str_replace('Joomla.tableOrdering','tableOrdering',
        JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_APPROWED').' ', 'a.approved', $filter_order_Dir, $filter_order ))."</th>\n";
      }
      if ($ad_comment_notify) {
        echo "  <th width=\"6%\" nowrap=\"nowrap\">".str_replace('Joomla.tableOrdering','tableOrdering',
        JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_COMMENT_NOTIFY').' ', 'a.notify', $filter_order_Dir, $filter_order ))."</th>\n";
      }
      echo "  <th colspan=\"2\" width=\"10%\" nowrap=\"nowrap\">".JText::_('COM_DATSOGALLERY_ACTION')."</th>\n";
      echo " </tr>\n";
      echo "</thead>\n";
      echo "<tbody>\n";
      $where[] = 'a.catid = cc.cid';
      $where[] = 'a.owner_id = '.$user->id;
      $pics = count($where) ? ' WHERE '.implode(' AND ', $where) : '';
      $query = "SELECT a.*, cc.name AS category FROM #__datsogallery AS a, #__datsogallery_catg AS cc" . $pics . $orderby;
      $db->setQuery($query, $pagination->limitstart, $pagination->limit);
	  $rows = $db->loadObjectList();
      $k = 0;
      if (count($rows)) {
        foreach ($rows as $row) {
          $k = 1 - $k;
          $kp = $k + 1;
          $imgprev = resize($row->imgoriginalname, 120, 120, $ad_crop, $ad_cropratio, 0, $row->catid);
          $db->setQuery("SELECT COUNT(cmtid) FROM #__datsogallery_comments WHERE cmtpic = ".$row->id);
          $comments = $db->loadResult();
          $overlib = '<table>';
          $overlib .= '<tr>';
          $overlib .= '<td>';
          $overlib .= JText::_('COM_DATSOGALLERY_DATE_ADD');
          $overlib .= '</td>';
          $overlib .= '<td>: ';
          $overlib .= strftime($ad_datef, $row->imgdate);
          $overlib .= '</td>';
          $overlib .= '</tr>';
          $overlib .= '<tr>';
          $overlib .= '<td>';
          $overlib .= JText::_('COM_DATSOGALLERY_HITS');
          $overlib .= '</td>';
          $overlib .= '<td>: ';
          $overlib .= $row->imgcounter;
          $overlib .= '</td>';
          $overlib .= '</tr>';
          $overlib .= '<tr>';
          $overlib .= '<td>';
          $overlib .= JText::_('COM_DATSOGALLERY_DOWNLOADS');
          $overlib .= '</td>';
          $overlib .= '<td>: ';
          $overlib .= $row->imgdownloaded;
          $overlib .= '</td>';
          $overlib .= '</tr>';
          if ($row->imgvotes > 0) {
            $fimgvotesum = number_format($row->imgvotesum / $row->imgvotes, 2, ",", ".");
            $dgvotes = "$fimgvotesum / $row->imgvotes";
          }
          else {
            $dgvotes = JText::_('COM_DATSOGALLERY_NO_VOTES');
          }
          $overlib .= '<tr>';
          $overlib .= '<td>';
          $overlib .= JText::_('COM_DATSOGALLERY_RATING');
          $overlib .= '</td>';
          $overlib .= '<td>: ';
          $overlib .= $dgvotes;
          $overlib .= '</td>';
          $overlib .= '</tr>';
          $overlib .= '<tr>';
          $overlib .= '<td>';
          $overlib .= JText::_('COM_DATSOGALLERY_COMMENT1');
          $overlib .= '</td>';
          $overlib .= '<td>: ';
          $overlib .= $comments;
          $overlib .= '</td>';
          $overlib .= '</tr>';
          $overlib .= '</table>';

          echo " <tr class=\"row".$k."\">\n";
          echo "  <td align=\"center\">".$row->id."</td>\n";
          echo "  <td><a href=\"".JRoute::_('index.php?option=com_datsogallery&task=editpic&uid='.$row->id.$itemid)."\" id=\"<strong>".jsspecialchars($row->imgtitle)."</strong><br /><br /><div style='text-align:center'><img src='".$imgprev."' class='dgimg' /></div><br />".$overlib."\" class=\"dgtip\">".$row->imgtitle."</a></td>\n";
          echo "  <td>".catNameById ($row->catid)."</td>\n";
          echo "  <td align=\"center\" style=\"font-size: 10px\">".strftime($ad_datef, $row->imgdate)."</td>\n";
          if ($ad_approve) {
            $a_pic = ($row->approved) ? dgTip(JText::_('COM_DATSOGALLERY_PIC_APPROVED'), 'dg-accept-icon.png') : dgTip(JText::_('COM_DATSOGALLERY_PIC_PENDING'), 'dg-pending-icon.png');
            echo "  <td align=\"center\">".$a_pic."</td>\n";
          }
          if ($ad_comment_notify) {
            $notify = ($row->notify) ? dgTip(JText::_('COM_DATSOGALLERY_COMMENT_NOTIFY_EMAIL_ICON'), 'email.png') : dgTip(JText::_('COM_DATSOGALLERY_COMMENT_NOTIFY_NO_EMAIL_ICON'), 'no_email.png');
            echo "  <td align=\"center\">".$notify."</td>\n";
          }
          echo "  <td align=\"center\">";
          echo "<a href='".JRoute::_("index.php?option=com_datsogallery&task=editpic&uid=".$row->id.$itemid)."'>";
          echo "<img src='".JURI::base(true)."/components/com_datsogallery/images/".$dg_theme."/edit.png' width='16' height='16' border='0' title='".JText::_('COM_DATSOGALLERY_EDIT')."' /></a>";
          echo "  </td>\n";
          echo "  <td align=\"center\">";
          echo "<a href=\"javascript:if (confirm('".JText::_('COM_DATSOGALLERY_SURE_DELETE_SELECT_ITEM')."')){ location.href='".JRoute::_("index.php?option=com_datsogallery&task=deletepic&uid=".$row->id.$itemid)."';}\" title='".JText::_('COM_DATSOGALLERY_DELETE')."'>";
          echo "<img src='".JURI::base(true)."/components/com_datsogallery/images/".$dg_theme."/dg-delete-image-icon.png' width='16' border='0' /></a>\n";
          echo "  </td>\n";
          echo " </tr>\n";
        }
      }
      else {
        echo " <tr class=\"row".$k."\">";
        echo "  <td colspan=\"7\">".JText::_('COM_DATSOGALLERY_NOT_HAVE_PIC')."</td>\n";
        echo " </tr>\n";
      }
      echo "</tbody>\n";
      echo "</table>\n";
      echo "<input type=\"hidden\" name=\"filter_order\" value=\"".$filter_order."\" />\n";
      echo "<input type=\"hidden\" name=\"filter_order_Dir\" value=\"".$filter_order_Dir."\" />";
      echo JHTML::_( 'form.token' );
      echo "</form>\n";
      echo '<div class="datso_pgn">';
      echo $pagination->getPagesLinks();
      echo "</div>";
      GalleryFooter();
    }

    function savePic() {
      $app = JFactory::getApplication('site');
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'class.datsogallery.php');
      $user = JFactory::getUser();
      $uri = JFactory::getURI();
	  $return	= $uri->toString();
      $url = 'index.php?option=com_users&view=login';
	  $url .= '&return='.base64_encode($return);
      $post = JRequest::get('post');
      $notify = JRequest::getVar('notify', 0, 'post', 'int');
      $notify_all = JRequest::getVar('notify_all');
      if (!$user->id) {
        $app->redirect($url, JText::_('You must login first') );
      }
      $db = JFactory::getDBO();
      if ($ad_comment_notify){
        if ($notify_all){
        $db->setQuery("UPDATE #__datsogallery SET notify = '".$notify."' WHERE owner_id = ".(int) $user->id);
        $db->query();
        }
      }
      $menu = JSite::getMenu();
      $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
      $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
      $row = new DatsoImages($db);
      if (!$row->bind($post)) {
        JError::raiseError(500, $row->getError());
      }
      if (!$row->store()) {
        JError::raiseError(500, $row->getError());
      }
      $app->redirect(JRoute::_("index.php?option=com_datsogallery&task=userpanel".$itemid, false), JText::_('COM_DATSOGALLERY_IMAGE_UPDATED'));
    }

    function deletePic($uid) {
      $app = JFactory::getApplication('site');
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $uri = JFactory::getURI();
	  $return	= $uri->toString();
      $url = 'index.php?option=com_users&view=login';
	  $url .= '&return='.base64_encode($return);
      if (!$user->id) {
        $app->redirect($url, JText::_('You must login first') );
      }
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      jimport('joomla.filesystem.file');
      $menu = JSite::getMenu();
      $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
      $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
      $db->setQuery('SELECT owner_id FROM #__datsogallery WHERE id = '.(int) $uid);
      $own = $db->loadResult();
      if ($own != $user->id) {
        $app->redirect(JRoute::_("index.php?option=com_datsogallery".$itemid, false), JText::_('COM_DATSOGALLERY_NOT_ALOWED_DELETE_IMAGE'));
      }
      if ($uid) {
        $row = new DatsoImages($db);
        $row->load($uid);
        if (JFile::exists(JPath::clean(JPATH_ROOT.$ad_pathoriginals.DS.$row->imgoriginalname))) {
        JFile::delete(JPATH_ROOT.$ad_pathoriginals.DS.$row->imgoriginalname);
        }
        $db->setQuery('DELETE FROM #__datsogallery_comments WHERE cmtpic = '.(int) $uid);
        if (!$db->query()) {
          echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
        }
        $db->setQuery('DELETE FROM #__datsogallery where id = '.(int) $uid);
        if (!$db->query()) {
          echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
        }
      }
      $app->redirect(JRoute::_("index.php?option=com_datsogallery&task=userpanel".$itemid, false), JText::_('COM_DATSOGALLERY_IMAGE_DELETED'));
    }

    function ShowDropDownCategoryList($cat, $cname, $extras = "", $levellimit = 999) {
      $app = JFactory::getApplication('site');
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $task = JRequest::getCmd('task');
      $userGroups = JAccess::getGroupsByUser($user->id, true);
      $is_admin = array(7,8);
      $db->setQuery('SELECT COUNT(cid)'
      .' FROM #__datsogallery_catg'
      .' WHERE user_id = '.$user->id
      );
      $count = $db->loadResult();
      if ($user_categories && $user->id) {
        $where = ' WHERE user_id = '.$user->id;
      }
      else {
        $where = ' WHERE cid IN ( '.$ad_category.' )';
      }
      $where1 = (!array_intersect($is_admin, $userGroups)) ? $where : '';

      $query = 'SELECT cid as id, parent AS parent_id, name AS title'
      .' FROM #__datsogallery_catg'
      . $where1
      .' ORDER BY title';
      $db->setQuery($query);
      $rows = $db->loadObjectList();

      $children = array();
      asort($children);
      if (count($rows)){
        foreach ($rows as $row) {
          $parent = $row->parent_id;
          $list = @$children[$parent] ? $children[$parent] : array();
          array_push($list, $row);
          $children[$parent] = $list;
        }
      }
      if ($user_categories) {
        $root = ($user->id && !array_intersect($is_admin, $userGroups)) ? $ad_category : 0;
      }
      else {
        $root = 0;
      }
      $list = JHTML::_('menu.treerecurse', $root, '', array(), $children);
      $items = array();
      $items[] = JHTML::_('select.option', '', ' - '.JText::_('COM_DATSOGALLERY_SELECT_CATEGORY'));
      if ($user_categories && $user->id && $task != 'editpic' && $count < $ad_max_categories) {
        $items[] = JHTML::_('select.option', 'usercat', ' + '.JText::_('COM_DATSOGALLERY_CREATE_NEW_CATEGORY'));
      }
      foreach ($list as $item) {
        $items[] = JHTML::_('select.option', $item->id, $item->treename);
      }
      $parlist = selectList($items, $cname, $extras, 'value', 'text', $cat);
      return $parlist;
    }

    function selectList(&$arr, $tag_name, $tag_attribs, $key, $text, $selected) {
      reset($arr);
      $html = "\n<select name=\"$tag_name\" $tag_attribs>";
      for ($i = 0, $n = count($arr); $i < $n; $i++) {
        $k = $arr[$i]->$key;
        $t = $arr[$i]->$text;
        $id = @$arr[$i]->id;
        $extra = '';
        $extra .= $id ? ' id="'.$arr[$i]->id.'"' : '';
        if (is_array($selected)) {
          foreach ($selected as $obj) {
            $k2 = $obj;
            if ($k == $k2) {
              $extra .= ' selected="selected"';
              break;
            }
          }
        }
        else {
          $extra .= ($k == $selected ? " selected=\"selected\"" : '');
        }
        $html .= "\n\t<option value=\"".$k."\"$extra>".$t."</option>";
      }
      $html .= "\n</select>\n";
      return $html;
    }

    function SortCatArray($a, $b) {
      return strcmp($a->name, $b->name);
    }

    function dgCategories($catid) {
      $app = JFactory::getApplication('site');
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $db         = JFactory::getDBO();
      $user       = JFactory::getUser();
      $menu = JSite::getMenu();
      $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
      $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
      $na         = ($ad_na) ? '#dgtop':'';
      $filter_order = $app->getUserStateFromRequest('com_datsogallery.filter_order', 'filter_order', 'd.ordering', 'cmd');
      $filter_order_Dir = $app->getUserStateFromRequest('com_datsogallery.filter_order_Dir', 'filter_order_Dir', '', 'word');
      $limit      = JRequest::getVar('limit', $ad_catsperpage);
      $limitstart = JRequest::getVar('limitstart', 0);
      if (!in_array($filter_order, array('d.name', 'd.ordering'))) {
      $filter_order = 'd.ordering';
      }
      if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
        $filter_order_Dir = $ad_sortby;
      }
      $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
      $par = "SELECT COUNT(*) FROM #__datsogallery_catg AS cid"
      . " WHERE parent = ".$catid
      . " AND approved = 1"
      . " AND published = 1"
      ;
      $db->setQuery($par);
      $total = $db->loadResult();
      jimport('joomla.html.pagination');
      $pageNav = new JPagination($total, $limitstart, $limit);
      $query = "SELECT d.*, u.id AS uid, u.name AS usercat FROM #__datsogallery_catg AS d"
      . " LEFT JOIN #__users AS u ON u.id = d.user_id"
      . " WHERE d.parent = ".$catid
      . " AND d.approved = 1"
      . " AND d.published = 1"
      . $orderby
      ;
      $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
      $rows = $db->loadObjectList();
      $num_rows = count($rows);
      $index = 0;
      if ($ad_ncsc > $num_rows) {
        $ad_ncsc = $num_rows;
      }
      else {
        $ad_ncsc = $ad_ncsc;
      }
      if ($ad_ncsc) {
        $cw = 100 / $ad_ncsc."%";
        $colspan = $ad_ncsc;
      }
      ?>
      <script language="javascript" type="text/javascript">
      	function tableOrdering( order, dir, task ) {
      	var form = document.viewCategories;

      	form.filter_order.value 	= order;
      	form.filter_order_Dir.value	= dir;
      	document.viewCategories.submit( task );
      }
      </script>
      <?php
      $output = "";
      $output .= '<form method="post" name="viewCategories">';
      if (@$rows[0]->parent) {
        $parcid = $rows[0]->parent;
        if ($total > $ad_catsperpage) {
          $output .= '<div class="datso_pgn">';
          $output .= $pageNav->getPagesLinks("index.php?option=com_datsogallery&task=category&catid=".$parcid.$itemid);
          $output .= '</div><div style="clear:both"></div>';
        }
      }
      elseif (@$rows[0]->cid) {
        if ($total > $ad_catsperpage) {
          $output .= '<div class="datso_pgn">';
          $output .= $pageNav->getPagesLinks();
          $output .= '</div><div style="clear:both"></div>';
        }
      }
      if (@$rows[0]->parent) {
        if ($show_grid):
        $output .= '  <div class="dg_head_background">'.str_replace('Joomla.tableOrdering','tableOrdering',
        JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_SUBCATEGORIES').' ', 'd.ordering', $filter_order_Dir, $filter_order )).'</div>';
        else:
        $output .= '  <div class="dg_head_background">'.JText::_('COM_DATSOGALLERY_SUBCATEGORIES').'</div>';
        endif;
      }
      else
        if (@$rows[0]->cid) {
          if ($show_grid):
          $output .= '  <div class="dg_head_background">'.str_replace('Joomla.tableOrdering','tableOrdering',
          JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_CATEGORIES').' ', 'd.name', $filter_order_Dir, $filter_order ));
          $output .= '  <span class="grid_txt"><div class="grid_border">'.str_replace('Joomla.tableOrdering','tableOrdering',
          JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_ORDER').' ', 'd.ordering', $filter_order_Dir, $filter_order )).'</div></span></div>';
          else:
          $output .= '  <div class="dg_head_background">'.JText::_('COM_DATSOGALLERY_CATEGORIES').'</div>';
          endif;
      }
      $output .= '<table width="100%" cellspacing="1" cellpadding="0" border="0" class="dg_body_background">';
        if ($num_rows)
          for ($row_count = 0; $row_count < ($num_rows / $ad_ncsc); $row_count++) {
            $output .= ' <tr>';
            for ($i = 0; $i < $ad_ncsc; $i++) {
              $cur_name = @$rows[$index];
              $output .= '  <td class="dg_body_background_td" align="center" valign="top" width="'.$cw.'">';
              if (@$cur_name->cid) {
                if (GetThumbsInCats(@$cur_name->cid)) {
                $output .= '<a href="'.JRoute::_("index.php?option=com_datsogallery&amp;task=category&amp;catid=".$cur_name->cid.$itemid) . $na .'">';
                }
              }
              if (!@$cur_name->cid) {
                $output .= '';
              }
              else {
                $catid = $cur_name->cid;
                $query = "SELECT p.*"
                . " FROM #__datsogallery AS p"
                . " LEFT JOIN #__datsogallery_catg AS c ON c.cid = p.catid"
                . " WHERE ".($catid ? " ( p.catid IN (".$catid.") )" : '')
                . " AND p.published = 1 AND p.approved = 1"
                . " OR ".($catid ? " ( c.parent IN (".$catid.") )" : '')
                . " AND p.published = 1 AND p.approved = 1 AND c.approved = 1"
                . " ORDER BY p.id ".@$ad_catimg." LIMIT 1";
                $db->setQuery($query);
                $rows2 = $db->loadObjectList();
                $count = count($rows2);
                $row2 = &$rows2[0];
                $db->setQuery($query);
                if ($count > 0) {
                  $output .= '   <img src="'.resize($row2->imgoriginalname, $ad_thumbwidth, $ad_thumbheight, $ad_crop, $ad_cropratio, 0, $cur_name->cid).'" class="dgimg" title="'.JText::_('COM_DATSOGALLERY_VIEW_CATEGORY').'" alt="" /></a>';
                  $output .= '   <p style="width:'.$ad_thumbwidth.'px;margin:5px auto 0 auto;text-align:left">';
                }
                else
                  if (GetThumbsInCats($cur_name->cid)) {
                    $output .= '   <img src="'.resize(GetThumbsInCats($cur_name->cid), $ad_thumbwidth, $ad_thumbheight, $ad_crop, $ad_cropratio, 0, $cur_name->cid).'" class="dgimg" title="'.JText::_('COM_DATSOGALLERY_VIEW_CATEGORY').'" alt="" /></a>';                       $output .= '   <p style="width:'.$ad_thumbwidth.'px;margin:5px auto 0 auto;text-align:left">';
                  }
                  else
                    if (!$count) {
                      $output .= '   <img src="'.resize("blank.jpg", $ad_thumbwidth, $ad_thumbheight, $ad_crop, $ad_cropratio, 0).'" class="dgimg" title="'.JText::_('COM_DATSOGALLERY_NO_IMAGES').'" alt="" />';
                      $output .= '   <p style="width:'.$ad_thumbwidth.'px;margin:5px auto 0 auto;text-align:left">';
                    }
              }
              if ($cur_name && $count) {
                $output .= '   <a href="'.JRoute::_("index.php?option=com_datsogallery&task=category&catid=".$cur_name->cid.$itemid).$na.'">';
                $output .= '<span style="text-transform: uppercase">'.$cur_name->name.'</span></a>';
                if ($ad_showimgauthor) {
                $output .= '<br /><span style="text-transform: uppercase">'.JText::_('COM_DATSOGALLERY_OWNER').': <a href="'.JRoute::_('index.php?option=com_datsogallery&amp;task=owner&amp;op='.$cur_name->uid.$itemid).'">'.$cur_name->usercat.'</a></span>';
}
              }
              else
                if ($cur_name && (!$count)) {
                  $output .= '<span style="text-transform: uppercase">'.$cur_name->name.'</span>';
                  if ($ad_showimgauthor) {
                  $output .= '<br /><span style="text-transform: uppercase">'.JText::_('COM_DATSOGALLERY_OWNER').': <a href="'.JRoute::_('index.php?option=com_datsogallery&amp;task=owner&amp;op='.$cur_name->uid.$itemid).'">'.$cur_name->usercat.'</a></span>';
}
                }
                if (@$cur_name->name) {
                  $output .= ' <br /><span style="text-transform: uppercase">'.GetNumberOfLinks($cur_name->cid);
                  if ($ad_showinformer) {
                    $output .= ' '.GetNewPics($cur_name->cid);
                  }
                  $output .= '</span>';
                }
                $output .= '<br /><span><em>'.@$cur_name->description.'</em></span></p></td>';
              $index++;
            }
            $output .= ' </tr>';
      }
      $output .= '</table>';
      if (@$rows[0]->parent) {
        $parcid = $rows[0]->parent;
        if ($total > $ad_catsperpage) {
          $output .= '<div class="datso_pgn">';
          $output .= $pageNav->getPagesLinks("index.php?option=com_datsogallery&task=category&catid=".$parcid.$itemid);
          $output .= "</div>";
        }
      }
      elseif (@$rows[0]->cid) {
        if ($total > $ad_catsperpage) {
          $output .= '<div class="datso_pgn">';
          $output .= $pageNav->getPagesLinks();
          $output .= "</div>";
        }
      }
      $output .= '<input type="hidden" name="filter_order" value="'.$filter_order.'" />';
      $output .= '<input type="hidden" name="filter_order_Dir" value="'.$filter_order_Dir.'" />';
      $output .= JHTML::_( 'form.token' );
      $output .= '</form>';

      return $output;
    }

    function Breadcrumb($catid, $id, $task = '') {
      $app = JFactory::getApplication('site');
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $groups = implode(',', $user->getAuthorisedViewLevels());
      $pathway = $app->getPathway();
      $menu = JSite::getMenu();
      $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
      $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
      $uid = JRequest::getVar('uid', 0, 'get', 'int');
      $sorting = JRequest::getCmd('sorting');
      $task = JRequest::getCmd('task');
      switch ($task) {

        case 'userpanel' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_USER_PANEL'));
          break;

        case 'paypal' :
          $pathway->addItem("COMPLETE");
          break;

        case 'upload' :
        case 'showupload' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_USER_PANEL'), 'index.php?option=com_datsogallery&task=userpanel'.$itemid);
          $pathway->addItem(JText::_('COM_DATSOGALLERY_NEW_PICTURE'));
          break;

        case 'editpic' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_EDIT'), 'index.php?option=com_datsogallery&task=editpic&uid='.$uid.$itemid);
          break;

        case 'popular' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_MOST_VIEWED'), 'index.php?option=com_datsogallery&task=popular'.$itemid);
          break;

        case 'rating' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_TOP_RATED'));
          break;

        case 'downloads' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_MOST_DOWNLOADED'));
          break;

        case 'lastadded' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_LAST_ADDED'));
          break;

        case 'lastcommented' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_LAST_COMMENTED'));
          break;

        case 'favorites' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_MY_FAVORITES'));
          break;

        case 'purchases' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_MY_PURCHASES'));
          break;

        case 'search' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_SEARCH'));
          break;

        case 'tag' :
          $pathway->addItem(JText::_('COM_DATSOGALLERY_TAGS'));
          break;
      }

      if ($task != '' && $task != 'category' && $task != 'image') {
        return;
      }
      if ($catid == 0 || $task == 'image') {
        if ($id != 0) {
          $db->setQuery("SELECT *"
          ." FROM #__datsogallery AS a,"
          ." #__datsogallery_catg AS cc"
          ." WHERE a.catid = cc.cid"
          ." AND a.id = ".$id
          ." AND cc.access IN (".$groups.")"
          );
          if (!$row = $db->loadObject()) {
            return false;
          }
          $catid = $row->catid;
        }
        else {
          return false;
        }
      }
      $cat_ids = array($catid);
      $cat_names = array();
      while ($catid != 0) {
        $db->setQuery("SELECT *"
        ." FROM #__datsogallery_catg"
        ." WHERE cid = ".$catid
        ." AND published = 1"
        ." AND access IN (".$groups.")"
        );
        if (!$rows = $db->loadObjectList()) {
          $catid = 0;
        }
        else {
          $catid = $rows[0]->parent;
        }
        if ($catid != 0) {
          array_unshift($cat_ids, $catid);
        }
        @array_unshift($cat_names, $rows[0]->name);
      }
      for ($i = 0; $i < count($cat_names); $i++) {
        $pathway->addItem($cat_names[$i], 'index.php?option=com_datsogallery&task=category&catid='.$cat_ids[$i].$itemid);
      }
      if (isset ($row->id)) {
        $pathway->addItem($row->imgtitle, 'index.php?option=com_datsogallery&task=image&id='.$row->id.$itemid);
      }
    }

    function dgNumRows($num_rows){
      $jconfig = JFactory::getConfig();
      $dbt = $jconfig->getValue( 'config.dbtype' );
      if ($dbt == 'mysql'){
        return mysql_num_rows($num_rows);
      } else {
        return mysqli_num_rows($num_rows);
      }
    }

    function dgFetchRow($row){
      $jconfig = JFactory::getConfig();
      $dbt = $jconfig->getValue( 'config.dbtype' );
      if ($dbt == 'mysql'){
        return mysql_fetch_row($row);
      } else {
        return mysqli_fetch_row($row);
      }
    }

    function dgFetchArray($row){
      $jconfig = JFactory::getConfig();
      $dbt = $jconfig->getValue( 'config.dbtype' );
      if ($dbt == 'mysql'){
        return mysql_fetch_array($row);
      } else {
        return mysqli_fetch_array($row);
      }
    }

    function GetNumberOfLinks($cat) {
      $db = JFactory::getDBO();
      $queue[] = intval($cat);
      while (list($key, $cat) = each($queue)) {
        $db->setQuery('SELECT cid'
        .' FROM #__datsogallery_catg'
        .' WHERE parent = '.$cat
        .' AND published = 1'
        );
        $result = $db->query();
        $total = dgNumRows($result);
        $j = 0;
        while ($j < $total) {
          $val = dgFetchRow($result);
          $queue[] = $val[0];
          $j++;
        }
      }
      reset($queue);
      $query = 'SELECT COUNT(*)'
      .' FROM #__datsogallery'
      .' WHERE (0 != 0';
      while (list($key, $cat) = each($queue)) {
        $query .= ' OR catid = '.$cat;
      }
      $query = $query
      .') AND published = 1'
      .' AND approved = 1';
      $db->setQuery($query);
      $result = $db->query();
      $val = dgFetchRow($result);
      if ($val[0] > 0) {
        $capics = JText::_('COM_DATSOGALLERY_COUNT_IMAGES');
      }
      else
        if ($val[0] == 0) {
          $capics = JText::_('COM_DATSOGALLERY_NO_IMAGES');
        }
        if ($val[0] == 0) {
          return $capics;
        }
        else {
          return $capics.': '.$val[0];
      }
    }

    function ShowCategoryPath($cat) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $groups = implode(',', $user->getAuthorisedViewLevels());
      $cat = intval($cat);
      $parent = 999;
      while ($parent) {
        $db->setQuery('SELECT *'
        .' FROM #__datsogallery_catg'
        .' WHERE cid = '.$cat
        .' AND access IN ('.$groups.')'
        );
        $rows = $db->loadObjectList();
        $row = $rows[0];
        $parent = $row->parent;
        $name = $row->name;
        if (empty ($path)) {
          $path = $name;
        }
        else {
          $path = $name.' &raquo; '.$path;
        }
        $cat = $parent;
      }
      return $path." ";
    }

    function catNameById ($cat){
      $db = JFactory::getDBO();
      $row = new DatsoCategories($db);
      $row->load($cat);
      return $row->name;
    }

    function GetThumbsInCats($cat) {
      $db = JFactory::getDBO();
      $queue[] = intval($cat);
      while (list($key, $cat) = each($queue)) {
        $db->setQuery('SELECT cid'
        .' FROM #__datsogallery_catg'
        .' WHERE parent = '.$cat
        .' AND published = 1'
        );
        $result = $db->query();
        $total = dgNumRows($result);
        $j = 0;
        while ($j < $total) {
          $val = dgFetchRow($result);
          $queue[] = $val[0];
          $j++;
        }
      }
      reset($queue);
      $query = 'SELECT imgoriginalname'
      .' FROM #__datsogallery'
      .' WHERE (0 != 0';
      while (list($key, $cat) = each($queue)) {
        $query .= ' OR catid = '.$cat;
      }
      $query = $query
      .') AND published = 1'
      .' AND approved = 1'
      .' ORDER BY rand()'
      .' LIMIT 1'
      ;
      $db->setQuery($query);
      $result = $db->query();
      $thumb = $db->loadResult($result);
      return $thumb;
    }

    function GetNewPics($cat) {
      $app = JFactory::getApplication('site');
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $db = JFactory::getDBO();
      $queue[] = intval($cat);
      while (list($key, $cat) = each($queue)) {
        $db->setQuery("select cid from #__datsogallery_catg where parent = $cat and published = 1");
        $result = $db->query();
        $total = dgNumRows($result);
        $j = 0;
        while ($j < $total) {
          $val = dgFetchRow($result);
          $queue[] = $val[0];
          $j++;
        }
      }
      reset($queue);
      $query = "select imgdate from #__datsogallery  where ( 0!=0";
      while (list($key, $cat) = each($queue)) {
        $query .= " or catid = $cat";
      }
      $query = $query." ) and published=1 and approved = 1 order by imgdate desc limit 1";
      $db->setQuery($query);
      $result = $db->query();
      $newpics = dgFetchRow($result);
      $today = strtotime('now');
      $diff = intval(($today - $newpics[0]) / $ad_periods);
      if (!$diff) {
        return dgTip(JText::_('COM_DATSOGALLERY_INFORMER_TIP'), 'new.png', '', '', 0);
      }
      else {
        return false;
      }
    }

    function format_filesize($tfilesize) {
      global $dgfilesize;
      $format = array(JText::_('COM_DATSOGALLERY_FILESIZE_BYTES'), JText::_('COM_DATSOGALLERY_FILESIZE_KB'), JText::_('COM_DATSOGALLERY_FILESIZE_MB'), JText::_('COM_DATSOGALLERY_FILESIZE_GB'));
      $i = 0;
      while ($tfilesize >= 1024) {
        $i++;
        $tfilesize = $tfilesize / 1024;
      }
      return number_format($tfilesize, ($i ? 2 : 0), ",", ".")." ".$format[$i];
    }

    function bytes($a) {
    $unim = array("b","kb","mb","gb","tb","pb");
    $c = 0;
    while ($a>=1024) {
        $c++;
        $a = $a/1024;
    }
    return number_format($a).$unim[$c];
    }

    function dgImgId($catid, $imgext) {
      return substr(strtoupper(md5(uniqid(time()))), 5, 12).'-'.$catid.'.'.strtolower($imgext);
    }

    function dgOrderId() {
      return substr(strtoupper(md5(uniqid(time()))), 5, 8);
    }

    function jsspecialchars($s) {
      $r = str_replace(array('\\', '"', "'"), array('\\\\', '&quot;', "&#039;"), $s);
      return htmlspecialchars($r, ENT_QUOTES);
    }

    function ampStrip($url) {
      $url = str_replace('&amp;', '&', $url);
      return $url;
    }

    function showVote ($id, $votes, $votesum, $idetails = false) {
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $user = JFactory::getUser();
      $groups = $user->getAuthorisedViewLevels();
      $allowed = in_array($ad_access_rating, $groups);
      $rating = ($votes > 0) ? ' ( '.JText::_('COM_DATSOGALLERY_VOTES').': '.$votes.' )' : '( '.JText::_('COM_DATSOGALLERY_NO_VOTES').' )';
      $result = ($votes > 0) ? number_format((intval($votesum) / intval($votes)) * 20, 2) : '0';
      if ($ad_showrating && ($allowed >= $ad_access_rating) && ($quick_rating || $idetails)) {
      echo "
<script type=\"text/javascript\">
  var sfolder = '".JURI::root(true)."';
  var theme = '".$dg_theme."';
  var vote_msg=Array('Your browser does not support AJAX','".JText::_('COM_DATSOGALLERY_LOADING')."','".JText::_('COM_DATSOGALLERY_VOTE_THANKS')."','".JText::_('COM_DATSOGALLERY_VOTE_ALREADY_VOTE')."','".JText::_('COM_DATSOGALLERY_VOTES')."','".JText::_('COM_DATSOGALLERY_VOTES')."');
</script>";
          $counter = - 1;
          $html = "";
          $html = "
<span class=\"vote-container-small\">
  <ul class=\"vote-stars-small\">
    <li id=\"rating_".$id."\" class=\"current-rating\" style=\"width:".(int) $result."%;\"></li>
    <li><a href=\"javascript:void(null)\" onclick=\"javascript:vote(".$id.",1,".$votesum.",".$votes.",".$counter
    .");\" title=\"".JText::_('COM_DATSOGALLERY_STARS_VERY_POOR')."\" class=\"dg-one-star\">1</a></li>
    <li><a href=\"javascript:void(null)\" onclick=\"javascript:vote(".$id.",2,".$votesum.",".$votes.",".$counter
    .");\" title=\"".JText::_('COM_DATSOGALLERY_STARS_POOR')."\" class=\"dg-two-stars\">2</a></li>
    <li><a href=\"javascript:void(null)\" onclick=\"javascript:vote(".$id.",3,".$votesum.",".$votes.",".$counter
    .");\" title=\"".JText::_('COM_DATSOGALLERY_STARS_REGULAR')."\" class=\"dg-three-stars\">3</a></li>
    <li><a href=\"javascript:void(null)\" onclick=\"javascript:vote(".$id.",4,".$votesum.",".$votes.",".$counter
    .");\" title=\"".JText::_('COM_DATSOGALLERY_STARS_GOOD')."\" class=\"dg-four-stars\">4</a></li>
    <li><a href=\"javascript:void(null)\" onclick=\"javascript:vote(".$id.",5,".$votesum.",".$votes.",".$counter
    .");\" title=\"".JText::_('COM_DATSOGALLERY_STARS_VERY_GOOD')."\" class=\"dg-five-stars\">5</a></li>
  </ul>
</span>
<span id=\"vote_".$id."\" class=\"vote-count\">";
          $html .= $rating;
          $html .= "</span>";
          echo $html.'<br />';
          }
          else {
            echo "<strong>".JText::_('COM_DATSOGALLERY_RATING')."</strong>:
          <span class=\"vote-container-small\">
            <ul class=\"vote-stars-small\">
              <li id=\"rating_".$id."\" class=\"current-rating\" style=\"width: ".(int) $result."%;cursor:default\"></li>
            </ul>
          </span>
        <span id=\"vote_".$id."\" class=\"vote-count\">".$rating."</span><br />";
          }
    }

    function recordVote() {
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $groups = $user->getAuthorisedViewLevels();
      $allowed = in_array($ad_access_rating, $groups);
      if ($ad_showrating && ($allowed >= $ad_access_rating)) {
      $rating = JRequest::getVar('rating');
      $id = JRequest::getVar('id');
      $vip = getIpAddress();
      if ($rating > 0 && $rating < 6) {
        $db->setQuery('SELECT vid'
        .' FROM #__datsogallery_votes'
        .' WHERE vpic = '.(int) $id
        .' AND vip = '.$db->Quote($vip)
        );
        if (!$db->loadObject()) {
          $db->setQuery('INSERT INTO #__datsogallery_votes'
          .' VALUES ('.$db->Quote('').', '.(int) $id
          .', '.$db->Quote($vip)
          .', NOW(), '.(int) $rating.')'
          );
          $db->query();
          $db->setQuery('UPDATE #__datsogallery'
          .' SET imgvotes = (imgvotes + 1),'
          .' imgvotesum = (imgvotesum + '.(int) $rating.')'
          .' WHERE id = '.(int) $id
          );
          $db->query();
          echo 'thanks';
          exit;
        }
        else {
          echo 'voted';
          exit;
        }
      }
     }
     else {
       exit;
     }
    }

    function getIpAddress() {
      return (empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP']);
    }

    function dgTip($dgtip, $image = 'dg-info-icon.png', $text = '', $href = '#', $class = '', $link = 0) {
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $document = JFactory::getDocument();
      if (!$text) {
        $image = JURI::base(true).'/components/com_datsogallery/images/'.$dg_theme.'/'.$image;
        $text = '<img src="'.$image.'" border="0" alt="dgtip" style="vertical-align:middle" />';
      }
      $style = 'style="text-decoration:none;color:#333;cursor:help"';
      if ($href) {
        $style = '';
      }
      else {
        $href = '#';
      }
      $title = 'id="'.jsspecialchars($dgtip).'"';
      $tip = "";
      if ($link) {
        $tip .= '<a rel="nofollow" href="'.$href.'"'.$class.' class="dgtip" '.$title.' '.$style.'>'.$text.'</a>';
      }
      else {
        $tip .= '<span class="dgtip" '.$title.' '.$style.$class.'>'.$text.'</span>';
      }
      return $tip;
    }

    function canDownload ($imageid, $catid, $price) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $uri = JFactory::getURI();
	  $return = $uri->toString();
      $url = 'index.php?option=com_users&view=login';
	  $url .= '&return='.base64_encode($return);
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $menu = JSite::getMenu();
      $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
      $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
      $db->setQuery('SELECT status'
      .' FROM #__datsogallery_purchases'
      .' WHERE image_id = '.(int) $imageid
      .' AND user_id = '.(int) $user->id
      );
      $status = $db->loadResult();
      $download_icon = '';
      if ($ad_showdownload && $ad_downpub && !$ad_shop && $price == '0.00'
       || $ad_showdownload && $ad_downpub && $ad_shop && $price == '0.00'
       || $ad_showdownload && !$ad_downpub && $user->id && !$ad_shop && $price == '0.00'
       || $ad_showdownload && !$ad_downpub && $user->id && $ad_shop && $price == '0.00'
       || $ad_showdownload && $ad_downpub && $user->id && $ad_shop && $price != '0.00' && $status == 'Completed'
       || $ad_showdownload && !$ad_downpub && $user->id && $ad_shop && $price != '0.00' && $status == 'Completed'
       ) {
       if ($ad_download_options) {
        if ($ad_download_resolutions != '0') {
        $resolutions = explode(',', $ad_download_resolutions);
        $download_icon = "<form action=\"".JRoute::_('index.php?option=com_datsogallery&amp;task=download&amp;catid='.$catid.'&amp;id='.$imageid.$itemid)."\" method=\"post\" style=\"display: inline;\"> ";
          foreach($resolutions as $key => $val) {
            if ($val == '1024') {
              $res = '1024 X 768';
            }
            elseif ($val == '1152') {
              $res = '1152 X 864';
            }
            elseif ($val == '1600') {
              $res = '1600 X 1200';
            }
            elseif ($val == '1920') {
              $res = '1920 X 1280';
            }
            elseif ($val == '360') {
              $res = 'iPhone 3G/3GS';
            }
            elseif ($val == '640') {
              $res = 'iPhone 4';
            }
            elseif ($val == 'org') {
              $res = 'Original';
            }
            else {
              $res = 'Not available';
            }
          $resolutions[$key] = JHTML::_('select.option', $val, $res);
        }
        $download_icon .= JHTML::_('select.genericlist', $resolutions, 'ad_download_resolutions', 'class="inputbox" style="font-size:9px"', 'value', 'text', $ad_download_resolutions);
        $download_icon .= '&nbsp;<input class="dgtip" id="'.JText::_('COM_DATSOGALLERY_SAVE_AS').'" type="image" src="'.JURI::base(true).'/components/com_datsogallery/images/'.$dg_theme.'/can_download.png" style="width:16px;height:16px;border:none;background:none;vertical-align:middle" />';
        $download_icon .= JHTML::_( 'form.token' );
        $download_icon .= "</form>";
        }
        else {
         $download_icon = '';
        }
        }
        else {
          $download_icon .= ''.dgTip(JText::_('COM_DATSOGALLERY_SAVE_AS'), $image = 'can_download.png', '', JRoute::_('index.php?option=com_datsogallery&amp;task=download&amp;catid='.$catid.'&amp;id='.$imageid.$itemid), '', $link = 1).'';
        }
      }
      elseif ($ad_showdownload && !$ad_downpub && !$user->id && !$ad_shop && $price == '0.00'
       || $ad_showdownload && !$ad_downpub && !$user->id && $ad_shop && $price == '0.00'
       ) {
          $download_icon = dgTip(JText::_('COM_DATSOGALLERY_LOGIN_FIRST'), $image = 'download_disable.png', '', $url, '', $link = 1);
       }
       else {
          $download_icon = '';
      }
      echo $download_icon.' ';
    }

    function DatsoDownload($id, $catid) {
      $app = JFactory::getApplication('site');
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $post = JRequest::get('post');
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'images.datsogallery.php');
      $menu = JSite::getMenu();
      $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
      $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
      $db->setQuery('SELECT status'
      .' FROM #__datsogallery_purchases'
      .' WHERE image_id = '.(int) $id
      .' AND user_id = '.(int) $user->id
      );
      $status = $db->loadResult();
      $db->setQuery('SELECT *'
      .' FROM #__datsogallery'
      .' WHERE id = '.(int) $id
      );
      $image = $db->loadObject();
      $ad_pathoriginals = str_replace('/', DS, $ad_pathoriginals);
      $dir = JPATH_SITE.$ad_pathoriginals.DS;
      if ($post['ad_download_resolutions'] !='' && $post['ad_download_resolutions'] != 'org') {
      if ($post['ad_download_resolutions'] == 360){
        $iphone_c = 1;
        $iphone_r = '3:4';
        $iphone_h = 640;
      }
      elseif ($post['ad_download_resolutions'] == 640) {
        $iphone_c = 1;
        $iphone_r = '3:4';
        $iphone_h = 960;
      }
      else {
        $iphone_h = $post['ad_download_resolutions'];
        $iphone_c = 0;
        $iphone_r = 0;
      }
      resize($image->imgoriginalname, $iphone_h, $post['ad_download_resolutions'], $iphone_c, $iphone_r, $ad_download_wm, $catid);
      $filename = getCacheFile($image->imgoriginalname, $iphone_h, $post['ad_download_resolutions'], $catid, $iphone_r);
      $filesize = getCacheFileSize($image->imgoriginalname, $iphone_h, $post['ad_download_resolutions'], $catid, $iphone_r);
      }
      elseif ($post['ad_download_resolutions'] !='' && $post['ad_download_resolutions'] == 'org') {
        $filename = $dir.DS.$image->imgoriginalname;
        $filesize = filesize($filename);
      }
      else {
        $filename = $dir.DS.$image->imgoriginalname;
        $filesize = filesize($filename);
      }
      $ext = strtolower(substr(strrchr($filename, '.'), 1));
      if ($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif') {
        $app->redirect(JRoute::_("index.php?option=com_datsogallery".$itemid, false));
      }
      else {
        if (is_file($filename)) {
          if ($ad_showdownload && $ad_downpub && !$ad_shop && $image->imgprice == '0.00'
           || $ad_showdownload && $ad_downpub && $ad_shop && $image->imgprice == '0.00'
           || $ad_showdownload && !$ad_downpub && $user->id && !$ad_shop && $image->imgprice == '0.00'
           || $ad_showdownload && !$ad_downpub && $user->id && $ad_shop && $image->imgprice == '0.00'
           || $ad_showdownload && $ad_downpub && $user->id && $ad_shop && $image->imgprice != '0.00' && $status == 'Completed'
           || $ad_showdownload && !$ad_downpub && $user->id && $ad_shop && $image->imgprice != '0.00' && $status == 'Completed' ) {
            adddownload($id);
            ob_clean();
            header('Pragma: public');
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: pre-check=0, post-check=0, max-age=0');
            header('Content-Transfer-Encoding: none');
            header('Accept-Ranges: bytes');
            header('Content-Length: '.$filesize);
            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename='.$image->imgoriginalname.'');
            readfile($filename);
            if ($post['ad_download_resolutions'] !='' && $post['ad_download_resolutions'] != 'org') {
              JFile::delete($filename);
            }
            exit;
            ob_end_flush();
            } else {
              $app->redirect(JRoute::_("index.php?option=com_datsogallery".$itemid, false), JText::_('COM_DATSOGALLERY_NOT_ACCESS_THIS_DIRECTORY'));
          }
        }
      }
    }

    function html2txt($document) {
      $search = array('@<script[^>]*?>.*?</script>@si', '@<[\/\!]*?[^<>]*?>@si', '@<style[^>]*?>.*?</style>@siU', '@<![\s\S]*?--[ \t\n\r]*>@');
      $text = preg_replace($search, '', $document);
      return $text;
    }

    function greatCommonDivisor($int1, $int2) {
      if (0 == $int1) {
        return 0 == $int2 ? 1 : $int2;
      }
      if (0 == $int2) {
        return 1;
      }
      if ($int1 == $int2) {
        return $int1;
      }
      if (0 == $int1 % 2 && 0 == $int2 % 2) {
        return 2 * greatCommonDivisor((integer) ($int1 / 2), (integer) ($int2 / 2));
      }
      if (0 == $int1 % 2) {
        return greatCommonDivisor((integer) ($int1 / 2), $int2);
      }
      if (0 == $int2 % 2) {
        return greatCommonDivisor((integer) ($int2 / 2), $int1);
      }
      return greatCommonDivisor($int1, abs($int2 - $int1));
    }

    function simplifyFraction($fraction) {
      if (preg_match('/^(\d+)\/(\d+)$/', $fraction, $matches)) {
        $numerator = $matches[1];
        $denominator = isset ($matches[2]) && $matches[2] ? $matches[2] : 1;
        $gcd = greatCommonDivisor($numerator, $denominator);
        return sprintf('%d/%d', $numerator / $gcd, $denominator / $gcd);
      }
      return $fraction;
    }

    function evalRational($value) {
      if (preg_match('/^(\d+)\/(\d+)$/', $value, $matches)) {
        $value = $matches[2] ? ($matches[1] / $matches[2]) : 0;
      }
      return (float) $value;
    }

    function metaGen($meta) {
      $app = JFactory::getApplication('site');
      jimport('joomla.filesystem.file');
      jimport('joomla.utilities.string');
      $lang = JFactory::getLanguage();
      $datsolang = strtolower($lang->getName());
      $datsolang = preg_replace("/\((.*?)\)/si", '', $datsolang);
      $words = JPATH_COMPONENT.DS.'words2ignore-'.$datsolang.'.txt';
      if (JFile::exists($words)) {
        $words = $words;
      }
      else {
        $msg = sprintf(JText::_('COM_DATSOGALLERY_META_GENERATOR_MSG'), 'words2ignore-'.$datsolang.'.txt');
        $msg = $app->enqueueMessage($msg, 'notice');
        return $msg;
      }
      $parsearray[] = JString::trim($meta);
      $parsestring  = JString::strtolower(join($parsearray, " "));
      $toremove = array('—', '“', '"', ',', '.', '-', '?', '•',
       ',', '.', ':', '!', '@', '#', '$', '%', '^', '&', '*',
       '(', ')', '_', '-', '1', '2', '3', '4', '5', '6', '7',
       '8', '9', '0', '.', ';', '{', '}', '[', ']', '|', '/',
       '<', '>', '+', '='
       );
      $parsestring = preg_replace('/[\r\n\t\s]+/s', ' ', $parsestring);
      $parsestring = html2txt($parsestring);
      $parsestring = str_replace($toremove, '', $parsestring);
      $commonwords = JFile::read($words);
      $commonwords = explode(' ', $commonwords);
      for ($i = 0; $i < count($commonwords); $i++) {
        $parsestring = str_replace(' '.$commonwords[$i].' ', ' ', $parsestring);
      }
      $parsestring = str_replace('  ', ' ', $parsestring);
      $wordsarray = explode(' ', $parsestring);
      for ($i = 0; $i < count($wordsarray); $i++) {
        $word = $wordsarray[$i];
        if (@$freqarray[$word]) {
          $freqarray[$word] += 1;
        }
        else {
          $freqarray[$word] = 1;
        }
      }
      @arsort($freqarray);
      $i = 0;
      while (list($key, $val) = @each($freqarray)) {
        $i++;
        $freqall[$key] = $val;
        if ($i == 10) {
          break;
        }
      }
      for ($i = 0; $i < count($wordsarray) - 1; $i++) {
        $j = $i + 1;
        $word2 = $wordsarray[$i].' '.$wordsarray[$j];
        if (@$freqarray2[$word2]) {
          $freqarray2[$word2] += 1;
        }
        else {
          $freqarray2[$word2] = 1;
        }
      }
      @arsort($freqarray2);
      $i = 0;
      while (list($key, $val) = @each($freqarray2)) {
        $i++;
        $freqall[$key] = $val;
        if ($i == 3) {
          break;
        }
      }
      for ($i = 0; $i < count($wordsarray) - 2; $i++) {
        $j = $i + 1;
        $word3 = $wordsarray[$i].' '.$wordsarray[$j].' '.$wordsarray[$j + 1];
        if (@$freqarray3[$word3]) {
          $freqarray3[$word3] += 1;
        }
        else {
          $freqarray3[$word3] = 1;
        }
      }
      @arsort($freqarray3);
      $i = 0;
      while (list($key, $val) = @each($freqarray3)) {
        $i++;
        $freqall[$key] = $val;
        if ($i == 1) {
          break;
        }
      }
      @arsort($freqall);
      $pagecontents = '';
      $keys = '';
      while (list($key, $val) = @each($freqall)) {
        $keys .= $key.', ';
      }
      rtrim($keys);
      return rtrim($keys, ', ');
    }

    function limit_words($string, $word_limit) {
      $words = explode(' ', $string);
      $toremove = array('—', '“', '"', ',', '.', '-', '?', '•',
       ',', '.', ':', '!', '@', '#', '$', '%', '^', '&', '*',
       '(', ')', '_', '-', '1', '2', '3', '4', '5', '6', '7',
       '8', '9', '0', '.', ';', '{', '}', '[', ']', '|', '/',
       '<', '>', '+', '='
       );
      $words = str_replace($toremove, "", $words);
      $words = preg_replace('/[\r\n\t\s]+/s', ' ', $words);
      $words = html2txt($words);
      return implode(' ', array_splice($words, 0, $word_limit));
    }

    function exifData($imagefile) {
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      if (function_exists('exif_read_data')) {
        if ($ad_exif) {
          $ext = strtolower(substr(strrchr(JPATH_SITE.$ad_pathoriginals.DS.$imagefile, '.'), 1));
          if ($ext == 'jpg' || $ext == 'jpeg') {
            $exif_data = @exif_read_data(JPATH_SITE.$ad_pathoriginals.DS.$imagefile, 'IFD0');
            $dg_exif = "";
            if (isset ($exif_data['Make']) != "") {
              echo "<span>";
              if ($exif_data['Make']) {
                $dg_exif .= "<span class=exifcamera>".$exif_data['Make']."</span><br />";
              }
              if (isset ($exif_data['Model']) != "") {
                $dg_exif .= "<span class=exifgray>".JText::_('COM_DATSOGALLERY_EXIF_MODEL').": <span class=exifolivedrab>".$exif_data['Model']."</span></span><br />";
              }
              if (isset ($exif_data['ExposureTime']) != "") {
                $dg_exif .= "<span class=exifgray>".JText::_('COM_DATSOGALLERY_EXIF_EXPOSURE').": <span class=exifolivedrab>".sprintf('%s (%01.3f sec)', simplifyFraction($exif_data['ExposureTime']), evalRational($exif_data['ExposureTime']))."</span></span><br />";
              }
              if (isset ($exif_data['FNumber']) != "") {
                $dg_exif .= "<span class=exifgray>".JText::_('COM_DATSOGALLERY_EXIF_APERTURE').": <span class=exifolivedrab>".sprintf('f/%01.1f', evalRational($exif_data['FNumber']))."</span></span><br />";
              }
              if (isset ($exif_data['FocalLength']) != "") {
                $dg_exif .= "<span class=exifgray>".JText::_('COM_DATSOGALLERY_EXIF_FOCALLENGTH').": <span class=exifolivedrab>".sprintf('%01.1f mm', evalRational($exif_data['FocalLength']))."</span></span><br />";
              }
              if (isset ($exif_data['ISOSpeedRatings']) != "") {
                $dg_exif .= "<span class=exifgray>".JText::_('COM_DATSOGALLERY_EXIF_ISO').": <span class=exifolivedrab>".$exif_data['ISOSpeedRatings']."</span></span><br />";
              }
              if (isset ($exif_data['DateTime']) != "") {
                $dg_exif .= "<span class=exifgray>".JText::_('COM_DATSOGALLERY_EXIF_DATETIME').": <span class=exifolivedrab>".$exif_data['DateTime']."</span></span><p></p>";
              }
              return '<strong>EXIF</strong>: '.dgTip($dg_exif, 'camera.png', '', '', 0);
              echo "</span>";
            }
          }
        }
      }
    }

    function dgPath($path) {
      $path = str_replace('/', DS, $path);
      return $path;
    }

    function mb_preg_replace($pattern, $replacement, $subject) {
      return preg_replace($pattern."u", $replacement, $subject);
    }

    function DatsoBookmarker($id, $title, $desc) {
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      jimport('joomla.utilities.string');
      $desc_tags = addslashes(str_replace("\n", "", $title));
      $desc_tags = trim($desc_tags);
      $desc_tags_space = str_replace(',', ' ', @$desc_tags_space);
      $desc_tags_semi = str_replace(',', ';', @$desc_tags_semi);
      $desc_tags_space = str_replace('  ', ' ', @$desc_tags_space);
      $description1 = strip_tags($desc);
      $description2 = str_replace("'", '', strip_tags($description1));
      $description = str_replace('"', '', strip_tags($description2));
      $markme_title = $desc_tags;
      $markme_ddesc = JString::substr($description, 0, 400).'...';
      $baseurl = JURI::base();
      $google = JRequest::getVar('google', '1');
      $facebook = JRequest::getVar('facebook', '1');
      $twitter = JRequest::getVar('twitter', '1');
      $myspace = JRequest::getVar('myspace', '1');
      $linkedin = JRequest::getVar('linkedin', '1');
      $yahoo = JRequest::getVar('yahoo', '1');
      $digg = JRequest::getVar('digg', '1');
      $del = JRequest::getVar('del', '1');
      $live = JRequest::getVar('live', '1');
      $furl = JRequest::getVar('furl', '1');
      $reddit = JRequest::getVar('reddit', '1');
      $technorati = JRequest::getVar('technorati', '1');
      $html = '';

      if ($ad_google == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://www.google.com" onclick="window.open(\'http://www.google.com/bookmarks/mark?op=add&amp;hl=en&amp;bkmk=\'+encodeURIComponent(location.href)+\'&amp;annotation='.$markme_ddesc.'&amp;labels='.$desc_tags.'&amp;title='.$markme_title.'\');return false;">
<img style="vertical-align:bottom;padding:1px;" align="baseline" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/google.png".'" title="Google" name="Google" border="0" id="Google" alt="" />
'.JText::_('Google').'
</a>
</div>';
      }
      if ($ad_facebook == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://www.facebook.com/" onclick="window.open(\'http://www.facebook.com/sharer.php?u=\'+encodeURIComponent(location.href)+\'&amp;t='.$markme_title.'&amp;d='.$markme_ddesc.'\');return false;">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/facebook.png".'" title="Facebook" name="facebook" border="0" id="facebook" alt="" />
'.JText::_('Facebook').'
</a>
</div>';
      }
      if ($ad_twitter == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://www.twitter.com/" onclick="window.open(\'http://twitter.com/home/?status=\'+encodeURIComponent(location.href)+\'-'.$markme_ddesc.'\');return false;">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/twitter.png".'" title="twitter" name="twitter" border="0" id="twitter" alt="" />
'.JText::_('Twitter').'
</a>
</div>';
      }
      if ($ad_myspace == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://www.myspace.com/" onclick="window.open(\'http://www.myspace.com/index.cfm?fuseaction=postto&amp;\' + \'t='.$markme_title.'&amp;u=\' + encodeURIComponent(location.href));return false;">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/myspace.png".'" title="Myspace" name="myspace" border="0" id="myspace" alt="" />
'.JText::_('Myspace').'
</a>
</div>';
      }
      if ($ad_linkedin == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://www.linkedin.com/" onclick="window.open(\'http://www.linkedin.com/shareArticle?mini=true&amp;url=\'+encodeURIComponent(location.href)+\'&amp;title='.$markme_title.'&amp;summary='.$markme_ddesc.'\');return false;">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/linkedin.png".'" title="LinkedIn" name="linkedin" border="0" id="linkedin" alt="" />
'.JText::_('Linkedin').'
</a>
</div>';
      }
      if ($ad_yahoo == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://www.yahoo.com" onclick="window.open(\'http://myweb2.search.yahoo.com/myresults/bookmarklet?t='.$markme_title.'&amp;d='.$markme_ddesc.'&amp;tag='.$desc_tags.'&amp;u=\'+encodeURIComponent(location.href)); return false;">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/yahoo.png".'" title="Yahoo" name="Yahoo" border="0" id="Yahoo" alt="" />
'.JText::_('Yahoo').'
</a>
</div>';
      }
      if ($ad_digg == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://digg.com" onclick="window.open(\'http://digg.com/submit?phase=2&amp;url=\'+encodeURIComponent(location.href)+\'&amp;bodytext='.$markme_ddesc.'&amp;tags='.$desc_tags_space.'&amp;title='.$markme_title.'\');return false;">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/digg.png".'" title="Digg" name="Digg" border="0" id="Digg" alt="" />
'.JText::_('Digg').'
</a>
</div>';
      }
      if ($ad_del == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://delicious.com/" onclick="window.open(\'http://delicious.com/save?v=5&amp;url=\'+encodeURIComponent(location.href)+\'&amp;notes='.$markme_ddesc.'&amp;tags='.$desc_tags_space.'&amp;title='.$markme_title.'\');return false;">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/delicious.png".'" title="Delicious" name="Delicious" border="0" id="Delicious" alt="" />
'.JText::_('Delicious').'
</a>
</div>';
      }
      if ($ad_live == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="https://favorites.live.com/" onclick="window.open(\'https://favorites.live.com/quickadd.aspx?url=\'+encodeURIComponent(location.href));return false;">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/windows.png".'" title="Windows Live" name="WindowsLive" border="0" id="WindowsLive" alt="" />
'.JText::_('Windows Live').'
</a>
</div>';
      }
      if ($ad_furl == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://www.furl.net/" onclick="window.open(\'http://www.furl.net/storeIt.jsp?u=\'+encodeURIComponent(location.href)+\'&amp;keywords='.$desc_tags.'&amp;t='.$markme_title.'\');return false; ">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/furl.png".'" title="Furl" name="Furl" border="0" id="Furl" alt="" />
'.JText::_('Furl').'
</a>
</div>';
      }
      if ($ad_reddit == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://reddit.com" onclick="window.open(\'http://reddit.com/submit?url=\'+encodeURIComponent(location.href)+\'&amp;title='.$markme_title.'\');return false;">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/reddit.png".'" title="Reddit" name="Reddit" border="0" id="Reddit" alt="" />
'.JText::_('Reddit').'
</a>
</div>';
      }
      if ($ad_technorati == 1) {
        $html .= '<div style="width:100px;float:left">
<a style="text-decoration:none;" href="http://www.technorati.com/" onclick="window.open(\'http://technorati.com/faves?add=\'+encodeURIComponent(location.href)+\'&amp;tag='.$desc_tags_space.'\');return false; ">
<img style="vertical-align:bottom;padding:1px;" src="'.$baseurl."components/com_datsogallery/images/".$dg_theme."/bookmarker/technorati.png".'" title="Technorati" name="Technorati" border="0" id="Technorati" alt="" />
'.JText::_('Technorati').'
</a>
</div>';
      }
      return $html;
    }

  function commentNotify($id, $name, $email, $comment){
    $app  = JFactory::getApplication('site');
    $db   = JFactory::getDBO();
    $menu = JSite::getMenu();
    $url  = JURI::getInstance();
    $ids  = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
    $itemId = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
    $db->setQuery('SELECT a.catid, a.imgtitle, u.name, u.email'
    .' FROM #__datsogallery AS a,'
    .' #__users AS u'
    .' WHERE a.id = '.(int) $id
    .' AND a.owner_id = u.id'
    );
    $row = $db->loadObject();
    $root = $url->toString( array('scheme', 'host', 'port') );
    $link = $root.JRoute::_('index.php?option=com_datsogallery&task=image&catid='.$row->catid.'&id='.$id.$itemId);
    $from = $app->getCfg('mailfrom');
    $fromname = $app->getCfg('fromname');
    $text  = sprintf(JText::_('COM_DATSOGALLERY_COMMENT_NOTIFY_HELLO'), $row->name)."\r\n\r\n";
    $text .= sprintf(JText::_('COM_DATSOGALLERY_COMMENT_NOTIFY_USER'), $name, $email, $row->imgtitle)."\r\n\r\n---\r\n".$comment."\r\n---\r\n\r\n";
    $text .= $link."\r\n\r\n";
    $text .= JText::_('COM_DATSOGALLERY_MAIL_MSG');
    $subject = sprintf(JText::_('COM_DATSOGALLERY_COMMENT_NOTIFY_SUBJECT'), $app->getCfg('sitename'));
    JUTility::sendMail($from, $fromname, $row->email, $subject, dgwordwrap($text), false);
    exit;
  }

  function commentAdd() {
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $post = JRequest::get('post');
    $post = array_map('addslashes', $post);
    $cmtip = getIpAddress();
    jimport('joomla.mail.helper');
    require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
    if ($post) {
      if (refererCheck()) {
        echo '<li class="dg_body_error_message"><div>'.refererCheck().'</div></li>';
      }
      elseif (blacklistCheck($cmtip)) {
        echo '<li class="dg_body_error_message"><div>'.blacklistCheck($cmtip).'</div></li>';
      }
      elseif (empty ($post['cmtname'])) {
        echo '<li class="dg_body_error_message"><div>'.JText::_('COM_DATSOGALLERY_ENTER_NAME').'</div></li>';
      }
      elseif (empty ($post['cmtmail'])) {
        echo '<li class="dg_body_error_message"><div>'.JText::_('COM_DATSOGALLERY_ENTER_EMAIL').'</div></li>';
      }
      elseif (JMailHelper::isEmailAddress($post['cmtmail']) == false) {
        echo '<li class="dg_body_error_message"><div>'.JText::_('COM_DATSOGALLERY_INVALID_EMAIL').'</div></li>';
      }
      elseif (empty ($post['cmttext'])) {
        echo '<li class="dg_body_error_message"><div>'.JText::_('COM_DATSOGALLERY_ENTER_COMMENT').'</div></li>';
      }
      elseif (empty ($post['dgcaptchaval'])) {
        echo '<li class="dg_body_error_message"><div>'.JText::_('COM_DATSOGALLERY_ENTER_CODE').'</div></li>';
      }
      else {
        if (!$user->id && strlen($post['cmtname']) < 4) {
          echo '<li class="dg_body_error_message"><div>'.JText::_('COM_DATSOGALLERY_NAME_IS_TOO_SHORT').'</div></li>';
        }
        elseif (empty($_SESSION['CAPTCHA']) || strtolower($post['dgcaptchaval']) != $_SESSION['CAPTCHA']) {
          echo '<li class="dg_body_error_message"><div>'.JText::_('COM_DATSOGALLERY_SECURITY_NOT_VALUE').'</div></li>';
        }
        else {
          $db->setQuery("INSERT INTO #__datsogallery_comments SET cmtpic='".$post['cmtpic']."', cmtip='".$cmtip."', cmtname='".$post['cmtname']."', cmtmail='".$post['cmtmail']."', cmttext='".$post['cmttext']."', cmtdate ='".time()."', published = 1");
          $db->query();
          echo "<li class=\"pane\">\n";
          echo "<div class=\"imgblock\">";
          if($ad_js || $ad_cb || $ad_kunena) {
          $avatar = ($user->id) ? getUserAvatar($user->id) : getUserAvatar(0);
          }
          else {
          $avatar = getGravatar($post['cmtmail']);
          }
          echo $avatar;
          echo "</div>";
          echo '<div style="display:block;min-height:60px;margin-left:70px;padding-bottom:20px">'.stripslashes(nl2br($post['cmttext'])).'</div>';
          echo '<div class="date">'.sprintf(JText::_('COM_DATSOGALLERY_ON'), $post['cmtname'], strftime($ad_datef, time())).'</div>';
      	  echo "</li>\n";
          $db->setQuery('SELECT notify'
          .' FROM #__datsogallery'
          .' WHERE id = '.$post['cmtpic']
          );
          $unotify = $db->loadResult();
          if ($ad_comment_notify && $unotify !=0) :
            commentNotify($post['cmtpic'], $post['cmtname'], $post['cmtmail'], stripslashes(dgwordlimiter($post['cmttext'],$ad_comment_wl)));
          endif;
        }
      }
      exit;
    }
  }

  function dgwordlimiter($text, $limit = 100){
    $explode = explode(' ',$text);
    $string  = '';

    $dots = '...';
    if(count($explode) <= $limit){
        $dots = '';
    }
    for($i=0;$i<$limit;$i++){
        $string .= @$explode[$i]." ";
    }

    return $string.$dots;
  }

  function dgwordwrap($str, $width = 100, $break = "\r\n") {
    jimport('joomla.utilities.string');
    if (empty ($str) || JString::strlen($str) <= $width)
      return $str;
    $br_width = JString::strlen($break);
    $str_width = JString::strlen($str);
    $return = '';
    $last_space = false;
    for ($i = 0, $count = 0; $i < $str_width; $i++, $count++) {
      if (JString::substr($str, $i, $br_width) == $break) {
        $count = 0;
        $return .= JString::substr($str, $i, $br_width);
        $i += $br_width - 1;
        continue;
      }
      if (JString::substr($str, $i, 1) == " ") {
        $last_space = $i;
      }
      if ($count > $width) {
        if (!$last_space) {
          $return .= $break;
          $count = 0;
        }
        else {
          $drop = $i - $last_space;
          if ($drop > 0) {
            $return = JString::substr($return, 0, - $drop);
          }
          $return .= $break;
          $i = $last_space + ($br_width - 1);
          $last_space = false;
          $count = 0;
        }
      }
      $return .= JString::substr($str, $i, 1);
    }
    return $return;
  }

    function addTag() {
    jimport('joomla.utilities.string');
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $userGroups = JAccess::getGroupsByUser($user->id, true);
    $admins = array(7,8);
    $is_admin = array_intersect($admins, $userGroups);
    $post = JRequest::get('post');
    require (JPATH_COMPONENT_ADMINISTRATOR . DS . 'config.datsogallery.php');
    $query = 'SELECT tag'
    . ' FROM #__datsogallery_tags'
    . ' WHERE image_id = ' . (int) $post['id']
    ;
    $db->setQuery($query);
    $rows = $db->LoadResultArray();
    $input = JString::strtolower($post['newtag']);
    $newtag = preg_replace('/[^\pN\pL\pM\pZ]/iu','',$input);
    $num = count($rows);
    if (!empty ($newtag) && ((int) $post['id']) && JString::strlen($newtag) > $ad_min_tag_chars && !in_array($newtag, $rows)) {
      if (!$is_admin && $num >= $ad_max_tags) {
      echo 1;
      exit;
      }
      else {
        $db->setQuery('INSERT INTO #__datsogallery_tags VALUES (' . $db->Quote("")
      . ', ' . (int) $post['id'] . ', ' . $user->id . ', NOW(), '
      . $db->Quote($newtag) . ', 0)'
      );
      $db->query() or die($db->stderr());
      echo showTags((int) $post['id']);
      exit;
      }
    }
    else if (empty ($newtag)) {
      echo 2;
      exit;
    }
    else if (JString::strlen($newtag) <= $ad_min_tag_chars && !in_array($newtag, $rows)) {
      echo 3;
      exit;
    }
    else if (in_array($newtag, $rows)) {
      echo 4;
      exit;
    }
    else {
      echo '';
      exit;
    }
  }

  function removeTag() {
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $userGroups = JAccess::getGroupsByUser($user->id, true);
    $groups = $user->getAuthorisedViewLevels();
    $allowed = in_array($ad_access_tags, $groups);
    $is_admin = array(7,8);
    $post = JRequest::get('post');
    $query = 'SELECT user_id'
    . ' FROM #__datsogallery_tags'
    . ' WHERE id = ' . (int) $post['tagid']
    ;
    $db->setQuery($query);
    $can = $db->LoadResult();
    if (!empty($post['tagid']) && ($post['id'])) {
      if ($can == $user->id)
      $db->setQuery('DELETE FROM #__datsogallery_tags'
      .' WHERE id = ' . (int) $post['tagid']
      );
      $db->query() or die($db->stderr());
      exit;
    }
    else {
      exit;
    }
  }

  function showTags($id) {
    require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $userGroups = JAccess::getGroupsByUser($user->id, true);
    $groups = $user->getAuthorisedViewLevels();
    $allowed = in_array($ad_access_tags, $groups);
    $is_admin = array(7,8);
    $menu = JSite::getMenu();
    $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
    $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
    $query = 'SELECT *'
    . ' FROM #__datsogallery_tags'
    . ' WHERE image_id = ' . (int) $id
    ;
    $db->setQuery($query);
    $result = $db->query();
    if ($result) {
      $tags = array();
      while ($row = dgFetchArray($result)) {
        $indent = ($row['user_id'] == $user->id  && $allowed
        || array_intersect($is_admin, $userGroups)) ? ' style="text-indent: 14px"' : '';
        $candel = ($row['user_id'] == $user->id && $allowed
        || array_intersect($is_admin, $userGroups)) ? '<div id="%s" class="deltag" title="'
        .JText::_('COM_DATSOGALLERY_REMOVE_TAG').'">x</div>' : '';
        $tags[] = '<div'.$indent.'>'.JText::sprintf($candel, $row['id']).'<a href="'
        . JRoute::_('index.php?option=com_datsogallery'.$itemid.'&task=tag&tagval='
        . urlencode($row['tag'])) . '" title="' . $row['tag'] . '">' . $row['tag']
        . '</a></div>';
      }
      $tags = implode(' ', $tags);
    }
    $out = (empty($tags) ? JText::_('COM_DATSOGALLERY_NO_TAGS') : $tags);
    return $out;
  }

    function editComment() {
      $app = JFactory::getApplication('site');
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $userGroups = JAccess::getGroupsByUser($user->id, true);
      $is_admin = array(7,8);
      $post = JRequest::get('post');
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $cmtip = getIpAddress();
      $db->setQuery('SELECT cmtip'
      .' FROM #__datsogallery_comments'
      .' WHERE cmtid = '.(int) $post['cmtid']
      );
      $dbip = $db->loadResult();
      $cmtdate = time();
      if (array_intersect($is_admin, $userGroups) || $dbip == $cmtip) {
        if ($post['cmtid']) {
          $db->setQuery('UPDATE #__datsogallery_comments'
          .' SET cmttext = '.$db->Quote($post['cmttext'])
          .', cmtdate = '.$db->Quote(time())
          .' WHERE cmtid = '.(int) $post['cmtid']
          );
          $db->query() or die($db->stderr());
          echo stripslashes(nl2br($post['cmttext']));
          exit;
        } else {
          echo 0;
          exit;
        }
      }
    }

    function delComment() {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $userGroups = JAccess::getGroupsByUser($user->id, true);
      $is_admin = array(7,8);
      $cmtid = JRequest::getVar('cmtid', 0, 'post', 'int');
      if (array_intersect($is_admin, $userGroups)) {
        if (isset($cmtid)) {
          $db->setQuery('DELETE FROM #__datsogallery_comments WHERE cmtid = '.$cmtid);
          $db->query() or die($db->stderr());
          exit;
        }
      }
    }

    function approveComment(){
    $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $userGroups = JAccess::getGroupsByUser($user->id, true);
      $is_admin = array(7,8);
      $cmtid = JRequest::getVar('cmtid', 0, 'post', 'int');
      if (array_intersect($is_admin, $userGroups)) {
        if (isset($cmtid)) {
          $db->setQuery('UPDATE #__datsogallery_comments SET published = 1 WHERE cmtid = '.$cmtid);
          $db->query() or die($db->stderr());
          exit;
        }
      }
    }

    function unapproveComment(){
    $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $userGroups = JAccess::getGroupsByUser($user->id, true);
      $is_admin = array(7,8);
      $cmtid = JRequest::getVar('cmtid', 0, 'post', 'int');
      if (array_intersect($is_admin, $userGroups)) {
        if (isset($cmtid)) {
          $db->setQuery('UPDATE #__datsogallery_comments SET published = 0 WHERE cmtid = '.$cmtid);
          $db->query() or die($db->stderr());
          exit;
        }
      }
    }

    function spamComment(){
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $userGroups = JAccess::getGroupsByUser($user->id, true);
      $is_admin = array(7,8);
      $cmtid = JRequest::getVar('cmtid', 0, 'post', 'int');
      if (array_intersect($is_admin, $userGroups)) {
        if (isset($cmtid)) {
          $db->setQuery('SELECT cmtip FROM #__datsogallery_comments WHERE cmtid = '.$cmtid);
          $cmtip = $db->loadResult();
          $db->setQuery("INSERT INTO `#__datsogallery_blacklist` VALUES ( '', ".$db->Quote($cmtip).", 1, NOW() )");
          $db->query() or die($db->stderr());
          $db->setQuery('DELETE FROM #__datsogallery_comments WHERE cmtip = '.$db->Quote($cmtip));
          $db->query() or die($db->stderr());
          exit;
        }
      }
    }

    function editImageTitle() {
      $app = JFactory::getApplication('site');
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $userGroups = JAccess::getGroupsByUser($user->id, true);
      $is_admin = array(7,8);
      $post = JRequest::get('post');
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $db->setQuery('SELECT owner_id'
      .' FROM #__datsogallery'
      .' WHERE id = '.(int) $post['id']
      );
      $owner = $db->loadResult();
      if (array_intersect($is_admin, $userGroups) || $user->id == $owner) {
        if ($post['id']) {
          $db->setQuery('UPDATE #__datsogallery'
          .' SET imgtitle = '.$db->Quote($post['imgtitle'])
          .' WHERE id = '.(int) $post['id']
          );
          $db->query() or die($db->stderr());
          echo stripslashes(nl2br($post['imgtitle']));
          exit;
        } else {
          echo 0;
          exit;
        }
      }
    }

    function editImageDesc() {
      $app = JFactory::getApplication('site');
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $userGroups = JAccess::getGroupsByUser($user->id, true);
      $is_admin = array(7,8);
      $post = JRequest::get('post');
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $db->setQuery('SELECT owner_id'
      .' FROM #__datsogallery'
      .' WHERE id = '.(int) $post['id']
      );
      $owner = $db->loadResult();
      if (array_intersect($is_admin, $userGroups) || $user->id == $owner) {
        if ($post['id']) {
          $db->setQuery('UPDATE #__datsogallery'
          .' SET imgtext = '.$db->Quote($post['imgtext'])
          .' WHERE id = '.(int) $post['id']
          );
          $db->query() or die($db->stderr());
          echo stripslashes(nl2br($post['imgtext']));
          exit;
        } else {
          echo 0;
          exit;
        }
      }
    }

    function captcha(){
    require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.captcha.php');
	$captcha = new captcha();
    $_SESSION['CAPTCHA'] = $captcha->getCaptcha();
	exit;
    }

    function refererCheck() {
     $referer = substr($_SERVER['HTTP_REFERER'], 7, strlen($_SERVER['SERVER_NAME']));
     if ($referer != $_SERVER['SERVER_NAME'] && $referer != "www.".$_SERVER['SERVER_NAME']) {
       $msg = HTTP_REFERER;
       return $msg;
     }
    }

    function blacklistCheck($ip){
      $db = JFactory::getDBO();
      $db->setQuery('SELECT *'
      .' FROM #__datsogallery_blacklist'
      .' WHERE ip = '.$db->Quote($ip)
      .' AND published = 1'
      );
      $db->query() or die($db->stderr());
      $found = $db->loadObject();
      if ($found) {
        $msg = JText::_('COM_DATSOGALLERY_SPAM_MSG');
        return $msg;
       }
      }

    function remove_http($url=''){
      return preg_replace("/^https?:\/\/(.+)$/i","\\1", $url);
    }

    function getUserAvatar($id){
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $db = JFactory::getDBO();
      if($ad_js){
        $db->setQuery('SELECT thumb FROM #__community_users WHERE userid = '.(int) $id);
        $thumb = $db->loadResult();
        $menu = JSite::getMenu();
        $js_ids = $menu->getItems('link', 'index.php?option=com_community&view=frontpage');
        $js_itemid = isset ($js_ids[0]) ? '&amp;Itemid='.(int) $js_ids[0]->id : '';
        if($id && $thumb){
          $avatar = '<a href="'.JRoute::_('index.php?option=com_community&amp;view=profile&amp;userid='.(int) $id.$js_itemid)
          .'" title="Profile Page"><img src="'.JURI::base().$thumb.'" class="dg-avatar" alt="" /></a>';
        }
        elseif($id && !$thumb){
          $default_thumb = 'components/com_community/assets/user_thumb.png';
          $avatar = '<a href="'.JRoute::_('index.php?option=com_community&amp;view=profile&amp;userid='.(int) $id.$js_itemid)
          .'" title="Profile Page"><img src="'.JURI::base().$default_thumb.'" class="dg-avatar" alt="" /></a>';
        }
        else {
          $default_thumb = 'components/com_community/assets/user_thumb.png';
          $avatar = '<img src="'.JURI::base().$default_thumb.'" class="dg-avatar" alt="" />';
        }
      }
      elseif($ad_cb){
        $db->setQuery('SELECT avatar FROM #__comprofiler WHERE user_id = '.(int) $id);
        $cb_avatar = $db->loadResult();
        $menu = JSite::getMenu();
        $cb_ids = $menu->getItems('link', 'index.php?option=com_comprofiler');
        $cb_itemid = isset ($cb_ids[0]) ? '&amp;Itemid='.(int) $cb_ids[0]->id : '';
        if($id && $cb_avatar){
          $avatar = '<a href="'.JRoute::_('index.php?option=com_comprofiler&task=userprofile&user='.(int) $id.$cb_itemid)
          .'" title="Profile Page"><img src="'.JURI::base().'images/comprofiler/'.$cb_avatar.'" class="dg-avatar" alt="" /></a>';
        }
        elseif ($id && !$cb_avatar) {
          $default_cb = 'components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png';
          $avatar = '<a href="'.JRoute::_('index.php?option=com_comprofiler&task=userprofile&user='.(int) $id.$cb_itemid)
          .'" title="Profile Page"><img src="'.JURI::base().$default_cb.'" class="dg-avatar" alt="" /></a>';
        }
        else {
          $default_cb = 'components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png';
          $avatar = '<img src="'.JURI::base().$default_cb.'" class="dg-avatar" alt="" />';
        }
      }
      elseif($ad_kunena){
        $db->setQuery('SELECT avatar FROM #__kunena_users WHERE userid = '.(int) $id);
        $kn_avatar = $db->loadResult();
        $menu = JSite::getMenu();
        $kn_ids = $menu->getItems('link', 'index.php?option=com_kunena&view=profile');
        $kn_itemid = isset ($kn_ids[0]) ? '&amp;Itemid='.(int) $kn_ids[0]->id : '';
        if($id && $kn_avatar){
          $avatar = '<a href="'.JRoute::_('index.php?option=com_kunena&amp;view=profile&amp;userid='.(int) $id.$kn_itemid)
          .'"><img src="'.JURI::root().'media/kunena/avatars/resized/size72/'.$kn_avatar.'" class="dg-avatar" alt="Profile Page" /></a>';
        }
        elseif ($id && !$kn_avatar) {
          $avatar = '<a href="'.JRoute::_('index.php?option=com_kunena&amp;view=profile&amp;userid='.(int) $id.$kn_itemid)
          .'" title="Profile Page"><img src="'.JURI::root().'media/kunena/avatars/s_nophoto.jpg" class="dg-avatar" alt="Profile Page" /></a>';
        }
        else {
          $avatar = '<img src="'.JURI::root().'media/kunena/avatars/s_nophoto.jpg" class="dg-avatar" alt="" />';
        }
      }
      elseif ($id && !$ad_js || !$ad_cb || !$ad_kunena){
        $db->setQuery('SELECT email FROM #__users WHERE id = '.(int) $id);
        $email = $db->loadResult();
        if(!$email)
          $db->setQuery('SELECT cmtmail'
          .' FROM #__datsogallery_comments'
          .' WHERE cmtid = '.(int) $id
          );
        $email = $db->loadResult();
        $d  = JURI::base().'components/com_datsogallery/images/guest.gif';
        $g  = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($email)));
        $g .= '?s=50&d='.$d;
        $avatar = '<img src="'.$g.'" class="dg-avatar" alt="" />';
      } else {
        $thumb = 'components/com_community/assets/default_thumb.jpg';
        $avatar = '<img src="'.JURI::base().$thumb.'" class="dg-avatar" alt="" />';
      }
      return $avatar;
    }

    function getGravatar($email) {
      $d  = JURI::base().'components/com_datsogallery/images/guest.gif';
      $g  = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($email)));
      $g .= '?s=50&d='.$d;
      return '<img src="'.$g.'" class="dg-avatar" alt="" />';
    }

    function uploadNotify($name, $category){
      $app = JFactory::getApplication('site');
      $db = JFactory::getDBO();
      $db->setQuery('SELECT u.*'
      .' FROM #__users AS u'
      .' LEFT JOIN #__user_usergroup_map AS map ON map.user_id = u.id'
      .' WHERE map.group_id IN (7,8)'
      );
      $rows = $db->loadObjectList();
      if (count($rows) > 0) {
        foreach ($rows as $row) {
          $from = $app->getCfg('mailfrom');
          $fromname = $app->getCfg('fromname');
          $text  = sprintf(JText::_('COM_DATSOGALLERY_COMMENT_NOTIFY_HELLO'), $row->name)."\r\n\r\n";
          $text .= sprintf(JText::_('COM_DATSOGALLERY_UPLOAD_NOTIFY_ADMIN'), $name, $category)."\r\n\r\n";
          $text .= JText::_('COM_DATSOGALLERY_MAIL_MSG');
          $subject = sprintf(JText::_('COM_DATSOGALLERY_UPLOAD_NOTIFY_SUBJECT'), $app->getCfg('sitename'));
          JUTility::sendMail($from, $fromname, $row->email, $subject, dgwordwrap($text), false);
        }
      }
    }

    function categoryNotify($name, $category){
      $app = JFactory::getApplication('site');
      $db = JFactory::getDBO();
      $db->setQuery('SELECT u.*'
      .' FROM #__users AS u'
      .' LEFT JOIN #__user_usergroup_map AS map ON map.user_id = u.id'
      .' WHERE map.group_id IN (7,8)'
      );
      $rows = $db->loadObjectList();
      if (count($rows) > 0) {
        foreach ($rows as $row) {
          $from = $app->getCfg('mailfrom');
          $fromname = $app->getCfg('fromname');
          $text  = sprintf(JText::_('COM_DATSOGALLERY_COMMENT_NOTIFY_HELLO'), $row->name)."\r\n\r\n";
          $text .= sprintf(JText::_('COM_DATSOGALLERY_CATEGORY_NOTIFY_ADMIN'), $name, $category)."\r\n\r\n";
          $text .= JText::_('COM_DATSOGALLERY_MAIL_MSG');
          $subject = sprintf(JText::_('COM_DATSOGALLERY_CATEGORY_NOTIFY_SUBJECT'), $app->getCfg('sitename'));
          JUTility::sendMail($from, $fromname, $row->email, $subject, dgwordwrap($text), false);
        }
      }
    }

    function batchUpload() {
      $app = JFactory::getApplication('site');
      jimport('joomla.filesystem.folder');
      jimport('joomla.filesystem.file');
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'class.datsogallery.php');
      require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'images.datsogallery.php');
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $userGroups = JAccess::getGroupsByUser($user->id, true);
      $is_admin = array(7, 8);
      $catid = JRequest::getVar('catid', 0, 'post', 'int');
      $menu = JSite::getMenu();
      $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
      $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
      $tmp_dir = JPATH_SITE.DS.'tmp'.DS.$user->id;
      dgChmod($tmp_dir, 0777);
      $dir = opendir($tmp_dir);
      while (false !== ($file = readdir($dir))) {
        $files[] = $file;
      }
      sort($files);
      $i = 0;
      foreach ($files as $file) {
        if ($file != '.'
        && $file != '..'
        && (strcasecmp($file, 'index.html') != 0)
        && (strcasecmp($file, 'Thumbs.db') != 0)) {
          $i++;
          $count = ($i > 1) ? ' '.$i : '';
          $origfilename = $file;
          $imagetype = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG');
          $imginfo = getimagesize($tmp_dir.DS.$origfilename);
          $ext = strtolower($imagetype[$imginfo[2]]);
          if (is_dir($tmp_dir.DS.$origfilename)) {
            JFolder::delete($tmp_dir);
            $msg = JText::_('COM_DATSOGALLERY_ZIP_PACKAGE_ERROR');
            $app->redirect(JRoute::_('index.php?option=com_datsogallery&task=userpanel'.$itemid, false), $msg, 'error');
          }
          if (!$ext) {
            JFile::delete($tmp_dir.DS.$origfilename);
          }
          else {
            $newfilename = ($ad_fname) ? $origfilename : dgImgId($catid, $ext);
            JFile::copy($tmp_dir.DS.$origfilename, JPath::clean(JPATH_SITE.$ad_pathoriginals.DS.$newfilename));
            JFile::delete($tmp_dir.DS.$origfilename);
            $row = new DatsoImages($db);
            if (!$row->bind(JRequest::get('post'))) {
              return JError::raiseWarning(500, $row->getError());
            }
            $row->ordering = $row->getNextOrder('catid = '.$catid);
            $row->imgtitle = JRequest::getVar('gentitle').$count;
            $row->imgtext = JRequest::getVar('gendesc');
            $row->imgauthor = JRequest::getVar('genimgauthor');
            $row->imgauthorurl = JRequest::getVar('genimgauthorurl');
            $row->imgdate = mktime();
            $row->owner_id = $user->id;
            $row->published = 1;
            $row->approved = ($ad_approve && !array_intersect($is_admin, $userGroups)) ? 0 : 1;
            $row->imgoriginalname = $newfilename;
            $row->useruploaded = (array_intersect($is_admin, $userGroups)) ? 0 : 1;
            if (!$row->store()) {
              JError::raiseError(500, $row->getError());
            }
            else {
              require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_messages'.DS.'tables'.DS.'message.php');
              $lang = JFactory::getLanguage();
              $lang->load('com_messages');
              $query = 'SELECT id FROM #__users WHERE sendEmail = 1';
              $db->setQuery($query);
              $users = $db->loadResultArray();
              foreach ($users as $user_id) {
                $msg = new MessagesTableMessage($db);
                $msg->publish($user->id, $user_id, JText::_('COM_DATSOGALLERY_NEW_PIC_SUBMITED'),
                sprintf(JText::_('COM_DATSOGALLERY_NEW_CONTENT_SUBMITED')
                ."%s ".JText::_('COM_DATSOGALLERY_TITLED')." %s.",
                $user->username, $row->imgtitle));
              }
            }
          }
        }
      }
      closedir($dir);
      if (JFolder::exists($tmp_dir)) {
        JFolder::delete($tmp_dir);
      }
      $db->setQuery('SELECT u.username, c.name AS category'
      .' FROM #__users AS u,'
      .' #__datsogallery_catg AS c'
      .' WHERE u.id = '.$user->id
      .' AND c.cid = '.$catid
      );
      $row = $db->loadObject();
      if ($ad_upload_notify) :
        uploadNotify($row->username, $row->category);
      endif;
      $app->redirect(JRoute::_('index.php?option=com_datsogallery&task=userpanel'.$itemid, false),
      JText::_('COM_DATSOGALLERY_UPLOAD_SUCCESS'));
    }

    function addToFavorites() {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $id = JRequest::getVar('id', 0, 'post', 'int');
      if ($user->id && $ad_favorite) {
        $db->setQuery('SELECT * FROM #__datsogallery_favorites WHERE user_id = '.(int) $user->id.' AND image_id = '.(int) $id);
        $db->query() or die($db->stderr());
        $found = $db->loadObject();
        if (!$found) {
          $db->setQuery('INSERT INTO #__datsogallery_favorites VALUES ( "", '.$id.', '.$user->id.' )');
          $db->query() or die($db->stderr());
          echo '<div class="dgtip" title="'.JText::_('COM_DATSOGALLERY_REMOVE_FAVORITE').'"><span class="remove_favorite"></span></div>';
          exit;
        }
        else {
          $db->setQuery('DELETE FROM #__datsogallery_favorites WHERE user_id = '.(int) $user->id.' AND image_id = '.(int) $id);
          $db->query() or die($db->stderr());
          echo '<div class="dgtip" title="'.JText::_('COM_DATSOGALLERY_ADD_FAVORITE').'"><span class="add_favorite"></span></div>';
          exit;
        }
      }
      else {
        exit;
      }
    }

     function ppIpn() {
      $app = JFactory::getApplication('site');
      jimport( 'joomla.utilities.utility' );
      require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
      $post = JRequest::get('post', JREQUEST_ALLOWRAW);
      if (!empty($post)){
      $req = 'cmd=_notify-validate';
      foreach ($post as $key => $value) {
        $value = urlencode(stripslashes($value));
        $req .= "&$key=$value";
      }
      $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
      $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
      $header .= "Content-Length: ".strlen($req)."\r\n\r\n";
      $url = ($ad_pp_mode) ? 'ssl://www.paypal.com' : 'ssl://www.sandbox.paypal.com';
      $fp = fsockopen($url, 443, $errno, $errstr, 30);
      $payment_status = JRequest::getVar('payment_status', null, 'post');
      $txn_id = JRequest::getVar('txn_id', null, 'post');
      $receiver_email = JRequest::getVar('receiver_email', null, 'post');
      $payer_email = JRequest::getVar('payer_email', null, 'post');
      $custom = urldecode(JRequest::getVar('custom', null, 'post'));
      $item_number = JRequest::getVar('item_number', null, 'post');
      if($custom != '') {
        list($user_id, $user_ip) = explode('|', $custom);
      }
      if (!$fp) {
      }
      else {
        fputs($fp, $header.$req);
        while (!feof($fp)) {
          $res = fgets($fp, 1024);
          if (strcmp($res, "VERIFIED") == 0) {
            if ($payment_status == 'Completed') {
                 $db = JFactory::getDBO();
                 $user = JFactory::getUser($user_id);
                 $db->setQuery('SELECT a.*, c.image_id'
                 .' FROM #__datsogallery AS a,'
                 .' #__datsogallery_basket AS c'
                 .' WHERE a.id = c.image_id'
                 .' AND c.user_id = '.$user->get('id')
                 );
                 $rows = $db->loadObjectList();
                 if (count($rows)){
                   foreach ($rows as $row){
                     $db->setQuery('INSERT INTO #__datsogallery_purchases'
                     .' VALUES ('.$db->Quote('')
                     .', '.$db->Quote($item_number)
                     .', '.$row->image_id
                     .', '.$user->get('id')
                     .', '.$db->Quote($user_ip)
                     .', NOW(), '.$db->Quote($payment_status)
                     .', '.$db->Quote($txn_id).')')
                     ;
                     $db->query() or die($db->stderr());
                   }
                 }
                 $db->setQuery('DELETE FROM #__datsogallery_basket'
                 .' WHERE user_id = '.$user->get('id')
                 );
                 $db->query() or die($db->stderr());
                 $menu = JSite::getMenu();
                 $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
                 $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
                 $juri = &JURI::getInstance();
                 $root = $juri->toString( array('scheme', 'host', 'port') );
                 $from = $app->getCfg('mailfrom');
                 $fromname = $app->getCfg('fromname');
                 $subject = $fromname.': '.JText::_( 'DatsoGallery Shopping Cart' );
                 $purchases_link = '<a href="'.$root.JRoute::_('index.php?option=com_datsogallery&task=purchases'.$itemid).'">'.JText::_('COM_DATSOGALLERY_MY_PURCHASES').'</a>';
                 $site_link = '<a href="'.$root.'">'.$root.'</a>';
                 $text = sprintf(JText::_('COM_DATSOGALLERY_MAIL_AFTER_PURCHASE'), $user->get('name'), $purchases_link, $app->getCfg('sitename'), $site_link);
                 JUTility::sendMail($from, $fromname, $user->get('email'), $subject, $text, true);
                }
                else {
                  return;
             }
          }
          else
            if (strcmp($res, "INVALID") == 0) {
          }
        }
        fclose($fp);
      }
     }
    }

   function addToBasket() {
     require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
     $db = JFactory::getDBO();
     $user = JFactory::getUser();
     $id = JRequest::getVar('id', 0, 'post', 'int');
     if ($user->id && $ad_shop) {
       $db->setQuery('SELECT *'
       .' FROM #__datsogallery_basket'
       .' WHERE image_id = '.(int) $id
       .' AND user_id = '.(int) $user->id
       );
       $db->query() or die($db->stderr());
       $result = $db->loadObject();
       if (!$result) {
         $db->setQuery('INSERT INTO #__datsogallery_basket'
         .' VALUES ( "", '.$id.', '.$user->id.', NOW() )'
         );
         $db->query() or die($db->stderr());
         echo showBasket();
         exit;
       }
       else {
         $db->setQuery('DELETE FROM #__datsogallery_basket'
         .' WHERE image_id = '.(int) $id
         .' AND user_id = '.(int) $user->id
         );
         $db->query() or die($db->stderr());
         echo showBasket();
         exit;
       }
     }
     else {
       exit;
     }
   }

   function showBasket() {
     $db = JFactory::getDBO();
     $user = JFactory::getUser();
     require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
     require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'images.datsogallery.php');
     $db->setQuery('SELECT a.*, c.image_id'
     .' FROM #__datsogallery AS a,'
     .' #__datsogallery_basket AS c'
     .' WHERE a.id = c.image_id'
     .' AND c.user_id = '.(int) $user->id
     .' GROUP BY a.id'
     .' ORDER BY c.id ASC'
     );
     $rows = $db->loadObjectList();
     $out = "";
     ?>
      <script type="text/javascript">
        datso(document).ready(function() {
            datso(".dg_item_id").click(function () {
                var id = datso(this).attr("id");
                var data = 'id=' + id;
                var parent = datso(this);
                datso(this).css({
                    opacity: '1.0'
                }, 50);
                datso.ajax({
                    type: "post",
                    url: siteurl + 'index.php?option=com_datsogallery&task=addtobasket&format=raw',
                    data: data,
                    cache: false,
                    success: function (response) {
                        parent.animate({
                            opacity: '0'
                        }, 300).hide(1);
                        datso(".dg_slider").html(response);
                        update_subtotal();
                        update_total();
                    }
                });
                return false;
            });
        });
      </script>
     <?php
     if (count($rows) > 0) {
       foreach ($rows as $row) {
         $out .= "<div class=\"dg_basket\">\n";
         $out .= " <ul>\n";
         $out .= "   <li id=\"image\"><img src=\"".resize($row->imgoriginalname, $ad_thumbwidth1, $ad_thumbheight1, $ad_crop, '1:1', 0, $row->catid)
         ."\" ".get_width_height($row->imgoriginalname, $ad_thumbwidth1, $ad_thumbheight1, $row->catid, '1:1')." class=\"dgimg\" alt=\"\" /></li>\n";
         $out .= "   <li id=\"item\">".shortName($row->imgtitle, 10)."</li>\n";
         $out .= "   <li id=\"cost\"><span>".JText::_('COM_DATSOGALLERY_PRICE').":</span> " . currencySymbol($ad_pp_currency) . $row->imgprice . "</li><br />\n";
         $out .= "   <li class=\"dg_basket_box\">\n";
         $out .= "     <span class=\"dg_item_id\" id=\"".$row->id."\">\n";
         $out .= "       <span title=\"".JText::_('COM_DATSOGALLERY_REMOVE_FROM_BASKET')."\"><span class=\"remove_from_basket\"></span></span>\n";
         $out .= "     </span>\n";
         $out .= "   </li>\n";
         $out .= " </ul>\n";
         $out .= "</div>\n";
       }
       $out .= "<div class=\"dg_clear\"></div>\n";
     }
     else {
       $out .= "<div style=\"font-size:16px;text-align:center;\">".JText::_('COM_DATSOGALLERY_EMPTY_BASKET')."</div>";
       $out .= "<div class=\"dg_clear\"></div>";
     }
     return $out;
   }

   function subTotal() {
     require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
     $db = JFactory::getDBO();
     $user = JFactory::getUser();
     $task = JRequest::getCmd('task');
     $db->setQuery('SELECT a.*, c.image_id'
     .' FROM #__datsogallery AS a,'
     .' #__datsogallery_basket AS c'
     .' WHERE a.id = c.image_id'
     .' AND c.user_id = '.(int) $user->id
     .' GROUP BY a.id'
     .' ORDER BY c.id ASC'
     );
     $rows = $db->loadObjectList();
     $total = count($rows);
     $subtotal = 0;
     foreach ($rows as $row) {
       $subtotal += $row->imgprice * 1;
     }
     echo number_format($subtotal, 2, '.', '');
   }

   function total() {
     require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
     $db = JFactory::getDBO();
     $user = JFactory::getUser();
     $task = JRequest::getCmd('task');
     $db->setQuery('SELECT a.*, c.image_id'
     .' FROM #__datsogallery AS a,'
     .' #__datsogallery_basket AS c'
     .' WHERE a.id = c.image_id'
     .' AND c.user_id = '.(int) $user->id
     .' GROUP BY a.id'
     .' ORDER BY c.id ASC'
     );
     $rows = $db->loadObjectList();
     $total = count($rows);
     $subtotal = 0;
     foreach ($rows as $row) {
       $subtotal += $row->imgprice * 1;
     }
     if($ad_pp_tax_type == '0'){
       $ad_pp_tax_value = $subtotal * ($ad_pp_tax_value / 100);
       $subtotal = $subtotal + $ad_pp_tax_value;
     }
     echo number_format($subtotal, 2, '.', '');
   }

   function checkBasket() {
     $db = JFactory::getDBO();
     $user = JFactory::getUser();
     $db->setQuery('SELECT count(*)'
     .' FROM #__datsogallery_basket'
     .' WHERE user_id = '.(int) $user->id
     );
     $result = $db->loadResult();
     if ($result) {
       $display = '';
     }
     else {
       $display = ' style="display:none"';
     }
     return $display;
   }

   function countItems() {
     $db = JFactory::getDBO();
     $user = JFactory::getUser();
     $db->setQuery('SELECT count(*)'
     .' FROM #__datsogallery_basket'
     .' WHERE user_id = '.(int) $user->id
     );
     $result = $db->loadResult();
     echo $result;
   }

   function checkMooPath() {
     $document = JFactory::getDocument();
     $moo_core = 'media/system/js/mootools-core.js';
     $moo_more = 'media/system/js/mootools-more.js';
     $core = JURI::root(true).'/'.$moo_core;
     $more = JURI::root(true).'/'.$moo_more;
     unset ($document->_scripts[$core]);
     unset ($document->_scripts[$more]);
     $document->addScript($core);
     $document->addScript($more);
   }

   function shortName($str, $chars) {
     jimport('joomla.utilities.string');
     if ($chars < 3) {
       $chars = 3;
     }
     if (strlen($str) > $chars) {
       return JString::substr($str, 0, $chars - 3).'&hellip;';
     }
     else {
       return $str;
     }
   }

   function currencySymbol($currency_code) {
     switch ($currency_code) {

       case 'AUD' :
         $currency_symbol = '&#36;';
         break;

       case 'GBP' :
         $currency_symbol = '&#163;';
         break;

       case 'CAD' :
         $currency_symbol = '&#36;';
         break;

       case 'CZK' :
         $currency_symbol = '&#75;&#269;';
         break;

       case 'DKK' :
         $currency_symbol = '&#107;&#114;';
         break;

       case 'HKD' :
         $currency_symbol = '&#36;';
         break;

       case 'HUF' :
         $currency_symbol = '&#70;&#116;';
         break;

       case 'ILS' :
         $currency_symbol = '&#8362;';
         break;

       case 'JPY' :
         $currency_symbol = '&#165;';
         break;

       case 'MXN' :
         $currency_symbol = '&#36;';
         break;

       case 'NZD' :
         $currency_symbol = '&#36;';
         break;

       case 'NOK' :
         $currency_symbol = '&#107;&#114;';
         break;

       case 'PLN' :
         $currency_symbol = '&#122;&#322;';
         break;

       case 'SGD' :
         $currency_symbol = '&#36;';
         break;

       case 'SEK' :
         $currency_symbol = '&#107;&#114;';
         break;

       case 'CHF' :
         $currency_symbol = '&#67;&#72;&#70;';
         break;

       case 'USD' :
         $currency_symbol = '&#36;';
         break;

       default :
         $currency_symbol = '&#8364;';
     }
     return $currency_symbol;
   }

  function geo_single_gps($crd, $dir) {
    $degrees = count($crd) > 0 ? geo_gps($crd[0]) : 0;
    $minutes = count($crd) > 1 ? geo_gps($crd[1]) : 0;
    $seconds = count($crd) > 2 ? geo_gps($crd[2]) : 0;
    $flip = ($dir == 'W' or $dir == 'S') ? - 1 : 1;
    return floatval($flip * ($degrees +($minutes/60)+($seconds/3600)));
  }

  function geo_gps($crd) {
    $prt = explode('/', $crd);
    if (count($prt) <= 0)
      return 0;
    if (count($prt) == 1)
      return $prt[0];
    return floatval($prt[0]) / floatval($prt[1]);
  }

  function addhit($id) {
    $db =& JFactory::getDBO();
    $ip = getIpAddress();

    $hit = 'UPDATE #__datsogallery'
    . ' SET imgcounter = ( imgcounter + 1 )'
    . ' WHERE id = ' . (int) $id
    ;
    $db->setQuery( $hit );
    $db->query();

    $data = 'INSERT INTO #__datsogallery_hits'
    .' VALUES ('.$db->Quote('')
    .', '.(int) $id
    .', '.$db->Quote($ip)
    .', NOW())'
    ;
    $db->setQuery( $data );
    $db->query();
  }

  function adddownload($id) {
    $db =& JFactory::getDBO();
    $ip = getIpAddress();

    $dld = 'UPDATE #__datsogallery'
    . ' SET imgdownloaded = ( imgdownloaded + 1 )'
    . ' WHERE id = ' . (int) $id
    ;
    $db->setQuery( $dld );
    $db->query();

    $data = 'INSERT INTO #__datsogallery_downloads'
    .' VALUES ('.$db->Quote('')
    .', '.(int) $id
    .', '.$db->Quote($ip)
    .', NOW())'
    ;
    $db->setQuery( $data );
    $db->query();
  }
?>