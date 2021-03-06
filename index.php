<?php

/*
Author: Pradeep Khodke
URL: http://www.codingcage.com/
*/

session_start();

if(isset($_SESSION['user_session'])!="")
{
	header("Location: pages/index.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pyme Easy - Logeo</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<link href="css/style_login.css" rel="stylesheet" type="text/css" media="screen">
<script type="text/javascript" src="js/script_login.js"></script>

</head>

<body>
    
<div class="signin-form">

	<div class="container">
     
        
       <form class="form-signin" method="post" id="login-form">
      
        <h2 class="form-signin-heading">Entrar al Sistema</h2><hr />
        
        <div id="error">
        <!-- error will be shown here ! -->
        </div>
        
        <div class="form-group">
        <input type="text" class="form-control" placeholder="Login" name="user" id="user" />
        <span id="check-e"></span>
        </div>
        
        <div class="form-group">
        <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
        </div>
        
		<div class="form-group">
        <select id="cboconexion" name="cboconexion" class="form-control input-sm" onchange="" >
		<option value='bdpymeasy'>INRECA</option>
		<option value='bdpymeasy_02'>ALIPERCA</option>
                    </select>
        </div>
     	<hr />
        
        <div class="form-group">
            <button type="submit" class="btn btn-default" name="btn-login" id="btn-login">
    		<span class="glyphicon glyphicon-log-in"></span> &nbsp; Entrar
			</button> 
        </div>  
        <a style="text-decoration:none;color:#DAA520;" href="nouser.php"> >>Click Aqui<< Para Recuperar Contrase??a</a>
      </form>

    </div>
    
</div>
    
<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
