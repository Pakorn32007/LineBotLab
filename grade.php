<?php   https://997a8de22484.ngrok.io//LIneBot102/grade.php
//error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
$date = date("Y-m-d");
$time = date("H:i:s");
$json = file_get_contents('php://input');
$request = json_decode($json, true);
$input = fopen("log_json.txt", "w") or die("Unable to open file!");
fwrite($input,$json);
fclose($input);

function processMessage($update) {
   
		  if($update["queryResult"]["action"] == "school.school-custom"){
            $id =  $update["queryResult"]["parameters"]["number"];
            $conn = mysqli_connect("localhost", "root", "12345678", "dialogflow"); 
            $sql = "SELECT pid,name,grade FROM school  where pid ='$id'"; 
            $result = mysqli_query($conn, $sql);
            //$count_row = mysqli_num_rows($result);
                while($row =mysqli_fetch_array($result)) {
                    $pid = $row["pid"];
		            $name = $row["name"];
		            $grade = $row["grade"];
		            $ppp = "ผลการเรียนของ ".$name.' หมายเลขประจำตัว  '.$id.' เกรดเฉลี่ยเท่ากับ '.$grade ;
		}
            sendMessage(array(
                "source" => $update["responseId"],
                "fulfillmentText"=>$ppp,
                "payload" => array(
                "items"=>[
                    array(
                        "simpleResponse"=>
                    array(
                        "textToSpeech"=>$ppp
                         )
                    )
                ],
                ),
           
        ));
		
         }else{
            sendMessage(array(
                "source" => $update["responseId"],
                "fulfillmentText"=>"ไม่ได้อยู่ใน intent ใดใด",
                "payload" => array(
                    "items"=>[
                        array(
                             "simpleResponse"=>
                            array(
                                "textToSpeech"=>"Bad request"
                                 )
                            )
                            ],
                                ),
           
        )  );
        
             }  
}
 
function sendMessage($parameters) {
    echo json_encode($parameters);
}
 
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);
if (isset($update["queryResult"]["action"])) {
    processMessage($update);
  
}else{
     sendMessage(array(
            "source" => $update["responseId"],
            "fulfillmentText"=>"Hello from webhook",
            "payload" => array(
                "items"=>[
                    array(
                        "simpleResponse"=>
                    array(
                        "textToSpeech"=>"Bad request"
                         )
                    )
                ],
                ),
           
        ));
}

 
?>
