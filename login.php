<?
session_start();
if(!isset($_SESSION)){session_destroy(); }
?>
<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="fresh Gray Bootstrap 3.0 Responsive Theme "/>
    <meta name="author" content="DigmaStudio (digmastudio.com @digmastudio), @diego_camacho - @adolfo">
	<meta name="author" content="Mindfreakerstuff"/>

	<title>KGB grill</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/theme.css" rel="stylesheet">
    <style>

	    .footer {
		  position: absolute;
		  bottom: 0;
		  width: 100%;
		  /* Set the fixed height of the footer here */
		  height: 60px;
		  color: #fff;
		  background-color: #127ba3;
		}


		/* Custom page CSS
		-------------------------------------------------- */
		/* Not required for template or sticky footer method. */

		.container .text-muted {
			text-align: center;
			margin: 20px 0;
		}
/* Login */
.form-signin
{
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
    padding-top: 5px;
}
.form-signin .form-signin-heading, .form-signin .checkbox
{
    margin-bottom: 10px;
}
.form-signin .checkbox
{
    font-weight: normal;
}
.form-signin .form-control
{
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.form-signin .form-control:focus
{
    z-index: 2;
}
.form-signin input[type="password"]
{
    margin-bottom: 10px;
}
.account-wall
{
    margin-top: 20px;
    padding: 40px 0px 20px 0px;
    background-color: #f7f7f7;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}
.login-title
{
    color: #555;
    font-size: 18px;
    font-weight: 400;
    display: block;
}
.profile-img
{
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}
.profile-name {
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    margin: 10px 0 0;
    height: 1em;
}
.profile-email {
    display: block;
    padding: 0 8px;
    font-size: 15px;
    color: #404040;
    line-height: 2;
    font-size: 14px;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.need-help
{
    display: block;
    margin-top: 10px;
}
.new-account
{
    display: block;
    margin-top: 10px;
}
	</style>
   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="js/jquery-2.1.1.min.js"></script>

  </head>

  <body style="background-color: #158cba;">


  	<div class="container">
  	    <div class="row">
  	        <div class="col-sm-12 col-md-4 col-md-offset-4">
	  	        <h2 style="color: #FFF;"><center>KGB grill</center></h2>
  	            <div class="account-wall">

  	                <form class="form-signin">
	  	                <div class="alert alert-dismissable alert-danger oculto" id="error"></div>
	  	                <div class="form-group">
	  	                	<input type="text" class="form-control" placeholder="usuario" maxlength="24" name="user" id="user" autofocus>
	  	                </div>
	  	                <div class="form-group">
  	                	<input type="password" class="form-control" placeholder="Contraseña" name="pass" maxlength="16" id="pass">
	  	                </div>
	  	                <div class="form-group text-center">
	  						<button class="btn btn-lg btn-success btn-block btn-login" type="button" onclick="javascript:Login()">Iniciar sesión</button>
	  						<div style="text-align: center; margin: 20px auto 20px;" class="oculto" id="load">
								<img src="img/load-verde.gif" width="35" />
                                
							</div>
	  						<!--<a href="#" class="need-help">¿Olvido su contraseña? </a>-->
	  	                </div>
  	                </form>
  	            </div>
  	        </div>
  	    </div>
  	</div>

  	<div class="footer">
      <div class="container">
        <p class="text-muted" style="color:#FFF;">Copyright &copy; <?=date('Y')?> KGB grill</p>
      </div>
    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
<script>

$(function() {

	$('#pass').keyup(function(e) {

			if(e.keyCode==13){
				Login();
			}

	});


});
function Login(){
	$('#error').hide('Fast');
	$('.btn-login').hide();
	$('#load').show();

	var user = $('#user').val();
	var pass = $('#pass').val();

	$.post('ac/login.php','user='+user+'&pass='+pass,function(data) {
		if(data==1){
			window.location = 'index.php';
		}else{
			$('#user').focus();
			$('#error').html(data);
			$('#error').show('Fast');
			$('#load').hide();
			$('.btn-login').show();
		}
	});
}
</script>
