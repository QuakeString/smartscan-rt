<?php
    include("dbCon.php");
    session_start();
    if(!isset($_SESSION['login_user']))
    {
        header("Location: login.php");
        die();
    }

    $_SESSION["NewBatchID"] = "0";
    $_SESSION["NewBatch"] = "";
    $_SESSION["NewProductID"] = "";
    $_SESSION["NewFromDate"] = "";
    $_SESSION["NewFromTime"] = "";
    $_SESSION["NewToDate"] = "";
    $_SESSION["NewToTime"] = "";
    $_SESSION["NewProductionYield"] = "";
    $_SESSION["NewProductionInCharge"] = "";
    $_SESSION["NewOperators"] = "";
    $_SESSION["NewRemarks"] = "";

    $colors = array("bg-warning", "bg-danger", "bg-success", "bg-primary"); 

    $rid = 1;
    if(!empty($_GET['id']) && $_GET['id'] > 0)
    {
        $rid = $_GET['id'];
    }

    $reactor_name = "&nbsp;";
    $product_name = "&nbsp;";
    $batch_no = "0";
    $temparature = "0";
    $power = "0";
    $weight = "0";
    $batch_time = "0";
    $time_remains = "0";
    $state = "0";
    
    $sql = "SELECT * FROM tb_device_master WHERE id = " . $rid;
    $conn->next_result();
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
        $i = 0;
        while($row = $result->fetch_assoc()) 
        {
            $device_name   = $row["device_name"];
            //$product_name   = $row["reactor_name"];
            $batch_no       = $row["batch_no"];
            $temparature    = $row["temperature"];
            $power          = $row["power"];
            $weight         = $row["weight"];
            $temperature_available  = $row["temperature_available"];
            $weight_available       = $row["weight_available"];
            $power_available        = $row["power_available"]; 
            $state                  = $row["state"]; 
        }
    }

    $interval = 0;
    if(isset($_POST["interval"]))
    {
        $interval = $_POST["interval"];
        $_SESSION["interval"] = $interval;
    } 

    if(isset($_SESSION["interval"]))
    {
        $interval = $_SESSION["interval"];
    }

    if(isset($_POST["btnNewBatch"]))
    {
        $txtBatch           = $_POST["txtBatch"];
        $txtProduct         = $_POST["txtProduct"];
        $txtFromDate        = $_POST["txtFromDate"];        
        $txtFromTime        = $_POST["txtFromTime"];       
        $txtToDate          = $_POST["txtToDate"];        
        $txtToTime          = $_POST["txtToTime"];        
        $ProductionYield    = $_POST["txtProductionYield"];
        $ProductionInCharge = $_POST["txtProductionInCharge"];
        $Operators          = $_POST["txtOperators"];
        $Remarks            = $_POST["txtRemarks"];
        $UserID             = $_SESSION['login_user_id'];  

        $sql = "CALL sp_SaveNewBatchDetails (" . $rid . ",'" . session_id() . "','" . $txtBatch . "'," . $txtProduct . ",'" . $txtFromDate . " " . $txtFromTime . "','" . $txtToDate . " " . $txtToTime . "'," . $ProductionYield . ",'" . $ProductionInCharge . "','" . $Operators . "','" . $Remarks . "'," . $UserID . ");";
        $conn->next_result();
        $conn->query($sql); 
        //echo $sql;               
    }

    if(isset($_POST["btnAddIngrediant"]))
    {
        echo "I AM IN";
    }
    
    $buttonHit = "";
    if(isset($_POST['tempTrends']))
    {
        $buttonHit = "tempTrends";
    }
    if(isset($_POST['tempData']))
    {
        $buttonHit = "tempData";
    }
    if(isset($_POST['powerTrends']))
    {
        $buttonHit = "powerTrends";
    }
    if(isset($_POST['powerData']))
    {
        $buttonHit = "powerData";
    }
    if(isset($_POST['weightTrends']))
    {
        $buttonHit = "weightTrends";
    }
    if(isset($_POST['weightData']))
    {
        $buttonHit = "weightData";
    }
    if(isset($_POST['btnHistoryData']))
    {
        $buttonHit = "btnHistoryData";
    }
    if(isset($_POST['btnAssignBatch']))
    {
        $buttonHit = "btnAssignBatch";
    }
    if($buttonHit!="")
    {
        $_SESSION["buttonHit"] = $buttonHit;
    }
    else
    {
        $buttonHit = (isset($_SESSION["buttonHit"])?$_SESSION["buttonHit"]:"");
    }
    $buttonHit = "btnHistoryData";
    $fromDate = "";
    $fromTime = "";
    $toDate = "";
    $toTime = "";
    $selBatch = "0";
    if(isset($_POST["selBatch"]))
    {
        $selBatch = $_POST["selBatch"];
    }

    if(isset($_POST["btnFilter"]))
    {
        $fromDate = $_POST["fromDate"];
        $fromTime = $_POST["fromTime"];
        $toDate = $_POST["toDate"];
        $toTime = $_POST["toTime"];
        $_SESSION["FromDate"] = $fromDate;
        $_SESSION["FromTime"] = $fromTime;
        $_SESSION["ToDate"] = $toDate;
        $_SESSION["ToTime"] = $toTime;

        $selBatch = $_POST["selBatch"];
        $_SESSION["selBatch"] = $selBatch;
    } 

    if(isset($_SESSION["FromDate"]))
    {
        $fromDate = $_SESSION["FromDate"] ;
    }

    if(isset($_SESSION["FromTime"]))
    {
        $fromTime = $_SESSION["FromTime"] ;
    }

    if(isset($_SESSION["ToDate"]))
    {
        $toDate = $_SESSION["ToDate"] ;
    }

    if(isset($_SESSION["ToTime"]))
    {
        $toTime = $_SESSION["ToTime"] ;
    }
    
    if(isset($_SESSION["selBatch"]))
    {
        $selBatch = $_SESSION["selBatch"];
    }
    $fromDate = ($fromDate=="" ? date('Y-m-d', strtotime("-1 month")) : $fromDate);
    $fromTime = ($fromTime=="" ? date('H:i:s') : $fromTime);
    $toDate = ($toDate=="" ? date('Y-m-d') : $toDate);
    $toTime = ($toTime=="" ? date('H:i:s') : $toTime); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SMART RT - Details Log</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css" />
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        table#tableHistoryData tr td a.button { display:none;}
        table#tableHistoryData tr:hover td a.button { display:inline-block;}
    </style>    
    <style>
        #tableFilter td
        {
            padding: 5px 10px;
        }
        #tableIngrediant td
        {
            border: 1px solid black;
        }
    </style>   
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
                    <div class="row">
                        <div class="col-xl-12 col-md-12 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">    
                            <div class="card <?php echo $colors[($rid-1)%4] ?>" style="border-radius: 10px;">        
                                <div class="card-body">            
                                    <div class="d-flex justify-content-between align-items-start" style="display: -webkit-box !important;">                                            
                                        <div class="color-card" style="text-align:center;">                    
                                            <h3 class="text-white">
                                                <select style="background-color: transparent;" id="selDevice" name="selDevice">
                                                <?php                       
                                                    $sql = "CALL sp_GetLiveDeviceDetails();";
                                                    $colors = array("bg-warning", "bg-danger", "bg-success", "bg-primary");
                                                    $conn->next_result();
                                                    $result = $conn->query($sql);

                                                    if ($result->num_rows > 0) 
                                                    {
                                                        $i = 0;
                                                        while($row = $result->fetch_assoc()) 
                                                        {  
                                                            if($row["id"]==$rid)
                                                            {                                  
                                                                echo '<option value="' . $row["id"] . '" selected>' . $row["device_name"] . '</option>' ;  //($row["state"]==1 ? ' <span class="btn" style="background-color:green;color:white;">Running</span>' : ' &nbsp;&nbsp;<span class="btn" style="background-color:red;color:white;">Stop</span>').
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="' . $row["id"] . '">' . $row["device_name"] . '</option>' ;  //($row["state"]==1 ? ' <span class="btn" style="background-color:green;color:white;">Running</span>' : ' &nbsp;&nbsp;<span class="btn" style="background-color:red;color:white;">Stop</span>').
                                                            }
                                                        }
                                                    }                         
                                                ?>      
                                                </select>                                        
                                            </h3> 
                                        </div> 
                                   </div>          
                                </div>    
                            </div>
                        </div>
                    </div>                                 
                    <div class="row">
                        <div class="col-xl-12 stretch-card grid-margin">
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-body"> 
                                    <form method="post" >                
                                        <div class="row">                                        
                                            <div class="col-xl-12 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                                <h5>History Logs (Trends)</h5>
                                            </div> 
                                            <div class="col-xl-12 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                                <!--<div class="row"> -->
                                                    <div class="col-xl-2 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                                        Select Batch :
                                                    </div>
                                                    <div class="col-xl-4 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                                        <select id="selBatch" name="selBatch" style="width:100%; border-radius: 10px;">
                                                            <option value='0' <?php echo($selBatch==0?"selected":"") ?>>ALL</option>
                                                            <?php 
                                                                $sql = "CALL sp_GetBatchList(".$rid.");";
                                                                $conn->next_result();
                                                                $result = $conn->query($sql);
                                                                if ($result->num_rows > 0) 
                                                                {
                                                                    $i = 0;
                                                                    while($row = $result->fetch_assoc()) 
                                                                    {    
                                                                        if($selBatch==$row["id"])   
                                                                        {
                                                                            echo "<option value='" . $row["id"] . "' selected>" . $row["batch_name"] . "</option>";
                                                                        } 
                                                                        else
                                                                        {
                                                                            echo "<option value='" . $row["id"] . "'>" . $row["batch_name"] . "</option>";
                                                                        }                                                        
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                                        <input type="submit" id="btnFilter" name="btnFilter" value="Filter" class="btn btn-info" />&nbsp;
                                                        <a class="btn btn-info button" href="batchdetails.php?kid=<?php echo $rid ?>">New Batch</a>
                                                    </div>
                                                <!--</div>-->
                                            </div>
                                            <div class="col-xl-6 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3 datePart">
                                                   
                                                    <div class="col-xl-4 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                                        From Date:
                                                    </div>
                                                    <div class="col-xl-8 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                                        <input type="date" id="fromDate" name="fromDate" style="border-radius: 8px;" value="<?php echo $fromDate; ?>" required><input type="time" id="fromTime" name="fromTime" style="border-radius: 8px;" step="any" value="<?php echo $fromTime; ?>" required>
                                                    </div>
                                            </div>        
                                            <div class="col-xl-6 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3 datePart">
                                                    <div class="col-xl-4 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                                        To Date:
                                                    </div>
                                                    <div class="col-xl-8 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                                        <input type="date" id="toDate" name="toDate" style="border-radius: 8px;" value="<?php echo $toDate; ?>" required><input type="time" id="toTime" name="toTime" style="border-radius: 8px;" step="any" value="<?php echo $toTime; ?>" required>
                                                    </div>
                                                
                                                <!--
                                                <div class="row" style="display:none;">       
                                                    <table id="tableFilter">
                                                        <tr>
                                                            <td>Select Batch :</td>
                                                            <td>
                                                                <select id="selBatch1" name="selBatch1" style="width:100%;">
                                                                    <option value='0' <?php echo($selBatch==0?"selected":"") ?>>ALL</option>
                                                                    <?php 
                                                                        $sql = "CALL sp_GetBatchList(".$rid.");";
                                                                        $conn->next_result();
                                                                        $result = $conn->query($sql);
                                                                        if ($result->num_rows > 0) 
                                                                        {
                                                                            $i = 0;
                                                                            while($row = $result->fetch_assoc()) 
                                                                            {    
                                                                                if($selBatch==$row["id"])   
                                                                                {
                                                                                    echo "<option value='" . $row["id"] . "' selected>" . $row["batch_name"] . "</option>";
                                                                                } 
                                                                                else
                                                                                {
                                                                                    echo "<option value='" . $row["id"] . "'>" . $row["batch_name"] . "</option>";
                                                                                }                                                        
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td></td>
                                                            <td><input type="submit" id="btnFilter" name="btnFilter" value="Filter" class="btn btn-info" /> </td>
                                                        </tr>
                                                        <tr id="datePart2">
                                                            <td>From Date:</td>
                                                            <td><input type="date" id="fromDate" name="fromDate" value="<?php echo $fromDate; ?>" required><input type="time" id="fromTime" name="fromTime" step="any" value="<?php echo $fromTime; ?>" required></td>
                                                            <td>To Date:</td>
                                                            <td><input type="date" id="toDate" name="toDate" value="<?php echo $toDate; ?>" required><input type="time" id="toTime" name="toTime" step="any" value="<?php echo $toTime; ?>" required> </td>
                                                        </tr>
                                                    </table>
                                                </div> 
                                                -->
                                            </div> 
                                            <hr/>                                                
                                            <div class="col-xl-12 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                                <div id="chart_div" style="width:100%;height:400px;"></div>                                                
                                            </div>
                                        </div>
                                    </form> 
                                    
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="row">
                        <div class="col-xl-12 stretch-card grid-margin">
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-body"> 


                                    <form method="post" >  
                                        <div class="page-header flex-wrap">                                         
                                            <h4 class="mb-0">History Log Data</h4>
                                            <div class="d-flex">
                                                <select id="interval" name="interval" class="btn btn btn-warning btn-fw border ml-3" onchange="this.form.submit();">
                                                    <option value="0" <?php echo ($interval==0 ? "selected": ""); ?>>Select no of Skip</option>   
                                                    <option value="1" <?php echo ($interval==1 ? "selected": ""); ?>>1 Record Skip</option>   
                                                    <option value="2" <?php echo ($interval==2 ? "selected": ""); ?>>2 Records Skip</option>   
                                                    <option value="5" <?php echo ($interval==5 ? "selected": ""); ?>>5 Records Skip</option>   
                                                    <option value="10" <?php echo ($interval==10 ? "selected": ""); ?>>10 Records Skip</option>   
                                                    <option value="15" <?php echo ($interval==15 ? "selected": ""); ?>>15 Records Skip</option>   
                                                    <option value="20" <?php echo ($interval==20 ? "selected": ""); ?>>20 Records Skip</option>
                                                </select>
                                                <button type="submit" class="btn ml-3 btn-success" onclick="exportReportToExcel(this)">
                                                    <i class="mdi mdi-download menu-icon"></i> Export to Excel 
                                                </button>   
                                            </div>                                        
                                        </div>
                                    </form>

                                    <div class="table-responsive">
                                        <table class="table table-striped" id="tableHistoryData" style="width:100%;height: 400px;display: block;overflow: auto;">
                                            <thead>
                                                <tr>
                                                    <th>SL#</th>
                                                    <th>Device Name</th>
                                                    <th>Batch No</th>
                                                    <th>Log Time</th>
                                                    <th>Temperature</th>
                                                    <th>Current</th>
                                                    <th>Weight</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $sql = "CALL sp_GetDeviceWiseTrend(" . $rid . "," . $selBatch . ",'" . $fromDate . " " . $fromTime . "','". $toDate . " " . $toTime . "')";
                                                $conn->next_result();
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) 
                                                {                                                    
                                                    $i = 1;
                                                    $j = 0;
                                                    while($row = $result->fetch_assoc()) 
                                                    {
                                                        if($interval == 0 OR ($j%$interval == 0))
                                                        {
                                                            echo '<tr class="py-1">';
                                                            echo '    <td>' . ($i) . '</td>';
                                                            echo '    <td>' . $row["device_name"] . '</td>';                                                                
                                                            //echo '    <td>' . ($row["batch"]=="" ? '<a class="btn btn-info button" data-toggle="modal" data-target="#assignBatch">New Batch</a>' : $row["batch"]) . '</td>';
                                                            echo '    <td>' . ($row["batch"]=="" ? '<a class="btn btn-info button" href="batchdetails.php?kid='.$rid.'">New Batch</a>' : $row["batch"]) . '</td>';
                                                            echo '    <td>' . $row["log"] . '</td>';  
                                                            echo '    <td>' . $row["temp"] . '</td>';     
                                                            echo '    <td>' . $row["power"] . '</td>';     
                                                            echo '    <td>' . $row["wg"] . '</td>';                                                     
                                                            echo '</tr>'; 
                                                            $i++;
                                                        }
                                                        $j++; 
                                                    }                                                    
                                                } 
                                                ?>
                                            </tbody>
                                        </table>

                                        <div class="modal fade" id="assignBatch" role="dialog">
                                            <div class="modal-dialog">                                                   
                                            <form method="post">    
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Assign New Batch</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    Batch :
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="txtBatch" name="txtBatch" style="width:100%" maxlength="20" required/>
                                                                </td>
                                                            </tr>
                                                            <tr>    
                                                                <td>
                                                                    Product :
                                                                </td>
                                                                <td>
                                                                    <select id="txtProduct" name="txtProduct" style="width:100%">
                                                                        <?php 
                                                                            $sql = "SELECT prd_id, product_name FROM tb_product_mst WHERE is_active = 1";
                                                                            $conn->next_result();
                                                                            $result = $conn->query($sql);
                                                                            if ($result->num_rows > 0) 
                                                                            {
                                                                                $i = 0;
                                                                                while($row = $result->fetch_assoc()) 
                                                                                {        
                                                                                    echo "<option value='" . $row["prd_id"] . "'>" . $row["product_name"] . "</option>";
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    Batch Started On : 
                                                                </td>
                                                                <td>
                                                                    <input type="date" id="txtFromDate" name="txtFromDate" value="<?php echo $fromDate; ?>" required><input type="time" id="txtFromTime" name="txtFromTime" step="any" value="<?php echo $fromTime; ?>" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    Batch End On : 
                                                                </td>
                                                                <td>
                                                                    <input type="date" id="txtToDate" name="txtToDate" value="<?php echo $toDate; ?>" required><input type="time" id="txtToTime" name="txtToTime" step="any" value="<?php echo $toTime; ?>" required> 
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    Production Yield:
                                                                </td>
                                                                <td>
                                                                    <input type="text" pattern="^\d*(\.\d{0,5})?$" id="txtProductionYield" name="txtProductionYield" style="width:80%" /> TON
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    Production in Charge:
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="txtProductionInCharge" name="txtProductionInCharge" style="width:100%" /> 
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    Operators:
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="txtOperators" name="txtOperators" style="width:100%" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    Remarks:
                                                                </td>
                                                                <td>
                                                                    <textarea id="txtRemarks" name="txtRemarks" style="width:100%"></textarea> 
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <br/>
                                                        <form method="POST">
                                                        <h5>Add Ingrediant for this Product</h5>
                                                        <table id="tableIngrediant" style="border:1px solid black;width:100%;">                                                                    
                                                            <tr>
                                                                <th>
                                                                    Ingrediant
                                                                </th>
                                                                <th>
                                                                    Quantity
                                                                </th>
                                                                <th>
                                                                    Unit
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <select id="addIngrediant" name="addIngrediant[]">
                                                                        <?php
                                                                            $sql = "SELECT ind_id, ingredient_name FROM tb_ingredient_mst WHERE is_active = 1";
                                                                            $result = $conn->query($sql);
                                                                            if ($result->num_rows > 0) 
                                                                            {
                                                                                $i = 0;
                                                                                while($row = $result->fetch_assoc()) 
                                                                                {                                                                        
                                                                                    echo "<option value='" . $row["ind_id"] . "'>" . $row["ingredient_name"] . "</option>";                                                                                        
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" id="addUnitQty" name="addUnitQty[]" style="width:100px;" required/>
                                                                </td>
                                                                <td>
                                                                    <select id="addUnit" name="addUnit[]">
                                                                        <option>KG</option>
                                                                        <option>TON</option>   
                                                                    </select>                                                                        
                                                                </td>
                                                            </tr>
                                                        </table> 
                                                        <span id="btnAddIngrediant" name="btnAddIngrediant" class="btn btn-warning border" style="padding: 1px 20px;">Add Ingrediant</span>
                                                        </form>                                                     
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" id="btnNewBatch" name="btnNewBatch" class="btn btn-success border" value="SAVE as Draft" />
                                                        <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
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
        $("#btnAddIngrediant").on("click",function(){
            var table = document.getElementById("tableIngrediant");
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount-1);

            var cell1 = row.insertCell(0);
            var element1 = document.createElement("input");
            element1.type = "text";
            element1.name = "addIngrediant[]";
            var e = document.getElementById("addIngrediant");
            var text = e.options[e.selectedIndex].text;
            element1.value = text;
            cell1.appendChild(element1);
            element1.size = 8;

            var cell2 = row.insertCell(1);
            var element2 = document.createElement("input");
            element2.type = "text";
            element2.name = "addUnitQty[]";
            cell2.appendChild(element2);
            element2.size = 8;

            var cell3 = row.insertCell(2);
            var element3 = document.createElement("input");
            element3.type = "text";
            element3.name = "addUnit[]";
            cell3.appendChild(element3);
            element3.size = 8;

            /*
            var table = document.getElementById("tableIngrediant");           
            var row = table.insertRow(table.rows.length-1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var e = document.getElementById("addIngrediant");
            var text = e.options[e.selectedIndex].text;
            cell1.innerHTML = text;
            cell2.innerHTML = document.getElementById("addUnitQty").value;
            cell3.innerHTML = document.getElementById("addUnit").value + " <input type='button' id='btnDeleleIngrediant' name='btnDeleleIngrediant' onclick='deleteRow(this)' class='btn btn-danger border' style='padding: 1px 10px;' value='-'>";
            */

            var tableID = "#tableIngrediant";
            var rowCount = $(tableID + ' tr').length;
            var results = [];
            for (var i = 2; i < rowCount; i++) 
            {
                results.push({
                    Ingrediant: $(tableID).find('tr').eq(i).find('[name="addIngrediant[]"]').val(),
                    UnitQty: $(tableID).find('tr').eq(i).find('[name="addUnitQty[]"]').val(),
                    Unit: $(tableID).find('tr').eq(i).find('[name="addUnit[]"]').val(),
                });
            }
            console.log(results);
            //alert(results);
            //return results;       
        });
        function deleteRow(r) {
            var i = r.parentNode.parentNode.rowIndex;
            //alert(i);
            document.getElementById("tableIngrediant").deleteRow(i);
        }
        /*
        function AddIngrediants() {
            var table = document.getElementById("tableIngrediant");
            var row = table.insertRow(table.rows.length-1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var e = document.getElementById("addIngrediant");
            var text = e.options[e.selectedIndex]. text;
            cell1.innerHTML = text;
            cell2.innerHTML = document.getElementById("addUnitQty").value;
            cell3.innerHTML = document.getElementById("addUnit").value;
        }*/
    </script>
    <script>
        $(document).ready(function(){
            var selBatch = $('#selBatch').val();
            if(selBatch==0)
            {
                $('.datePart').show();
            }
            else
            {
                $('.datePart').hide();
            }
            $('#selBatch').on('change',function(){
                if(this.value==0)
                {
                    $('.datePart').show();
                }
                else
                {
                    $('.datePart').hide();
                }
            });
            $('#selDevice').on('change',function(){
                if(this.value>0)
                {
                    window.location="details.php?id="+this.value; 
                }
            });
        });
    </script> 
    <script>
        function exportReportToExcel() 
        {
            var uri = 'data:application/vnd.ms-excel;base64,',
            template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
            base64 = function(s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            },
            format = function(s, c) {
                return s.replace(/{(\w+)}/g, function(m, p) {
                return c[p];
                })
            }
            var toExcel = document.getElementById("tableHistoryData").innerHTML;
            var ctx = {
            worksheet: name || '',
            table: toExcel
            };
            var link = document.createElement("a");
            link.download = "export.xls";
            link.href = uri + base64(format(template, ctx))
            link.click();
        }
    </script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
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
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Log Date', 'Temp', 'Current', 'Weight'],

        <?php
            $sql = "CALL sp_GetDeviceWiseTrend(" . $rid . "," . $selBatch . ",'" . $fromDate . " " . $fromTime . "','". $toDate . " " . $toTime . "')";
            $conn->next_result();
            $result = $conn->query($sql);
            $i = 0;
            while($data = $result->fetch_assoc()) 
            {
                echo "['" . $data['log'] . "'," . $data['temp'] . ",". $data['power'] . "," . $data['wg'] . "],";  
                $i++;
            }                                                            
        ?>
          
        ]);

        var options = {
          title: 'History Chart',
          curveType: 'function',
          legend: { position: 'top' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        <?php if($i>0){ ?>
            chart.draw(data, options);
        <?php } ?>
      }
    </script>
</body>
</html>