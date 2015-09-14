<?php
  defined('_JEXEC') or die('Restricted access');
  header('Content-type: text/plain; charset=UTF-8');
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  jimport('joomla.filesystem.folder');
  jimport('joomla.filesystem.file');
  jimport('joomla.filesystem.archive');
  require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'images.datsogallery.php');
  $user   = JFactory::getUser();
  if(!$user->id){
    jexit();
  }
  $targetDir = JPATH_ROOT.DS.'tmp'.DS.$user->id;
  if (!JFolder::exists(JPATH_ROOT.DS.'tmp'.DS.$user->id)) {
      JFolder::create(JPATH_ROOT.DS.'tmp'.DS.$user->id);
  }
  dgChmod($targetDir, 0777);
  //$finalDir = JPATH_ROOT.DS.'tmp'.DS.$user->id;
  $cleanupTargetDir = false;
  $maxFileAge = 60 * 60;
  @set_time_limit(5 * 60);
  $chunk = isset ($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
  $chunks = isset ($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
  $fileName = isset ($_REQUEST["name"]) ? $_REQUEST["name"] : '';
  $fileName = preg_replace('/[^\w\._]+/', '', $fileName);
  if (!JFolder::exists($targetDir))
    JFolder::create($targetDir);
  if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
    while (($file = readdir($dir)) !== false) {
      $filePath = $targetDir.DS.$file;
      if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
        JFile::delete($filePath);
    }
    closedir($dir);
  }
  else
    die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
  if (isset ($_SERVER["HTTP_CONTENT_TYPE"]))
    $contentType = $_SERVER["HTTP_CONTENT_TYPE"];
  if (isset ($_SERVER["CONTENT_TYPE"]))
    $contentType = $_SERVER["CONTENT_TYPE"];
  if (strpos(@$contentType, "multipart") !== false) {
    if (isset ($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
      $out = fopen($targetDir.DS.$fileName, $chunk == 0 ? "wb" : "ab");
      if ($out) {
        $in = fopen($_FILES['file']['tmp_name'], "rb");
        if ($in) {
          while ($buff = fread($in, 4096)) fwrite($out, $buff);
        }
        else
          die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
        fclose($out);
        JFile::delete($_FILES['file']['tmp_name']);
      }
      else
        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
    }
    else
      die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
  }
  else {
    $out = @fopen($targetDir.DS.$fileName, $chunk == 0 ? "wb" : "ab");
    if ($out) {
      $in = fopen("php://input", "rb");
      if ($in) {
        while ($buff = fread($in, 4096)) {
          fwrite($out, $buff);
        }
      }
      else
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
      fclose($out);
    }
    else
      die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
  }
  if ($chunk == ($chunks - 1)) {
    if (is_zip($targetDir.DS.$fileName)){
      $exr = JArchive::extract($targetDir.DS.$fileName, $targetDir);
      if ($exr === true) {
	    JFile::delete($targetDir.DS.$fileName);
	  }
    }
  }
  die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
?>