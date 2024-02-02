<?php

if (empty($_FILES)) {
    http_response_code(400);
    echo "Please Browse Your File";
    exit;
}
$fileUploded = $_FILES['file'];
$fileTmpPath = $fileUploded['tmp_name'];
$fileName = $fileUploded['name'];

if ($_POST['url'] == 'null') {
    $imageSavePath = __DIR__ . '/Files/' . $fileName;
    if (file_exists($imageSavePath)) {
        http_response_code(400);
        echo "Your File Is Uploded Before";
    } else {
        move_uploaded_file($fileTmpPath, $imageSavePath);
        echo "Your File Is Uploded";
    }

} else {
    $dirPath = json_decode($_POST['url']);
    $imageSavePath = $dirPath . '/' . $fileName;
    if (file_exists($imageSavePath)) {
        http_response_code(400);
        echo "Your File Is Uploded Before";
    } else {
        move_uploaded_file($fileTmpPath, $imageSavePath);
        echo "Your File Is Uploded";
    }
}


?>