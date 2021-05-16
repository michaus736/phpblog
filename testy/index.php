<?php
//echo preg_match('/[1-9a-zA-Z"-"]{3,}/i',"aa^");
if(isset($_FILES['newphoto'])){
    $file=$_FILES['newphoto'];

    $fileName=$file['name'];
    $filePath=$file['tmp_name'];
    $fileSize=$file['size'];
    $fileError=$file['error'];
    $fileErrorMes= array();

    $fileNameExt=explode(".", $fileName);
    $fileExt=strtolower(end($fileNameExt));
    $photoExt=array("jpg","jpeg","png","gif");

    if(in_array($fileExt,$photoExt)){
        if($fileError==0){
            if($fileSize<=500000){
                $newFileName=uniqid('',true).".".$fileExt;
                $fileServerPath="photos/".$newFileName;

                if(move_uploaded_file($filePath, $fileServerPath)){
                    echo "123";
                }
                else{
                    $fileErrorMes['final']=1;
                }

            }else{
                $fileErrorMes['size']=1;
            }
        }else{
            $fileErrorMes['fileUploadError']=1;
        }
    }else{
        $fileErrorMes['ExtError']=1;
    }



}


unlink("photos/6080501863d2a3.77217066.png");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fotos</title>
</head>
<body>
    <form action="./index.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="newphoto">
        <input type="submit" value="send nudes">
    </form>
    <?php
    if(isset($_FILES["newphoto"])){
    echo json_encode($_FILES['newphoto'])."<br \>";
    if(isset($fileErrorMes)&&$fileErrorMes!=[]){
        echo json_encode($fileErrorMes);

    }
    else{
        echo $fileServerPath;

    }
} 
?>
</body>
</html>