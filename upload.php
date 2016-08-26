<?php

  require_once('lib/masters/master.php');

  $outputmessage["display"] = "none";
  $outputmessage["message"] = "";

  if (session_status() == PHP_SESSION_NONE)
      session_start();
 
  if(!isset($_SESSION["user"]['id'])){
    kill();
    redirect("login.php");
    die();
  }


  if(!empty($_GET["message"])){
      $outputmessage["display"] = "block";
      $outputmessage["message"] = $_GET["message"];

  }
  

?>
<!doctype html>

<html class="no-js" lang="en">
  <head>
  <!-- meta data -->
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="description" content="main admin">
    <meta name="keywords" content="RGU">
    <meta name="author" content="Sam McRuvie">

    <title>RGU upload</title>
    <!-- javascript  -->
    <script src="js/vendor/modernizr.js"></script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/parallax.js"></script>
    <script src="js/app.js"></script>
    <script src="js/home.js"></script>
       

    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/app.css" />
    <link rel="stylesheet" href="css/upload.css" />

    
  </head>

  <body>
    <div id="main_body" class="columns small-12">
      <div id="nav_bar" class="columns small-12">
        <div class="row">
          <div id="logo" class="columns small-4 medium-3 large-2">
            logo
          </div>
          <div id="nav_links_medium_up" class="columns small-8 medium-9 large-10 nav_links show-for-medium-up">
            <nav class="breadcrumbs right">
              <a href="search.php">Search</a>
              <a class="current" href="upload.php">Upload</a>
              <a href="#">Settings</a>
              <a href="#">Admin options</a>
              <a href="index.php">logOut</a>
            </nav>
          </div>
          <div id="nav_links_small" class="columns small-8 medium-9 large-10 nav_links show-for-small-only">
            <a  data-dropdown="autoCloseExample" aria-controls="autoCloseExample" aria-expanded="false">
              dropdownbutton
                <!-- <img class="menu" src="assets/img/menu.png" alt="drop down menu"> -->
            </a>
            <ul  id="autoCloseExample" class="f-dropdown" data-dropdown-content tabindex="-1" aria-hidden="true" aria-autoclose="false" tabindex="-1">
              <li><a href="search.php">Search</a></li>
              <li><a href="upload.php">Upload</a></li>
              <li><a href="#">Settings</a></li>
              <li><a href="#">Admin options</a></li>
              <li><a href="#">logOut</a></li>
            </ul>
          </div>
        </div>    
      </div>
      <div class="row">
        <div id="content" class="columns small-12">
          <div id="usermesage" class="columns small-12">
            <div id="messagebox" style="display:<?php echo $outputmessage["display"]; ?>;" class="columns small-12 medium-6 medium-offset-3 large-4 large-offset-4 text-center simpleContentContainer">
              <h3>File Upload</h3>
              <p id="message"><?php echo $outputmessage["message"]; ?></p>
            </div>
          </div>

          <div id="fileupload" class="columns small-12">
            <div id="fileFormCt" class="small-12 medium-6 medium-offset-3 large-4 large-offset-4 text-center simpleContentContainer">
              <h4>sign-in sheet PDF upload</h4>
              <form action="lib/pdf_upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="file" ">
                <button type="submit">Submit</button>
              </form>
            </div>

          </div>
                    
        </div>
      </div>      
    </div>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>      
</html>
