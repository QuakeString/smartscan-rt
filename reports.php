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
    <title>SMART RT - Reports</title>
    <!-- Bootstrap
    <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css" />
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="assets/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet" />
    <!-- Datatables -->
    <link href="assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
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
                        <h3 class="mb-0">Batch Reports
                        </h3>                        
                    </div>  
                    <div class="row">                     
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-body">
                                    <!--<h4 class="card-title">Details Logs</h4> -->
                                    <div class="x_content" style="width:100%;height: 400px;overflow:auto;">
                                        <table class="table table-striped table-bordered" id="tableBatch">
                                            <thead>
                                                <tr>
                                                    <th>SL#</th>
                                                    <th>Batch No.</th>
                                                    <th>Status</th>
                                                    <th>Kettle No.</th>
                                                    <th>Product Name</th>
                                                    <th>Production Date</th>
                                                    <th>Weight</th>       
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sql = "CALL sp_GetBatchList(0);";
                                                    $conn->next_result();
                                                    $result = $conn->query($sql);
                                                    
                                                    if ($result->num_rows > 0) 
                                                    {
                                                        $i = 1;
                                                        while($row = $result->fetch_assoc()) 
                                                        {
                                                            echo '<tr class="py-1">';
                                                            echo '    <td>' . ($i) . '</td>';
                                                            echo '    <td>' . $row["batch_name"] . '</td>';                                                                
                                                            echo '    <td style="text-align:center;">' . $row["status"] . '<br/>' . ($row["status"]=="DRAFT" ? "<a class='btn btn-success button mylink' href='batchdetails.php?bid=". $row["id"] ."'>Confirm</a>" : "<a class='btn btn-warning border myPrint' target='_blank' href='batch_report.php?b=". $row["id"] ."'>Print</a>") . '</td>';
                                                            echo '    <td>' . $row["device_name"] . '</td>';  
                                                            echo '    <td>' . $row["product_name"] . '</td>';     
                                                            echo '    <td>' . $row["batch_start_date"] . ' - ' . $row["batch_end_date"]. '</td>';     
                                                            echo '    <td>' . $row["production_yield"] . ' ton</td>';   
                                                            //echo '    <td>' . ($row["status"]=="DRAFT" ? "<a class='btn btn-success button mylink' href='batchdetails.php?bid=". $row["id"] ."'>Confirm</a>" : "<a class='btn btn-warning border myPrint' href='batch_report.php?b=". $row["id"] ."'>Print</a>") . '</td>';                                                 
                                                            echo '</tr>'; 
                                                            $i++; 
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
   
    <script>
        $(document).ready(function () {
            $('#tableBatch').DataTable({
                "searching": true // false to disable search (or any other option)
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
     <!-- jQuery -->
     <script src="assets/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>    
       
    <script src="assets/vendors/flot/jquery.flot.js"></script>
    <script src="assets/vendors/flot/jquery.flot.resize.js"></script>
    <script src="assets/vendors/flot/jquery.flot.categories.js"></script>
    <script src="assets/vendors/flot/jquery.flot.fillbetween.js"></script>
    <script src="assets/vendors/flot/jquery.flot.stack.js"></script>
    <script src="assets/vendors/flot/jquery.flot.pie.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>

    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>-->
    <!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>-->
        <!-- Datatables -->
    <script src="assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="assets/vendors/datatables.net-responsive/js/dataTables.responsive.js"></script>
    <script src="assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>

</body>
</html>