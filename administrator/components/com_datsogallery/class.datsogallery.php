<?php
  defined('_JEXEC') or die('Direct Access to this location is not allowed.');

  class DatsoImages extends JTable {
    var $id = null;
    var $catid = null;
    var $imgprice = null;
    var $imgtitle = null;
    var $imgauthor = null;
    var $imgauthorurl = null;
    var $imgtext = null;
    var $imgdate = null;
    var $imgcounter = null;
    var $imgdownloaded = null;
    var $imgvotes = null;
    var $imgvotesum = null;
    var $published = null;
    var $ordering = null;
    var $imgoriginalname = null;
    var $owner_id = null;
    var $notify = null;
    var $approved = null;
    var $useruploaded = null;

    function __construct(&$db) {
      parent::__construct('#__datsogallery', 'id', $db);
    }

    function dgMove($id, $catid) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $id = JRequest::getVar('id', array(0), '', 'array');
      if ((count($id) < 1)) {
        echo "<script> alert('".JText::_(_SELECT_ITEM_MOVE, true)." ); window.history.go(-1);</script>\n";
        return false;
      }
      if (is_array($id)) {
        $ids = implode(',', $id);
        $db->setQuery('UPDATE #__datsogallery SET catid = '.$catid.' WHERE id IN ('.$ids.')');
      }
      else {
        $db->setQuery('UPDATE #__datsogallery SET catid = '.$catid.' WHERE id = '.$id);
      }
      if (!$db->query()) {
        echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
        return false;
      }
      return true;
    }
  }

  class DatsoCategories extends JTable {
    var $cid = null;
    var $name = null;
    var $parent = null;
    var $description = null;
    var $ordering = null;
    var $user_id = null;
    var $access = null;
    var $approved = null;
    var $published = null;
    var $date = null;

    function __construct(&$db) {
      parent::__construct('#__datsogallery_catg', 'cid', $db);
    }

    function check() {
      $this->cid = intval($this->cid);
      return true;
    }
  }

  class Timer {
    static function DatetimeInGMT() {
        return date("Y-m-d H:i:s", time()-date("Z",time()));
    }

    static function GMTDatetimeToLocal($datetime) {
        $time = strtotime($datetime);
        return date("Y-m-d H:i:s", $time+date("Z",$time));
    }
  }
?>
