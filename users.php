<?php
    include("dbCon.php");
    session_start();
    if(!isset($_SESSION['login_user']))
    {
        header("Location: login.php");
        die();
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
                        <h3 class="mb-0"> <!--Hi <?php echo $_SESSION['full_name'] ?>, welcome back! -->
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
                                <h4 class="card-title">User Master <a href="userdetails.php?id=0" class="btn btn-sm ml-3 btn-success">NEW USER</a></h4> 
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User ID</th>
                                                <th>Full Name</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Rule</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                              $sql = "SELECT u_id, user_id, password, full_name, mobile_no, email_id, U.rule_id, R.rule_name, U.is_active FROM tb_user_master U LEFT JOIN tb_user_rule R ON U.rule_id = R.rule_id;";
                                              $result = $conn->query($sql);

                                              if ($result->num_rows > 0) 
                                              {
                                                  $i = 0;
                                                  while($row = $result->fetch_assoc()) 
                                                  {
                                                      echo '<tr class="py-1">';
                                                      echo '    <td>' . $row["u_id"] . '</td>';
                                                      echo '    <td>' . $row["user_id"] . '</td>';
                                                      echo '    <td>' . $row["full_name"] . '</td>';
                                                      echo '    <td>' . $row["mobile_no"] . '</td>'; 
                                                      echo '    <td>' . $row["email_id"] . '</td>';  
                                                      echo '    <td>' . $row["rule_name"] . '</td>';                                                      
                                                      echo '    <td><a href="userdetails.php?id=' . $row["u_id"] . '"><img src="assets/images/edit.png"></a></td>';                                                      
                                                      echo '</tr>'; 
                                                  }
                                              } 
                                            ?>
                                        </tbody>
                                    </table>
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