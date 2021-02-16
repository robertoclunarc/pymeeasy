<?php
	session_start();	
	if(isset($_POST['btn-login']))
	{
		//$user_name = $_POST['user_name'];
		$user_login = trim($_POST['user']);
		$user_password = trim($_POST['password']);		
		$psw = md5($user_password);
		$conexion=$_POST['cboconexion'];
		try
		{	
			$_SESSION['conexion']=$conexion;
			include("BD/conexion.php");
			require('pages/auditoria.php');
			$link=crearConexion();
			$result = $link->query("SELECT a.* FROM tbl_usuarios a WHERE a.login='".$user_login."' and a.estatus_user='ACTIVO'");
			$row = $result->fetch_assoc();			
			$count = $result->num_rows;			
			
			if($row['passw']==$psw){
				
				echo "ok"; // log in
				$_SESSION['user_session'] = $row['login'];
				$_SESSION['username']	= $row['user_name'];
				$_SESSION['userid']	= $row['email'];
				$_SESSION['nivel']	= $row['nivel'];
				$_SESSION['estatususer']= $row['estatus_user'];
				
				error_reporting(E_ERROR | E_PARSE);
				if ($_SESSION['conexion']=='bdpymeasy'){					
					$_SESSION['empresa']= 'INRECA';
					$_SESSION['logo']= 'images/icons/logo_inreca.png';
				}
				else{
					$_SESSION['empresa']= 'ALIPERCA';
					$_SESSION['logo']= 'images/icons/logo_aliperca.png';
				}
				
				$aud=auditar("Nuevo inicio de sesion", $_SESSION['user_session'],$link);
			}
			else{
				
				echo "Login y/o password no coinsiden."; // wrong details
				 
			}

			$result->free();
 			$link->close();
				
		}
		catch(PDOException $e){
			echo $e->getMessage();
		} 		
	} 

?>
