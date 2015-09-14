<?php
defined('_JEXEC') or die;

/**
 * Template for Joomla! CMS, created with Artisteer.
 * See readme.txt for more details on how to use the template.
 */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.php';

// Create alias for $this object reference:
$document = $this;

// Shortcut for template base url:
$templateUrl = $document->baseurl . '/templates/' . $document->template;

Artx::load("Artx_Page");

// Initialize $view:
$view = $this->artx = new ArtxPage($this);

// Decorate component with Artisteer style:
$view->componentWrapper();

JHtml::_('behavior.framework', true);

?>
<!DOCTYPE html>
<html dir="ltr" lang="<?php echo $document->language; ?>">
<head>
    <jdoc:include type="head" />
    <link rel="stylesheet" href="<?php echo $document->baseurl; ?>/templates/system/css/system.css" />
    <link rel="stylesheet" href="<?php echo $document->baseurl; ?>/templates/system/css/general.css" />

    <!-- Created by Artisteer v4.1.0.59861 -->
    
    
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="<?php echo $templateUrl; ?>/css/template.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="<?php echo $templateUrl; ?>/css/template.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="<?php echo $templateUrl; ?>/css/template.responsive.css" media="all">


    <script>if ('undefined' != typeof jQuery) document._artxJQueryBackup = jQuery;</script>
    <script src="<?php echo $templateUrl; ?>/jquery.js"></script>
    <script>jQuery.noConflict();</script>

    <script src="<?php echo $templateUrl; ?>/script.js"></script>
    <?php $view->includeInlineScripts() ?>
    <script>if (document._artxJQueryBackup) jQuery = document._artxJQueryBackup;</script>
    <script src="<?php echo $templateUrl; ?>/script.responsive.js"></script>
</head>
<body>

<div id="art-main">
<header class="art-header"><?php echo $view->position('position-30', 'art-nostyle'); ?>


    <div class="art-shapes">

            </div>
<h2 class="art-slogan" data-left="2.39%"><?php echo $this->params->get('siteSlogan'); ?></h2>




                <div id="art-flash-area">
                    <div id="art-flash-container">
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="1000" height="300" id="art-flash-object">
                    	<param name="movie" value="<?php echo $templateUrl; ?>/container.swf">
                    	<param name="quality" value="high">
                    	<param name="scale" value="default">
                    	<param name="wmode" value="transparent">
                    	<param name="flashvars" value="color1=0xFFFFFF&amp;alpha1=1&amp;framerate1=24&amp;loop=true&amp;wmode=transparent&amp;clip=<?php echo $templateUrl; ?>/images/flash.swf&amp;radius=5&amp;clipx=-100&amp;clipy=0&amp;initalclipw=900&amp;initalcliph=225&amp;clipw=1200&amp;cliph=300&amp;width=1000&amp;height=300&amp;textblock_width=0&amp;textblock_align=no&amp;hasTopCorners=true&amp;hasBottomCorners=true">
                        <param name="swfliveconnect" value="true">
                    	<!--[if !IE]>-->
                    	<object type="application/x-shockwave-flash" data="<?php echo $templateUrl; ?>/container.swf" width="1000" height="300">
                    	    <param name="quality" value="high">
                    	    <param name="scale" value="default">
                    	    <param name="wmode" value="transparent">
                        	<param name="flashvars" value="color1=0xFFFFFF&amp;alpha1=1&amp;framerate1=24&amp;loop=true&amp;wmode=transparent&amp;clip=<?php echo $templateUrl; ?>/images/flash.swf&amp;radius=5&amp;clipx=-100&amp;clipy=0&amp;initalclipw=900&amp;initalcliph=225&amp;clipw=1200&amp;cliph=300&amp;width=1000&amp;height=300&amp;textblock_width=0&amp;textblock_align=no&amp;hasTopCorners=true&amp;hasBottomCorners=true">
                            <param name="swfliveconnect" value="true">
                    	<!--<![endif]-->
                    		<div class="art-flash-alt"><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player"></a></div>
                    	<!--[if !IE]>-->
                    	</object>
                    	<!--<![endif]-->
                    </object>
                    </div>
                    </div>
                    
</header>
<div class="art-sheet clearfix">
            <?php echo $view->position('position-15', 'art-nostyle'); ?>
<?php echo $view->positions(array('position-16' => 33, 'position-17' => 33, 'position-18' => 34), 'art-block'); ?>
<div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <?php if ($view->containsModules('position-7', 'position-4', 'position-5')) : ?>
<div class="art-layout-cell art-sidebar1">
<?php echo $view->position('position-7', 'art-block'); ?>
<?php echo $view->position('position-4', 'art-block'); ?>
<?php echo $view->position('position-5', 'art-block'); ?>




                        </div>
<?php endif; ?>

                        <div class="art-layout-cell art-content">
<?php
  echo $view->position('position-19', 'art-nostyle');
  if ($view->containsModules('position-2'))
    echo artxPost($view->position('position-2'));
  echo $view->positions(array('position-20' => 50, 'position-21' => 50), 'art-article');
  echo $view->position('position-12', 'art-nostyle');
  echo artxPost(array('content' => '<jdoc:include type="message" />', 'classes' => ' art-messages'));
  echo '<jdoc:include type="component" />';
  echo $view->position('position-22', 'art-nostyle');
  echo $view->positions(array('position-23' => 50, 'position-24' => 50), 'art-article');
  echo $view->position('position-25', 'art-nostyle');
?>



                        </div>
                        <?php if ($view->containsModules('position-6', 'position-8', 'position-3')) : ?>
<div class="art-layout-cell art-sidebar2">
<?php echo $view->position('position-6', 'art-block'); ?>
<?php echo $view->position('position-8', 'art-block'); ?>
<?php echo $view->position('position-3', 'art-block'); ?>


                        </div>
<?php endif; ?>
                    </div>
                </div>
            </div>
<?php echo $view->positions(array('position-9' => 33, 'position-10' => 33, 'position-11' => 34), 'art-block'); ?>
<?php echo $view->position('position-26', 'art-nostyle'); ?>

<footer class="art-footer">
<?php if ($view->containsModules('position-27')) : ?>
    <?php echo $view->position('position-27', 'art-nostyle'); ?>
<?php else: ?>
<div style="margin:-15px -25px -25px -25px;width:100%;height:120px;"><img width="1920" height="140" alt="" src="<?php echo $document->baseurl ?>/templates/<?php echo $document->template; ?>/images/carhinno_ftr.jpg" style="float: left;" class="">
<div style="position:absolute;top:40px;left:2%;width:1200px"><span style="color: rgb(255, 255, 255);">Center Ar - © 2015 - Todos os direitos reservados.<br><br></span>
</div>
</div>
<?php endif; ?>
</footer>

    </div>
    <p class="art-page-footer">
        <span id="art-footnote-links">Designed by Jorge Maia.</span>
    </p>
</div>



<?php echo $view->position('debug'); ?>
</body>
</html>