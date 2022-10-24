<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./../Assets/Jquery/Jquery.js"></script>
    <title>Document</title>
</head>
<body>
    <!-- <video class="media" src=""></video> -->
    <div class="border-gradient">
        <h1>Well</h1>
    </div>
    <style>
        .border-gradient{
            clip-path: polygon(16px 0,100% 0,100% calc(100% - 16px),calc(100% - 16px) 100%,0 100%,0 16px);
            height: 128px;
            background-color: red;
            filter: drop-shadow(0 0 2px var(--gx-highlight-color));
        }
    </style>
</body>
<!-- <script>
$(document).ready( () => {
    // console.log("<?php // echo $movie_path; ?>")
    // console.log("Fetching")
    // const result = await fetch("<?php // echo $movie_path; ?>")
    // console.log("Creating blob")
    // const blob = await result.blob()
    // console.log("done")
    // alert(blob)
    // console.log(blob)
    var xhr = new XMLHttpRequest();
    xhr.responseType = 'blob';
    xhr.onload = function() {
    
    var reader = new FileReader();
    
    reader.onloadend = function() {
    
        var byteCharacters = atob(reader.result.slice(reader.result.indexOf(',') + 1));
        
        var byteNumbers = new Array(byteCharacters.length);
        for (var i = 0; i < byteCharacters.length; i++) {
        
        byteNumbers[i] = byteCharacters.charCodeAt(i);
        
        }
        var byteArray = new Uint8Array(byteNumbers);
        var blob = new Blob([byteArray], {type: 'video/ogg'});
        var url = URL.createObjectURL(blob);
        
        console.log(url)
        $(".media").attr("src",url.toString());
        $(".media").each(function() {
            this.play()
        })
        
    }
    
    reader.readAsDataURL(xhr.response);
    
    };
    xhr.open('GET', './redditsave.com_sus-ce91y0qtuyn71.mp4');
    xhr.send();
})
</script> -->
</html>