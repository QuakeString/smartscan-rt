<?php
    include("dbCon.php");
    session_start();
    if(!isset($_SESSION['login_user']))
    {
        header("Location: login.php");
        die();
    }
    
    $ing = 0;
    if(!empty($_GET['ing']) && $_GET['ing'] > 0)
    {
        $ing = $_GET['ing'];
        $sql = "CALL sp_DeleteBatchIngrediant (" . $ing . ");";
        $conn->query($sql);
    }

    $kid = 1;
    if(!empty($_GET['kid']) && $_GET['kid'] > 0)
    {
        $kid = $_GET['kid'];
    }
    $bid = 0;
    if(!empty($_GET['bid']) && $_GET['bid'] > 0)
    {
        $bid = $_GET['bid'];
        $sql = "CALL sp_GetBatchDetails ('" . $bid . "');";
        $conn->next_result();
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc()) 
            {    
                $_SESSION["NewBatchID"]     = $row["id"];
                $_SESSION["NewBatch"]       = $row["batch_name"];
                $_SESSION["NewProductID"]   = $row["product_id"];
                $kid = $row["kettle_id"];
                $_SESSION["NewProductionYield"] = $row["production_yield"];
                $_SESSION["NewFromDate"]        = explode(" ", $row["batch_start_date"])[0];
                $_SESSION["NewFromTime"]        = explode(" ", $row["batch_start_date"])[1];
                $_SESSION["NewToDate"]          = explode(" ", $row["batch_end_date"])[0];
                $_SESSION["NewToTime"]          = explode(" ", $row["batch_end_date"])[1];
                $_SESSION["NewProductionInCharge"]    = $row["production_in_charge"];
                $_SESSION["NewOperators"]       = $row["operators"];
                $_SESSION["NewRemarks"]         = $row["remarks"];
            }
        }
    }

    $items = 0;
    $txtBatchID = $bid;
    $txtBatch           = "";
    $txtProduct         = "";
    $txtFromDate        = "";        
    $txtFromTime        = "";       
    $txtToDate          = "";        
    $txtToTime          = "";        
    $ProductionYield    = "";
    $ProductionInCharge = "";
    $Operators          = "";
    $Remarks            = "";
    $UserID             = "";

    if(isset($_POST["btnNewBatch"]) || isset($_POST["btnSaveBatch"]))
    {
        if(isset($_SESSION["NewBatchID"]) && $_SESSION["NewBatchID"] > 0)
        {
            $txtBatchID = $_SESSION["NewBatchID"];
        }
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

        $sql = "CALL sp_SaveNewBatchDetails (". $txtBatchID ."," . $kid . ",'" . session_id() . "','" . $txtBatch . "'," . $txtProduct . ",'" . $txtFromDate . " " . $txtFromTime . "','" . $txtToDate . " " . $txtToTime . "'," . $ProductionYield . ",'" . $ProductionInCharge . "','" . $Operators . "','" . $Remarks . "'," . $UserID . ");";
        //echo $sql; 
        $conn->next_result();
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc()) 
            {    
                $txtBatchID         = $row["id"];
                $txtBatch           = $row["batch_name"];
                $txtProduct         = $row["product_id"];
                $txtFromDate        = explode(" ", $row["batch_start_date"])[0];        
                $txtFromTime        = explode(" ", $row["batch_start_date"])[1];      
                $txtToDate          = explode(" ", $row["batch_end_date"])[0];        
                $txtToTime          = explode(" ", $row["batch_end_date"])[1];         
                $ProductionYield    = $row["production_yield"];
                $ProductionInCharge = $row["production_in_charge"];
                $Operators          = $row["operators"];
                $Remarks            = $row["remarks"];
            }
        }  
        
        $_SESSION["NewBatchID"] = $txtBatchID;
        $_SESSION["NewBatch"] = $txtBatch;
        $_SESSION["NewProductID"] = $txtProduct;
        $_SESSION["NewFromDate"] = $txtFromDate;
        $_SESSION["NewFromTime"] = $txtFromTime;
        $_SESSION["NewToDate"] = $txtToDate;
        $_SESSION["NewToTime"] = $txtToTime;
        $_SESSION["NewProductionYield"] = $ProductionYield;
        $_SESSION["NewProductionInCharge"] = $ProductionInCharge;
        $_SESSION["NewOperators"] = $Operators;
        $_SESSION["NewRemarks"] = $Remarks;
    }
    if(isset($_SESSION["NewBatchID"]))
    {
        $txtBatchID = $_SESSION["NewBatchID"];
    }
    if(isset($_SESSION["NewBatch"]))
    {
        $txtBatch = $_SESSION["NewBatch"];
    }
    if(isset($_SESSION["NewProductID"]))
    {
        $txtProduct = $_SESSION["NewProductID"];
    }
    if(isset($_SESSION["NewFromDate"]))
    {
        $txtFromDate = $_SESSION["NewFromDate"];
    }
    if(isset($_SESSION["NewFromTime"]))
    {
        $txtFromTime = $_SESSION["NewFromTime"];
    }
    if(isset($_SESSION["NewToDate"]))
    {
        $txtToDate = $_SESSION["NewToDate"];
    }
    if(isset($_SESSION["NewToTime"]))
    {
        $txtToTime = $_SESSION["NewToTime"];
    }
    if(isset($_SESSION["NewProductionYield"]))
    {
        $ProductionYield = $_SESSION["NewProductionYield"];
    }
    if(isset($_SESSION["NewProductionInCharge"]))
    {
        $ProductionInCharge = $_SESSION["NewProductionInCharge"];
    }
    if(isset($_SESSION["NewOperators"]))
    {
        $Operators = $_SESSION["NewOperators"];
    }
    if(isset($_SESSION["NewRemarks"]))
    {
        $Remarks = $_SESSION["NewRemarks"];
    }

    if(isset($_POST["btnAddIngredient"]))
    {
        $txtIngredientID = $_POST["addIngrediant"];
        $txtIngredientQty = $_POST["addUnitQty"];
        $txtIngredientUnit = $_POST["addUnit"];
        
        $sql = "CALL sp_SaveBatchIngredient (" . $txtBatchID . ",'" . session_id() . "'," . $txtIngredientID . "," . $txtIngredientQty . ",'" . $txtIngredientUnit . "');";
        //echo $sql; 
        $conn->next_result();
        $conn->query($sql);
    }
    
    if(isset($_POST["btnConfirmBatch"]) || isset($_POST["btnConfirmBatch2"]))
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

        $sql = "CALL sp_ConfirmBatchDetails ('" . $txtBatch . "'," . $txtProduct . ",'" . $txtFromDate . " " . $txtFromTime . "','" . $txtToDate . " " . $txtToTime . "'," . $ProductionYield . ",'" . $ProductionInCharge . "','" . $Operators . "','" . $Remarks . "'," . $UserID . ");";
        $conn->next_result();
        $conn->query($sql); 
        header("Location: details.php?id=".$kid);
    }

    $sql = "SELECT * FROM tb_device_master WHERE id=" . $kid . ";";
    $conn->next_result();
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
        while($row = $result->fetch_assoc()) 
        {    
            $pr_device_name      = $row["device_name"];
            $pr_kettle_id        = $row["id"];
        }
    }

    $fromDate = $txtFromDate;
    $fromTime = $txtFromTime ;
    $toDate = $txtToDate;
    $toTime = $txtToTime;

    if(isset($_POST["txtFromDate"]))
    {
        $fromDate = $_POST["txtFromDate"];
    }

    if(isset($_POST["txtFromTime"]))
    {
        $fromTime = $_POST["txtFromTime"];
    }

    if(isset($_POST["txtToDate"]))
    {
        $toDate = $_POST["txtToDate"];
    }

    if(isset($_POST["txtToTime"]))
    {
        $toTime = $_POST["txtToTime"];
    }

    $fromDate = ($fromDate=="" ? date('Y-m-d', strtotime("-1 day")) : $fromDate);
    $fromTime = ($fromTime=="" ? date('H:i:s') : $fromTime);
    $toDate = ($toDate=="" ? date('Y-m-d') : $toDate);
    $toTime = ($toTime=="" ? date('H:i:s') : $toTime);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SMART RT - Reports</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css" />
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        #tableIngrediant td
        {
            border: 1px solid black;
        }

        #tableBatchDetails td
        {
            padding: 7px 10px;
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
                    <form method="POST">
                    <div class="page-header flex-wrap">
                        <h3 class="mb-0">Batch Details</h3>   
                        <div class="d-flex">
                            <input type="submit" id="btnSaveBatch" name="btnSaveBatch" class="btn btn btn-info border" value="Save"  />    
                            <?php
                                $sql = "CALL sp_GetIngredient(".$txtBatchID.")"; 
                                $conn->next_result();
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) 
                                {                                                                
                                    while($row = $result->fetch_assoc()) 
                                    {
                                        $items++;
                                    }
                                }  
                                if($items>0)
                                {                                                 
                            ?>                            
                            <input type="submit" id="btnConfirmBatch2" name="btnConfirmBatch2" class="btn btn btn-warning border ml-3" value="Confirm"  />
                            <?php
                                }
                            ?>     
                            <button type="button" class="btn btn-sm ml-3 btn-success">
                                <a style="text-decoration:none;color:#FFF;" target="_blank" href="batch_report.php?b=<?php echo $bid; ?>"> Print </a></button>
                            <?php 
                                if($_SESSION['rule_name']=='ADMIN')
                                {
                            ?>
                                <button type="button" class="btn btn-sm ml-3 btn-success">
                                <a style="text-decoration:none;color:#FFF;" href="batch_delete.php?bid=<?php echo $bid; ?>"> Delete </a></button>  
                            <?php
                                }
                            ?> 
                        </div>                     
                    </div>
                    
                    <div class="row">
                        
                            <div class="col-lg-6 grid-margin stretch-card">
                                <div class="card" style="border-radius: 10px;">
                                    <div class="card-body">
                                        <h4 class="card-title">Update Batch Details</h4>
                                        <hr/>
                                        
                                            <table id="tableBatchDetails">
                                                <tr>
                                                    <td>
                                                        Kettle Name :
                                                    </td>
                                                    <td>
                                                        <input type="text" id="pr_device_name" name="pr_device_name" style="width:100%;" value="<?php echo $pr_device_name; ?>" readonly/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Batch * :
                                                    </td>
                                                    <td>
                                                        <input type="hidden" id="txtBatchID" name="txtBatchID" value="<?php echo $txtBatchID; ?>"/>
                                                        <input type="text" id="txtBatch" name="txtBatch" style="width:100%;" value="<?php echo $txtBatch; ?>" placeholder="Enter New Batch Name" required/>
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
                                                                    while($row = $result->fetch_assoc()) 
                                                                    {  
                                                                        if($txtProduct == $row["prd_id"])   
                                                                        {   
                                                                            echo "<option value='" . $row["prd_id"] . "' selected>" . $row["product_name"] . "</option>";
                                                                        }
                                                                        else
                                                                        {   
                                                                            echo "<option value='" . $row["prd_id"] . "'>" . $row["product_name"] . "</option>";
                                                                        }
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
                                                        <input type="date" style="font-size:12px;" id="txtFromDate" name="txtFromDate" value="<?php echo $fromDate; ?>" required><input type="time" style="font-size:12px;" id="txtFromTime" name="txtFromTime" step="any" value="<?php echo $fromTime; ?>" required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Batch End On :
                                                    </td>
                                                    <td>
                                                    <input type="date" style="font-size:12px;" id="txtToDate" name="txtToDate" value="<?php echo $toDate; ?>" required><input type="time" style="font-size:12px;" id="txtToTime" name="txtToTime" step="any" value="<?php echo $toTime; ?>" required> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Production Yield :
                                                    </td>
                                                    <td>
                                                    <input type="text" pattern="^\d*(\.\d{0,5})?$" id="txtProductionYield" name="txtProductionYield" style="width:80%" value="<?php echo $ProductionYield; ?>" /> TON
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Production in Charge:
                                                    </td>
                                                    <td>
                                                        <input type="text" id="txtProductionInCharge" name="txtProductionInCharge" style="width:100%;" value="<?php echo $ProductionInCharge; ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Operators :
                                                    </td>
                                                    <td>
                                                        <input type="text"  id="txtOperators" name="txtOperators" style="width:100%;" value="<?php echo $Operators; ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Remarks :
                                                    </td>
                                                    <td>
                                                        <textarea id="txtRemarks" name="txtRemarks" style="width:100%"><?php echo $Remarks; ?></textarea> 
                                                    </td>
                                                </tr>
                                                <?php
                                                if($txtBatchID==0)
                                                {  
                                                ?>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <input type="submit" id="btnNewBatch" name="btnNewBatch" class="btn btn-success border" value="SAVE as Draft" />
                                                    </td>
                                                </tr>
                                                <?php 
                                                }
                                                ?>
                                            </table>  
                                       
                                    </div>
                                </div>
                            </div>  
                            <div class="col-lg-6 grid-margin stretch-card">
                                <div class="card" style="border-radius: 10px;">
                                    <div class="card-body">
                                        <h4 class="card-title">List of Ingrediants</h4>
                                        <div class="table-responsive">
                                            
                                                <table class="table table-striped" id="tableBatch">
                                                    <thead>
                                                        <tr>
                                                            <th>Ingrediant</th>
                                                            <th>Qty</th>
                                                            <th></th>                                                   
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $sql = "CALL sp_GetIngredient(".$txtBatchID.")"; 
                                                            $conn->next_result();
                                                            $result = $conn->query($sql);

                                                            if ($result->num_rows > 0) 
                                                            {                                                                
                                                                while($row = $result->fetch_assoc()) 
                                                                {
                                                                    echo '<tr>';
                                                                    echo '    <td><img src="assets/images/tick.png"> ' . $row["ingredient_name"] . '</td>';  
                                                                    echo '    <td>' . $row["qty"] . ' ' . $row["unit"] . '</td>';                                                 
                                                                    echo '    <td><a href="batchdetails.php?bid='.$_SESSION["NewBatchID"].'&ing='.$row["id"].'"  class="btn-warning" style="padding:3px 8px;text-decoration:none;color:white;">-</a></td>';                                                 
                                                                    echo '</tr>'; 
                                                                    $items++;
                                                                }
                                                            }  
                                                            if($txtBatchID>0)
                                                            {                                                 
                                                        ?>
                                                        <tr id="hideEntry">
                                                            <td>
                                                                <select id="addIngrediant" name="addIngrediant">
                                                                    <?php
                                                                        $sql = "SELECT ind_id, ingredient_name FROM tb_ingredient_mst";
                                                                        $conn->next_result();
                                                                        $result = $conn->query($sql);
                                                                        if ($result->num_rows > 0) 
                                                                        {
                                                                            while($row = $result->fetch_assoc()) 
                                                                            {                                                                        
                                                                                echo "<option value='" . $row["ind_id"] . "'>" . $row["ingredient_name"] . "</option>";                                                                                        
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" id="addUnitQty" name="addUnitQty" style="width:80px;" value="0" required/>
                                                            </td>
                                                            <td>
                                                                <select id="addUnit" name="addUnit">
                                                                    <option>KG</option>
                                                                    <option>TON</option>   
                                                                </select> 
                                                                <input type="submit" class="btn-success" id="btnAddIngredient" name="btnAddIngredient" value="+">                                                                       
                                                            </td>                                            
                                                        </tr>
                                                        <?php
                                                            }
                                                            if($items > 0)
                                                            {
                                                        ?>
                                                        <tr>
                                                            <td></td>
                                                            <td colspan="2">
                                                                <input type="submit" id="btnConfirmBatch" name="btnConfirmBatch" class="btn btn-success border" value="Confirm the Batch"  />
                                                            </td>
                                                        </tr>
                                                        <?php 
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            
                                        </div>                                          
                                    </div>
                                </div>
                            </div>  
                    
                    </div>         
                    </form>

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
        function AddIngrediants() {
            var table = document.getElementById("tableIngrediant");
            var row = table.insertRow(table.rows.length-1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var e = document. getElementById("addIngrediant");
            var text = e. options[e. selectedIndex]. text;
            cell1.innerHTML = text;
            cell2.innerHTML = document.getElementById("addUnitQty").value;
            cell3.innerHTML = document.getElementById("addUnit").value;
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#tableBatch").on('click', '.mylink', function () {                
                var currentRow = $(this).closest("tr");
                
                //Batch No
                var batch = currentRow.find("td:eq(0)").text();
                $("#txtBatch").val(batch);

                //Device/Kettle
                //var product = currentRow.find("td:eq(1)").text();
                //$("#txtProduct").val(product);

                //Product
                var product = currentRow.find("td:eq(2)").text();
                //$("#txtProduct").val(product);
                $("#txtProduct option").filter(function() {
                    return this.text == product; 
                }).attr('selected', true);
                //Yield
                var yield = currentRow.find("td:eq(3)").text().split(" ")[0];
                $("#txtProductionYield").val(yield);

                //Start Date
                var startDate = currentRow.find("td:eq(4)").text().split(" ")[0];
                $("#txtFromDate").val(startDate);

                var startTime = currentRow.find("td:eq(4)").text().split(" ")[1];
                $("#txtFromTime").val(startTime);

                //End Date
                var endDate = currentRow.find("td:eq(5)").text().split(" ")[0];
                $("#txtToDate").val(endDate);
                
                var endTime = currentRow.find("td:eq(5)").text().split(" ")[1];
                $("#txtToTime").val(endTime);
                //Status
                //var details = currentRow.find("td:eq(6)").text();
                //$("#Details").val(details);                
            });
            
            $("#tableBatch").on('click', '.myPrint', function () {                
                var currentRow = $(this).closest("tr");
                
                //Batch No
                var batch = currentRow.find("td:eq(0)").text();
                $("#lblBatch").text(batch);
                $("#lblBatchID").text(batch);
                
                //Device/Kettle
                var kettle = currentRow.find("td:eq(1)").text();
                $("#lblKettle").text(kettle);

                //Product
                var product = currentRow.find("td:eq(2)").text();
                $("#lblProduct").text(product);
                
                //Yield
                var yieldval = currentRow.find("td:eq(3)").text();
                $("#lblYield").text(yieldval);

                //Start Date
                var startDate = currentRow.find("td:eq(4)").text().split(" ")[0];
                $("#lblDate").text(startDate);
            });            
        });    
    </script> 
    <script>
        $(document).ready(function (){
            $('#btnConfirmBatch').on('click', function() {
                $('input, select').each(function() {    
                  $(this).removeAttr('required');
                });
            });
            $('#btnSaveBatch').on('click', function() {
                $('input, select').each(function() {    
                  $(this).removeAttr('required');
                });
            }); 
        }); 
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
</body>
</html>