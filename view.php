<?php
$filePath=$_REQUEST['fileName'];
$fileHandler = fopen($filePath,'r');
while(!feof($fileHandler)){
$line = fgets($fileHandler);
echo "$line" . "<br>";
}

?>