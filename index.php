<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Upload</title>
    <style>
        body{
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }
    </style>
</head>
<body>
    <h1>Secure Upload Model</h1>
    <input type="file" name="myFile" id="myFile">
    <button id="uploadFile">Upload File</button>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js"></script>
<script>

    function uploadFile() 
    {
        var myFile      = document.querySelector('#myFile');
        if(fileChecks(myFile)){

            var formData    = new FormData();
            formData.append("myFile" , myFile.files[0]);
            formData.append("action" , "uploadFile");
            
            axios.post('backend/upload.php',
            formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (res) {
                console.log(res.data);
            })
            .catch(function () {
                console.log('Something went wrong !');
            });

        }
    }


    function fileChecks(myFile) {

        if(myFile.value){
            var fileName        = myFile.value;
            var Ext             = fileName.split('.').pop().toLowerCase();
            var Size            = myFile.files[0].size;
            var allowedFormats  = ["jpeg", "jpg", "pdf", "png"];

            if(!(allowedFormats.indexOf(Ext) > -1)){

                alert("Enter a jpg / jpeg /pdf / png File");
                return false;    

            }

            if( ((Size/1024)/1024) > 2){

                alert("Your file should be less than 2 MB");
                return false;

            } 
            return true;
        }
        else{
            alert("Please select a file to upload");
            return false;
        }

    }

    document.querySelector('#uploadFile').addEventListener('click' , uploadFile);

</script>
</html>
