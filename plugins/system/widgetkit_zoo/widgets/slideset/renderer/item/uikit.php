<?php
/**
* @package   Widgetkit
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$media_position = $params->get('media_position', 'top');

?>

<article class="wk-zoo-item uk-article clearfix">

	<?php if (($media_position == 'top' || $media_position == 'left' || $media_position == 'right') && $this->checkPosition('media')) : ?>
	<div class="uk-thumbnail uk-align-medium-<?php echo $media_position; ?>">
		<?php echo $this->renderPosition('media'); ?>
	</div>
	<?php endif; ?>

	<?php if ($this->checkPosition('title')) : ?>
	<h3 class="uk-h3 uk-margin-remove">
		<?php echo $this->renderPosition('title'); ?>
	</h3>
	<?php endif; ?>

	<?php if ($this->checkPosition('meta')) : ?>
	<p class="uk-article-meta uk-margin-remove">
		<?php echo $this->renderPosition('meta', array('style' => 'comma')); ?>
	</p>
	<?php endif; ?>

	<?php if (($media_position == 'middle') && $this->checkPosition('media')) : ?>
	<div class="uk-thumbnail uk-align-medium-<?php echo $media_position; ?>">
		<?php echo $this->renderPosition('media'); ?>
	</div>
	<?php endif; ?>

	<?php if ($this->checkPosition('description')) : ?>
		<?php echo $this->renderPosition('description', array('style' => 'uikit_block')); ?>
	<?php endif; ?>

	<?php if (($media_position == 'bottom') && $this->checkPosition('media')) : ?>
	<div class="media media-<?php echo $media_position; ?>">
		<?php echo $this->renderPosition('media'); ?>
	</div>
	<?php endif; ?>

	<?php if ($this->checkPosition('links')) : ?>
	<ul class="uk-subnav uk-subnav-line">
		<?php echo $this->renderPosition('links', array('style' => 'uikit_subnav')); ?>
	</ul>
	<?php endif; ?>

</article>