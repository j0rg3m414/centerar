<?php
  defined('_JEXEC') or die('Restricted access');

  function dgChmod($dir, $mode = 0755) {
    static $ftpOptions;
    if (!isset ($ftpOptions)) {
      jimport('joomla.client.helper');
      $ftpOptions = JClientHelper::getCredentials('ftp');
    }
    if ($ftpOptions['enabled'] == 1) {
      jimport('joomla.client.ftp');
      $ftp = JFTP::getInstance($ftpOptions['host'], $ftpOptions['port'], null, $ftpOptions['user'], $ftpOptions['pass']);
      $dir = JPath::clean(str_replace(JPATH_ROOT, $ftpOptions['root'], $dir), '/');
      return $ftp->chmod($dir, $mode);
    }
    else {
      return true;
    }
  }

  function dgDelDir($dir) {
    jimport('joomla.filesystem.folder');
    if (!JFolder::exists($dir))
      return true;
    if (!is_dir($dir) || is_link($dir))
      return JFolder::delete($dir);
    foreach (scandir($dir) as $item) {
      if ($item == '.' || $item == '..')
        continue;
      if (!dgDelDir($dir.DS.$item)) {
        dgChmod($dir.DS.$item, 0777);
        if (!dgDelDir($dir.DS.$item))
          return false;
      }
    }
    return rmdir($dir);
  }

  function is_image($filename) {
    $ext = strtolower(strrchr($filename, "."));
    if ($ext == ".jpg" || $ext == ".jpeg" || $ext == ".png" || $ext == ".gif") {
     return true;
    }
    else {
      return false;
    }
  }

  function is_zip($filename) {
    $ext = strtolower(strrchr($filename, "."));
    if ($ext == ".zip"){
      return true;
    }
    else {
      return false;
    }
  }

  function dgFileCheck($file) {
    if(($imginfo = getimagesize($file)) === FALSE) {
	   return false;
	}
   return true;
  }

  function resize($original, $w, $h, $crop, $cropratio = 0, $wm = 0, $catid = '') {
    require (dirname(__FILE__).DS.'config.datsogallery.php');
    jimport('joomla.filesystem.file');
    jimport('joomla.filesystem.folder');
    $cro = str_replace(':', 'x', $cropratio);
    $dirname = "datsogallery_catid-{$catid}_{$w}x{$h}_{$cro}";
    $types = array(1 => 'gif', 'jpeg', 'png');
    if (!JFolder::exists(JPATH_SITE.DS.'cache'.DS.$dirname)) {
      JFolder::create(JPATH_SITE.DS.'cache'.DS.$dirname);
      $content = '<html><body bgcolor="#ffffff"></body></html>';
      JFile::write(JPATH_SITE.DS.'cache'.DS.$dirname.DS.'index.html', $content);
    }
    if(!JFile::exists(JPATH_SITE.$ad_pathoriginals.DS.'blank.jpg')){
      JFile::copy(JPATH_COMPONENT_SITE.DS.'images'.DS.'blank.jpg', JPATH_SITE.$ad_pathoriginals.DS.'blank.jpg');
    }
    $path = JPath::clean(JPATH_SITE.$ad_pathoriginals.DS.$original);
    dgFileCheck($path);
    if (!file_exists($path) || is_dir($path) || !($size = getimagesize($path)))
    return;
    $width = $size[0];
    $height = $size[1];
    $mw = $w;
    $mh = $h;
    $x = 0;
    $y = 0;
    if ($crop == '1') {
      $cr = explode(':', $cropratio);
      if (count($cr) == 2) {
        $rc = $width / $height;
        $crc = (float) $cr[0] / (float) $cr[1];
        if ($rc < $crc) {
          $oh = $height;
          $height = $width / $crc;
          $y = ($oh - $height) / 2;
        }
        else
          if ($rc > $crc) {
            $ow = $width;
            $width = $height * $crc;
            $x = ($ow - $width) / 2;
          }
      }
    }
    $xr = $mw / $width;
    $yr = $mh / $height;
    if ($xr * $height < $mh) {
      $th = ceil($xr * $height);
      $tw = $mw;
    }
    else {
      $tw = ceil($yr * $width);
      $th = $mh;
    }
    $relfile = JURI::root(true).'/cache/'.$dirname.'/'.basename($original);
    $cachefile = JPATH_SITE.DS.'cache'.DS.$dirname.DS.basename($original);
    if (file_exists($cachefile)) {
      $cachesize = getimagesize($cachefile);
      $cached = ($cachesize[0] == $tw && $cachesize[1] == $th);
      if (filemtime($cachefile) < filemtime($path))
        $cached = false;
    }
    else {
      $cached = false;
    }
    if (!$cached && ($size[0] >= $w || $size[1] >= $h)) {
      $resize = ($size[0] >= $w || $size[1] >= $h);
    }
    elseif (!$cached && ($size[0] <= $w || $size[1] <= $h)) {
      $resize = true;
    }
    else {
      $resize = false;
    }
    if ($resize) {
      @increasememory($original);
      $image = call_user_func('imagecreatefrom'.$types[$size[2]], $path);
      $temp = ($size[0] <= $w || $size[1] <= $h) ? imagecreatetruecolor($width, $height) : imagecreatetruecolor($tw, $th);
      if (function_exists('imagecreatetruecolor') && ($temp)) {
        if (in_array($types[$size[2]], array('gif', 'png'))) {
          $color = 'F5F5F5';
          $background = imagecolorallocate($temp, hexdec($color[0].$color[1]), hexdec($color[2].$color[3]), hexdec($color[4].$color[5]));
          imagefillalpha($temp, $background);
        }
        if ($resize && ($size[0] <= $w || $size[1] <= $h)) {
          if (in_array($types[$size[2]], array('gif', 'png'))) {
            imagecopyresampled($temp, $image, 0, 0, 0, 0, $size[0], $size[1], $width, $height);
          }
          else {
            fastimagecopyresampled($temp, $image, 0, 0, 0, 0, $size[0], $size[1], $width, $height);
          }
        }
        else {
          if (in_array($types[$size[2]], array('gif', 'png'))) {
              imagecopyresampled($temp, $image, 0, 0, $x, $y, $tw, $th, $width, $height);
            }
            else {
              fastimagecopyresampled($temp, $image, 0, 0, $x, $y, $tw, $th, $width, $height);
            }
        }
        imagedestroy($image);
        $sharpness = findsharp($width, $tw);
        $sharpenMatrix = array(array(- 1, - 2, - 1), array(- 2, $sharpness + 12, - 2), array(- 1, - 2, - 1));
        $divisor = $sharpness;
        $offset = 0;
        imageconvolution($temp, $sharpenMatrix, $divisor, $offset);
        if ($wm) {
          $watermarkPNGFile = JPATH_SITE.DS.'components'.DS.'com_datsogallery'.DS.'images'.DS.'watermark.png';
          $watermarkMargin = 5;
          $watermark = imagecreatefrompng($watermarkPNGFile);
          $watermarkWidth = imagesx($watermark);
          $watermarkHeight = imagesy($watermark);
          $imageWidth = imagesx($temp);
          $imageHeight = imagesy($temp);
          switch($ad_wmpos) {
            case 'topleft':
                $placeWatermarkX = $watermarkMargin;
                $placeWatermarkY = $watermarkMargin;
                break;
            case 'topright':
                $placeWatermarkX = ($imageWidth) - ($watermarkWidth) - $watermarkMargin;
                $placeWatermarkY = $watermarkMargin;
                break;
            case 'bottomleft':
                $placeWatermarkX = $watermarkMargin;
                $placeWatermarkY = $imageHeight - $watermarkHeight - $watermarkMargin;
                break;
            case 'bottomright':
                $placeWatermarkX = $imageWidth - $watermarkWidth - $watermarkMargin;
                $placeWatermarkY = $imageHeight - $watermarkHeight - $watermarkMargin;
                break;
            case 'center':
               $placeWatermarkX = ($imageWidth - $watermarkWidth)/2 - $watermarkMargin;
               $placeWatermarkY = ($imageHeight - $watermarkHeight)/2 - $watermarkMargin;
               break;
          }
          imagecopymerge_alpha($temp, $watermark, $placeWatermarkX, $placeWatermarkY, 0, 0, $watermarkWidth, $watermarkHeight, 0);
        }
      }
      if ($types[$size[2]] == 'jpeg') {
        dgChmod(JPATH_SITE.DS.'cache'.DS.$dirname, 0777);
        call_user_func('image'.$types[$size[2]], $temp, $cachefile, $ad_thumbquality);
        dgChmod(JPATH_SITE.DS.'cache'.DS.$dirname);
      }
      else {
        dgChmod(JPATH_SITE.DS.'cache'.DS.$dirname, 0777);
        call_user_func('image'.$types[$size[2]], $temp, $cachefile);
        dgChmod(JPATH_SITE.DS.'cache'.DS.$dirname);
      }
      imagedestroy($temp);
    }
    return $relfile;
    exit;
  }

  function get_width_height($original, $w, $h, $catid, $cropratio = '0') {
    $cro = str_replace(':', 'x', $cropratio);
    $dir = "datsogallery_catid-{$catid}_{$w}x{$h}_{$cro}";
    $cachefile = JPATH_SITE.DS.'cache'.DS.$dir.DS.basename($original);
    $wh = getimagesize($cachefile);
    return $wh[3];
  }

  function getCacheFile($original, $w, $h, $catid, $cropratio = '0') {
    $cro = str_replace(':', 'x', $cropratio);
    $dir = "datsogallery_catid-{$catid}_{$w}x{$h}_{$cro}";
    $cachefile = JPATH_SITE.DS.'cache'.DS.$dir.DS.basename($original);
    return $cachefile;
  }

  function getCacheFileSize($original, $w, $h, $catid, $cropratio = '0') {
    $cro = str_replace(':', 'x', $cropratio);
    $dir = "datsogallery_catid-{$catid}_{$w}x{$h}_{$cro}";
    $cachefilesize = filesize(JPATH_SITE.DS.'cache'.DS.$dir.DS.basename($original));
    return $cachefilesize;
  }

  function findsharp($orig, $final) {
    $final = $final * (750.0 / $orig);
    $a = 52;
    $b = - 0.27810650887573124;
    $c =.00047337278106508946;
    $result = $a + $b * $final + $c * $final * $final;
    return max(round($result), 0);
  }

  function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
    $opacity = $pct;
    $w = imagesx($src_im);
    $h = imagesy($src_im);
    $cut = imagecreatetruecolor($src_w, $src_h);
    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    $opacity = 100 - $opacity;
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
  }

  if (!function_exists('imageconvolution')) {

    function imageconvolution($src, $filter, $filter_div, $offset) {
      if ($src == NULL) {
        return 0;
      }
      $sx = imagesx($src);
      $sy = imagesy($src);
      $srcback = ImageCreateTrueColor($sx, $sy);
      ImageCopy($srcback, $src, 0, 0, 0, 0, $sx, $sy);
      if ($srcback == NULL) {
        return 0;
      }
      $pxl = array(1, 1);
      for ($y = 0; $y < $sy;++$y) {
        for ($x = 0; $x < $sx;++$x) {
          $new_r = $new_g = $new_b = 0;
          $alpha = imagecolorat($srcback, $pxl[0], $pxl[1]);
          $new_a = $alpha >> 24;
          for ($j = 0; $j < 3;++$j) {
            $yv = min(max($y - 1 + $j, 0), $sy - 1);
            for ($i = 0; $i < 3;++$i) {
              $pxl = array(min(max($x - 1 + $i, 0), $sx - 1), $yv);
              $rgb = imagecolorat($srcback, $pxl[0], $pxl[1]);
              $new_r += (($rgb >> 16) & 0xFF) * $filter[$j][$i];
              $new_g += (($rgb >> 8) & 0xFF) * $filter[$j][$i];
              $new_b += ($rgb & 0xFF) * $filter[$j][$i];
            }
          }
          $new_r = ($new_r / $filter_div) + $offset;
          $new_g = ($new_g / $filter_div) + $offset;
          $new_b = ($new_b / $filter_div) + $offset;
          $new_r = ($new_r > 255) ? 255 : (($new_r < 0) ? 0 : $new_r);
          $new_g = ($new_g > 255) ? 255 : (($new_g < 0) ? 0 : $new_g);
          $new_b = ($new_b > 255) ? 255 : (($new_b < 0) ? 0 : $new_b);
          $new_pxl = ImageColorAllocateAlpha($src, (int) $new_r, (int) $new_g, (int) $new_b, $new_a);
          if ($new_pxl == - 1) {
            $new_pxl = ImageColorClosestAlpha($src, (int) $new_r, (int) $new_g, (int) $new_b, $new_a);
          }
          if (($y >= 0) && ($y < $sy)) {
            imagesetpixel($src, $x, $y, $new_pxl);
          }
        }
      }
      imagedestroy($srcback);
      return 1;
    }

  }

  function fastimagecopyresampled(&$dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 3) {
    if (empty ($src_image) || empty ($dst_image) || $quality <= 0) {
      return false;
    }
    if ($quality < 5 && (($dst_w * $quality) < $src_w || ($dst_h * $quality) < $src_h)) {
      $temp = imagecreatetruecolor($dst_w * $quality + 1, $dst_h * $quality + 1);
      imagecopyresized($temp, $src_image, 0, 0, $src_x, $src_y, $dst_w * $quality + 1, $dst_h * $quality + 1, $src_w, $src_h);
      imagecopyresampled($dst_image, $temp, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $dst_w * $quality, $dst_h * $quality);
      imagedestroy($temp);
    }
    else
      imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
    return true;
  }

  function increasememory($filename) {
    require (dirname(__FILE__).DS.'config.datsogallery.php');
    $imageInfo = getimagesize(JPath::clean(JPATH_SITE.$ad_pathoriginals.DS.$filename));
    $MB = 1048576;
    $K64 = 65536;
    $TWEAKFACTOR = 5;
    if (strpos($filename, '.jpg') === false) {
      $imageInfo['bits'] = 24;
      $imageInfo['channels'] = 16;
    }
    $memoryNeeded = round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + $K64) * $TWEAKFACTOR);
    $memoryLimitMB = str_replace('M', '', ini_get('memory_limit'));
    $memoryLimit = $memoryLimitMB * $MB;
    if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > $memoryLimit) {
      $newLimit = $memoryLimitMB + ceil((memory_get_usage() + $memoryNeeded - $memoryLimit) / $MB);
      ini_set('memory_limit', $newLimit.'M');
      return true;
    }
    else {
      return false;
    }
  }

  function imagefillalpha($image, $color) {
    imagefilledrectangle($image, 0, 0, imagesx($image), imagesy($image), $color);
  }
?>