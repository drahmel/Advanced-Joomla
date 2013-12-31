<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

class Form_builderControllerform_builder extends JControllerAdmin {
	function __construct( $config = array() ) {
		parent::__construct( $config );
		// Register Extra tasks
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'resethits', 'save' );
		$this->registerTask( 'unpublish', 'publish' );
	}

	// Record data received from form posting
	function save() {
		$app  = JFactory::getApplication();
		// Set title in Administrator interface		 
		JToolBarHelper::title( JText::_( 'Update Form Entry' ), 'addedit.png' );
		
		// Get reference to database object
		$db = JFactory::getDBO();
		
		// Retrieve data from form
		$colName = $db->quote($app->input->getVar('name'));
		$colAlias = $db->quote($app->input->getVar('alias'));
		// Decode and recode the JSON to make sure it's valid
		$colJSON = $app->input->getVar('json');
		$data = json_decode($colJSON, true);
		$colJSON = $db->quote(json_encode($data));
		$id = $app->input->getInt('id');
		if(empty($id)) {
			$query = "INSERT INTO formbuilder_forms (`name`, `alias`, `json`)
				VALUES
				({$colName}, {$colAlias}, {$colJSON});";
			$db->setQuery( $query, 0);
			$result = $db->query();
			$app->enqueueMessage (JText::_ ('Form inserted!'));
			$id = $db->insertid();
		} else {
			// Record updates
			$query = "UPDATE formbuilder_forms " .
				" SET name={$colName}, alias={$colAlias}, json={$colJSON} " .
				" WHERE id = " . $id ;
			$db->setQuery( $query, 0);
			$result = $db->query();
			$app->enqueueMessage (JText::_ ('Form updated!'));
		}
		if($app->input->getVar('task') == 'apply') {
			$this->setRedirect (JRoute::_ ("index.php?option=com_formbuilder&task=edit&id=$id", false));
		} else {
			$this->setRedirect (JRoute::_ ("index.php?option=com_formbuilder", false));			
		}
	}

	// Display edit list of all guestbook entries
	function display($cachable = false, $urlparams = array()) {
		$version = "0.1.170";
		$db = JFactory::getDBO();
		
		// Set title in Administrator interface		 
		JToolBarHelper::title( JText::_( 'Form Builder admin component -- ' . JText::_( 'version' )." $version" ) , 'addedit.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'Copy' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::preferences('com_formbuilder', '200');
		JToolBarHelper::help( 'screen.formbuilder' );
		
		$query = "SELECT * FROM formbuilder_forms ORDER BY id; ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
	?>
	 <script type="text/javascript">
			window.addEvent('domready', function(){ new Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {onActive: function(toggler, i) { toggler.addClass('jpane-toggler-down'); toggler.removeClass('jpane-toggler'); },onBackground: function(toggler, i) { toggler.addClass('jpane-toggler'); toggler.removeClass('jpane-toggler-down'); },duration: 300,opacity: false}); });
			window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });
	</script>
	
	<form action="/administrator/index.php?option=com_formbuilder&amp;view=forms" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="task" value="" />
	<table class="table table-striped adminlist">
	<tr>
		<th width=5%> <?php echo JText::_( 'EntryID' ); ?> </th>
		<th width=50%> <?php echo JText::_( 'Name' ); ?> </th>
		<th width=5%> <?php echo JText::_( 'SQL?' ); ?> </th>
		<th width=5%> <?php echo JText::_( 'Display?' ); ?> </th>
		<th width=5%> <?php echo JText::_( 'HTML?' ); ?> </th>
	</tr>
	
	<?php
		$out = '';
		foreach ($rows as $row) {
			// Create url to allow user to click & jump to edit article
			$url = "index.php?option=com_formbuilder&task=edit&" .
				"&id=" . $row->id;
			$link = 'index.php?option=com_formbuilder&task=edit&id='. $row->id;
			if(strlen($row->sql)>0) { $hasSQL = "Y"; } else { $hasSQL = "N"; }
			if(strlen($row->json)>0) { $hasJSON = "Y"; } else { $hasJSON = "N"; }
			if(strlen($row->html)>0) { $hasHTML = "Y"; } else { $hasHTML = "N"; }
			$target = ""; // TARGET='_blank'
			$out .= "<tr>" .
				"<td>" . $row->id . "</td>" .
				"<td><a href='" . $url . "' $target >" . $row->name . "</a></td>" .
				"<td>" . $hasSQL . "</td>" .
				"<td>" . $hasJSON . "</td>" .
				"<td>" . $hasHTML . "</td>" .
				"</tr>";
		 }
		 $out .= "</table></form>";
		 echo $out;
	}
	
	function edit() {
		$id = JRequest::getVar( 'id' );
		JToolBarHelper::title( JText::_( 'Form Builder - Form Editor' ), 'addedit.png' );
		JToolBarHelper::apply('apply');
		JToolBarHelper::save( 'save' );
		JToolBarHelper::cancel( 'cancel' );


		$app  = JFactory::getApplication();
		$db = JFactory::getDBO();
		
		// Retrieve data from form
		$task = $app->input->getVar('task');
		
		$query = "SELECT a.id, a.name,a.alias,a.sql,a.json,a.html FROM formbuilder_forms AS a";
		if($task != 'add') {
			$query .= " WHERE a.id = " . $id;
		}
		$db->setQuery($query, 0, 1);
		if($row = $db->loadAssoc()) {
			// If adding a new form, clear the existing fields
			if($task == 'add') {
				foreach($row as $col => $val) {
					$row[$col] = '';
					if($col == 'json') {
						// Provide basic dummy fields
						$row[$col] = '{"fields":{"name":{"label":"Name","type":"text","class":"span12","placeholder":"","single":1},"email":{"label":"Email","type":"text","class":"span12","placeholder":"(optional)","single":1},"comment":{"label":"Comment","type":"text","class":"span12","placeholder":"","single":1}}}';
						
					}
				}
			}
	?>
	
	<form id="adminForm" name="adminForm" method="post" action="index.php?option=com_formbuilder&task=update">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $id ?>" />
		<?php echo JHtml::_('form.token'); ?>
		<input type="hidden" name="boxchecked" value="0" />
		<!-- div>
			SQL:
			<textarea name="sql" class="span12" rows="4" id="sql"><?php  echo $row['sql'];  ?></textarea>
		</div -->
		<div>
			Name: <input name="name" class="span6" id="name" value="<?php  echo $row['name'];  ?>" />
		</div>
		<div>
			Alias: <input name="alias" class="span4" id="alias" value="<?php  echo $row['alias'];  ?>" />
		</div>
		<div>
			JSON:
			<textarea name="json" class="span12" rows="20" id="json"><?php  echo $row['json'];  ?></textarea>
		</div>
		<!-- div>
			HTML:
			<textarea name="html" class="span12" rows="12" id="html"><?php  echo $row['html'];  ?></textarea>
		</div -->
		<!-- div>
			<label>Location (optional) : </label>
			<input name="location" class="span12" type="text" id="location" value='<?php echo ''; ?>' />
			<input name="id" type="hidden" id="id" value='<?php echo $row['id']; ?>' />
		</div -->
	</form>
	<script>
		var json = JSON.stringify(JSON.parse(jQuery("#json").text()), null, "\t");
		jQuery("#json").text(json);
	</script>
	
	<?php }	   
	
}

}?>
