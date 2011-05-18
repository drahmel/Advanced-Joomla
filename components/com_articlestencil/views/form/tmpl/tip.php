<?php
/**
 * @version		$Id: default.php 12812 2009-09-22 03:58:25Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	com_newsfeeds
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
// If the page class is defined, wrap the whole output in a div.
$pageClass = ''; //$this->params->get('pageclass_sfx');
?>
<h1>Article Template</h1>
<form id="inputArea" method="post" action="/index.php?option=com_articlestencil&task=display&view=form">
	<input type="hidden" name="stencil_submit" id="stencil_submit" value="1" />
	<input type="submit"/>
	<div class="title">
		<h2>Title</h2>
		<input name="title" type="text" value="<?php echo $this->cleanVars['title']; ?>"/>
	</div>
	<div>
		<div class="section">
			<h2>Introtext</h2>
			<textarea name="htmlintrotext" id="htmlintrotext" cols="80" rows="3"><?php echo $this->cleanVars['htmlintrotext']; ?></textarea>
		</div>
		<div class="section">
			<h2>Bodytext</h2>
			<textarea name="htmlbodytext" id="htmlbodytext" cols="80" rows="5"><?php echo $this->cleanVars['htmlbodytext']; ?></textarea>
		</div>
		<div class="section">
			<h2>Keywords</h2>
			<input type="text" name="keyword1" id="keyword1" value="<?php echo $this->cleanVars['keyword1']; ?>" />
			<input type="text" name="keyword2" id="keyword2" value="<?php echo $this->cleanVars['keyword2']; ?>" />
			<input type="text" name="keyword3" id="keyword3" value="<?php echo $this->cleanVars['keyword3']; ?>" />
		</div>
		<div class="section">
			<h2>Resources</h2>
			<div>
				<label for="resource1_title">Title</label>
				<input type="text" name="resource1_title" id="resource1_title" size="30"
					value="<?php echo $this->cleanVars['resource1_title']; ?>" />
				<label for="resource1_url">URL</label>
				<input type="text" name="resource1_url" id="resource1_url" size="40"
					value="<?php echo $this->cleanVars['resource1_url']; ?>" />
			</div>
			<div>
				<label for="resource2_title">Title</label>
				<input type="text" name="resource2_title" id="resource2_title" size="30"
					value="<?php echo $this->cleanVars['resource2_title']; ?>" />
				<label for="resource2_url">URL</label>
				<input type="text" name="resource2_url" id="resource2_url" size="40"
					value="<?php echo $this->cleanVars['resource2_url']; ?>" />
			</div>
			<div>
				<label for="resource3_title">Title</label>
				<input type="text" name="resource3_title" id="resource3_title" size="30"
					value="<?php echo $this->cleanVars['resource3_title']; ?>" />
				<label for="resource3_url">URL</label>
				<input type="text" name="resource3_url" id="resource3_url" size="40"
					value="<?php echo $this->cleanVars['resource3_url']; ?>" />
			</div>
		</div>
	</div>
	
</form>
<hr/>
<?php echo $this->previewStr; ?>
