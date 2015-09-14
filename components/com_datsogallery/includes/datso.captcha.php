<?php
//session_start();
defined( '_JEXEC' ) or die( 'Restricted access' );

class captcha
{
    var $imagetype       = 'FREETYPE';
	var $size            = 15;
    var $length          = 4;
    var $type            = 'ALPHANUMERIC';

    function captcha()
	{
	    $this->fontpath = JPATH_COMPONENT.DS.'fonts'.DS;
	    $this->fonts    = $this->getFonts();
	    $errormgr       = new error;

		if ($this->imagetype == 'FREETYPE')
		{
			if ($this->fonts == FALSE)
			{
				$errormgr->addError('No fonts available!');
				$errormgr->displayError();
				die();
			}

			if (function_exists('imagettftext') == FALSE)
			{
				$errormgr->addError('the function imagettftext does not exist.');
				$errormgr->displayError();
				die();
			}
		}
        header('Content-type: image/png');
        ob_clean();
	    $this->stringGenerate();
	    $this->makeCaptcha();
        //exit;
    }

    function getFonts()
	{
      $fonts = array();
      if ($handle = opendir($this->fontpath))
	  {
        while (($file = readdir($handle)) !== false){
          $extension = strtolower(substr($file, strlen($file) - 3, 3));
          if ($extension == 'ttf')
            $fonts[] = $file;
        }
        closedir($handle);
      }
	  else
          return false;

      if (count($fonts) == 0)
          return false;
      else
          return $fonts;
    }

    function getRandomFont()
	{
		return $this->fontpath . $this->fonts[mt_rand(0, count($this->fonts) - 1)];
    }

	function stringGenerate()
	{

    	switch($this->type){
    		case 'ALPHA':
    			$CharPool = range('a','z');
    		break;
    		case 'NUMERIC':
    			$CharPool = range('0','9');
    		break;
    		case 'ALPHANUMERIC':
    		default:
    			$CharPool = array_merge(range('0','9'),range('a','z'));
    		break;
    	}
		$PoolLength = count($CharPool) - 1;

		for ($i = 0; $i < $this->length; $i++)
			$this->CaptchaString .= $CharPool[mt_rand(0, $PoolLength)];
    }

    function makeCaptcha ()
	{
	    require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
        if ($dg_theme == 'customtheme') {
          $backgroundcolor = '#'.$dg_body_background;
          $textcolor       = '#'.$dg_captcha_color;
        }
        else {
          $backgroundcolor = ($dg_theme != 'lightteme' && $dg_theme == 'darktheme') ? '#808080' : '#F9F9F9';
          $textcolor      = ($dg_theme != 'lightteme' && $dg_theme == 'darktheme') ? '#99CCFF' : '#696969';
        }
		$imagelength = $this->length * $this->size + 10;
		$imageheight = $this->size*1.6;
		$image       = imagecreate($imagelength, $imageheight);
		$usebgrcolor = sscanf($backgroundcolor, '#%2x%2x%2x');
		$usestrcolor = sscanf($textcolor, '#%2x%2x%2x');

		$bgcolor     = imagecolorallocate($image, $usebgrcolor[0], $usebgrcolor[1], $usebgrcolor[2]);
		$stringcolor = imagecolorallocate($image, $usestrcolor[0], $usestrcolor[1], $usestrcolor[2]);

		$filter      = new filters;

		if ($this->imagetype == 'FREETYPE')
		{
			for ($i = 0; $i < strlen($this->CaptchaString); $i++)
			{
				imagettftext($image,$this->size, mt_rand(-15,15), $i * $this->size + 10,
						$imageheight/100*80,
						$stringcolor,
						$this->getRandomFont(),
						$this->CaptchaString{$i});
			}
		}

		if ($this->imagetype == 'NOFREETYPE')
		{
			imagestring ($image, mt_rand(3,5), 10, 0,  $this->CaptchaString, $usestrcolor);
		}

		//$filter->noise($image, 2);
		imagepng($image);
		imagedestroy($image);
    }

    function getCaptcha ()
    {
		return $this->CaptchaString;
    }
}

class error
{

      var $errors;

      function error ()
      {

        $this->errors = array();

      } //error

      function addError ($errormsg)
      {

        $this->errors[] = $errormsg;

      } //addError

      function displayError ()
      {

      $iheight     = count($this->errors) * 20 + 10;
      $iheight     = ($iheight < 130) ? 130 : $iheight;

      $image       = imagecreate(600, $iheight);

//      $errorsign   = imagecreatefromjpeg('./gfx/errorsign.jpg');
//      imagecopy($image, $errorsign, 1, 1, 1, 1, 180, 120);

      $bgcolor     = imagecolorallocate($image, 255, 255, 255);

      $stringcolor = imagecolorallocate($image, 0, 0, 0);

      for ($i = 0; $i < count($this->errors); $i++)
      {

        $imx = ($i == 0) ? $i * 20 + 5 : $i * 20;


        $msg = 'Error[' . $i . ']: ' . $this->errors[$i];

        imagestring($image, 5, 190, $imx, $msg, $stringcolor);

        }

      imagepng($image);

      imagedestroy($image);

      } //displayError

      function isError ()
      {

        if (count($this->errors) == 0)
        {

            return FALSE;

        }
        else
        {

            return TRUE;

        }

      } //isError

  } //class: error



  class filters
  {

    function noise (&$image, $runs = 30){

      $w = imagesx($image);
      $h = imagesy($image);

      for ($n = 0; $n < $runs; $n++)
      {

        for ($i = 1; $i <= $h; $i++)
        {

          $randcolor = imagecolorallocate($image,
                                          mt_rand(0, 255),
                                          mt_rand(0, 255),
                                          mt_rand(0, 255));

          imagesetpixel($image,
                        mt_rand(1, $w),
                        mt_rand(1, $h),
                        $randcolor);

        }

      }

    } //noise

    function signs (&$image, $font, $cells = 3){

      $w = imagesx($image);
      $h = imagesy($image);

         for ($i = 0; $i < $cells; $i++)
         {

             $centerX     = mt_rand(5, $w);
             $centerY     = mt_rand(1, $h);
             $amount      = mt_rand(5, 10);
        $stringcolor = imagecolorallocate($image, 150, 150, 150);

             for ($n = 0; $n < $amount; $n++)
             {

          $signs = range('A', 'Z');
          $sign  = $signs[mt_rand(0, count($signs) - 1)];

               imagettftext($image, 15,
                            mt_rand(-15, 15),
                            $n * 15,//mt_rand(0, 15),
                            30 + mt_rand(-5, 5),
                            $stringcolor, $font, $sign);

             }

         }

    } //signs


  } //class: filters