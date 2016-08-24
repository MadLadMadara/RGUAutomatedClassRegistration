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

    <title>RGU search</title>
    <!-- javascript  -->
    <script src="js/vendor/modernizr.js"></script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/parallax.js"></script>
    <script src="js/app.js"></script>
    <script src="js/home.js"></script>
       

    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/app.css" />
    <link rel="stylesheet" href="css/home.css" />

    
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
              <a class="current" href="search.php">Search</a>
              <a href="upload.php">Upload</a>
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
              <li><a href="index.php">logOut</a></li>
            </ul>
          </div>
        </div>    
      </div>
      <div class="row">
        <div id="content" class="columns small-12">
          
        </div>
      </div>      
    </div>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>      
</html>
