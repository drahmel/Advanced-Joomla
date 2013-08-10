<?php
defined('_JEXEC') or die;
$app = JFactory::getApplication();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
    xml:lang="<?php echo $this->language; ?>" 
    lang="<?php echo $this->language; ?>" 
    dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="head" />
    <link rel="stylesheet" 
      href="<?php echo 
      $this->baseurl ?>/templates/system/css/offline.css" 
      type="text/css" />
    <?php if ($this->direction == 'rtl') : ?>
    <link rel="stylesheet" href="<?php echo 
        $this->baseurl 
        ?>/templates/system/css/offline_rtl.css" type="text/css" />
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo 
        $this->baseurl ?>/templates/system/css/general.css" 
        type="text/css" />
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap
         /2.1.1/css/bootstrap-combined.min.css" rel="stylesheet" />
</head>
<body>
<jdoc:include type="message" />
    <div id="frame" class="outline">
        <?php if ($app->getCfg('offline_image')) : ?>
        <img src="<?php echo $app->getCfg('offline_image'); ?>" 
            alt="<?php echo htmlspecialchars(
            $app->getCfg('sitename')); ?>" />
        <?php endif; ?>
        <h1>
            <?php echo htmlspecialchars($app->getCfg('sitename')); ?>
        </h1>
    <?php if ($app->getCfg('display_offline_message', 1) == 1 && 
              str_replace(' ', '', $app->getCfg('offline_message'))
              != ''): ?>
        <p>
            <?php echo $app->getCfg('offline_message'); ?>
        </p>
    <?php elseif ($app->getCfg('display_offline_message', 1) == 2 &&
        str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != ''): ?>
        <p>
            <?php echo JText::_('JOFFLINE_MESSAGE'); ?>
        </p>
    <?php  endif; ?>
    <form action="<?php echo JRoute::_('index.php', true); ?>" 
        method="post" id="form-login"  style="display:none;"    
        onclick="document.getElementById('form-login').">
    <fieldset class="input">
        <p id="form-login-username">
            <label for="username"><?php echo 
              JText::_('JGLOBAL_USERNAME') ?></label>
            <input name="username" id="username" type="text" 
               class="inputbox" alt="<?php echo 
               JText::_('JGLOBAL_USERNAME') ?>" size="18" />
        </p>
        <p id="form-login-password">
            <label for="passwd"><?php echo 
              JText::_('JGLOBAL_PASSWORD') ?></label>
            <input type="password" name="password" class="inputbox"
                size="18" alt="<?php echo 
                JText::_('JGLOBAL_PASSWORD') ?>" id="passwd" />
        </p>
        <p id="form-login-remember">
            <label for="remember"><?php echo 
                JText::_('JGLOBAL_REMEMBER_ME') ?></label>
            <input type="checkbox" name="remember" class="inputbox" 
               value="yes" alt="<?php echo
               JText::_('JGLOBAL_REMEMBER_ME') ?>" id="remember" />
        </p>
        <input type="submit" name="Submit" class="button" value="<?php 
            echo JText::_('JLOGIN') ?>" />
        <input type="hidden" name="option" value="com_users" />
        <input type="hidden" name="task" value="user.login" />
        <input type="hidden" name="return" value="<?php echo 
            base64_encode(JURI::base()) ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </fieldset>
    </form>
    
    </div>

<?php
$online_ts = strtotime("2012-12-24 23:59:59");
$ts = time();
// If the return time has already passed, make it next Friday
if($ts > $online_ts) {
    $online_ts = strtotime("next Friday");
}
define('ONEDAY',60*60*24);
$delta = $online_ts-$ts;
$days = floor(($delta)/ONEDAY);
$days_in_secs = $days*ONEDAY;
$hours = floor(($delta-$days_in_secs)/60/60);
$hours_in_secs = $hours*60*60;
$minutes = floor(($delta-$days_in_secs-$hours_in_secs)/60);
// Calculated percentages for progress bars
$days_perc = floor(((365-$days)/365)*100);
$hours_perc = floor(((24-$hours)/24)*100);
$minutes_perc = floor(((60-$minutes)/60)*100); 
?>

    
        <div class="container">
            <div class="row">
                <h1 onclick='document.getElementById
                    ("form-login").style.display=""'
                 >Site will be online again in:</h1>
                <div class="span2">
                    <p>
                        <?php echo $days; ?> Days</p>
                </div>
                <div class="span10">
                    <div class="progress progress-striped  active">
                        <div class="bar" style="width: 
                            <?php echo $days_perc; ?>%;"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="span2">
                    <?php echo $hours; ?> Hours
                        </div>
                <div class="span10">
                    <div class="progress progress-striped 
                         progress-success active">
                        <div class="bar" style="width: <?php 
                            echo $hours_perc; ?>%;"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="span2">
                    <?php echo $minutes; ?> Minutes
                        </div>
                <div class="span10">
                    <div class="progress progress-striped 
                        progress-warning active">
                        <div class="bar" style="width: <?php 
                            echo $minutes_perc; ?>%;"></div>
                    </div>
                </div>
            </div>
        </div>
    
</body>
</html>

