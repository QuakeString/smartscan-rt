<?php
    include("dbCon.php");
    //include('libs/fpdf.php');
    include('libs/features.php');
    session_start();
    $pr_batch_id    = $_GET["b"];
    $pr_batch_name  = "";
    $pr_product_id  = "0";
    $pr_product_name= "";
    $pr_kettle_id   = "0";
    $pr_device_name = "";
    $pr_production_yield = "0";
    $pr_production_in_charge = "";
    $pr_operators            = "";
    $pr_batch_start_date = "";
    $pr_batch_end_date   = "";
    $pr_remarks          = "";
    $pr_status           = "";

    $sql = "CALL sp_GetBatchDetails ('" . $pr_batch_id . "');";
    //$conn->next_result();
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
        while($row = $result->fetch_assoc()) 
        {    
            $pr_batch_id         = $row["id"];
            $pr_batch_name       = $row["batch_name"];
            $_SESSION["batch_name"] = $pr_batch_name;
            $pr_product_id       = $row["product_id"];
            $pr_product_name     = $row["product_name"];
            $pr_device_name      = $row["device_name"];
            $pr_kettle_id        = $row["kettle_id"];
            $pr_production_yield = $row["production_yield"];
            $pr_batch_start_date = $row["batch_start_date"];
            $pr_batch_end_date   = $row["batch_end_date"];
            $pr_production_in_charge = $row["production_in_charge"];;
            $pr_operators            = $row["operators"];;
            $pr_remarks          = $row["remarks"];
            $pr_status           = $row["status"]; 
            $_SESSION["batch_status"] = $pr_status;
        }
    }

    class PDF extends PDF_Fetures
    {
        function Header()
        {
            $this->Image('logo.png',10,6,30);
            $this->SetFont('Arial','B',15);
            $this->Cell(60);
            $this->Cell(80,10,'Report for the Batch '.$_SESSION["batch_name"],0,0,'C');
            $this->Ln(15);

            if($_SESSION["batch_status"]!="CONFIRM")
            {
                $this->SetFont('Arial','B',120);
                $this->SetTextColor(224,224,224);
                $this->RotatedText(60,220,$_SESSION["batch_status"],45);
            }
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetFont('Times','BI',14);
    $pdf->Cell(0,10,'Batch Details ',0,1);

    $pdf->SetDrawColor(206, 209, 201);
    $pdf->SetFont('Times','',12);
    $pdf->Cell(60,7,'Device / Kettle Name :',1,1); $pdf->Cell(60); $pdf->Cell(120,-7,$pr_device_name,1,1);  
    $pdf->Ln(7);

    $pdf->Cell(60,7,'Unique Batch ID :',1,1);$pdf->Cell(60); $pdf->Cell(120,-7,$pr_batch_name,1,1);  
    $pdf->Ln(7);
    
    $pdf->Cell(60,7,'Name of the Product :',1,1);$pdf->Cell(60); $pdf->Cell(120,-7,$pr_product_name,1,1);  
    $pdf->Ln(7);

    $pdf->Cell(60,7,'Date of Production :',1,1);$pdf->Cell(60); $pdf->Cell(120,-7,date('d/M/Y',strtotime($pr_batch_start_date)),1,1);  
    $pdf->Ln(7);

    $pdf->Cell(60,7,'Batch Started On :',1,1);$pdf->Cell(60); $pdf->Cell(120,-7,$pr_batch_start_date,1,1);  
    $pdf->Ln(7);
    
    $pdf->Cell(60,7,'Batch End On :',1,1);$pdf->Cell(60); $pdf->Cell(120,-7,$pr_batch_end_date,1,1);  
    $pdf->Ln(7);
    
    $pdf->Cell(60,7,'Production Yield :',1,1);$pdf->Cell(60); $pdf->Cell(120,-7,$pr_production_yield." TON",1,1);  
    $pdf->Ln(7);

    $pdf->Cell(60,7,'Production in Charge :',1,1);$pdf->Cell(60); $pdf->Cell(120,-7,$pr_production_in_charge,1,1);  
    $pdf->Ln(7);

    $pdf->Cell(60,7,'Operators :',1,1);$pdf->Cell(60); $pdf->Cell(120,-7,$pr_operators,1,1);  
    $pdf->Ln(7);
    
    $pdf->Cell(60,18,'Additional Remarks :',1,1); 
    //$pdf->SetX(70);
    $pdf->SetY(98);
    $pdf->Cell(60); 
    $pdf->Rect(70,98,120,18);
    $pdf->MultiCell(120,5,$pr_remarks,0,'J',0,6);  
    $pdf->Ln(15);
    
    $pdf->SetFont('Times','BI',14);
    $pdf->Cell(0,8,'Ingrediants',0,1);

    $pdf->SetFont('Times','',10);
    $sql = "CALL sp_GetIngredient(".$pr_batch_id.")"; 
    $conn->next_result();
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
        $pdf->Cell(10,7,'SL#',1,1);$pdf->Cell(10);
        $pdf->Cell(120,-7,'Ingrediant',1,1);$pdf->Cell(130); 
        $pdf->Cell(20,7,'Qty',1,1);$pdf->Cell(150); 
        $pdf->Cell(30,-7,'Unit',1,1); 
        $pdf->Ln(7);

        $i = 1;
        while($row = $result->fetch_assoc()) 
        {
            $pdf->Cell(10,7,($i),1,1);$pdf->Cell(10);
            $pdf->Cell(120,-7,$row["ingredient_name"],1,1);$pdf->Cell(130); 
            $pdf->Cell(20,7,$row["qty"],1,1);$pdf->Cell(150);
            $pdf->Cell(30,-7,$row["unit"],1,1);
            $pdf->Ln(7);
            $i++; 
        }
    }    
    $pdf->Ln(15);

    //--------------CHART------------//    
    $pdf->SetFont('Arial','',10);
    $data_temp      = array();
    $data_power     = array();
    $data_weight    = array();
    
    $sql = "CALL sp_GetDeviceWiseTrend(" . $pr_kettle_id . "," . $pr_batch_id . ",'" . $pr_batch_start_date . "','". $pr_batch_end_date . "')";
    $conn->next_result();
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc())
    {
        $data_temp[]    = array("Log"=>$row['log'],"Temp"   =>  $row['temp']);
        $data_power[]   = array("Log"=>$row['log'],"Power"  =>  $row['power']);
        $data_weight[]  = array("Log"=>$row['log'],"Weight" =>  $row['wg']);        
    }
    $data1      = (array('Group 1' => array_column($data_temp, "Temp","Log")));
    $data2      = (array('Group 1' => array_column($data_power, "Power","Log")));
    $data3      = (array('Group 1' => array_column($data_weight, "Weight","Log")));

    $DegreeCentigrade = utf8_decode("°C");      
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(80); $pdf->Cell(0,10,'Temparature Trend ('.$DegreeCentigrade.')',0,1);
    if(!empty($data_temp))
    {
        $pdf->LineGraph(220,60, $data1,'HvB');
    }
    $pdf->AddPage();
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(40); $pdf->Cell(0,10,'Power Trend (Amp)',0,1);
    $pdf->Cell(130); $pdf->Cell(0,-10,'Weight Trend (Ton)',0,1);
    $pdf->Ln(10);

    if(!empty($data_power))
    {
        $pdf->LineGraph(110,40,$data2,'HvB');
    }
    $pdf->Cell(80); 

    if(!empty($data_weight))
    {
        $pdf->LineGraph(110,40,$data3,'HvB');
    }
    $pdf->Ln(50);
    
    //--------------CHART END ------------//

    $pdf->SetDrawColor(206, 209, 201);
    $pdf->SetFont('Times','BI',14);
    $pdf->Cell(0,10,'Log Details',0,1);
    $pdf->SetFont('Times','',10);
    
    $sql = "";
    switch($pr_kettle_id)
    {
        case 1:
            $sql = "SELECT 'COOKING KETTLE - 1' device_name, kettle_1_batch batch, timestamp, temp1 temp, pw1 pw, wg1 wg FROM master_logs WHERE timestamp BETWEEN '" . $pr_batch_start_date . "' AND '" . $pr_batch_end_date .  "';";
            break;

        case 2:
            $sql = "SELECT 'COOKING KETTLE - 2' device_name, kettle_2_batch batch, timestamp, temp2 temp, pw2 pw, wg2 wg FROM master_logs WHERE timestamp BETWEEN '" . $pr_batch_start_date . "' AND '" . $pr_batch_end_date .  "';";
            break;

        case 3:
            $sql = "SELECT 'COOKING KETTLE - 3' device_name, kettle_3_batch batch, timestamp, temp3 temp, pw3 pw, wg3 wg FROM master_logs WHERE timestamp BETWEEN '" . $pr_batch_start_date . "' AND '" . $pr_batch_end_date .  "';";
            break;  

        case 4:
            $sql = "SELECT 'COOLING KETTLE - 2' device_name, kettle_4_batch batch, timestamp, temp4 temp, 0 pw, 0 wg FROM master_logs WHERE timestamp BETWEEN '" . $pr_batch_start_date . "' AND '" . $pr_batch_end_date .  "';";
            break;

        case 5:
            $sql = "SELECT 'COOLING KETTLE - 3' device_name, kettle_5_batch batch, timestamp, temp5 temp, 0 pw, 0 wg FROM master_logs WHERE timestamp BETWEEN '" . $pr_batch_start_date . "' AND '" . $pr_batch_end_date .  "';";
            break;
            
        case 6:
            $sql = "SELECT 'HOLDING KETTLE' device_name, kettle_6_batch batch, timestamp, temp6 temp, 0 pw, 0 wg FROM master_logs WHERE timestamp BETWEEN '" . $pr_batch_start_date . "' AND '" . $pr_batch_end_date .  "';";
            break; 

        case 7:
            $sql = "SELECT 'BLENDING' device_name, kettle_7_batch batch, timestamp, temp7 temp, 0 pw, 0 wg FROM master_logs WHERE timestamp BETWEEN '" . $pr_batch_start_date . "' AND '" . $pr_batch_end_date .  "';";
            break;

        case 8:
            $sql = "SELECT 'BOILER' device_name, kettle_8_batch batch, timestamp, temp8 temp, 0 pw, 0 wg FROM master_logs WHERE timestamp BETWEEN '" . $pr_batch_start_date . "' AND '" . $pr_batch_end_date .  "';";
            break;
            
        case 9:
            $sql = "SELECT 'COOLING TOWER' device_name, kettle_9_batch batch, timestamp, temp9 temp, 0 pw, 0 wg FROM master_logs WHERE timestamp BETWEEN '" . $pr_batch_start_date . "' AND '" . $pr_batch_end_date .  "';";
            break;                 
    }
    //echo $sql;
    $conn->next_result();
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
        $pdf->Cell(10,7,'SL#',1,1);$pdf->Cell(10);
        $pdf->Cell(60,-7,'Device',1,1);$pdf->Cell(70); 
        $pdf->Cell(50,7,'Log Time',1,1);$pdf->Cell(120);
        $pdf->Cell(20,-7,'Temparature',1,1);$pdf->Cell(140); 
        $pdf->Cell(20,7,'Current',1,1);$pdf->Cell(160); 
        $pdf->Cell(20,-7,'Weight',1,1);
        $pdf->Ln(7);

        $i = 1;
        while($row = $result->fetch_assoc()) 
        { 
            $pdf->Cell(10,7,($i),1,1);$pdf->Cell(10);
            $pdf->Cell(60,-7,$row["device_name"],1,1);$pdf->Cell(70); 
            $pdf->Cell(50,7,$row["timestamp"],1,1);$pdf->Cell(120);
            $pdf->Cell(20,-7,$row["temp"]." ".$DegreeCentigrade,1,1);$pdf->Cell(140); 
            $pdf->Cell(20,7,$row["pw"]." Amp",1,1);$pdf->Cell(160); 
            $pdf->Cell(20,-7,$row["wg"]." Ton",1,1);
            $pdf->Ln(7);            

            $i++; 
        }
    } 
    //for($i=1;$i<=40;$i++)
    //    $pdf->Cell(0,10,'Printing line number '.$i,0,1);
    $pdf->Output();
?>