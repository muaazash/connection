<?php

$serverName="";
$connectionInfo = array("Database"=>"connection");
$conn = sqlsrv_connect($serverName ,$connectionInfo);
if($conn){
    echo "Sucessful Connection <br/> ";

}else{
    echo "Failed Connection";
    die(print_r(sqlsrv_errors(),true));
}
?>