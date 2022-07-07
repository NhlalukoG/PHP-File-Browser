<?php
	session_start();
    $_SESSION["lastpage"] = $_SERVER['REQUEST_URI'];
    // My Server	
	$currentdir = isset($_GET['dir']) ? $_GET['dir'] : "/";
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Server Bitch</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/animate.css"><!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    <style>
    .parallax {
        /* The image used */
        background-image: url("/img/gallery4.jpg");
        /* Set a specific height */
        /* Create the parallax scrolling effect */
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        -webkit-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        transform: rotate(90deg);
    }
    .overlay {
        position: fixed; /* Sit on top of the page content */
        /*display: none; /* Hidden by default */
        width: 100%; /* Full width (cover the whole page) */
        height: 100%; /* Full height (cover the whole page) */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(200,200,200,0.5); /* Black background with opacity */
        /*z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
        /*cursor: pointer; /* Add a pointer on hover */
    }
        
    </style>
</head>
<body class="container bg-primary">
    <header class="header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a <?php echo "href='http://95.179.181.97/projects/browser/'";?>>root</a></li>
                <?php
                    $path = explode("/",$currentdir);
                    foreach($path as $dir){
                        if(empty($dir) || $dir == "")
                        continue;
                        ?>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $dir?></li>
                    <?php }
                ?>
            </ol>
        </nav>
    </header>
    <!-- Container element -->
    <div class="container">
        <div class="nav justify-content-center mb-2">
            <div class="btn-group-md center" role="group" aria-label="Button group with nested dropdown">
                <a class="btn  bg-transparent" <?php echo "href='/api/browser/browser.php?dir=".dirname($currentdir)."'"?>>
                    <div class="d-flex flex-column">
                        <img src="icons/arrowrightgreen.png" width="32" height="32">
                        <small>back</small>
                    </div>
                    </a>
                <button type="button" class="btn  bg-transparent" onclick="location.reload()">
                    <div class="d-flex flex-column">
                        <img src="icons/refresh.png" width="32" height="32">
                        <small>refresh</small>
                    </div>
                </button>

                <form class="btn bg-transparent" action="/browser/upload.php" method="post" enctype="multipart/form-data" multiple="multiple">
                    <input style="display: none" multiple="mutiple" type="file" name="filesToUpload[]" id="filesToUpload">
                    <input type="hidden" name="saveTo" <?php echo "value='$currentdir'"?> >
                    <button type="button" class="btn  bg-transparent" onclick="document.getElementById('filesToUpload').click()">
                        <div class="d-flex flex-column">
                            <img src="icons/uploadcloud.png" width="32" height="32">
                            <small>upload</small>
                        </div>
                    </button>
                    <input style="display: none" id="submitBtn" type="submit" value="Upload Image" name="submit">
                </form>
                
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn bg-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="d-flex flex-column justify-content-center">
                            <img class="text-center" src="icons/website.png" width="32" height="32">
                            <small>Quick Links</small>
                        </div>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" <?php echo "href='http://95.179.181.97/projects/browser/?dir=/home'";?>>Home</a>
                    <a class="dropdown-item" <?php echo "href='http://95.179.181.97/projects/browser/?dir=/home/root'";?>>System Root</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
            // We make use of the browse api to get json files and stuff
            $data = file_get_contents("http://95.179.181.97/api/browser/getFileSystem.php?dir=$currentdir");
            $json = json_decode($data);
            // Let's display folders
            
            echo "<div class='container'>";
            foreach($json->folders as $folder=>$metadata){
                ?>
                <a class="input-group mb-3 w-100" <?php echo "href='/projects/browser/?dir=$currentdir"."/".$folder."'";?>>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><img class="text-center center" src="icons/directory.png" width="28" height="28"></span>
                    </div>
                    <p class="form-control bg-primary"><?php echo $folder;?></p>
                </a><?php
            }
            // Let's display files & add player if need be
            foreach($json->files as $file=>$metadata){
                $type = explode("/",$metadata->mime_type)[0];
                switch($type){
                    case "audio" :
                        // We will play the audio
                        ?>
                        <div class="list-group list-group-flush m-2">
                            <div class="list-group-item w-100">
                                <div class="input-group w-100">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><img class="center" src="icons/video2.png" width="26" height="26"></span>
                                    </div>
                                    <p class="form-control bg-primary" ><?php echo $file;?></p>
                                </div>
                            </div>
                            <audio class="list-group-item form-control mt-0" controls>
                                <source <?php echo "src='http://95.179.181.97:/$currentdir"."/".$file."' type='".$metadata->mime_type."'"?>>
                            </audio>
                        </div>
                        <?php
                        break;
                    case "video" :
                        // Video Pop Up
                        ?>
                        <div class="input-group mb-3 w-100">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><img class="center" src="icons/video2.png" width="26" height="26"></span>
                            </div>
                            <p class="form-control bg-primary" ><?php echo $file;?></p>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <img width="26" height="26" src="icons/play.png" onclick="playvideo(this)" <?php echo "data-value='http://95.179.181.97:/$currentdir"."/".$file."'";?>>
                                </span>
                            </div>
                        </div>
                        
                        <?php
                        break;
                    case "image" :?>
                        <div class="input-group mb-3 w-100">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><img class="text-center center" src="icons/image2.png" width="26" height="26"></span>
                            </div>
                            <p class="form-control bg-primary" ><?php echo $file;?></p>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <a class="input-group-append" id="delete" onclick="return confirmIt(this)" <?php echo "href=/api/browser/delete.php?file=$currentdir/$file"?>>
                                        <img width="26" height="26" src="icons/delete.png">
                                    </a>
                                </span>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <img class="input-group-append" width="26" height="26" src="icons/play.png" onclick="viewImage(this)" <?php echo "data-value='http://95.179.181.97:/$currentdir"."/".$file."'";?>>
                                </span>
                            </div>
                        </div> <?php
                        // Image Viewer Pop Up
                        break;
                        case "text" :?>
                            <div class="input-group mb-3 w-100">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><img class="text-center center" src="icons/txt.png" width="26" height="26"></span>
                                </div>
                                <a class="form-control bg-primary" <?php echo "href='http://95.179.181.97:/$currentdir"."/".$file."'";?>><?php echo $file;?></a>
                            </div> <?php
                            // Image Viewer Pop Up
                            break;
                    default:
                        // Download the file?>
                        <div class="input-group mb-3 w-100">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><img class="text-center center" src="icons/binary.png" width="26" height="26"></span>
                            </div>
                            <a class="form-control bg-primary" <?php echo "href='http://95.179.181.97:/$currentdir"."/".$file."'";?>><?php echo $file;?></a>
                        </div> <?php
                    }
            }
            echo "</div>";
        ?>
    </div>
    <!-- Video Player modal -->
    <!-- Button trigger modal -->
    <button id="btnDialog" style="display: none" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"?></button>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body row justify-content-center">
                    <video id="videoPlayer" class="col" controls>
                        <source id="theSource" src="http://95.179.181.97/api/home/gsthings/Videos/x.mp4" type="video/mp4">
                    </video>
                    <img class="center col" id="imageViewer" src="http://95.179.181.97/api/home/gsthings/Pictures/x.jpg">
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        // Open the modal
        $(function(){ // let all dom elements are loaded
            $('#exampleModal').on('hidden.bs.modal', function (e) {
                document.getElementById("videoPlayer").pause();
            });
        });
        function confirmIt(e){
            return confirm("Delete " + e.getAttribute("href"));
        }
        
        function playvideo(object){
            var vidP = document.getElementById("videoPlayer");
            vidP.autoplay = true;
            vidP.setAttribute("src",object.getAttribute("data-value"));
            vidP.style.display = "block";
            document.getElementById("imageViewer").style.display = "none";
            document.getElementById("btnDialog").click();
        }
        function viewImage(object){
            /*Some Quick UI Settings*/
            var imgV = document.getElementById("imageViewer");
            imgV.setAttribute("src",object.getAttribute("data-value"));
            imgV.style.display = "block";
            document.getElementById("videoPlayer").style.display = "none";
            document.getElementById("btnDialog").click();
        }
        document.getElementById('filesToUpload').onchange = function () {
            if($(this).get(0).files.length){
                $("#submitBtn").click();
            }
        };
    </script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/functions.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--script src="contactform/contactform.js"></script-->
</body>
