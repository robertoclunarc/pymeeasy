<?php 
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    require('menu.php');
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US"><head><!-- Created by Artisteer v4.1.0.59861 -->
    <meta charset="utf-8">
    <title>Home</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="css/style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="css/style.responsive.css" media="all">


    <script src="js/jquery.js"></script>
    <script src="js/script.js"></script>
    <script src="js/script.responsive.js"></script>


<style>.serv-content .serv-postcontent-0 .layout-item-0 { padding-right: 10px;padding-left: 10px;  }
.ie7 .serv-post .serv-layout-cell {border:none !important; padding:0 !important; }
.ie6 .serv-post .serv-layout-cell {border:none !important; padding:0 !important; }

</style></head>
<body>
<div id="serv-main">
    <div class="serv-sheet clearfix">
<header class="serv-header">
    <div class="serv-shapes"></div>
<h1 class="serv-headline" data-left="12.21%">
    <a href="#">Estaciones de Servicio</a>
</h1>                    
</header>
<?php echo menu(); ?>
<div class="serv-layout-wrapper">
                <div class="serv-content-layout">
                    <div class="serv-content-layout-row">
                        <div class="serv-layout-cell serv-content"><article class="serv-post serv-article">
                                <h2 class="serv-postheader">Parametros</h2>
                                                
                <div class="serv-postcontent serv-postcontent-0 clearfix"><div class="serv-content-layout">
    <div class="serv-content-layout-row">
    <div class="serv-layout-cell layout-item-0" style="width: 100%" >
        
        
    

    </div>
    </div>
</div>

</div>


</article>
                    </div>
                </div>
            </div><footer class="serv-footer">
<p><a href="#">Link1</a></p>

</footer>

    </div>
</div>
</body>
</html>
<?php 
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='index.php';
</script>
</body>";
}
?>