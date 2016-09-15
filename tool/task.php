<?php 
$ini = parse_ini_file("config.ini");
date_default_timezone_set('Asia/Tokyo');
include_once '/home/hirai/aliyun-openapi-php-sdk/aliyun-php-sdk-core/Config.php'; 
use Ecs\Request\V20140526 as Ecs; 

$iClientProfile = DefaultProfile::getProfile("cn-hongkong",$_SERVER['ALIYUN_KEY'],$_SERVER['ALIYUN_SECRET']);
$client = new DefaultAcsClient($iClientProfile); 

//getting the number of instances before creating an instance
$request = new Ecs\DescribeInstancesRequest();
$request->setMethod("GET");
$response = $client->getAcsResponse($request);


$initialTotal = $response->TotalCount;

if($initialTotal > 5){
echo "error: The number of instances are too many\n";
exit();
}

date_default_timezone_set('Asia/Tokyo');
$date = date("YmdHi");

// Creating the instance
try{

$request = new Ecs\CreateInstanceRequest();
                                                                                                                       
$imageId = $ini["ImageID"];
//$imageId = "win2012_64_datactr_r2_cn_40G_alibase_20160622.vhd";
$request->setImageId($imageId);
$regionId = $ini["RegionID"];
$request->setRegionId($regionId);
$zoneId = ["ZoneID"];
$request->setZoneId($zoneId);
$instanceType = $ini["InstanceType"];
$request->setInstanceType($instanceType);
$name = $ini["Name"];
$request->setInstanceName($name);
$securityId = $ini["SecurityID"];
$request->setSecurityGroupId($securityId);
$diskCategory = $ini["DiskCategory"];
$request->setSystemDiskCategory($diskCategory);
$opt = $ini["IoOptimized"];
$request->setIoOptimized($opt); 
$request->setMethod("GET");
 
 
$response = $client->getAcsResponse($request);
//$response = $client->doAction($request); 
}catch(Exception $e){

// When cannot create the instance
$csv = $date . ",,NG,,NG,,NG,,NG,,\n";
echo "error: cannot create the instance\n";                                                        
file_put_contents($ini["Output_Path"],$csv,FILE_APPEND);
//file_put_contents("/var/www/html/task2.csv",$csv,FILE_APPEND);
exit();
}

//getting an instanceID
$getInstanceId = $response->InstanceId;
$csv = $date . "," . $getInstanceId . "," . "OK";


$start = microtime(true);


// monitoring the state
while(true){
        $request = new Ecs\DescribeInstancesRequest();
        $request->setMethod("GET");
        $response = $client->getAcsResponse($request);
	if($response->TotalCount == $initialTotal + 1){
		break;
	}
}



$end = microtime(true);

// Showing the information of the instance

$request = new Ecs\DescribeInstancesRequest(); 
$request->setMethod("GET");  
$response = $client->getAcsResponse($request);

$total = $response->TotalCount;
$level_1 = $response->Instances;
$level_2 = $level_1->Instance;

for($count = 0; $count < $total; $count++){
	$level_3 = $level_2[$count];
	$instanceId = $level_3->InstanceId;
	if($instanceId == $getInstanceId){
     		break;
	}
	if($count == $total - 1){
		echo "error: cannot find the instance\n";
		exit();
	}
}

echo "\nInstance Information------------------------------\n" . "InstanceID: ". $instanceId . "\n" .
"RegionID: " . $level_3->RegionId . "\n" . "ZoneID: " . $level_3->ZoneId . "\n" .
"ImageID: " . $level_3->ImageId . "\n" . "CPU: " . $level_3->Cpu . "\n" .
"Memory: " . $level_3->Memory . "\n" . 
"--------------------------------------------------";


$instanceInfo = $level_3->RegionId . "," . $level_3->ZoneId . "," .$level_3->ImageId . "," .
                $level_3->Cpu . "," . $level_3->Memory;
file_put_contents("/var/www/html/tool/instanceInfo.csv",$instanceInfo);

// Showing the time of creating the instance
$calculatedTime = $end - $start;
echo "\nCreating time: ".$calculatedTime."sec\n";
$csv = $csv . "," . $calculatedTime;

//Calculated total time
$totalTime = $calculatedTime;

// Starting the instance
$request = new Ecs\StartInstanceRequest(); 
$request->setMethod("GET"); 
//instance ID
$instanceId = $getInstanceId; 
$request->setInstanceId($instanceId); 
$response = $client->getAcsResponse($request); 
// Starting the instance

$start = microtime(true);

// monitoring the state
while(true){
	$request = new Ecs\DescribeInstancesRequest();
	$request->setMethod("GET");
	$response = $client->getAcsResponse($request);
                                                                                                                 
	$level_1 = $response->Instances;
	$level_2 = $level_1->Instance;
	$level_3 = $level_2[$count];
                                                                                                             
	if($level_3->Status == "Running"){
		break;
	}

	//when cannot start the instance
	if($level_3->Status == "Stopped"){
		$csv = $csv . ",NG,,NG,,NG,,\n";
		file_put_contents($ini["Output_Path"],$csv,FILE_APPEND);
		//file_put_contents("/var/www/html/task2.csv",$csv,FILE_APPEND);
    echo "error: cannot start the instance\n";
    exit();
	}
	
}

$end = microtime(true);

//Showing the status of the instance (wanna show only one instance)
echo "\nInstace status: ";
print_r($level_3->Status);
echo "\n";

//Showing the time of starting
$calculatedTime = $end - $start;
echo "Starting time: ".$calculatedTime."sec\n";

$csv = $csv . ",OK,". $calculatedTime;

//Calculated total time
$totalTime = $totalTime + $calculatedTime;

// Stoping the instance  
$request = new Ecs\StopInstanceRequest(); 
$request->setMethod("GET");  
$request->setInstanceId($getInstanceId);
$response = $client->getAcsResponse($request);  

$start = microtime(true);

//monitoring the status
while(true){
        $request = new Ecs\DescribeInstancesRequest();
        $request->setMethod("GET");
        $response = $client->getAcsResponse($request);
                                                                                                                 
        $level_1 = $response->Instances;
        $level_2 = $level_1->Instance;
        $level_3 = $level_2[$count];
                                                                                                                 
        if($level_3->Status == "Stopped"){
                break;
        }

	//when cannot stop the instance
	if($level_3->Status == "Running"){
        	$csv = $csv . ",NG,,NG,,\n";
        	file_put_contents($ini["Output_Path"],$csv,FILE_APPEND);
        	//file_put_contents("/var/www/html/task2.csv",$csv,FILE_APPEND);
          exit();
	}
}

$end = microtime(true);
                                                                                                                 
//Showing the status of the instance
echo "\nInstace status: ";
print_r($level_3->Status);
echo "\n";

//Showing the time of stopping
$calculatedTime = $end - $start;
echo "Stopping time: ".$calculatedTime."sec\n";

$csv = $csv . ",OK,". $calculatedTime;

//Calculated total time
$totalTime = $totalTime + $calculatedTime;

// removing the instance
try{
$request = new Ecs\DeleteInstanceRequest(); 
$request->setMethod("GET");  
$request->setInstanceId($getInstanceId);  
$response = $client->getAcsResponse($request);
}catch(Exception $e){
                                                                                                                       
// When cannot remove the instance
$csv = $csv . ",NG,,\n";
echo "error: cannot remove the instance\n";
file_put_contents($ini["Output_Path"],$c,FILE_APPEND);
//file_put_contents("/var/www/html/task2.csv",$csv,FILE_APPEND);
exit();
}

$start = microtime(true);


// monitoring the status
while(true){
        $request = new Ecs\DescribeInstancesRequest();
        $request->setMethod("GET");
        $response = $client->getAcsResponse($request);
        if($response->TotalCount == $initialTotal){
                break;
        }
}



$end = microtime(true);                                                                                                        

/*
//Showing the status of the instance
$request = new Ecs\DescribeInstanceStatusRequest();
$request->setMethod("GET"); 
$zoneId = "cn-hongkong-b"; 
$request->setZoneId($zoneId);
$response = $client->getAcsResponse($request);
echo "\n";
print_r($response);
*/

$calculatedTime = $end - $start;
echo "\nfinished removing the instance(instanceID: " . $getInstanceId . ")\n" . 
     "Removeing time: " . $calculatedTime . "sec" . "\n\n";

//Calculated total time
$totalTime = $totalTime + $calculatedTime;

$csv = $csv . ",OK," . $calculatedTime . "," . $totalTime . "\n";

file_put_contents($ini["Output_Path"],$csv,FILE_APPEND);
//file_put_contents("/var/www/html/task2.csv",$csv,FILE_APPEND);
