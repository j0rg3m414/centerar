<?php
defined('_JEXEC') or die();
$user = JFactory::getUser();
if (!$user->id) exit;
jimport('joomla.filesystem.file');
include_once (JPATH_COMPONENT.DS.'includes'.DS.'wmresize.php');
$document = JFactory::getDocument();
$uri = JFactory::getURI();
$error = null;
$success = null;
$output = null;
$dest = JPATH_COMPONENT_SITE.DS.'images'.DS.'watermark.png';
$file = JRequest::getVar('file_upload', null, 'files', 'array');
if ($file) {
  $filename = JFile::makeSafe($file['name']);
  $src = $file['tmp_name'];
  $size = getimagesize($src);
  if ($size['mime'] == 'image/png') {
    if ($size[1] < 64)
      $height = $size[1];
    else
      $height = 64;
    if ($size[1] > 64 || $size['mime'] != 'image/png') {
      $dgwm = new waterMark();
      $dgwm->resize($src, 0, $height, IMAGETYPE_PNG);
    }
    if (JFile::upload($src, $dest)) {
      $success = JText::_('COM_DATSOGALLERY_WM_SUCCESS'); //"Successfully!";
      dgclean();
    }
    else {
      $error = JText::_('COM_DATSOGALLERY_WM_ERROR_COPY'); //"Error: Could not copy file to destination";
    }
  }
  else {
    $error = JText::_('COM_DATSOGALLERY_WM_ERROR_MIME'); //"Error: Only PNG files allowed";
  }
  if ($error) {
    $output = '<div class="dg_uploaderror">'.$error.'</div>';
  }
  else {
    $output = '<div class="dg_uploadsuccess">'.$success.'</div>';
  }
}

$wm_url = $uri->root().'components/com_datsogallery/images/watermark.png';
$wm_url .= '?'.intval(microtime(true));
?>
<html>
<head>
<style>
body{font:11px "Segoe UI",sans-serif;margin:10px 0}
.dg_wmbg{margin-bottom:10px;padding:10px 20px;display:inline-block;background:transparent url(./components/com_datsogallery/images/transparent.gif)}
.dg_wm{vertical-align:middle}
.dg_output{padding-bottom:10px}
.dg_uploaderror{color:#C00;font-weight:bold}
.dg_uploadsuccess{color:#6B8E23;font-weight:bold}
.dgbrowse{font-family:"Segoe UI",sans-serif;border:#A9A9A9 1px solid;text-shadow:0 1px 0 #FFFFFF;color:#464646;cursor:pointer;font-size: 10px !important;font-weight:bold;text-transform:uppercase;line-height:12px;padding:4px 16px;margin:0;text-decoration:none;-moz-border-radius:3px;border-radius:3px;background:#E4E4E4 url(../components/com_datsogallery/images/gradient-white.png);}.dgbrowse:hover{border-color:#808080}
form{position:absolute;left:-99999px}
</style>
<script type="text/javascript" src="<?php echo $uri->root(); ?>components/com_datsogallery/libraries/jquery.min.js"></script>
<script>
  jQuery(document).ready(function() {
      jQuery('.dgbrowse').bind('click', function() {
          jQuery('input[type="file"]').trigger('click');
      });
  });
  function upload(e){
    var target = jQuery.event.fix(e).target;
    jQuery(target).closest('form').submit();
  }
</script>

</head>
<body>
<div class="dg_wmbg"><img src="<?php echo $wm_url;?>" alt="wm" class="dg_wm" /></div>
<div class="dg_output"><?php echo $output;?></div>
<form method="post" action="" enctype="multipart/form-data" onSubmit="if(file_upload.value=='') {alert('Select a file!');return false;}">
  <input style="margin-left: 0" type="file" name="file_upload" onChange="upload(event)" />
  <?php echo JHTML::_('form.token');?>
</form>
<div><button class="dgbrowse"><?php echo JText::_('COM_DATSOGALLERY_SELECT_WATERMARK'); ?></button></div>
</body>
</html>
