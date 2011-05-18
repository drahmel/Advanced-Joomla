<?php
/**
 * @version		$Id: default.php 8483 2009-11-12 18:56:29Z drahmel $
 * @package		Joomla.Extensionsbb
 * @subpackage	com_articlestencil
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
// If the page class is defined, wrap the whole output in a div.
$pageClass = ''; //$this->params->get('pageclass_sfx');
?>
<h1>Article Template</h1>
<form id="inputArea" method="post" action="/index.php?option=com_articlestencil&task=insert&view=form">
	<input type="hidden" name="stencil_submit" id="stencil_submit" value="1" />
	<input type="submit" value="Save Task"/>
	<div class="title">
		<h2>Task</h2>
		<input name="title" type="text" value="<?php echo $this->cleanVars['title']; ?>"/>
	</div>
	<div>
		<div class="section">
			<h2>Description</h2>
			<textarea name="htmlintrotext" id="htmlintrotext" cols="80" rows="3"><?php echo $this->cleanVars['htmlintrotext']; ?></textarea>
		</div>
		<div class="section">
			<h2>Deliverables</h2>
			<div style="float:left;">aaa
			<p><input type="text" name="keyword1" id="keyword1" value="<?php echo $this->cleanVars['keyword1']; ?>" /></p>
			<p><input type="text" name="keyword1" id="keyword1" value="<?php echo $this->cleanVars['keyword1']; ?>" /></p>
			<p><input type="text" name="keyword1" id="keyword1" value="<?php echo $this->cleanVars['keyword1']; ?>" /></p>
			</div>
		</div>
		<div class="section">
			<h2>Subtasks</h2>
			<p><input type="text" name="keyword1" id="keyword1" value="<?php echo $this->cleanVars['keyword1']; ?>" /></p>
			<p><input type="text" name="keyword1" id="keyword1" value="<?php echo $this->cleanVars['keyword1']; ?>" /></p>
			<p><input type="text" name="keyword1" id="keyword1" value="<?php echo $this->cleanVars['keyword1']; ?>" /></p>
		</div>
		<div class="section">
			<h2>Relevant files</h2>
			<input type="text" name="keyword1" id="keyword1" value="<?php echo $this->cleanVars['keyword1']; ?>" />
			<input type="text" name="keyword2" id="keyword2" value="<?php echo $this->cleanVars['keyword2']; ?>" />
			<input type="text" name="keyword3" id="keyword3" value="<?php echo $this->cleanVars['keyword3']; ?>" />
		</div>
		<div class="section">
			<h2>Details</h2>
			<div>
				<label for="resource1_title">Level of Effort</label>
				<input type="text" name="resource1_title" id="resource1_title" size="8"
					value="<?php echo $this->cleanVars['resource1_title']; ?>" />
				<label for="resource1_url">Points</label>
				<input type="text" name="resource1_url" id="resource1_url" size="8"
					value="<?php echo $this->cleanVars['resource1_url']; ?>" />
				<label for="resource1_url">Estimated Time</label>
				<input type="text" name="resource1_url" id="resource1_url" size="8"
					value="<?php echo $this->cleanVars['resource1_url']; ?>" />
				<label for="resource1_url">Priority</label>
				<input type="text" name="resource1_url" id="resource1_url" size="4"
					value="<?php echo $this->cleanVars['resource1_url']; ?>" />
				<div>
				<label for="resource1_url">Possible Problems</label>
				<input type="text" name="resource1_url" id="resource1_url" size="40"
					value="<?php echo $this->cleanVars['resource1_url']; ?>" />
				</div>
				<div>
				<label for="resource1_url">Comments</label>
				<input type="text" name="resource1_url" id="resource1_url" size="40"
					value="<?php echo $this->cleanVars['resource1_url']; ?>" />
				</div>
			</div>
		</div>
	</div>
	
</form>
<hr/>
<?php echo $this->previewStr; ?>
