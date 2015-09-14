<?php
  defined('_JEXEC') or die('Restricted access');
  jimport('joomla.filesystem.file');
  $ddp_file = JPATH_ROOT.DS.'components'.DS.'com_datsogallery'.DS.'datso.datsopic.php';
  if (JFile::exists($ddp_file)) {
    $file_data = JFile::read($ddp_file);
    $search = '/ AND published = 1/i';
    $replace = '';
    $file_data = preg_replace($search, $replace, $file_data);
    JFile::write($ddp_file, $file_data);
  }
?>