<?php
    session_start();
    $_SESSION["lastpage"] = $_SERVER['REQUEST_URI'];
    // My Server	
    $currentdir = isset($_GET['dir']) ? $_GET['dir'] : "/";
    $uri = $_SERVER['REQUEST_URI'];
    $root = explode("?",$uri)[0];

    $path = str_replace(pathinfo(__FILE__,PATHINFO_BASENAME),"",$uri);  // URI without file name

    require_once __DIR__."/api/getFileSystem.php";

    $host = "http://".$_SERVER['HTTP_HOST'];

?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP File Browser</title>
    <!-- Bootstrap core CSS -->
	<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- Custom styles for this template -->
	<link href="css/style.css" rel="stylesheet">
	<link href="css/modern-business.css" rel="stylesheet">
	<!--animation and stuff-->
	<link href="css/animate.css" rel="stylesheet">
	<link href="css/animate.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script> 
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 

    <!--Custom CSS & JS-->
    <link href="css/my.css" rel="stylesheet" >
</head>
<body class="container bg-light">
    <?php require_once "header.php"?>
    <header class="header mt-5" style="margin-top: 50px">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a <?php echo "href='$root'";?>>root</a></li>
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
        <div class="loading" id="loading" style="display: none">Loading&#8230;</div>
        <div class="nav justify-content-center mb-2">
            <div class="d-flex center" role="group" aria-label="Button group with nested dropdown">
                <a class="btn bg-transparent h-100" onclick="window.history.back()">
                    <div class="d-flex flex-column">
                        <img src="icons/arrowrightgreen.png" width="32" height="32">
                        <i class="bi bi-arrow-left-short"></i>
                        <small>back</small>
                    </div>
                </a>
                <button type="button" class="btn h-100 bg-transparent" onclick="location.reload()">
                    <div class="d-flex flex-column h-100">
                        <img src="icons/refresh.png" width="32" height="32">
                        <small>refresh</small>
                    </div>
                </button>

                <form id="uploadForm" class="btn bg-transparent" action="<?php echo $path?>/upload.php" method="post" enctype="multipart/form-data" multiple="multiple">
                    <input style="display: none" multiple="mutiple" type="file" name="filesToUpload[]" id="filesToUpload">
                    <input type="hidden" name="saveTo" <?php echo "value='$currentdir'"?> >
                    <?php if(!isset($_SESSION['loggedin'])) {?>
                    <button type="button" class="border-0 bg-transparent" onclick="document.getElementById('filesToUpload').click()">
                        <div class="d-flex flex-column">
                            <img src="icons/uploadcloud.png" width="32" height="32">
                            <small>upload</small>
                        </div>
                    </button>
                    <?php } ?>
                    <input style="display: none" id="submitBtn" type="submit" value="Upload Image" name="submit">
                </form>
            </div>
        </div>
        <?php
            // Let's get the file with results
            require_once __DIR__."/api/getFileSystem.php";
            $data = getFileSystem($currentdir);
            // We make use of the browse api to get json files and stuff
            $json = json_decode($data);
            // Let's display folders
            echo "<div class='container'>";
            foreach($json->folders as $folder=>$metadata){
                ?>
                <a class="input-group mb-3 w-100" <?php echo "href='$host?dir=$currentdir"."/".$folder."'";?>>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><img class="text-center center" src="icons/directory.png" width="22" height="22"></span>
                    </div>
                    <p class="form-control bg-light.bg-gradient"><?php echo $folder;?></p>
                </a><?php
            }
            // Let's display files & add player if need be
            foreach($json->files as $file=>$metadata){
                $type = explode("/",$metadata->mime_type)[0];
                switch($type){
                    case "audio" :
                        // We will play the audio
                        ?>
                        <div class="list-group list-group-flush mb-3 w-100">
                            <div class="list-group-item w-100">
                                <div class="input-group w-100">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><img class="center" src="icons/video2.png" width="22" height="22"></span>
                                    </div>
                                    <a class="form-control text-truncate bg-light.bg-gradient" ><?php echo $file;?></a>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <img class="input-group-append delete" <?php echo "data-value='/".$currentdir."/".$file."'" ?> width="22" height="22" src="icons/delete.png">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <audio class="list-group-item form-control mt-0" controls>
                                <source <?php echo "src='".$host.":/$currentdir"."/".$file."' type='".$metadata->mime_type."'"?>>
                            </audio>
                        </div>
                        <?php
                        break;
                    case "video" :
                        // Video Pop Up
                        ?>
                        <div class="input-group mb-3 w-100">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><img class="center" src="icons/video2.png" width="22" height="22"></span>
                            </div>
                            <a class="form-control text-truncate bg-light.bg-gradient" ><?php echo $file;?></a>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <img class="input-group-append delete" <?php echo "data-value='/".$currentdir."/".$file."'" ?> width="22" height="22" src="icons/delete.png">
                                </span>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <img width="22" height="22" src="icons/play.png" onclick="playvideo(this)" <?php echo "data-type='".$metadata->mime_type."' data-value='$host:/$currentdir"."/".$file."'";?>>
                                </span>
                            </div>
                        </div>
                        
                        <?php
                        break;
                    case "image" :?>
                        <div class="input-group mb-3 w-100">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><img class="text-center center" src="icons/image2.png" width="22" height="22"></span>
                            </div>
                            <a class="form-control bg-light.bg-gradient">
                                <p class="text-truncate" ><?php echo $file;?></p>
                            </a>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <img class="input-group-append delete" <?php echo "data-value='/".$currentdir."/".$file."'" ?> width="22" height="22" src="icons/delete.png">
                                </span>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <img class="input-group-append" width="22" height="22" src="icons/play.png" onclick="viewImage(this)" <?php echo "data-type='".$metadata->mime_type."' data-value='$host:/$currentdir"."/".$file."'";?>>
                                </span>
                            </div>
                        </div> <?php
                        // Image Viewer Pop Up
                        break;
                        case "text" :?>
                            <div class="input-group mb-3 w-100">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><img class="text-center center" src="icons/txt.png" width="22" height="22"></span>
                                </div>
                                <a class="form-control text-truncate bg-light.bg-gradient"><?php echo $file;?></a>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <img class="input-group-append delete" <?php echo "data-value='/".$currentdir."/".$file."'" ?> width="22" height="22" src="icons/delete.png">
                                    </span>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <img class="input-group-append" width="22" height="22" src="icons/play.png" onclick="viewEmbed(this)" <?php echo "data-type='".$metadata->mime_type."' data-value='$host:/$currentdir"."/".$file."'";?>>
                                    </span>
                                </div>
                            </div> <?php
                            break;
                    default:
                        // Download the file?>
                        <div class="input-group mb-3 w-100">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><img class="text-center center" src="icons/binary.png" width="22" height="22"></span>
                            </div>
                            <a class="form-control text-truncate bg-light.bg-gradient" <?php echo "href='$host:/$currentdir"."/".$file."'";?>><?php echo $file;?></a>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <img class="input-group-append delete" <?php echo "data-value='/".$currentdir."/".$file."'" ?> width="22" height="22" src="icons/delete.png">
                                </span>
                            </div>
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
                        <source id="theSource" src="" type="">
                    </video>
                    <img class="center col" id="imageViewer" src="">
                    <embed id="embed" src="/upload/mirrorlist_africa_world" type="text/plain" width="500" height="500">
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
            $(".delete").click(function(e) {
                document.getElementById("loading").style.display = "block";
                var u = "<?php echo $path?>/delete.php?file=" + e["target"].getAttribute("data-value");
                $.ajax({
                    type: "GET",
                    url: u,
                    success: function(result) {
                        if(result == "success"){
                            location.reload()
                        }else{
                            alert("Failed deleting file");
                            document.getElementById("loading").style.display = "none";
                        }
                    },
                    error: function(result) {
                        alert("Error occured processing request. Error " + result["status"]);
                        document.getElementById("loading").style.display = "none";
                    }
                });
            });
            //bind the form's submit to ajaxForm
            $('#uploadForm').submit( function( e ) {
                document.getElementById("loading").style.display = "block";
                $.ajax({
                    url: this.getAttribute("action"),
                    type: 'POST',
                    data: new FormData( this ),
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data == "success"){
                            location.reload()
                        }else{
                            alert("Failed uploading file");
                            document.getElementById("loading").style.display = "none";
                        }
                    },
                    error: function(data){
                        alert(data["status"]);
                        document.getElementById("loading").style.display = "none";
                    }
                });
                e.preventDefault();
            } ); 
        });
        function confirmIt(e){
            return confirm("Delete " + e.getAttribute("href"));
        }
        
        function playvideo(object){
            var vidP = document.getElementById("videoPlayer");
            vidP.autoplay = true;
            vidP.setAttribute("src",object.getAttribute("data-value"));
            vidP.setAttribute("type",object.getAttribute("data-type"));
            vidP.style.display = "block";
            document.getElementById("imageViewer").style.display = "none";
            document.getElementById("embed").style.display = "none";
            document.getElementById("btnDialog").click();
        }
        function viewImage(object){
            /*Some Quick UI Settings*/
            var imgV = document.getElementById("imageViewer");
            imgV.setAttribute("src",object.getAttribute("data-value"));
            imgV.setAttribute("type",object.getAttribute("data-type"));
            imgV.style.display = "block";
            document.getElementById("videoPlayer").style.display = "none";
            document.getElementById("embed").style.display = "none";
            document.getElementById("btnDialog").click();
        }
        function viewEmbed(object){
            /*Some Quick UI Settings*/
            var embed = document.getElementById("embed");
            embed.setAttribute("src",object.getAttribute("data-value"));
            embed.setAttribute("type",object.getAttribute("data-type"));
            embed.style.display = "block";
            document.getElementById("videoPlayer").style.display = "none";
            document.getElementById("imageViewer").style.display = "none";
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
	<!--script src="contactform/contactform.js"></script-->
</body>
