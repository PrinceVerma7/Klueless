<?php include("config.php");?>
<?php include("recaptchalib.php");?>
<?php
$msg = null;
$errorpass=null;
$exist=null;
if(isset($_POST['recaptcha_challenge_field'])){
    $privatekey = "6LeABvwSAAAAALiAAGqVgnyHef267ofZJ1pesKmz";
    $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    $msg="Please enter correct captcha.";
  } else {
    // Your code here to handle a successful verification
 
        if(isset($_POST['inputEmail'])){
            $inemail=mysqli_real_escape_string($conn, $_POST['inputEmail']);
            $inpass=mysqli_real_escape_string($conn, $_POST['inputPassword']);
            $conpass=mysqli_real_escape_string($conn, $_POST['confirmPassword']);
            $name=mysqli_real_escape_string($conn, $_POST['firstName']);
            $team=mysqli_real_escape_string($conn, $_POST['team']);

            if($inpass!=$conpass){
                $errorpass="Please confirm your password correctly.";
            }
            else{
                $sql = "SELECT email FROM $table_name WHERE email='$inemail'";
                $query = mysqli_query($conn, $sql);
                if(mysqli_num_rows($query) > 0) {
                    $exist="This email already exists !";
                } 
                else {
                    $enpass=sha1($inpass);
                    if(mysqli_query($conn, "INSERT INTO $table_name (email, team, password, name, max_level, present_level_folder) VALUES ('$inemail','$team','$enpass','$name',1,'$first_level_folder_name')")){
                        $msg = "You have been registered successfully !";
                    }

                }
            }
        }
        
    }
}
$wrongpass=null;
if($game_started==1||$_POST['loginEmail']=="admin@admin"){
    if(isset($_POST['loginEmail'])){
        $loginemail=mysqli_real_escape_string($conn, $_POST['loginEmail']);
        $loginpass=mysqli_real_escape_string($conn, $_POST['loginPassword']);


            $sql = "SELECT id, password,max_level FROM $table_name WHERE email='$loginemail' LIMIT 1";
            $query = mysqli_query($conn, $sql);
            if(mysqli_num_rows($query) > 0){
                $result=mysqli_fetch_array($query);
                if(sha1($loginpass)==$result[1]){
                        $milliseconds = round(microtime(true) * 1000);
                        $expire=time()+60*60*24*30;
                        setcookie("kltenid",$milliseconds,$expire);
                        $query = "UPDATE $table_name SET cookie_id='$milliseconds' WHERE id=$result[0]";
                        mysqli_query($conn, $query);

                    session_start(); 
                    $_SESSION['authenticated'] = 1;
                    header("Location: $first_level_folder_name");
                    exit;
                }
                else
                    $wrongpass="Please enter correct password.";
            }  
            else
                $wrongpass="Please enter correct email or register yourself.";
    }
}
else{
    $wrongpass="Sorry ! The quest has not begun yet.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   
    <title>Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/agency.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

</head>

<body id="page-top" class="index">




  

    <!-- Services Section -->
    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Are you the Chosen One?</h2>
                    <h3 class="section-subheading text-muted">I solemnly swear that I am up to no good</h3>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-md-6">
                    <h3>Join the hunt</h3>
                    <?php echo $msg; echo $exist; echo $errorpass;?>
                    <form class="form-horizontal" name="reg" id="reg" id="reg" role="form" action="#services" method="post">
                        <div class="form-group">
                            <label class="control-label col-xs-3" for="inputEmail">Email:</label>
                            <div class="col-xs-9">
                                <input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Enter your email" required data-bv-emailaddress-message="The input is not a valid email address">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-3" for="firstName">Username:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter your codename" required data-validation-required-message="Please enter your username.">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-3" for="inputPassword">Password:</label>
                            <div class="col-xs-9">
                                <input type="password" class="form-control" name="inputPassword" pid="inputPassword" placeholder="Enter password" required data-validation-required-message="Please enter your password.">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-3" for="confirmPassword">Confirm Password:</label>
                            <div class="col-xs-9">
                                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required data-validation-required-message="Please confirm your password.">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-3" for="team">Team name:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="team" id="team" placeholder="Enter your team name" required data-validation-required-message="Please confirm your password.">
                            </div>
                        </div>
                            <div class="form-group">
                                <div class="col-xs-offset-3 col-xs-9">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="checkb" value="agree" required>  I agree to the <a href="#portfolio">Terms and Conditions</a>.
                                    </label>
                                     <script type="text/javascript">
                                     var RecaptchaOptions = {
                                        theme : 'clean'
                                     };
                                     </script><br>
                                    <?php
                                        $publickey = "6LeABvwSAAAAAPzQqOzt5fZWyaN47jnk6g1qndWf";
                                        echo recaptcha_get_html($publickey);
                                    ?>
                                </div>

                            </div>
                                    
                            <br>

                            <div class="form-group">
                                <div class="col-xs-offset-3 col-xs-9">
                                    <input type="submit" class="btn btn-primary" value="Register">
                                    <input type="reset" class="btn btn-default" value="Reset">
                                </div>
                            </div>
                        </form>
                    

                    </div>
               
                <div class="col-md-6">
                    <h3>Login</h3>
                    <?php echo $wrongpass;?>
                    <form class="form-horizontal" name="loger" id="loger" role="form" action="#services"  autocomplete='on' method="post">
                        <div class="form-group">
                            <label class="control-label col-xs-3" for="loginEmail">Email:</label>
                            <div class="col-xs-9">
                                <input type="email" class="form-control" name="loginEmail" id="loginEmail" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-3" for="loginPassword">Password:</label>
                            <div class="col-xs-9">
                                <input type="password" class="form-control" name="loginPassword" id="loginPassword" placeholder="Password" required>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-xs-offset-3 col-xs-9">
                                <input type="submit" class="btn btn-primary" value="Login">
                                <input type="reset" class="btn btn-default" value="Reset">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/agency.js"></script>


    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-56024357-2', 'auto');
  ga('send', 'pageview');

</script>

</body>

</html>