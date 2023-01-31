<?php
    include("dbCon.php");
?>
  

<?php
    //include("dbCon.php");
    //$colors = array("bg-warning", "bg-danger", "bg-success", "bg-primary");
    //$sql = "SELECT * FROM tb_device_master";
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
            echo '                    <table style="width:100%; margin-top:30px">';
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
            //echo '                    <div id="chart_div' . $row["id"] . '"></div>';                                  
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
    //$conn->close();
?>
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