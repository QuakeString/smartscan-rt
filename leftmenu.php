<nav class="sidebar sidebar-offcanvas" id="sidebar" >
    <div class="text-center sidebar-brand-wrapper d-flex align-items-center">
        <a class="sidebar-brand brand-logo" href="dashboard.php"><img src="assets/images/inv_logo.png" alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini pl-4 pt-3" href="dashboard.php"><img src="assets/images/favicon.png"
              style="width:24px;height:24px;"  alt="logo" /></a>
    </div>
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="assets/images/faces-clipart/pic-4.png" alt="profile" />
                    <span class="login-status online"></span>
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column pr-3">
                    <span class="font-weight-medium mb-2"><?php echo $_SESSION['full_name'] ?></span>
                    <span class="font-weight-normal"><?php echo $_SESSION['rule_name'] ?></span>
                </div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <!--
        <li class="nav-item">
            <a class="nav-link" href="device.php">
                <i class="mdi mdi-chip menu-icon"></i>
                <span class="menu-title">Device</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="batch.php">
                <i class="mdi mdi-google-circles-group menu-icon"></i>
                <span class="menu-title">Batch</span>
            </a>
        </li>-->
        <?php 
          if($_SESSION['rule_name']=='ADMIN')
          {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="users.php">
                <i class="mdi mdi-account-card-details menu-icon"></i>
                <span class="menu-title">User Master</span>
            </a>
        </li>
        <?php
          }
        ?>
        <?php 
          if($_SESSION['rule_name']!='VIEWER')
          {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="settings.php">
                <i class="mdi mdi-account-card-details menu-icon"></i>
                <span class="menu-title">Product Master</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="settings.php">
                <i class="mdi mdi-account-card-details menu-icon"></i>
                <span class="menu-title">Ingredient Master</span>
            </a>
          </li>
        <?php
          }
        ?>
        <!--<li class="nav-item">
            <span class="nav-link" href="#">
                <span class="menu-title">Reports</span>
            </span>
        </li>-->
        <!--
        <li class="nav-item">
            <a class="nav-link" href="logs.php">
                <i class="mdi mdi-cloud-print menu-icon"></i>
                <span class="menu-title">Logs</span>
            </a>
        </li>
        -->
        <!--
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              <span class="menu-title">Basic UI Elements</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/ui-features/dropdowns.html">Dropdowns</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/ui-features/typography.html">Typography</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/icons/mdi.html">
              <i class="mdi mdi-contacts menu-icon"></i>
              <span class="menu-title">Icons</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/forms/basic_elements.html">
              <i class="mdi mdi-format-list-bulleted menu-icon"></i>
              <span class="menu-title">Forms</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/charts/chartjs.html">
              <i class="mdi mdi-chart-bar menu-icon"></i>
              <span class="menu-title">Charts</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/tables/basic-table.html">
              <i class="mdi mdi-table-large menu-icon"></i>
              <span class="menu-title">Tables</span>
            </a>
          </li>
          <li class="nav-item">
            <span class="nav-link" href="#">
              <span class="menu-title">Docs</span>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.bootstrapdash.com/demo/breeze-free/documentation/documentation.html">
              <i class="mdi mdi-file-document-box menu-icon"></i>
              <span class="menu-title">Documentation</span>
            </a>
          </li>
          -->
        <li class="nav-item sidebar-actions">
            <div class="nav-link">
                <div class="mt-4">
                    <!-- <div class="border-none">
                  <p class="text-black">Notification</p>
                </div> -->

                    <ul class="mt-4 pl-0">
                        <li>
                            <a class="nav-link" href="logout.php">Sign Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</nav>