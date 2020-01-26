<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" crossorigin="anonymous">

    <title>ImageSplitter</title>
  </head>
  <body>
    <header class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
      <h5 class="my-0 mr-md-auto font-weight-normal">ImageSplitter</h5>
    </header>

    <div class="container">
      <div class="row">
        <div class="card-deck mb-3 text-center col-4">
          <div class="card mb-4 shadow-sm">
            <div class="card-header">
              <h4 class="my-0 font-weight-normal">Upload Image</h4>
            </div>
            <div class="card-body">
              <p>Upload an image or several images in <strong>JPG or PNG</strong> format.</p>
              <p>Each image <strong>must</strong> be an A4 paper dimension. Images would be split into image tiles of equal sizes.</p>
              <p>To check application results click <a target="_blank" href="/?action=scan&session=<?php echo App::getSessionId(); ?>">here</a>.</p>
              <p class="small">Note: Refreshing the page or opening it in a new tab will start a new session.</p>
            </div>
          </div>
        </div>
        <div class="col-8">
          <form action="/?action=upload&session=<?php echo App::getSessionId(); ?>"
            class="dropzone"
            id="images-upload"
          ></form>
        </div>
      </div>
      <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <div class="row">
          <div class="col-12 col-md text-right">
            <small class="d-block mb-3 text-muted">&copy; 2020</small>
          </div>
        </div>
      </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone-amd-module.min.js" crossorigin="anonymous"></script>
  </body>
</html>
