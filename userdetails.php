<?php
    include("dbCon.php");
    session_start();
    if(!isset($_SESSION['login_user']))
    {
        header("Location: login.php");
        die();
    }
 
    $u_id       = $_GET['id'];
    if(isset($_POST['btnSave']))
    {
        $sql = "CALL sp_SaveUserDetails ('" . $_POST["user_id"] . "','" . $_POST["password"] . "','" . $_POST["full_name"] . "','" . $_POST["mobile_no"] . "','" . $_POST["email_id"] . "'," . $_POST["rule_id"] . ")";
        $conn->query($sql);
        header("Location: users.php");
    }

    if(isset($_POST['btnDelete']))
    {
        $sql = "CALL sp_DeleteUser ('" . $u_id . "')";       
        $conn->query($sql);
        header("Location: users.php");
    }

    $user_id    = "";
    $password   = "";
    $full_name  = "";
    $mobile_no  = "";
    $email_id   = "";
    $rule_id    = "";

    $sql = "SELECT * FROM tb_user_master WHERE u_id=" . $u_id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
        while($row = $result->fetch_assoc()) 
        {  
            $u_id       = $row["u_id"];
            $user_id    = $row["user_id"];
            $password   = $row["password"];
            $full_name  = $row["full_name"];
            $mobile_no  = $row["mobile_no"];
            $email_id   = $row["email_id"];
            $rule_id    = $row["rule_id"];
        }
    }    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Data Log - Dashboard</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css" />
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <?php 
          include "leftmenu.php";
        ?>
        <div class="container-fluid page-body-wrapper">
            <div id="theme-settings" class="settings-panel">
                <i class="settings-close mdi mdi-close"></i>
                <p class="settings-heading">SIDEBAR SKINS</p>
                <div class="sidebar-bg-options selected" id="sidebar-default-theme">
                    <div class="img-ss rounded-circle bg-light border mr-3"></div> Default
                </div>
                <div class="sidebar-bg-options" id="sidebar-dark-theme">
                    <div class="img-ss rounded-circle bg-dark border mr-3"></div> Dark
                </div>
                <p class="settings-heading mt-2">HEADER SKINS</p>
                <div class="color-tiles mx-0 px-4">
                    <div class="tiles light"></div>
                    <div class="tiles dark"></div>
                </div>
            </div>
            <?php
              include "header.php";
            ?>
            <div class="main-panel">
                <div class="content-wrapper pb-0">
                    <div class="page-header flex-wrap">
                        <h3 class="mb-0">User Details
                        </h3>
                        <div class="d-flex">
                            <a href="reports.php" class="btn btn btn-warning btn-fw border ml-3">
                                <i class="mdi mdi-cloud-print menu-icon"></i> REPORTS </a>
                            <a href="settings.php" class="btn btn-sm ml-3 btn-success">
                                <i class="mdi mdi-settings menu-icon"></i> SETTING </a>
                        </div>
                    </div>
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form method="POST">
                                        <table style="width:100%;">  			
                                            <tr>
                                                <td>
                                                    <h5 style="text-align: right; padding-right: 30px;">User ID : *</h5>
                                                </td>
                                                <td style="padding: 5px;">  
                                                    <input type="text" class="btn btn-dark" style="width:50%; text-align:left;" maxlength="80" id="user_id" name="user_id" value="<?php echo $user_id; ?>" required>  
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5 style="text-align: right; padding-right: 30px;">Password : *</h5>
                                                </td>
                                                <td style="padding: 5px;">
                                                    <input type="password" class="btn btn-dark" style="width:50%; text-align:left;" maxlength="40" id="password" name="password" value="<?php echo $password; ?>" required>  
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5 style="text-align: right; padding-right: 30px;">Full Name : *</h5>
                                                </td>
                                                <td style="padding: 5px;">
                                                    <input type="text" class="btn btn-dark" style="width:50%; text-align:left;" maxlength="100" id="full_name" name="full_name" value="<?php echo $full_name; ?>" required>  
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5 style="text-align: right; padding-right: 30px;">Mobile :</h5>
                                                </td>
                                                <td style="padding: 5px;">
                                                    <input type="text" class="btn btn-dark" style="width:50%; text-align:left;" maxlength="10" id="mobile_no" name="mobile_no" value="<?php echo $mobile_no; ?>">  
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5 style="text-align: right; padding-right: 30px;">Email :</h5>
                                                </td>
                                                <td style="padding: 5px;">
                                                    <input type="text" class="btn btn-dark" style="width:50%; text-align:left;" maxlength="100" id="email_id" name="email_id" value="<?php echo $email_id; ?>">  
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5 style="text-align: right; padding-right: 30px;">Rule :</h5>
                                                </td>
                                                <td style="padding: 5px;">
                                                    <?php
                                                        $sql = "SELECT rule_id, rule_name FROM tb_user_rule WHERE is_active = 1;";
                                                        $result = $conn->query($sql);
                                                        echo "<select class='btn btn-dark' style='width:50%;height:30px; text-align:left;' id='rule_id' name='rule_id'>";
                                                        if ($result->num_rows > 0) 
                                                        {
                                                            while($row = $result->fetch_assoc()) 
                                                            { 
                                                                if($row["rule_id"]==$rule_id) 
                                                                {
                                                                    echo "<option value='".$row["rule_id"]."' selected>". $row["rule_name"]."</option>";
                                                                }
                                                                else 
                                                                {
                                                                    echo "<option value='".$row["rule_id"]."'>". $row["rule_name"]."</option>";
                                                                }
                                                            }
                                                        }  
                                                        echo "</select>";                                                    
                                                    ?>                                               
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td style="padding: 5px;">
                                                    <input type="submit" class="btn btn-success" style="padding:3px 30px;" id="btnSave" name="btnSave" value="SAVE" />
                                                    <input type="submit" class="btn btn-warning" style="padding:3px 30px;" id="btnDelete" name="btnDelete" value="DELETE" />
                                                </td>
                                            </tr>                                        
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                  include "footer.php";
                ?>
            </div>
        </div>
    </div>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="assets/vendors/flot/jquery.flot.js"></script>
    <script src="assets/vendors/flot/jquery.flot.resize.js"></script>
    <script src="assets/vendors/flot/jquery.flot.categories.js"></script>
    <script src="assets/vendors/flot/jquery.flot.fillbetween.js"></script>
    <script src="assets/vendors/flot/jquery.flot.stack.js"></script>
    <script src="assets/vendors/flot/jquery.flot.pie.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/dashboard.js"></script>
</body>

</html>