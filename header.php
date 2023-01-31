<nav class="navbar col-lg-12 col-12 p-lg-0 fixed-top d-flex flex-row">
    <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between" style="background:#BBB;">

           <a class="navbar-brand brand-logo-mini align-self-center d-lg-none" href="dashboard.php"><img
                src="assets/images/favicon.png" style="height:25px;width:25px;" alt="logo" /></a>
        <button class="navbar-toggler navbar-toggler align-self-center mr-2" type="button" data-toggle="minimize">
            <i class="mdi mdi-menu"></i>
        </button>
        
        
    
        <ul class="navbar-nav navbar-nav-right ml-lg-auto">
            <li class="nav-item nav-profile dropdown border-0">
                <?php 
                    if(basename($_SERVER['PHP_SELF'], '.php')=='reports')
                    {
                ?>
                    <a href="dashboard.php" class="btn btn btn-warning btn-fw border ml-3">
                        <i class="mdi mdi-google-circles-group menu-icon btn-icon-prepend"></i> DASHBOARD </a>
                <?php
                    }
                    else
                    {
                ?>
                    <a href="reports.php" class="btn btn-warning btn-fw border ml-3">
                        <i class="mdi mdi-cloud-print menu-icon"></i> REPORTS </a>
                <?php 
                    }
                if($_SESSION['rule_name']!='VIEWER')
                {
                ?>    
                <a href="settings.php" class="btn ml-3 btn-success">
                    <i class="mdi mdi-settings menu-icon"></i> SETTING </a>
                <?php
                }
                ?>
                    &nbsp; 
                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown">
                    <img class="nav-profile-img mr-2" alt="" src="assets/images/faces-clipart/pic-4.png" />
                    <span class="profile-name" style="color:black;"><?php echo $_SESSION['full_name'] ?></span>
                </a>
                <div class="dropdown-menu navbar-dropdown w-100" aria-labelledby="profileDropdown">
                    <!--
                    <a class="dropdown-item" href="logs.php">
                        <i class="mdi mdi-cached mr-2 text-success"></i> Activity Log </a>-->
                    <a class="dropdown-item" href="logout.php">
                        <i class="mdi mdi-logout mr-2 text-primary"></i> Signout </a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>