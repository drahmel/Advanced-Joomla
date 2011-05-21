<?php
/**
 * @version		$Id: index.php 9749 2010-01-26 19:12:15Z drahmel $
 * @lastchangedate	$LastChangedDate: 2010-01-26 11:12:15 -0800 (Tue, 26 Jan 2010) $
 * @revision		$Revision: 9749 $
 * @package		aj_dynamic
 * @author		Dan Rahmel
 * @description		Dynamic template from the Advanced Joomla book 
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Template setup code
$user =& JFactory::getUser();

/* The following line loads the MooTools JavaScript Library */
JHTML::_('behavior.framework', true);

/* The following line gets the application object for things like displaying the site name */
$app = JFactory::getApplication();

// Include a common PHP functions file 
//require(JPATH_BASE.DS."templates/includes/header_buttons.php");
$curCache = JCache::getInstance(); //$handler, $options);
$testArray = 'KIPPER'; //array('one','two','twee');
$curCache->store($testArray,'MYTESTKEY');
$myArray = $curCache->get('MYTESTKEY');
if($myArray===false) {
	echo 'Empty';
} else {
	print_r($myArray);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
	xml:lang="<?php echo $this->language; ?>" 
	lang="<?php echo $this->language; ?>">
	
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Content-Style-Type" content="text/css" />

	<link rel="stylesheet" 
		href="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/css/template.css" 
		type="text/css" />
  
<!--[if lte IE 6]>
<link href="/templates/thenewblack/css/ieonly.css" rel="stylesheet" type="text/css" />
<style>
#content{height:100%;overflow:hidden}
</style>
<![endif]-->

<script type="text/javascript" src="<?php echo JURI::base(); ?>templates/includes/common.js"></script>
<script type="text/javascript" src="<?php echo JURI::base(); ?>templates/thenewblack/js/logintoggle.js"></script>
<script type="text/javascript" src="<?php echo JURI::base(); ?>media/system/js/mootools-core.js"></script>
<script type="text/javascript" src="<?php echo JURI::base(); ?>media/system/js/mootools-more.js"></script>




<script type="text/javascript">
window.addEvent('domready', function() {
    //alert("The DOM is ready.");

    // applying the settings
    jQuery('#accMenu').accordion({
        active: 'div.selected',
        header: 'div.head',
        alwaysOpen: false,
        animated: 'easeslide',
        showSpeed: 1000,
        hideSpeed: 800
    });
    jQuery('#libprofiles').jcarousel({
        vertical: true,
        size: 33,
        scroll: 9,
        auto: 4,
        wrap: "last"
    });

});    
</script>

<jdoc:include type="head" />
</head>

<body>
		<div id="bodydiv">
			<a name="up" id="up"></a>
			<div id="centerslab">
				<div id="headerholder">

					<?php 
            if(!empty($user->name)) { 
                //echo " <div style='text-align:center;'>Welcome, ".$user->name.'</div>';
            } 
            ?>
					<jdoc:include type="modules" name="login" />

					<div id="headermenu">
						<jdoc:include type="modules" name="top" />
					</div>
				</div><!--end headerholder-->

				<div id="flashheader">
					<jdoc:include type="modules" name="topbanner" />
				</div>

				<div id="contentholder">
					<div id="contentarea">
						<div id="textcontent">
							<jdoc:include type="component" />
						</div><!--end textcontent-->

						<?php if($this->countModules('newreleases')) : ?>
						<jdoc:include type="modules" name="newreleases" />
						<?php endif; ?>

						<?php if($this->countModules('explore-viewall')) : ?>
						<div id="bragspots">
							<div class="titlebar">
								<div class="title">EXPLORE</div>
								<jdoc:include type="modules" name="explore-viewall" />
							</div>
							<div id="exploreholder">
								<div id="brag1">
									<jdoc:include type="modules" name="fpcenterl" />
								</div>

								<div id="brag2">
									<jdoc:include type="modules" name="fpcenterr" />
								</div>
							</div><!-- end exploreholder-->

							<div style="clear:both"></div>

						</div><!--end bragspots-->
						<?php endif; ?>

					</div><!--end contentarea-->

<!--...............begin right column................................-->
					<div id="tallmenu">

						<?php if($this->countModules('topright')) : ?>
						<div id="rightmenuitem">
							<jdoc:include type="modules" name="topright" />
						</div>
						<?php endif; ?>

    <!-- accordion menu-->

						<div id="menu">
							<div class="head">
								<jdoc:include type="modules" name="myapmsearch" />
							</div>

							<ul id="accMenu">
								<li id="accMenuLib">

									<div class="head">
										<h5>
											<a href="javascript:;" hidefocus="true"></a>
										</h5>
									</div>
									<div class="headcontent" id="librarymenu">
										<jdoc:include type="modules" name="libprofiles" />
									</div>
								</li>
								<li id="accMenuLicense">
									<div class="head">
										<h5>
											<a href="javascript:;" hidefocus="true"></a>
										</h5>
									</div>
									<div id="license">
										<jdoc:include type="modules" name="license" />
									</div>
								</li>
								<li id="accMenuNews">
									<div class="head selected">
										<h5>
											<a href="javascript:;" hidefocus="true"></a>
										</h5>
									</div>
									<div id="newsfeatures">
										<jdoc:include type="modules" name="newsfeatures" />
									</div>

								</li>

							</ul>
						</div><!-- end menu-->
    <!--end accordion menu-->

						<?php if($this->countModules('midright')) : ?>
						<div class="midright">
							<jdoc:include type="modules" name="midright" />
						</div>
						<?php endif; ?>

						<?php if($this->countModules('bottomright')) : ?>
						<div id="bottomright">
							<jdoc:include type="modules" name="bottomright" />
						</div>
						<?php endif; ?>

					</div><!--end tallmenu-->

<!-- ...............end right column...................... -->



					<div class="footerspacer"></div>

				</div><!--end contentholder-->

				<div id="footerholder">
					<div id="footermenu">
						<jdoc:include type="modules" name="footer" />
						<?php if($this->countModules('copyright')) : ?>
						<div id="copyright">
							<jdoc:include type="modules" name="copyright" />
						</div>
						<?php endif; ?>
					</div>
					<?php if($this->countModules('syndicate')) : ?>
					<div id="syndicate">
						<jdoc:include type="modules" name="syndicate" />
					</div>
					<?php endif; ?>
        

				</div><!-- end footermenu-->

			</div><!--end centerslab-->

			<div class="clearboth"></div>

			<div>Alpha 0.1. 
                
				<?php 
                echo trim(str_replace(array('$','LastChangedRevision:'),
                	array('',''),'$LastChangedRevision: 3846 $')); 
                ?>
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

