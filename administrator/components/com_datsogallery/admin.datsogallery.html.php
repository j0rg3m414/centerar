<?php

defined('_JEXEC') or die('Restricted access');

class datsogallery_html
{

  function showPictures(&$rows, &$clist, &$slist, &$lists, &$search, &$pagination) {
    $app = JFactory::getApplication('administrator');
    $db =& JFactory::getDBO();
    $uri = JFactory::getURI();
    require (JPATH_COMPONENT.DS.'config.datsogallery.php');
    $colspan = ($ad_shop) ? '16':'14';
    $ordering = ($lists['order'] == 'a.ordering');
  ?>

  <script type="text/javascript">
      window.addEvent('domready', function() {
         var limit_select = $('limit').options;
         if ( !limit_select ) return;
         for ( var i = 0; i < limit_select.length; i++ ) {
              if ( limit_select[i].value == 0 ) {
                 limit_select[i].value = 20;
                 limit_select[i].innerHTML = '- default -';
                 return false;
              }
         }
      });
  </script>

  <script type="text/javascript">
        function editEntry(number) {
            var elm = document.getElementById('cb' + number);
            if (elm) {
                elm.checked = true;
            }
        }
  </script>

  <form action="index.php" method="post" name="adminForm">
    <table>
      <tr>
    	<td align="left" width="100%">
    		<?php echo JText::_( 'COM_DATSOGALLERY_FILTER' ); ?>:
    		<input type="text" name="search" id="search" value="<?php echo $search;?>" class="text_area" onchange="document.adminForm.submit();" />
            <input class="button" type="button" onclick="this.form.submit();" value="<?php echo JText::_( 'COM_DATSOGALLERY_GO' ); ?>" />
		    <input class="button" type="button" onclick="document.getElementById('search').value='';this.form.submit();" value="<?php echo JText::_( 'COM_DATSOGALLERY_RESET' ); ?>" />
    	</td>
        <td nowrap="nowrap">
          <?php
            echo $clist;
            echo $slist;
          ?>
        </td>
      </tr>
    </table>

    <table class="adminlist">
      <thead>
        <tr>
          <th width="5">
		     <?php echo JText::_( 'COM_DATSOGALLERY_NUM' ); ?>
		  </th>
          <th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
		  </th>
          <th class="title">
             <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_TITLE'), 'a.imgtitle', $lists['order_Dir'], $lists['order'] ); ?>
          </th>
          <th class="title">
             <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_CATEGORY'), 'category', $lists['order_Dir'], $lists['order'] ); ?>
          </th>
          <th width="5%">
             <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_HITS'), 'a.imgcounter', $lists['order_Dir'], $lists['order'] ); ?>
          </th>
          <th width="5%">
             <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_DOWNLOADS'), 'a.imgdownloaded', $lists['order_Dir'], $lists['order'] ); ?>
          </th>
          <th width="5%">
             <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_RATING'), 'a.imgvotesum', $lists['order_Dir'], $lists['order'] ); ?>
          </th>
          <th width="7%">
		     <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_ORDER'), 'a.ordering', $lists['order_Dir'], $lists['order'] ); ?><?php if ($ordering) echo JHTML::_('grid.order',  $rows ); ?>
		  </th>
          <th width="5%" nowrap="nowrap">
		     <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_PUBLISHED'), 'a.published', $lists['order_Dir'], $lists['order'] ); ?>
		  </th>
          <th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_APPROWED'), 'a.approved', $lists['order_Dir'], $lists['order'] ); ?>
		  </th>
          <?php if ($ad_shop) : ?>
          <th width="5%" nowrap="nowrap">
		     <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_PRICE'), 'a.imgprice', $lists['order_Dir'], $lists['order'] ); ?>
		  </th>
          <th width="5%">
             <?php echo JText::_('COM_DATSOGALLERY_SALES'); ?>
          </th>
          <?php endif; ?>
          <th width="10%">
             <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_OWNER'), 'u.username', $lists['order_Dir'], $lists['order'] ); ?>
          </th>
          <th width="2%">
             <?php echo JText::_('COM_DATSOGALLERY_TYPE'); ?>
          </th>
          <th width="8%">
             <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_DATE_ADD'), 'a.imgdate', $lists['order_Dir'], $lists['order'] ); ?>
          </th>
          <th width="1%" nowrap="nowrap">
             <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_ID'), 'a.id', $lists['order_Dir'], $lists['order'] ); ?>
          </th>
        </tr>
      </thead>

      <tfoot>
		<tr>
		  <td colspan="<?php echo $colspan; ?>">
		     <?php echo $pagination->getListFooter(); ?>
		  </td>
		</tr>
	  </tfoot>

      <tbody>
          <?php

          $k = 0;
          for ( $i = 0, $n = count($rows); $i < $n; $i++ )
          {
            $row = &$rows[$i];
            if ($row->imgvotes > 0)
            {
              $fimgvotesum = number_format($row->imgvotesum / $row->imgvotes, 2, ",", ".");
              $frating = "$fimgvotesum / $row->imgvotes";
            }
            else
            {
              $frating = JText::_('COM_DATSOGALLERY_NO_VOTES');
            }
            $taska = $row->approved ? 'rejectpic':'approvepic';
            $imga = $row->approved ? 'dg-accept-icon.png':'dg-pending-icon.png';
            $task = $row->published ? 'unpublish':'publish';
            $img = $row->published ? 'dg-accept-icon.png':'dg-pending-icon.png';
            $db->setQuery("SELECT count(*) from #__datsogallery_purchases where image_id = ".$row->id);
            $sales = $db->loadResult();
            $ad_pathoriginals = str_replace('/', DS, $ad_pathoriginals);
            $imgprev = resize($row->imgoriginalname, 120, 120, $ad_crop, $ad_cropratio, 0, $row->catid);
            $info = getimagesize(JPATH_SITE.$ad_pathoriginals.DS.$row->imgoriginalname);
            $size = filesize(JPATH_SITE.$ad_pathoriginals.DS.$row->imgoriginalname);
            $type = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG');
            $info[2] = $type[$info[2]];
            $fsize = format_filesize($size);
            $overlib = '<table>';
            $overlib .= '<tr>';
            $overlib .= '<td>';
            $overlib .= JText::_('COM_DATSOGALLERY_ORG_WIDTH');
            $overlib .= '</td>';
            $overlib .= '<td>: ';
            $overlib .= $info[0].' '.JText::_('COM_DATSOGALLERY_PIXELS');
            $overlib .= '</td>';
            $overlib .= '</tr>';
            $overlib .= '<tr>';
            $overlib .= '<td>';
            $overlib .= JText::_('COM_DATSOGALLERY_ORG_HEIGHT');
            $overlib .= '</td>';
            $overlib .= '<td>: ';
            $overlib .= $info[1].' '.JText::_('COM_DATSOGALLERY_PIXELS');
            $overlib .= '</td>';
            $overlib .= '</tr>';
            $overlib .= '<tr>';
            $overlib .= '<td>';
            $overlib .= JText::_('COM_DATSOGALLERY_ORG_TYPE');
            $overlib .= '</td>';
            $overlib .= '<td>: ';
            $overlib .= $info[2];
            $overlib .= '</td>';
            $overlib .= '</tr>';
            $overlib .= '<tr>';
            $overlib .= '<td>';
            $overlib .= JText::_('COM_DATSOGALLERY_FILESIZE');
            $overlib .= '</td>';
            $overlib .= '<td>: ';
            $overlib .= $fsize;
            $overlib .= '</td>';
            $overlib .= '</tr>';
            $overlib .= '</table>';
       ?>
        <tr class="row<?php echo $k;?>">
          <td align="center"><?php echo $pagination->getRowOffset( $i );?></td>
          <td align="center"><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id;?>" onclick="isChecked(this.checked);" /></td>
          <td><a href="javascript:editEntry(<?php echo $i;?>); submitform('edit');"
          id="<strong><?php echo jsspecialchars($row->imgtitle);?></strong><br /><br />
          <img src='<?php echo $imgprev;?>' class='dgimg' /><br /><br /><?php echo $overlib;?>" class="dgtip"><?php echo $row->imgtitle;?></a></td>
          <td><a href="index.php?option=com_datsogallery&task=editcatg&cid=<?php echo $row->catid; ?>"><?php echo $row->category; ?></a></td>
          <td align="center"><?php echo $row->imgcounter;?></td>
          <td align="center"><?php echo $row->imgdownloaded;?></td>
          <td align="center"><?php echo $frating;?></td>
          <td class="order">
              <?php
                  if ($ordering) {
                  if ($lists['order_Dir'] == 'asc') {
                    $dirup = 'orderup';
                    $dirdown = 'orderdown';
                  }
                  else {
                    $dirup = 'orderdown';
                    $dirdown = 'orderup';
                  }
                ?>
				<span><?php echo $pagination->orderUpIcon( $i, ($row->catid == @$rows[$i-1]->catid),$dirup, JText::_('COM_DATSOGALLERY_MOVE_UP'), $ordering ); ?></span>
				<span><?php echo $pagination->orderDownIcon( $i, $n, ($row->catid == @$rows[$i+1]->catid), $dirdown, JText::_('COM_DATSOGALLERY_MOVE_DOWN'), $ordering ); ?></span>
                <?php } ?>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
		  </td>
          <td align='center'><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"> <img src="<?php echo $uri->root();?>components/com_datsogallery/images/<?php echo $img;?>" width="16" height="16" border="0" alt="" /></a></td>
          <td align='center'><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $taska;?>')"> <img src="<?php echo $uri->root();?>components/com_datsogallery/images/<?php echo $imga;?>" width="16" height="16" border="0" alt="" /></a></td>
          <?php if($ad_shop){ ?>
          <td align="center"><b><?php echo $row->imgprice;?></b></td>
          <td align="center"><b><?php echo $sales;?></b></td>
          <?php } ?>
          <td align="center"><a href="index.php?option=com_users&task=user.edit&id=<?php echo $row->owner_id; ?>"><?php echo $row->username;?></a></td>
          <td align="center"><?php if ($row->useruploaded) {?>
            <img src="<?php echo $uri->root();?>components/com_datsogallery/images/dg-user-icon.png" title="<?php echo JText::_('COM_DATSOGALLERY_USER_UPLOADED'); ?>">
            <?php
            } else {
            ?>
            <img src="<?php echo $uri->root();?>components/com_datsogallery/images/dg-admin-icon.png" title="<?php echo JText::_('COM_DATSOGALLERY_ADMIN_UPLOADED'); ?>">
            <?php
            }
            ?>
          </td>
          <td width="10%" align="center"><?php echo strftime($ad_datef, $row->imgdate);?></td>
          <td align="center"><?php echo $row->id;?></td>
          </tr>
        <?php
    		$k = 1 - $k;
    		}
	    ?>
     </tbody>
    </table>

    <input type="hidden" name="option" value="com_datsogallery" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
      <?php

    }

    function movePic($rows, $lists){
      $app = JFactory::getApplication('administrator');
      $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
      require (JPATH_COMPONENT.DS.'config.datsogallery.php');
    ?>

  <form action="index.php" method="post" name="adminForm" >
    <fieldset class="adminform">
      <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_MASS_MOVING'); ?>&nbsp;</legend>
      <table class="admintable">
        <tr>
          <td><?php echo $lists['catgs'] ?></td>
          <td width="100%">&nbsp;&nbsp;<?php echo dgTip(JText::_('COM_DATSOGALLERY_MOVE_TO_CATEGORY')); ?></td>
        </tr>
      </table>
      <table class="adminlist">
      <thead>
        <tr>
          <th width="2%"><?php echo JText::_('COM_DATSOGALLERY_ID'); ?></th>
          <th width="23%"> <div align="left"> <?php echo JText::_('COM_DATSOGALLERY_PICS_TO_MOVING'); ?> </div> </th>
          <th width="75%"> <div align="left"> <?php echo JText::_('COM_DATSOGALLERY_CURRENT_CATEGORY'); ?> </div>
          </th>
        </tr>
        </thead>
        <tbody>
            <?php
            if(count($rows) > 0)
            foreach($rows as $row) {
              $imgprev = resize($row->imgoriginalname, 120, 120, $ad_crop, $ad_cropratio, 0, $row->catid);
            ?>
        <tr>
          <td align="center" width="2%" style="padding:7px"><?php echo $row->id;?></td>
          <td align="left" width="20%"><a href="#"
          id="<img src='<?php echo $imgprev;?>' class='dgimg' />" class="dgtip"><?php echo $row->imgtitle;?></a></td>
          <td><div align="left" width="75%"> <?php echo ShowCategoryPath($row->catid);?> </div></td>
        </tr>
         <?php } ?>
         </tbody>
      </table>
    </fieldset>
    <input type="hidden" name="option" value="com_datsogallery" />
    <input type="hidden" name="task" value="movepicres" />
    <input type="hidden" name="boxchecked" value="1" />
        <?php

        foreach ($cid as $cids)
        {
          echo "<input type='hidden' name='id[]' value='".$cids."' />";
        }


        ?>
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
      <?php

    }

    function editPicture(&$row, &$clist, &$ad_pathoriginals, &$ad_thumbwidth, &$ad_thumbheight, &$ad_crop, &$ad_cropratio)
    {
      $app = JFactory::getApplication('administrator');
      $user = JFactory::getUser();
      $ad_pathoriginals = JPath::clean($ad_pathoriginals);
      $info = getimagesize(JPATH_SITE.$ad_pathoriginals.DS.$row->imgoriginalname);
      $size = filesize(JPATH_SITE.$ad_pathoriginals.DS.$row->imgoriginalname);
      require (JPATH_COMPONENT.DS.'config.datsogallery.php');
      ?>
  <script type="text/javascript">
    Joomla.submitbutton = function(task) {
        var form = document.adminForm;
        if (form.imgtitle.value == '') {
            alert('<?php echo JText::_('COM_DATSOGALLERY_ENTER_IMAGE_TITLE'); ?>');
            return false;
        } else if (form.catid.value == 0) {
            alert('<?php echo JText::_('COM_DATSOGALLERY_SELECT_CATEGORY'); ?>');
            return false;
        } else {
            submitform(task);
            return true;
        }
        if (task == 'cancel') {
            submitform(task);
            return true;
        }
    }
  </script>
  <table width="100%" class="admintable">
    <tr>
      <td width="40%" valign="top">
        <form action="index.php" method="post" name="adminForm" id="adminForm">
        <fieldset style="min-height: 300px" class="adminform">
         <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_IMAGE_DATA'); ?>&nbsp;</legend>
          <table class="admintable">
            <tr>
              <td width="200"><b><?php echo JText::_('COM_DATSOGALLERY_IMAGE_TITLE'); ?>:</b></td>
              <td width="300"><input class="inputbox" type="text" name="imgtitle" size="39" maxlength="100" value="<?php echo $row->imgtitle;?>" /></td>
            </tr>
            <tr>
              <td><b><?php echo JText::_('COM_DATSOGALLERY_CATEGORY'); ?>:</b></td>
              <td><?php echo $clist;?></td>
            </tr>
            <tr>
              <td valign="top"><b><?php echo JText::_('COM_DATSOGALLERY_DESCRIPTION'); ?>:</b></td>
              <td>
                <?php
                $editor =& JFactory::getEditor();
                echo $editor->display( 'imgtext',  htmlspecialchars($row->imgtext, ENT_QUOTES), '550', '300', '60', '20', array('image', 'pagebreak', 'readmore') );
                ?>
                </td>
            </tr>
            <tr>
              <td><b><?php echo JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR'); ?>:</b></td>
              <td><input class="inputbox" type="text" name="imgauthor" value="<?php echo $row->imgauthor;?>" size="39" maxlength="100" /></td>
            </tr>
            <tr>
              <td><b><?php echo JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR_URL'); ?>:</b></td>
              <td><input class="inputbox" type="text" name="imgauthorurl" value="<?php echo $row->imgauthorurl;?>" size="39" maxlength="100" /></td>
            </tr>
            <?php if ($ad_shop) { ?>
            <tr>
              <td><b><?php echo JText::_('COM_DATSOGALLERY_PRICE'); ?>:</b></td>
              <td><input class="inputbox" type="text" name="imgprice" value="<?php echo $row->imgprice;?>" size="12" maxlength="100" />&nbsp;<?php echo dgTip(JText::_('COM_DATSOGALLERY_PRICE_DESC')); ?></td>
            </tr>
             <?php } ?>
          </table>
          <input type="hidden" name="option" value="com_datsogallery" />
          <input type="hidden" name="task" value="save" />
          <input type="hidden" name="id" value="<?php echo $row->id;?>" />
          <input type="hidden" name="owner_id" value="<?php if ($row->owner_id) { echo $row->owner_id; } else { echo $user->id; } ?>" />
          <input type="hidden" name="approved" value="<?php if ($row->approved == 0) { echo "1"; } else { echo $row->approved; } ?>" />
          </fieldset>
          <?php echo JHTML::_( 'form.token' ); ?>
        </form></td>
      <td width="60%" valign="top">
    <?php
      $type = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG');
      $info[2] = $type[$info[2]];
      $fsize = format_filesize($size);
      $orginfo = '<table>';
      $orginfo .= '<tr>';
      $orginfo .= '<td>';
      $orginfo .= JText::_('COM_DATSOGALLERY_ORG_WIDTH');
      $orginfo .= '</td>';
      $orginfo .= '<td>: ';
      $orginfo .= $info[0].' '.JText::_('COM_DATSOGALLERY_PIXELS');
      $orginfo .= '</td>';
      $orginfo .= '</tr>';
      $orginfo .= '<tr>';
      $orginfo .= '<td>';
      $orginfo .= JText::_('COM_DATSOGALLERY_ORG_HEIGHT');
      $orginfo .= '</td>';
      $orginfo .= '<td>: ';
      $orginfo .= $info[1].' '.JText::_('COM_DATSOGALLERY_PIXELS');
      $orginfo .= '</td>';
      $orginfo .= '</tr>';
      $orginfo .= '<tr>';
      $orginfo .= '<td>';
      $orginfo .= JText::_('COM_DATSOGALLERY_ORG_TYPE');
      $orginfo .= '</td>';
      $orginfo .= '<td>: ';
      $orginfo .= $info[2];
      $orginfo .= '</td>';
      $orginfo .= '</tr>';
      $orginfo .= '<tr>';
      $orginfo .= '<td>';
      $orginfo .= JText::_('COM_DATSOGALLERY_FILESIZE');
      $orginfo .= '</td>';
      $orginfo .= '<td>: ';
      $orginfo .= $fsize;
      $orginfo .= '</td>';
      $orginfo .= '</tr>';
      $orginfo .= '</table>';
    ?>
    <fieldset style="min-height:300px;width:220px" class="adminform">
       <legend>&nbsp;<?php echo JText::_('COM_DATSOGALLERY_IMAGE_INFO'); ?>&nbsp;</legend>
        <table class="admintable">
          <tr>
            <td>
                <div align="center"><img src="<?php echo resize($row->imgoriginalname, $ad_thumbwidth, $ad_thumbheight, $ad_crop, $ad_cropratio, 0, $row->catid);?>" class="dgimg" title="<?php echo JText::_('COM_DATSOGALLERY_IMAGE_PREVIEW'); ?>" /></div>
                <br />
                <br />
                <?php echo $orginfo;?>
            </td>
          </tr>
        </table>
        </fieldset>
      </td>
    </tr>
  </table>
  <?php
  }

  function showComments(&$rows, &$search, &$pageNav, &$ad_datef){
      $app = JFactory::getApplication('administrator');
      $uri = JFactory::getURI();
      require (JPATH_COMPONENT.DS.'config.datsogallery.php');
  ?>
  <form action="index.php" method="post" name="adminForm">
    <table class="adminheading">
      <tr>
        <td align="left" width="100%"><?php echo JText::_( 'COM_DATSOGALLERY_FILTER' );?>:&nbsp;
          <input type="text" name="search" id="search" value="<?php echo $search;?>" class="text_area" onchange="document.adminForm.submit();" />
          <input class="button" type="button" onclick="this.form.submit();" value="<?php echo JText::_( 'COM_DATSOGALLERY_GO' ); ?>" />
		  <input class="button" type="button" onclick="document.getElementById('search').value='';this.form.submit();" value="<?php echo JText::_( 'COM_DATSOGALLERY_RESET' ); ?>" />
        </td>
      </tr>
    </table>
    <table class="adminlist">
      <tr>
      <thead>
      <th width="20"> <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows);?>);" />
        </th>
        <th width="12%"><?php echo JText::_('COM_DATSOGALLERY_IMAGE_TITLE');?></th>
        <th width="15%"><?php echo JText::_('COM_DATSOGALLERY_OWNER'); ?></th>
        <th width="40%"><?php echo JText::_('COM_DATSOGALLERY_COMMENT_TEXT'); ?></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_USER_EMAIL'); ?></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_IP'); ?></th>
        <th width="5%"><?php echo JText::_('COM_DATSOGALLERY_PUBLISHED'); ?></th>
        <th width="8%"><?php echo JText::_('COM_DATSOGALLERY_DATE_ADD'); ?></th>
        </thead>
      </tr>
      <?php
        $k = 0;
        for ( $i = 0, $n = count($rows); $i < $n; $i++ ){
          $row = &$rows[$i];
          $row->cmtdate = strftime($ad_datef, $row->cmtdate);
          $imgprev = resize($row->imgoriginalname, 120, 120, $ad_crop, $ad_cropratio, 0, $row->catid);
      ?>
      <tr class="<?php echo "row$k";?>">
        <td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $row->cmtid;?>" onclick="isChecked(this.checked);" /></td>
        <td align="center"><a href="javascript:void(0);" id="<img src='<?php echo $imgprev;?>' class='dgimg' />" class="dgtip" style="cursor:default"><?php echo $row->imgtitle;?></a></td>
        <td><?php echo $row->cmtname;?></td>
        <td><?php echo $row->cmttext;?></td>
        <td align="center"><?php echo $row->cmtmail;?></td>
        <td align="center"><?php echo $row->cmtip;?></td>
              <?php

              echo "<td align='center'>";
              if ($row->published == "1")
              {
                echo "<img src='".$uri->root()."components/com_datsogallery/images/dg-accept-icon.png'>";
              }
              else
              {
                echo "<img src='".$uri->root()."components/com_datsogallery/images/dg-pending-icon.png'>";
              }
              echo "</td>";


              ?>
        <td align="center"><?php echo $row->cmtdate;?></td>
        <?php $k = 1 - $k; ?>
      </tr>
      <?php } ?>
      <tr>
      <tfoot>
      <th colspan="8"> <?php echo $pageNav->getListFooter();?></th>
        </tfoot>
      </tr>
    </table>
    <input type="hidden" name="option" value="com_datsogallery" />
    <input type="hidden" name="task" value="comments" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
  <?php
   }
    function showCatgs(&$cats, &$lists, &$search, &$pagination){
      $app = JFactory::getApplication('administrator');
      $db = JFactory::getDBO();
      $uri = JFactory::getURI();
      $ordering = ($lists['order'] == 'c.ordering');
  ?>
  <form action="index.php" method="post" name="adminForm">
    <table>
      <tr>
        <td align="left" width="100%"><?php echo JText::_('COM_DATSOGALLERY_FILTER'); ?>:&nbsp;
          <input type="text" name="search" id="search" value="<?php echo $search;?>" class="text_area" onchange="document.adminForm.submit();" />
          <input class="button" type="button" onclick="this.form.submit();" value="<?php echo JText::_( 'COM_DATSOGALLERY_GO' ); ?>" />
		  <input class="button" type="button" onclick="document.getElementById('search').value='';this.form.submit();" value="<?php echo JText::_( 'COM_DATSOGALLERY_RESET' ); ?>" />
        </td>
      </tr>
    </table>

<table class="adminlist">
	<thead>
		<tr>
			<th width="1%" nowrap="nowrap">
				<?php echo JText::_( 'COM_DATSOGALLERY_NUM' ); ?>
			</th>
            <th width="1%" nowrap="nowrap">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($cats); ?>);" />
			</th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_CATEGORY'), 'title', $lists['order_Dir'], $lists['order'] ); ?>
            </th>
            <th width="5%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_CATEGORY_OWNER'), 'owner', $lists['order_Dir'], $lists['order'] ); ?>
            </th>
            <th width="4%" nowrap="nowrap">
             <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_PUBLISHED'), 'c.published', $lists['order_Dir'], $lists['order'] ); ?>
		    </th>
            <th width="4%" nowrap="nowrap">
             <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_APPROVED'), 'c.approved', $lists['order_Dir'], $lists['order'] ); ?>
		    </th>
            <th width="4%">
		     <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_ORDER'), 'c.ordering', $lists['order_Dir'], $lists['order'] ); ?><?php if ($ordering) echo JHTML::_('grid.order',  $cats, '', 'savecatorder' ); ?>
		    </th>
            <th width="3%" nowrap="nowrap">
                <?php echo JText::_('COM_DATSOGALLERY_FILES_IN_CATEGORY'); ?>
            </th>
            <th width="5%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_ACCESS'), 'groupname', $lists['order_Dir'], $lists['order'] ); ?>
            </th>
            <th width="5%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_DATE_ADD'), 'c.date', $lists['order_Dir'], $lists['order'] ); ?>
            </th>
            <th width="1%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', JText::_('COM_DATSOGALLERY_ID'), 'id', $lists['order_Dir'], $lists['order'] ); ?>
            </th>
        </tr>
     </thead>

     <tfoot>
        <tr>
			<td colspan="11"><?php echo $pagination->getListFooter();?></td>
        </tr>
     </tfoot>

     <tbody>
      <?php
          $k = 0;
          $rows = $cats;
          for ( $i = 0, $n = count($rows); $i < $n; $i++ ) {
            $row = &$rows[$i];
            $task1 = $row->published ? 'unpublishcatg':'publishcatg';
            $img1 = $row->published ? 'dg-accept-icon.png':'dg-pending-icon.png';
            $task2 = $row->approved ? 'unapprovecatg':'approvecatg';
            $img2 = $row->approved ? 'dg-accept-icon.png':'dg-pending-icon.png';
      ?>
      <tr class="row<?php echo $k; ?>">
        <td align="center">
           <?php echo $pagination->getRowOffset( $i );?>
        </td>
        <td align="center">
           <input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->cid;?>" onclick="isChecked(this.checked);">
        </td>
        <td align="left" width="25%">
           <a href="#edit" onclick="return listItemTask('cb<?php echo $i;?>','editcatg')"><?php echo $row->treename;?></a>
        </td>
        <td align="center">
           <a href="index.php?option=com_users&task=user.edit&id=<?php echo $row->user_id; ?>"><?php echo $row->owner;?></a>
        </td>
        <td align="center">
           <a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task1;?>')">
           <img src="<?php echo $uri->root();?>components/com_datsogallery/images/<?php echo $img1;?>" border="0" alt="" /></a>
        </td>
        <td align="center">
           <a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task2;?>')">
           <img src="<?php echo $uri->root();?>components/com_datsogallery/images/<?php echo $img2;?>" border="0" alt="" /></a>
        </td>
        <td class="order">
            <?php
              if ($ordering) {
              if ($lists['order_Dir'] == 'asc') {
                $dirup = 'orderupcatg';
                $dirdown = 'orderdowncatg';
              }
              else {
                $dirup = 'orderdowncatg';
                $dirdown = 'orderupcatg';
              }
            ?>
            <span><?php echo $pagination->orderUpIcon( $i, $row->parent == 0 || $row->parent == @$rows[$i-1]->parent, $dirup, JText::_('COM_DATSOGALLERY_MOVE_UP'), $row->ordering); ?></span>
			<span><?php echo $pagination->orderDownIcon( $i, $n, $row->parent == 0 || $row->parent == @$rows[$i+1]->parent, $dirdown, JText::_('COM_DATSOGALLERY_MOVE_DOWN'), $row->ordering ); ?></span>
            <?php } ?>
            <?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
			<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
		</td>
        <td align="center">
           <?php echo GetNumberOfLinks($row->cid); ?>
        </td>
        <td align="center">
           <?php echo $row->groupname;?>
        </td>
        <td align="center">
           <?php echo $row->date;?>
        </td>
        <td align="center">
           <?php echo $row->cid;?>
        </td>
      </tr>
        <?php
    		$k = 1 - $k;
    		}
	    ?>
     </tbody>
    </table>
    <input type="hidden" name="option" value="com_datsogallery" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
    <input type="hidden" name="task" value="showcatg" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
  <?php
   }

   function editCatg($row, $publist, $glist, $Lists) {
     $app = JFactory::getApplication('administrator');
     jimport('joomla.filter.output');
     JFilterOutput::objectHTMLSafe($row, ENT_QUOTES, 'description');
  ?>
  <script type="text/javascript">
    Joomla.submitbutton = function(task) {
        var form = document.adminForm;
        if (task == 'savecatg') {
        if (form.name.value == '') {
            alert('<?php echo JText::_('COM_DATSOGALLERY_ENTER_CATEGORY_TITLE'); ?>');
            return false;
        } else {
            submitform(task);
            return true;
        }
        }
        if (task == 'cancelcatg') {
            submitform(task);
            return true;
        }
    }
  </script>
  <form action="index.php" method="post" name="adminForm">
    <table width="100%">
      <tr>
        <td><fieldset class="adminform">
            <legend>&nbsp;<?php echo $row->cid ? JText::_('COM_DATSOGALLERY_EDIT_CATEGORY'):JText::_('COM_DATSOGALLERY_CREATE_CATEGORY'); ?>&nbsp;</legend>
            <table class="admintable">
              <tr>
                <td width="200"><b><?php echo JText::_('COM_DATSOGALLERY_TITLE'); ?>:</b></td>
                <td><input class="inputbox" type="text" name="name" size="25" value="<?php echo $row->name;?>"></td>
              </tr>
              <tr>
                <td><b><?php echo JText::_('COM_DATSOGALLERY_PARENT_CATEGORY'); ?>:</b></td>
                <td><?php echo $Lists["catgs"];?></td>
              </tr>
              <tr>
                <td valign="top"><b><?php echo JText::_('COM_DATSOGALLERY_DESCRIPTION'); ?>:</b></td>
                <td>
                <?php
                  $editor =& JFactory::getEditor();
                  echo $editor->display( 'description',  htmlspecialchars($row->description, ENT_QUOTES), '550', '300', '60', '20', array('image', 'pagebreak', 'readmore') );
                ?>
                </td>
              </tr>
              <tr>
                <td ><b><?php echo JText::_('COM_DATSOGALLERY_ACCESS'); ?>:</b></td>
                <td><?php echo $glist;?></td>
              </tr>
              <tr>
                <td><b><?php echo JText::_('COM_DATSOGALLERY_PUBLISH'); ?>:</b></td>
                <td><?php echo $publist;?></td>
              </tr>
            </table>
          </fieldset></td>
      </tr>
    </table>
    <input type="hidden" name="cid" value="<?php echo $row->cid;?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="option" value="com_datsogallery" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
<?php

}

  function showBlacklist(&$rows, &$search, &$pageNav) {
    $app = JFactory::getApplication('administrator');
    $uri = JFactory::getURI();
?>
  <form action="index.php" method="post" name="adminForm">
    <table class="adminlist">
      <thead>
        <tr>
          <th width="5">
  				<?php echo JText::_( 'COM_DATSOGALLERY_NUM' ); ?>
  		  </th>
        <th width="20"> <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows);?>);" /></th>
        <th width="50%" style="text-align:left"><?php echo JText::_('COM_DATSOGALLERY_IP'); ?></th>
        <th width="30%"><?php echo JText::_('COM_DATSOGALLERY_MOTIF_BLOCKAGE'); ?></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_PUBLISHED'); ?></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_DATE_ADD'); ?></th>
        </tr>
      </thead>
      <tfoot>
		<tr>
			<td colspan="6">
				<?php echo $pageNav->getListFooter(); ?>
			</td>
		</tr>
	  </tfoot>
      <tbody>
      <?php
        $k = 0;
        for ( $i = 0, $n = count($rows); $i < $n; $i++ ) {
          $row = &$rows[$i];
      ?>
      <tr class="row<?php echo $k;?>">
         <td>
				<?php echo $row->id; ?>
		 </td>
        <td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $row->id;?>" onclick="isChecked(this.checked);" /></td>
        <td><?php echo $row->ip;?></td>
        <td><?php echo JText::_('COM_DATSOGALLERY_BLOCKED_FOR'); ?></td>
        <?php
          echo '<td align="center">';
          if ($row->published == '1') {
            echo '<img src="'.$uri->root().'components/com_datsogallery/images/dg-accept-icon.png">';
          }
          else {
            echo '<img src="'.$uri->root().'components/com_datsogallery/images/dg-pending-icon.png">';
          }
          echo '</td>';
          $k = 1 - $k;
        ?>
        <td align="center"><?php echo $row->date;?></td>
      </tr>
      <?php } ?>
      </tbody>
    </table>
    <input type="hidden" name="option" value="com_datsogallery" />
    <input type="hidden" name="task" value="blacklist" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
      <?php
    }
  function showTransactions(&$rows, &$search, &$pagination){
      JHTML::_('behavior.modal', 'a.modal-button');
      require (JPATH_COMPONENT.DS.'config.datsogallery.php');
  ?>
  <form action="index.php?option=com_datsogallery&task=transactions" method="post" name="adminForm">
    <table class="adminheading">
      <tr>
        <td align="left" width="100%"><?php echo JText::_( 'Filter' );?>:&nbsp;
          <input type="text" name="search" id="search" value="<?php echo $search;?>" class="text_area" onchange="document.adminForm.submit();" />
          <input class="button" type="button" onclick="this.form.submit();" value="<?php echo JText::_( 'Go' ); ?>" />
		  <input class="button" type="button" onclick="document.getElementById('search').value='';this.form.submit();" value="<?php echo JText::_( 'Reset' ); ?>" />
        </td>
      </tr>
    </table>
    <table class="adminlist">
      <tr>
      <thead>
        <th width="1"><?php echo JText::_('#');?></th>
        <th width="1"> <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows);?>);" /></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_BUYER');?></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_USER_EMAIL');?></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_IP');?></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_ORDER_ID');?></th>
        <th width="30%"><?php echo JText::_('COM_DATSOGALLERY_TRANSACTION_ID');?></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_AMOUNT');?></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_STATUS');?></th>
        <th width="10%"><?php echo JText::_('COM_DATSOGALLERY_DATE_ADD');?></th>
        </thead>
      </tr>
      <?php
        $k = 0;
        for ( $i = 0, $n = count($rows); $i < $n; $i++ ){
          $row = &$rows[$i];
      ?>
      <tr class="<?php echo "row$k";?>">
        <td align="center"><?php echo $pagination->getRowOffset( $i );?></td>
        <td><input type="checkbox" id="cb<?php echo $i;?>" name="order_id[]" value="<?php echo $row->order_id;?>" onclick="isChecked(this.checked);" /></td>
        <td align="center"><a href="index.php?option=com_users&task=user.edit&id=<?php echo $row->user; ?>"><?php echo $row->username;?></a></td>
        <td align="center"><a href="mailto:<?php echo $row->email;?>"><?php echo $row->email;?></a></td>
        <td align="center"><?php echo $row->user_ip;?></td>
        <td align="center"><a class="modal-button" rel="{handler: 'iframe', size: {x: 720, y: 520}}" href="index.php?option=com_datsogallery&amp;task=vieworder&amp;orderid=<?php echo $row->order_id;?>&type=raw"><?php echo $row->order_id;?></a></td>
        <td align="center"><?php echo $row->hash;?></td>
        <td align="center"><?php echo $row->amount;?></td>
        <td align="center"><strong><?php echo $row->status;?></strong></td>
        <td align="center"><?php echo $row->date;?></td>
        <?php $k = 1 - $k; ?>
      </tr>
      <?php } ?>
      <tr>
      <tfoot>
      <th colspan="10"> <?php echo $pagination->getListFooter();?></th>
        </tfoot>
      </tr>
    </table>
    <input type="hidden" name="option" value="com_datsogallery" />
    <input type="hidden" name="task" value="transactions" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
<?php
  }
  }
?>
