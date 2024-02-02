<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FileManager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="bootstrap-5.3.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <?php
    include_once 'functionsRemove.php';
    if (isset($_REQUEST['folderName'])) {
        $currentDir = $_REQUEST['folderName'];
    } else {
        $currentDir = __DIR__ . '/Files';
    }

    if (isset($_REQUEST['delete'])) {
        if (file_exists($_REQUEST['delete'])) {
            $dir = $_REQUEST['delete'];
            if (is_dir($_REQUEST['delete'])) {
                myRemoveDir($dir);
                //header("Location: $dir");
            } else {
                unlink($dir);
                //header("Location: $dir");
            }
        }
    }



    if (isset($_REQUEST['edit'])) {
        ?>
        <form action="" method="get">
            <input type="hidden" name="oldName" id="" value="<?php echo $_REQUEST['edit']; ?>">
            <input type="hidden" name="folderName" id="" value="<?php echo $currentDir; ?>">
            <input type="text" name="newName" id="" value="<?php echo $_REQUEST['nameFile']; ?>">
            <input type="submit" value="Edit">
        </form>
        <?php
    }

    if (isset($_REQUEST['oldName'])) {
        $oldNamePath = $_REQUEST['oldName'];
        $newNamePath = $currentDir . '/' . $_REQUEST['newName'];
        rename($oldNamePath, $newNamePath);
    }

    ?>
    <div class="flex-container">
        <?php

        $content = scandir($currentDir);
        //var_dump($content);
        foreach ($content as $file) {
            if ($file == '.' or $file == '..') {
                continue;
            }
            if (is_dir($currentDir . '/' . $file)) {
                ?>
                <div>

                    <a href="index.php?folderName=<?php echo $currentDir . '/' . $file; ?>"><i
                            style="font-size: 80px;color: #F8D775;" class="fa fa-folder"></i></a>
                    <a href="index.php?delete=<?php echo $currentDir . '/' . $file; ?>&folderName=<?php echo $currentDir; ?>"><i
                            style="color:black;" class="fa fa-trash"></i></a>
                    <a
                        href="index.php?edit=<?php echo $currentDir . '/' . $file; ?>&nameFile=<?php echo $file; ?>&folderName=<?php echo $currentDir; ?>"><i
                            style="color:black;" class="fa fa-edit"></i></a>
                    <br>
                    <p style="font-size: 16px; width:wrap;">
                        <?php echo $file; ?>
                    </p>
                </div>
                <?php
            } else {
                ?>
                <div>
                    <a href="view.php?fileName=<?php echo $currentDir . '/' . $file; ?>"><i
                            style="font-size: 80px;color: #F8D775;" class="fa fa-file"></i></a>
                    <a href="index.php?delete=<?php echo $currentDir . '/' . $file; ?>&folderName=<?php echo $currentDir; ?>"><i style="color:black;"
                            class="fa fa-trash"></i></a>
                    <a
                        href="index.php?edit=<?php echo $currentDir . '/' . $file; ?>&nameFile=<?php echo $file; ?>&folderName=<?php echo $currentDir; ?>"><i
                            style="color:black;" class="fa fa-edit"></i></a>
                    <br>
                    <p style="font-size: 16px; width:wrap;">
                        <?php echo $file; ?>
                    </p>
                </div>
                <?php
            }

        }
        ?>
    </div>


    <form id="form-upload" class="mt-4 w-50 bg-light p-4" style="position: fixed;bottom:10px ;">
        <label for="formFileLg" class="form-label">
            <h4>Upload File</h4>
        </label>
        <input class="form-control form-control-lg  " id="upload-input" type="file">
        <button type="submit" class="btn btn-primary btn-sm mt-1">
            <h5>Upload</h5>
        </button>
        <!-- <button id ="cancel" type="submit" class="btn btn-secondary btn-sm mt-1">
            <h5>Cancel Upload</h5>
        </button> -->
        <span id="message" class="text-danger mx-4 h5"></span>
        <div>
            <label for="formFileLg" class="form-label mt-4">
                <h6>Upload Progress</h6>
            </label>
        </div>
        <div id="myProgress" class="progress ">
            <div  class="progress-bar" role="progressbar" style="width: 0%;" 
                aria-valuemin="0" aria-valuemax="100">
                <span class="percent">0%</span>
            </div>
        </div>
    </form>

    <script>
        let form = document.getElementById('form-upload');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            sendUploadedFile();

        });

        function sendUploadedFile() {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'processor.php', true);
            let uploadedFile = document.getElementById('upload-input');
            //progressbar cod begin{
            let progrssBar = document.querySelector('#myProgress > .progress-bar');
            let percentText = progrssBar.querySelector('.percent');
            xhr.upload.addEventListener('progress', function(e){
                console.log(e);
                if(e.total >= 300){
                const darsad = e.lengthComputable ? (e.loaded / e.total) * 100 : 0;
                progrssBar.style.width = darsad.toFixed(2) + '%';
                percentText.textContent = darsad.toFixed(2) + '%';
                }
            });
            //progressbar code end}

            const formData = new FormData();
            const currentUrl = new URLSearchParams(window.location.search);
            let queryString = currentUrl.get('folderName');
            let jsonData = JSON.stringify(queryString);
            //console.log(jsonData);
            formData.append('file', uploadedFile.files[0]);
            formData.append('url', jsonData);


            xhr.onreadystatechange = function () {
                let message = document.getElementById('message');
                if (this.status == 200) {
                    //console.log(this.responseText);
                    message.textContent = this.responseText;
                    // setInterval(function () {
                        location.reload();
                    // }, 3000);
                } else if (this.status == 400) {
                    //console.log(this.responseText);
                    message.textContent = this.responseText;
                }
            }
            xhr.send(formData);
        }
        
        function uploadProgressBar(){
            
        }
    </script>

</body>

</html>