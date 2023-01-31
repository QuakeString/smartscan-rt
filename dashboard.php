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
    <title>SMART RT - Dashboard</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css" />
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <style>
        .box-header
        { 
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            padding: 5px;
            border-radius: 10px;
            margin-top:0px;
            margin: -30px 20px 10px 20px;
            background-color: #FFF;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        $(document).ready(function(){
            $("#div_refresh").load("_load_dashboard.php");
            setInterval(function() {
                $("#div_refresh").load("_load_dashboard.php");
            }, 5000);
        });    
    </script>
</head>

<body class="sidebar-icon-only">
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
            <div class="main-panel" style="padding-top: 30px;">
                <div class="content-wrapper pb-0" style="background:#EEE;">
                   
                    <div class="row" id="div_refresh">
                    <?php                       
                        $colors = array("bg-warning", "bg-danger", "bg-success", "bg-primary");
                        $sql = "CALL sp_GetLiveDeviceDetails();";
                        
                        $conn->next_result();
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) 
                        {
                            $i = 0;
                            while($row = $result->fetch_assoc()) 
                            {                                    
                                echo '<div class="col-xl-4 col-md-6 grid-margin-sm-0 pb-sm-3 py-sm-3">';                                    
                                echo '    <div style="border-radius: 10px;background-color:#FFF;">';                               
                                echo '        <div class="card-body" style="margin:5px;">';
                                echo '            <div class="d-flex justify-content-between align-items-start">';
                                echo '                <div class="color-card" style="text-align:center;width: 100%;">';  
                                echo '                  <a href="details.php?id=' . $row["id"] . '" style="text-decoration: none;">';
                                echo '                    <h4 class="text-blue box-header">' . $row["device_name"] . '</h4>' ;  //($row["state"]==1 ? ' <span class="btn" style="background-color:green;color:white;">Running</span>' : ' &nbsp;&nbsp;<span class="btn" style="background-color:red;color:white;">Stop</span>').
                                echo '                  </a>';
                                //echo '                    <hr/>';
                                echo '                    <table style="width:100%;">';
                                //echo '                      <tr>';  
                                //echo ($row["temperature_available"] == "1" ? '<td class="text-blue">Temp</td>' : '');
                                //echo ($row["weight_available"] == "1" ? '<td class="text-blue">Power</td>' : '');
                                //echo ($row["power_available"] == "1" ? '<td class="text-blue">Weight</td>' : '');
                                //echo '                      </tr>'; 
                                echo '                      <tr>';  
                                echo ($row["temperature_available"] == "1" ? '<td><button type="button" class="btn btn-light text-blue" style="border-radius: 14px;font-size:12px;min-width:80px;max-width:100%;border-color:blue;color:blue;" data-toggle="modal" data-target="#liveTrend' . $row["id"] . '">Temp ' . $row["temperature"] . '°C</button></td>' : '');
                                echo ($row["weight_available"] == "1" ? '<td><button type="button" class="btn btn-light text-blue" style="border-radius: 14px;font-size:12px;min-width:80px;max-width:100%;border-color:blue;color:blue;" data-toggle="modal" data-target="#liveTrend' . $row["id"] . '2">Current ' . $row["power"] . 'Amp</button></td>' : '');
                                echo ($row["power_available"] == "1" ? '<td><button type="button" class="btn btn-light text-blue" style="border-radius: 14px;font-size:12px;min-width:80px;max-width:100%;border-color:blue;color:blue;" data-toggle="modal" data-target="#liveTrend' . $row["id"] . '3">Weight ' . $row["weight"] . 'Ton</button></td>' : '');
                                echo '                      </tr>';  
                                echo '                    </table><br/>';
                                //echo '                    <div id="chart_div' . $row["id"] . '" style="width:100%;"></div>';                                     
                                echo '                </div>';
                                //echo '                <i class="card-icon-indicator mdi mdi-react bg-inverse-icon-warning"></i>';
                                echo '            </div>';
                                //echo '            <h6 class="text-white">Last used on: '. $row["last_active_on"] .'</h6>';
                                echo '        </div>';                                
                                echo '    </div>';                                    
                                echo '</div>';                                     
                                $i++; 
                            }
                        }                         
                    ?>
                    </div>
                    <div class="modal fade" id="liveTrend1" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Temperature Trend</h4>
                                </div>
                                <div class="modal-body" style="width:100%;">
                                    <div id="chart_div1" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="liveTrend12" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Current Trend</h4>
                                </div>
                                <div class="modal-body" style="width:100%;">
                                    <div id="chart_div12" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="liveTrend13" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Weight Trend</h4>
                                </div>
                                <div class="modal-body" style="width:100%;">
                                    <div id="chart_div13" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="liveTrend2" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Temperature Trend</h4>
                                </div>
                                <div class="modal-body" style="width:100%;">
                                    <div id="chart_div2" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="liveTrend22" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Current Trend</h4>
                                </div>
                                <div class="modal-body" style="width:100%;">
                                    <div id="chart_div22" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="liveTrend23" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Weight Trend</h4>
                                </div>
                                <div class="modal-body" style="width:100%;">
                                    <div id="chart_div23" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="liveTrend3" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Temperature Trend</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="chart_div3" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="modal fade" id="liveTrend32" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Current Trend</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="chart_div32" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="modal fade" id="liveTrend33" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Weight Trend</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="chart_div33" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="modal fade" id="liveTrend4" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Temperature Trend</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="chart_div4" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="modal fade" id="liveTrend5" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Temperature Trend</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="chart_div5" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="modal fade" id="liveTrend6" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Temperature Trend</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="chart_div6" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="liveTrend7" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Temperature Trend</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="chart_div7" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="modal fade" id="liveTrend8" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Temperature Trend</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="chart_div8" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="modal fade" id="liveTrend9" role="dialog">
                        <div class="modal-dialog modal-lg">                                                   
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Live Data Log - Temperature Trend</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="chart_div9" style="width:100%;height:400px"></div>                                               
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger border" data-dismiss="modal">Close</button>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <script>
        google.charts.load('current', {packages: [ 'corechart']});
        google.charts.setOnLoadCallback(drawBackgroundColor);

        function drawBackgroundColor() {  
            var options = 
            {
                title: 'Live Trend for Data Log',
                curveType: 'function',
                legend: { position: 'bottom' },
                //chartArea: {width:800,left:20,top:20,height:200},
                hAxis: {viewWindow: {min:0, max:60}}
            };
            //options.hAxis.viewWindow.max = 120;

            var data1 = new google.visualization.arrayToDataTable([
                ['Trend On','Temp'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, temp1 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['" . $row['mytimestamp'] . "',".$row['temp1']."],";
                    }                
                ?>
            ]);            
            var chart1 = new google.visualization.LineChart(document.getElementById('chart_div1'));
            chart1.draw(data1, options);

            var data12 = new google.visualization.arrayToDataTable([
                ['Trend On','Current'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, pw1 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['" . $row['mytimestamp'] . "',".$row['pw1']."],";
                    }                
                ?>
            ]);            
            var chart12 = new google.visualization.LineChart(document.getElementById('chart_div12'));
            chart12.draw(data12, options);

            var data13 = new google.visualization.arrayToDataTable([
                ['Trend On','Weight'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, wg1 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['" . $row['mytimestamp'] . "',".$row['wg1']."],";
                    }                
                ?>
            ]);            
            var chart13 = new google.visualization.LineChart(document.getElementById('chart_div13'));
            chart13.draw(data13, options);

            var data2 = new google.visualization.arrayToDataTable([
                ['Trend On','Temp'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, temp2 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['".$row['mytimestamp']."',".$row['temp2']."],";
                    }                
                ?>
            ]);
            var chart2 = new google.visualization.LineChart(document.getElementById('chart_div2'));
            chart2.draw(data2, options);

            var data22 = new google.visualization.arrayToDataTable([
                ['Trend On','Current'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, pw2 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['".$row['mytimestamp']."',".$row['pw2']."],";
                    }                
                ?>
            ]);
            var chart22 = new google.visualization.LineChart(document.getElementById('chart_div22'));
            chart22.draw(data22, options);

            var data23 = new google.visualization.arrayToDataTable([
                ['Trend On','Weight'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, wg2 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['".$row['mytimestamp']."',".$row['wg2']."],";
                    }                
                ?>
            ]);
            var chart23 = new google.visualization.LineChart(document.getElementById('chart_div23'));
            chart23.draw(data23, options);
            
            var data3 = new google.visualization.arrayToDataTable([
                ['Trend On','Temp'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, temp3 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['". $row['mytimestamp']."',".$row['temp3']."],";
                    }                
                ?>
            ]);
            var chart3 = new google.visualization.LineChart(document.getElementById('chart_div3'));
            chart3.draw(data3, options);

            var data32 = new google.visualization.arrayToDataTable([
                ['Trend On','Current'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, pw3 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['". $row['mytimestamp']."',".$row['pw3']."],";
                    }                
                ?>
            ]);
            var chart32 = new google.visualization.LineChart(document.getElementById('chart_div32'));
            chart32.draw(data32, options);


            var data33 = new google.visualization.arrayToDataTable([
                ['Trend On','Weight'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, wg3 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['". $row['mytimestamp']."',".$row['wg3']."],";
                    }                
                ?>
            ]);
            var chart33 = new google.visualization.LineChart(document.getElementById('chart_div33'));
            chart33.draw(data33, options);

            var data4 = new google.visualization.arrayToDataTable([
                ['Trend On','Temp'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, temp4 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['".$row['mytimestamp']."',".$row['temp4']."],";
                    }                
                ?>
            ]);
            var chart4 = new google.visualization.LineChart(document.getElementById('chart_div4'));
            chart4.draw(data4, options);

            var data5 = new google.visualization.arrayToDataTable([
                ['Trend On','Temp'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, temp5 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['".$row['mytimestamp']."',".$row['temp5']."],";
                    }                
                ?>
            ]);
            var chart5 = new google.visualization.LineChart(document.getElementById('chart_div5'));
            chart5.draw(data5, options);

            var data6 = new google.visualization.arrayToDataTable([
                ['Trend On','Temp'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, temp6 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['".$row['mytimestamp']."',".$row['temp6']."],";
                    }                
                ?>
            ]);
            var chart6 = new google.visualization.LineChart(document.getElementById('chart_div6'));
            chart6.draw(data6, options);

            var data7 = new google.visualization.arrayToDataTable([
                ['Trend On','Temp'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, temp7 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['".$row['mytimestamp']."',".$row['temp7']."],";
                    }                
                ?>
            ]);
            var chart7 = new google.visualization.LineChart(document.getElementById('chart_div7'));
            chart7.draw(data7, options);

            var data8 = new google.visualization.arrayToDataTable([
                ['Trend On','Temp'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, temp8 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['".$row['mytimestamp']."',".$row['temp8']."],";
                    }                
                ?>
            ]);
            var chart8 = new google.visualization.LineChart(document.getElementById('chart_div8'));
            chart8.draw(data8, options);

            var data9 = new google.visualization.arrayToDataTable([
                ['Trend On','Temp'],
                <?php                 
                    $query = "SELECT * from (SELECT DATE_FORMAT(timestamp,'%H:%i:%s') mytimestamp, temp9 from live_logs GROUP BY timestamp ORDER BY id DESC LIMIT 1800) T ORDER BY mytimestamp"; 
                    $conn->next_result();
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "['". $row['mytimestamp']."',".$row['temp9']."],";
                    }                
                ?>
            ]);
            var chart9 = new google.visualization.LineChart(document.getElementById('chart_div9'));
            chart9.draw(data9, options);
            }
    </script>   
</body>

</html>