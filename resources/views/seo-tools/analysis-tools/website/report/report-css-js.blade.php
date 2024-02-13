<?php 

$path = asset('assets/pdf/css/bootstrap.css');
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";

$path2 = asset('assets/pdf/css/component.css');

$type = pathinfo($path2, PATHINFO_EXTENSION);
$data = file_get_contents($path2);
$data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";

$path3 = asset('assets/pdf/css/custom.css');
$type = pathinfo($path3, PATHINFO_EXTENSION);
$data = file_get_contents($path3);
$data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";
?>
