<?php
    include("dbCon.php");
    session_start();
    if(!isset($_SESSION['login_user']))
    {
        header("Location: login.php");
        die();
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
    $log_interval = 10;
    if(isset($_POST['btnSaveLogInterval']))
    {
        $sql = "UPDATE tb_setting_log_interval SET log_interval = " . $_POST["LogInterval"];
        $log_interval = $_POST["LogInterval"];
        $conn->query($sql);
    }
    else
    {
        $sql = "SELECT log_interval FROM tb_setting_log_interval";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc()) 
            {  
                $log_interval = $row["log_interval"];
            }
        }    
    }

    if(isset($_POST["btnSaveIngredient"]))
    {
        $sql = "CALL sp_SaveIngrediant('" . $_POST["IngredientName"] . "','" . $_POST["Details"] . "','" . $_POST["Unit"] . "');";
        $conn->query($sql);
    }

    if(isset($_POST["btnDeleteIngredient"]))
    {
        $sql = "CALL sp_DeleteIngrediant('" . $_POST["IngredientName"] . "');";
        $conn->query($sql);
    }

    if(isset($_POST["btnSaveProduct"]))
    {
        $sql = "CALL sp_SaveProduct('" . $_POST["ProductName"] . "','" . $_POST["Details"] . "');";
        $conn->query($sql);
    }

    if(isset($_POST["btnDeleteProduct"]))
    {
        $sql = "CALL sp_DeleteProduct('" . $_POST["ProductName"] . "');";
        $conn->query($sql);
    }

    $buttonHit = "";
    if(isset($_POST['btnLogInterval']))
    {
        $buttonHit = "btnLogInterval";
    }
    if(isset($_POST['btnProducts']))
    {
        $buttonHit = "btnProducts";
    }
    if(isset($_POST['btnIngredients']))
    {
        $buttonHit = "btnIngredients";
    }
    
    if($buttonHit!="")
    {
        $_SESSION["buttonHit"] = $buttonHit;
    }
    else
    {
        $buttonHit = (isset($_SESSION["buttonHit"])?$_SESSION["buttonHit"]:"");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SMART RT - Settings</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css" />
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
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
                    <form method="post" >
                        <div class="row">
                            <div class="col-xl-12 col-md-12 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">    
                                <div class="card bg-primary" style="border-radius: 10px;">        
                                    <div class="card-body px-3 py-4">            
                                        <div class="d-flex justify-content-between align-items-start" style="display: -webkit-box !important;"> 
                                            <table style="width:100%;">
                                                <tr>
                                                    <td style="width:33%; padding: 5px;"><input type="submit" class="btn <?php echo ($buttonHit == "btnLogInterval" ? 'btn-warning' : 'btn-light'); ?>" style="width:100%; padding: 30px 0;" id="btnLogInterval" name="btnLogInterval" value="Log Interval" /></td>
                                                    <td style="width:33%; padding: 5px;"><input type="submit" class="btn <?php echo ($buttonHit == "btnProducts" ? 'btn-warning' : 'btn-light'); ?>" style="width:100%; padding: 30px 0;" id="btnProducts" name="btnProducts" value="Products" /></td>
                                                    <td style="width:33%; padding: 5px;"><input type="submit" class="btn <?php echo ($buttonHit == "btnIngredients" ? 'btn-warning' : 'btn-light'); ?>" style="width:100%; padding: 30px 0;" id="btnIngredients" name="btnIngredients" value="Ingredients" /></td>
                                                </tr>
                                            </table>                                    
                                        </div>         
                                    </div>    
                                </div>
                            </div>
                        </div>                    
                        <div class="row">
                            <div class="col-xl-12 col-md-6 stretch-card grid-margin grid-margin-sm-0 pb-sm-3">
                                <div class="card bg-info" style="border-radius: 10px;">
                                    <div class="card-body px-3 py-4">
                                        <?php 
                                        if($buttonHit == "btnLogInterval") 
                                        {
                                        ?>
                                            <table style="width:100%;">
                                                <tr>
                                                    <td>
                                                        <h5 class="text-white" style="text-align: right; padding-right: 30px;">Log Interval:</h5>
                                                    </td>
                                                    <td style="padding: 5px;">
                                                        <select class="btn btn-light" style="width:50%; text-align:left;" id="LogInterval" name="LogInterval" >
                                                            <option value="300" <?php echo ($log_interval==300?"selected":""); ?>>5 MIN</option>
                                                            <option value="600" <?php echo ($log_interval==600?"selected":""); ?>>10 MIN</option>
                                                            <option value="900" <?php echo ($log_interval==900?"selected":""); ?>>15 MIN</option>
                                                            <option value="1800" <?php echo ($log_interval==1800?"selected":""); ?>>30 MIN</option>
                                                            <option value="2100" <?php echo ($log_interval==2100?"selected":""); ?>>35 MIN</option>
                                                            <option value="3600" <?php echo ($log_interval==3600?"selected":""); ?>>60 MIN</option>
                                                        </select>
                                                        <?php
                                                            if(isset($_SESSION['rule_name']) && $_SESSION['rule_name']=="ADMIN")        
                                                            {                                            
                                                                echo '<input type="submit" class="btn btn-success" style="padding:3px 30px;" id="btnSaveLogInterval" name="btnSaveLogInterval" value="SAVE" />';
                                                            }
                                                        ?>    
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php 
                                        }
                                        else if($buttonHit == "btnProducts") 
                                        {
                                        ?>
                                            <table style="width:100%;">
                                                <tr>
                                                    <td>
                                                        <h5 class="text-white" style="text-align: right; padding-right: 30px;">Product Name : *</h5>
                                                    </td>
                                                    <td style="padding: 5px;">
                                                        <input type="text" class="btn btn-light" style="width:50%; text-align:left;" id="ProductName" name="ProductName" required>  
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="text-white" style="text-align: right; padding-right: 30px;">Details :</h5>
                                                    </td>
                                                    <td style="padding: 5px;">
                                                        <input type="text" class="btn btn-light" style="width:50%; text-align:left;" id="Details" name="Details" >
                                                        <input type="submit" class="btn btn-success" style="padding:3px 30px;" id="btnSaveProduct" name="btnSaveProduct" value="SAVE" />
                                                        <input type="submit" class="btn btn-warning" style="padding:3px 30px;" id="btnDeleteProduct" name="btnDeleteProduct" value="DELETE" />
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php 
                                        }
                                        if($buttonHit == "btnIngredients") 
                                        {
                                        ?>
                                            <table style="width:100%;">
                                                <tr>
                                                    <td>
                                                        <h5 class="text-white" style="text-align: right; padding-right: 30px;">Ingredient Name : *</h5>
                                                    </td>
                                                    <td style="padding: 5px;">
                                                        <input type="text" class="btn btn-light" style="width:50%; text-align:left;" id="IngredientName" name="IngredientName" required>  
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="text-white" style="text-align: right; padding-right: 30px;">Details :</h5>
                                                    </td>
                                                    <td style="padding: 5px;">
                                                        <input type="text" class="btn btn-light" style="width:50%; text-align:left;" id="Details" name="Details" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="text-white" style="text-align: right; padding-right: 30px;">Unit :</h5>
                                                    </td>
                                                    <td style="padding: 5px;">
                                                        <select class="btn btn-light" style="width:50%; text-align:left;" id="Unit" name="Unit" >
                                                            <option>KG</option>
                                                            <option>TON</option>                                                          
                                                        </select> 
                                                        <input type="submit" class="btn btn-success" style="padding:3px 30px;" id="btnSaveIngredient" name="btnSaveIngredient" value="SAVE" />
                                                        <input type="submit" class="btn btn-warning" style="padding:3px 30px;" id="btnDeleteIngredient" name="btnDeleteIngredient" value="DELETE" />
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php 
                                        }
                                        ?>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        
                        <?php 
                        if($buttonHit == "btnProducts") 
                        {
                        ?>
                        <div class="row">
                            <div class="col-xl-12 stretch-card grid-margin">
                                <div class="card" style="border-radius: 10px;">
                                    <div class="card-body">                
                                        <h4 class="card-title">Product Master</h4>
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="tableProduct">
                                                <thead>
                                                    <tr>
                                                        <th>SL#</th>
                                                        <th>Product Name</th>
                                                        <th>Details</th>
                                                        <th>Use in Batch (Count)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql = "SELECT *, (SELECT COUNT(*) FROM tb_batch_mst WHERE product_id = prd_id) batch_cnt FROM tb_product_mst;";
                                                    $result = $conn->query($sql);

                                                    if ($result->num_rows > 0) 
                                                    {
                                                        $i = 1;
                                                        while($row = $result->fetch_assoc()) 
                                                        {
                                                            echo '<tr class="py-1">';
                                                            echo '    <td class="mylink">' . ($i) . '</td>';
                                                            echo '    <td class="mylink">' . $row["product_name"] . '</td>';
                                                            echo '    <td class="mylink">' . $row["details"] . '</td>';         
                                                            echo '    <td class="mylink">' . $row["batch_cnt"] . '</td>';                                                
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
                        <?php 
                        } 
                        if($buttonHit == "btnIngredients") 
                        {
                        ?>
                        <div class="row">
                            <div class="col-xl-12 stretch-card grid-margin">
                                <div class="card" style="border-radius: 10px;">
                                    <div class="card-body">                
                                        <h4 class="card-title">Ingredients Master</h4>
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="tableIngredient">
                                                <thead>
                                                    <tr>
                                                        <th>SL#</th>
                                                        <th>Ingredient Name</th>
                                                        <th>Details</th>
                                                        <th>Unit</th>
                                                        <th>Use in Batch (Count)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $sql = "SELECT *, (SELECT COUNT(*) FROM tb_batch_product_ingredient WHERE ingredient_id = ind_id) batch_cnt FROM tb_ingredient_mst;";
                                                        $result = $conn->query($sql);

                                                        if ($result->num_rows > 0) 
                                                        {
                                                            $i = 1;
                                                            while($row = $result->fetch_assoc()) 
                                                            {
                                                                echo '<tr class="py-1">';
                                                                echo '    <td class="mylink">' . $row["ind_id"] . '</td>';
                                                                echo '    <td class="mylink">' . $row["ingredient_name"] . '</td>';
                                                                echo '    <td class="mylink">' . $row["details"] . '</td>';
                                                                echo '    <td class="mylink">' . $row["unit"] . '</td>'; 
                                                                echo '    <td class="mylink">' . $row["batch_cnt"] . '</td>';                                                                                                                   
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
                        <?php 
                        }                                         
                        ?>                                        
                    </form>
                </div>
                <?php
                  include "footer.php";
                ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#tableProduct").on('click', '.mylink', function () {                
                var currentRow = $(this).closest("tr");
                
                var product = currentRow.find("td:eq(1)").text();
                $("#ProductName").val(product);

                var details = currentRow.find("td:eq(2)").text();
                $("#Details").val(details);
            });
            
            $("#tableIngredient").on('click', '.mylink', function () {                
                var currentRow = $(this).closest("tr");
                
                var product = currentRow.find("td:eq(1)").text();
                $("#IngredientName").val(product);

                var details = currentRow.find("td:eq(2)").text();
                $("#Details").val(details);
                
                var unit = currentRow.find("td:eq(3)").text();
                $('#Unit option')
                    .removeAttr('selected');

                $('#Unit option')
                    .filter('[value=' + unit + ']')
                    .attr('selected', true);
            });
        });
    </script>
    <script>
        $(document).ready(function (){
            $('#btnLogInterval').on('click', function() {
                $('input, select').each(function() {    
                  $(this).removeAttr('required');
                });
            }); 
            $('#btnProducts').on('click', function() {
                $('input, select').each(function() {    
                  $(this).removeAttr('required');
                });
            });
            $('#btnIngredients').on('click', function() {
                $('input, select').each(function() {    
                  $(this).removeAttr('required');
                });
            });
        }); 
    </script>    
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!--     <script src="assets/vendors/chart.js/Chart.min.js"></script> -->
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