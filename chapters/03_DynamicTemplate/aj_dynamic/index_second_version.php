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
    <link rel="stylesheet" href="<?php echo $template_path;
        ?>/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $template_path; 
        ?>/css/bootstrap-responsive.min.css" type="text/css" />

    <!-- Load custom font from Google fonts -->
    <link href="http://fonts.googleapis.com/css?family=Cabin+Condensed:700" 
        rel="stylesheet" type="text/css" />
    
    <!-- Load other template-specific CSS -->
    <link rel="stylesheet" href="<?php echo $template_path;
        ?>/css/template.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $template_path; 
        ?>/css/position.css" type="text/css" />

    <!--[if lte IE 6]>
    <link href="<?php echo $template_path; ?>/css/ieonly.css" 
        rel="stylesheet" type="text/css" />
    <style>
        #content{height:100%;overflow:hidden}
    </style>
    <![endif]-->
    
    <script type="text/javascript" src="<?php echo $template_path;
        ?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $template_path; 
        ?>/js/common.js"></script>
</head>
<style>
.color {
    background-color: gray;
    text-align: center;
    color:white;
}
</style>
<div class="container-fluid">
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
</div>
<div class="row-fluid">
        <div class="span3 color">left</div>
        <div class="span6 color">center</div>
        <div class="span3 color">right</div>
</div>

