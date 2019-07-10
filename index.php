<!DOCTYPE HTML>
<html>
<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>

    <!-- Latest compiled and minified Bootstrap CSS -->
   <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
      crossorigin="anonymous"
    />


    <!-- custom css -->
    <style>
    .m-r-1em{ margin-right:1em; }
    .m-b-1em{ margin-bottom:1em; }
    .m-l-1em{ margin-left:1em; }
    .mt0{ margin-top:0; }
    </style>

</head>
<body>

    <!-- container -->
    <div class="container">

        <div class="page-header">
            <h1>Read Products</h1>
        </div>

      <?php
include "functions.php";
read_item();
?>
    </div> <!-- end .container -->


<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
crossorigin="anonymous"></script>


<script type='text/javascript'>
// confirm record deletion
// function delete( id ){

//     var answer = confirm('Are you sure?');
//     if (answer){
//         // if user clicked ok,
//         // pass the id to delete.php and execute the delete query
//         window.location = 'php-beginner-crud-level-1/delete.php?id=' + id;
//     }
// }
</script>
<?php

// $action = isset($_GET['action']) ? $_GET['action'] : "";

// // if it was redirected from delete.php
// if ($action == 'deleted') {
//     echo "<div class='alert alert-success'>Record was deleted.</div>";
// }

?>

</body>
</html>
