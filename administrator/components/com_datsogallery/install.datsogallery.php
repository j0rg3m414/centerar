<?php
defined('_JEXEC') or die;

function com_install() {
  jimport('joomla.filesystem.file');
  jimport('joomla.filesystem.folder');
  $lang = JFactory::getLanguage();
  $lang->load('com_datsogallery.sys');
  $content = '<html><body bgcolor="#ffffff"></body></html>';
  if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_datsogallery'
  .DS.'config.datsogallery.php')) {
    JFile::delete(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_datsogallery'
    .DS.'config.datsogallery.bak');
  }
  else {
    JFile::move(
    JPATH_ADMINISTRATOR.DS.'components'.DS.'com_datsogallery'.DS
    .'config.datsogallery.bak',
    JPATH_ADMINISTRATOR.DS.'components'.DS.'com_datsogallery'.DS
    .'config.datsogallery.php'
    );
  }
  if (!JFolder::exists(JPATH_ROOT.DS.'zipimport')) {
    JFolder::create(JPATH_ROOT.DS.'zipimport');
    JFile::write(JPATH_ROOT.DS.'zipimport'.DS.'index.html', $content);
  }
  if (!JFolder::exists(JPATH_ROOT.DS.'images'.DS.'dg_originals')) {
    JFolder::create(JPATH_ROOT.DS.'images'.DS.'dg_originals');
    JFile::write(JPATH_ROOT.DS.'images'.DS.'dg_originals'.DS.'index.html',
    $content);
  }
?>
<div style='display:block;margin:20px auto;width:300px;border:1px solid #CCC;
background:#F8F8FF;-webkit-border-radius: 5px;-moz-border-radius: 5px;
border-radius: 5px;-moz-box-shadow: 0 0 5px #888;
-webkit-box-shadow: 0 0 5px#888;box-shadow: 0 0 5px #888;'>
<div style='padding:20px'>
  <p style='text-align:left'>
  <img src='components/com_datsogallery/images/datsogallery-box.png'
  alt="datsogallery box" /></p>
  <p><?php echo JText::_('COM_DATSOGALLERY_INSTALL_DESC');?></p>
  <p style='color:DimGray'>
  <?php echo JText::_('COM_DATSOGALLERY_INSTALL_VERSION');?> 1.14</p>
  <p><h1><?php echo JText::_('COM_DATSOGALLERY_INSTALL_FINISHED');?></h1></p>
  <p>&copy; 2006-2011 <a href="http://www.datso.fr">Andrey Datso</a></p>
  </div>
</div>
<?php
}