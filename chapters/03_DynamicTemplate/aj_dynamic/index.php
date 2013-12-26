
<?php
/**
 * @revision		$Revision: 9749 $
 * @package		aj_dynamic
 * @author		Dan Rahmel
 * @site		www.joomlajumpstart.com
 * @description		Dynamic template from the Advanced Joomla! book 
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
define('DS','/');

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Add JavaScript Frameworks
//JHtml::_('bootstrap.framework');
//$doc->addScript('templates/' .$this->template. '/js/template.js');

// Add Stylesheets
//$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');

// Load optional RTL Bootstrap CSS
//JHtml::_('bootstrap.loadCss', false, $this->direction);

// Template setup code
$user = JFactory::getUser();
$app = JFactory::getApplication();
$base_url = JURI::base();
$template_path = $base_url."templates".DS.$this->template;
$menu_view = $app->getMenu()->getActive()->query['view'];

// echo htmlspecialchars($app->getCfg('sitename'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
	
	<head>
		<jdoc:include type="head" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<!-- Viewport definition for responsive style sheets -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
		<!-- Favicons for browser tabs, Google TV bookmark, and iPhone/iPad-->
		<link rel="icon" href="/ui/template/" type="image/png" />
		<!-- iPhone standard bookmark icon (57x57px) home screen -->
		<link rel="apple-touch-icon" href="/ui/template/" />
		<!-- iPhone Retina display icon (114x114px) home screen -->
		<link rel="apple-touch-icon" href="/ui/template/" sizes="114x114" />

		<!-- Load minimized Twitter Bootstrap styles -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" type="text/css" />
		<!-- link rel="stylesheet" href="<?php echo $template_path; ?>/css/bootstrap.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $template_path; ?>/css/bootstrap-responsive.min.css" type="text/css" / -->
	
		<!-- Load custom font from Google fonts -->
		<link href="http://fonts.googleapis.com/css?family=Cabin+Condensed:700" rel="stylesheet" type="text/css" />
		
		<!-- Load other template-specific CSS -->
		<link rel="stylesheet" href="<?php echo $template_path; ?>/css/template.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $template_path; ?>/css/position.css" type="text/css" />
		
		<?php if($menu_view=='category'): ?>
			<link rel="stylesheet" href="<?php echo $template_path; ?>/css/category.css" type="text/css" />
		<?php endif; ?>
		
  
		<!--[if lte IE 6]>
		<link href="<?php echo $template_path; ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
		<style>
		#content{height:100%;overflow:hidden}
		</style>
		<![endif]-->
		
		<?php if($this->params->get('include_jquery')): ?>
			<script type="text/javascript" src="<?php echo $template_path; ?>/js/jquery.js"></script>
		<?php endif; ?>
		<script type="text/javascript" src="<?php echo $template_path; ?>/js/common.js"></script>
		
		<style>
		body {
			text-align: left;
		}
		.navbar .brand,h1,h2,h3 {
			font-family: 'Cabin Condensed', sans-serif;
			font-size: 26px;
		}
		label {
			display: inline;
		}
		</style>
	
		
		
		<script type="text/javascript">
			function trace(msg) {
				console.log(msg);
			}
			window.addEvent('domready', function() {
			    trace("The DOM is ready.");
			
			});    
		</script>
		
	</head>
	<body style="<?php 
		$bg = $this->params->get('background_color');
		if(!empty($bg)) {
			echo "background-color:".$bg.';';
		}
		?>">
		<style>
		.color {
			background-color: gray;
			text-align: center;
			color:white;
		}
		</style>
		<div id="bodydiv" class="container-fluid">
			<?php if(!empty($_GET['guide'])): ?>
			<div class="row-fluid">
				<div class="span1 color">1</div>
				<div class="span1 color">2</div>
				<div class="span1 color">3</div>
				<div class="span1 color">4</div>
				<div class="span1 color">5</div>
				<div class="span1 color">6</div>
				<div class="span1 color">7</div>
				<div class="span1 color">8</div>
				<div class="span1 color">9</div>
				<div class="span1 color">10</div>
				<div class="span1 color">11</div>
				<div class="span1 color">12</div>
			</div>
			<div class="row-fluid">
				<div class="span3 color">left</div>
				<div class="span6 color">center</div>
				<div class="span3 color">right</div>
			</div>
			<?php endif; ?>
			<div class="row-fluid">
				<?php if($this->countModules('atomic-search') or $this->countModules('position-0')) : ?>
				<div class="span9">
				</div>
				<div class="joomla-search span3 last">
					<jdoc:include type="modules" name="position-0" style="none" />
				</div>
				<?php endif; ?>
			</div>
			<?php if($this->countModules('atomic-topmenu') or $this->countModules('position-2') ) : ?>
			<div class="row-fluid">
				<div class="span12">
					<jdoc:include type="modules" name="atomic-topmenu" style="container" class="nav" />
					<jdoc:include type="modules" name="position-1" style="container" />
				</div>
			</div>
			<?php endif; ?>
			<div class="row-fluid">
				<div id="leftslab" class="span2">
					<div id="breadcrumbs">
						<jdoc:include type="modules" name="position-2" />
					</div>
					<jdoc:include type="modules" name="atomic-sidebar" style="sidebar" />
					<jdoc:include type="modules" name="position-7" style="beezDivision" headerLevel="3" />
					<jdoc:include type="modules" name="position-4" style="sidebar" />
					<jdoc:include type="modules" name="position-5" style="sidebar" />
					<jdoc:include type="modules" name="position-6" style="sidebar" />
					<jdoc:include type="modules" name="position-8" style="sidebar" />
					<jdoc:include type="modules" name="position-3" style="sidebar" />
					<jdoc:include type="modules" name="login" />
				</div>
				<div id="centerslab" class="span9">
					<div id="headerholder">
						
						<?php 
						    if(!empty($user->name)) { 
							echo " <div style='text-align:center;'>Welcome, ".$user->name.'</div>';
						    } 
						    ?>
						
					</div><!--end headerholder-->
					
					
					<div id="contentholder">
						<div id="contentarea">
							<div id="textcontent">
								<jdoc:include type="component" />
							</div><!--end textcontent-->
							
						
						</div><!--end contentarea-->
					</div>
				</div><!--end centerslab-->
				<div id="rightslab" class="span1">
					RIGHT
				</div>
			</div>
		</div><!--end bodydiv-->
		<div id="footerholder">
			<div class="row-fluid"  id="footermenu">
				<div class="span4 box box1">
					<jdoc:include type="modules" name="position-9" style="beezDivision" headerlevel="3" />
				</div>
				<div class="span4 box box2">
					<jdoc:include type="modules" name="position-10" style="beezDivision" headerlevel="3" />
				</div>
				<div class="span4 box box3">
					<jdoc:include type="modules" name="position-11" style="beezDivision" headerlevel="3" />
				</div>
			</div>
			<div class="row-fluid"  id="footermenu">
				<div>
					<jdoc:include type="modules" name="footer" />
				</div>
				<?php if($this->countModules('syndicate')) : ?>
				<div id="syndicate">
					<jdoc:include type="modules" name="syndicate" />
				</div>
				<?php endif; ?>
			</div>					
				
		</div><!-- end footerholder-->
			
	</div>
			
	<div id="debug">
		<jdoc:include type="modules" name="debug" />
	</div>
	<div id="dev">
		<jdoc:include type="modules" name="dev" />
	</div>
	<div id="preload">
		<jdoc:include type="modules" name="preloadimages" />
	</div>
	<div id="shared">
		<jdoc:include type="modules" name="shared" />
	</div>
</div>
</body>

	

</html>
