<?php 

require_once('core/library.php');
$call = new chat;

header("Content-type:application/json");
echo json_encode($call->dashboardExec());


?>