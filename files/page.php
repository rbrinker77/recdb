<!doctype html>
<? $title = $_GET['title']; ?>
<html>
    <head>
        <link href="/CSS/styles.css" rel="stylesheet" type="text/css">
        <link href="/CSS/dropzone.css" rel="stylesheet" type="text/css">
        <script src="/JS/dropzone.js" type="text/javascript"></script>
    </head>
    <body >
        <div class="pageDiv"><h1>FOR THE ALBUM <? echo $title; ?></h1></p>
        <br />
        <div class="container" >
            <div class='content'>
            <form action="uploads.php?title=<? echo $title; ?>" class="dropzone" id="dropzonewidget" method="post" enctype="multipart/form-data">
            </form>
            </div>
        </div>
        <br /><br />
        <div class="pageDiv">
            <button type="button" class="smallPurple"><a class="indexes" href="index.php">Back</a></button>
        </div>
    </body>
</html>
