<?php
    include("dbCon.php");
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        /* echo $_POST["userid"];
        echo $_POST["password"]; */
        $myuserid       = mysqli_real_escape_string($conn,$_POST['userid']);
        $mypassword     = mysqli_real_escape_string($conn,$_POST['password']); 
        
        $sql = "SELECT u_id, full_name, u.rule_id, rule_name FROM tb_user_master u LEFT JOIN tb_user_rule r ON u.rule_id = r.rule_id WHERE user_id = '$myuserid' and password = '$mypassword' and u.is_active=1";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);      
        $count = mysqli_num_rows($result);
        if($count == 1) {
            echo "Third in";
            $full_name = $row['full_name'];
            $rule_name = $row['rule_name'];
            $uid = $row['u_id'];
            //session_register("myuserid");
            $_SESSION['login_user_id'] = $uid;
            $_SESSION['login_user'] = $myuserid;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['rule_name'] = $rule_name;

            header("location: dashboard.php");            
        }
        else 
        {
            $error = "Your Login Name or Password is invalid";
        }
        //echo $error;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SMART RT - Login</title>
    <link rel="stylesheet" href="assets/css/login.css" />
    <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>

<body>
    <h2>Please login to access the data log portal</h2>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="#">
                <h1>SMART - RT</h1>
                <!--
                <input type="text" placeholder="Name" />
                <input type="email" placeholder="Email" />
                <input type="password" placeholder="Password" />
                <button>Send Request</button>
                -->
                Industrial Kettle Data Log Software
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="#" method="post">
                <image src="assets/images/inv_logo.png" style="width:200px;" />
                <br/>
                <h1>Sign in</h1>
                <input name="userid" type="text" placeholder="UserID" />
                <input name="password" type="password" placeholder="Password" />
                <!-- <a href="#">Forgot your password?</a> -->
                <button>Log In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>SMART - RT</h1>
                    <br/>
                    <!--<p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>-->
                    Industrial Kettle Data Log Software
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/login.js"></script>
</body>

</html>