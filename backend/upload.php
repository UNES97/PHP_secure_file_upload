<?php

function genUid($l=5){
    return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 10, $l);
}

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (0 === error_reporting()) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});


if(isset($_POST['action'])){

    $action = $_POST['action'];
    if($action == 'uploadFile'){
        try {
            //--General Configs--//
            $allowedTypes   = ['image/jpg', 'image/png', 'image/jpeg', 'application/pdf']; 
            $maxSize        = 2097152;
            $uploadDir      = '../uploads/';

            //--File Infos--//
            $myFile         = $_FILES['myFile'];
            $typeFile       = $_FILES['myFile']['type'];
            $tmpFile        = $_FILES['myFile']['tmp_name'];
            $nameFile       = pathinfo($_FILES['myFile']['name'], PATHINFO_FILENAME);
            $extFile        = pathinfo($_FILES['myFile']['name'], PATHINFO_EXTENSION);  
            $mimeFile       = mime_content_type($tmpFile);
            $sizeFile       = $_FILES['myFile']['size'];

            //--Check File Size--//
            if($sizeFile > $maxSize){
                $errors[]   = 'File size must be less than 2 MB';
            }

            //--Check File Extension--//
            if(in_array($typeFile , $allowedTypes) === false){
                $errors[]   = "Extension not allowed, Please choose a JPEG , PNG or PDF file.";
            }
            
            //--Check File Mime--//
            if(in_array($mimeFile , $allowedTypes) === false){
                $errors[]   = "File type is NOT allowed.";
            }

            if( empty($errors) == true ){

                $newName        = genUid(rand(0,30));
                $newExt         = explode('/' , $mimeFile)[1];
                $uploadFile     = $uploadDir.$newName.'.'.$extFile;

                if (move_uploaded_file($tmpFile , $uploadFile)) {
                    chmod($uploadFile , 0644);
                    $Res = [
                        'statusCode'    => 201,
                        'data'          => 'File uploaded successfully.',
                    ];
                }
                else{
                    $Res = [
                        'statusCode'    => 500,
                        'data'          => 'File upload failed !',
                    ];
                }
            }
            else{
                $Res = [
                    'statusCode'    => 500,
                    'data'          => $errors,
                ];
            }
        } catch (ErrorException $e) {
            $Res = [
                'statusCode'    => 500,
                'data'          => $e->getMessage(),
            ];
        }
    }
    else{
        $Res = [
            'statusCode'    => 400,
            'data'          => 'Action not found',
        ];
    }
}
else{
    $Res = [
        'statusCode'    => 405,
        'data'          => 'Action not allowed',
    ];
}

echo json_encode($Res);
