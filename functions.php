<?php

function create_item($post)
{
    include 'config/database.php';

    if ($post) {

        try {
            // insert query
            $query = "INSERT INTO products
            SET name=:name, description=:description,
                price=:price, image=:image, created=:created";

            // prepare query for execution
            $stmt = $con->prepare($query);

            // posted values
            $name = htmlspecialchars(strip_tags($_POST['name']));
            $description = htmlspecialchars(strip_tags($_POST['description']));
            $price = htmlspecialchars(strip_tags($_POST['price']));
            // new 'image' field
            $image = !empty($_FILES["image"]["name"])
            ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
            : "";
            $image = htmlspecialchars(strip_tags($image));

            if (empty($name) && empty($description) && empty($price)) {
                echo "<div class='alert alert-danger'>please fill the form with right info</div>";
            } else {
                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':image', $image);

                // specify when this record was inserted to the database
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);

                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                    // now, if image is not empty, try to upload the image
                    if ($image) {
                        // sha1_file() function is used to make a unique file name
                        $target_directory = "uploads/";
                        $target_file = $target_directory . $image;
                        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                        // error message is empty
                        $file_upload_error_messages = "";
                        // make sure that file is a real image
                        $check = getimagesize($_FILES["image"]["tmp_name"]);
                        if ($check !== false) {
                            // submitted file is an image
                        } else {
                            $file_upload_error_messages .= "<div>Submitted file is not an image.</div>";
                        }
                        // make sure certain file types are allowed
                        $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                        if (!in_array($file_type, $allowed_file_types)) {
                            $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                        }
                        // make sure file does not exist
                        if (file_exists($target_file)) {
                            $file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
                        }
                        // make sure submitted file is not too large, can't be larger than 1 MB
                        if ($_FILES['image']['size'] > (1024000)) {
                            $file_upload_error_messages .= "<div>Image must be less than 1 MB in size.</div>";
                        }
                        // make sure the 'uploads' folder exists
                        // if not, create it
                        if (!is_dir($target_directory)) {
                            mkdir($target_directory, 0777, true);
                        }
                        // if $file_upload_error_messages is still empty
                        if (empty($file_upload_error_messages)) {
                            // it means there are no errors, so try to upload the file
                            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                // it means photo was uploaded
                            } else {
                                echo "<div class='alert alert-danger'>";
                                echo "<div>Unable to upload photo.</div>";
                                echo "<div>Update the record to upload photo.</div>";
                                echo "</div>";
                            }
                        }

                        // if $file_upload_error_messages is NOT empty
                        else {
                            // it means there are some errors, so show them to user
                            echo "<div class='alert alert-danger'>";
                            echo "<div>{$file_upload_error_messages}</div>";
                            echo "<div>Update the record to upload photo.</div>";
                            echo "</div>";
                        }

                    }

                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            }
        }

        // show error
         catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
    }

}

function read_item()
{
    include 'config/database.php';
// PAGINATION VARIABLES
    // page is the current page, if there's nothing set, default is page 1
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

// set records or rows of data per page
    $records_per_page = 4;

// calculate for the query LIMIT clause
    $from_record_num = ($records_per_page * $page) - $records_per_page;

// delete message prompt will be here

// select all data
    // $query = "SELECT id, name, description, price FROM products ORDER BY id ASC";
    // $stmt = $con->prepare($query);
    // $stmt->execute();
    // select data for current page
    $query = "SELECT id, name, description, price FROM products ORDER BY id DESC
    LIMIT :from_record_num, :records_per_page";

    $stmt = $con->prepare($query);
    $stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
    $stmt->execute();

// this is how to get number of rows returned
    $num = $stmt->rowCount();

// link to create record form
    echo "<a href='php-beginner-crud-level-1/create.php' class='btn btn-primary m-b-1em'>Create New Product</a>";

//check if more than 0 record found
    if ($num > 0) {

        echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

        //creating our table heading
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Name</th>";
        echo "<th>Description</th>";
        echo "<th>Price</th>";
        echo "<th>Action</th>";
        echo "</tr>";

        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['firstname'] to
            // just $firstname only
            extract($row);

            // creating new table row per record
            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$name}</td>";
            echo "<td>{$description}</td>";
            echo "<td>&#36;{$price}</td>";
            echo "<td>";
            // read one record
            echo "<a href='php-beginner-crud-level-1/read_one.php?id={$id}' class='btn btn-lg btn-info m-r-1em'>Read</a>";

            // we will use this links on next part of this post
            echo "<a href='php-beginner-crud-level-1/update.php?id={$id}' class='btn btn-lg btn-primary m-r-1em'>Edit</a>";

            // we will use this links on next part of this post
            echo "<a href='php-beginner-crud-level-1/delete.php?id={$id}' class='btn btn-lg btn-danger'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }

// end table
        echo "</table>";
// PAGINATION
        // count total number of rows
        $query = "SELECT COUNT(*) as total_rows FROM products";
        $stmt = $con->prepare($query);

// execute query
        $stmt->execute();

// get total rows
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];

// paginate records
        $page_url = "index.php?";
        include_once "paging.php";

    }

// if no records found
    else {
        echo "<div class='alert alert-danger'>No records found.</div>";
    }

}

function read_item_once($id)
{
    //include database connection
    include 'config/database.php';
    // $id = isset($id) ? "id is  $id" : die('ERROR: Record ID not found.');
    if (!isset($id)) {
        die('ERROR: Record ID not found.');
    }
// read current record's data
    try {

        // prepare select query
        $query = "SELECT id, name, description, price, image FROM products WHERE id = ? LIMIT 0,1";

        $stmt = $con->prepare($query);

// this is the first question mark
        $stmt->bindParam(1, $id);

// execute our query
        $stmt->execute();

// store retrieved row to a variable
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

// values to fill up our form
        global $name, $description, $price, $image;
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
        $image = htmlspecialchars($row['image'], ENT_QUOTES);

    } catch (PDOException $e) {
        die('ERROR: ' . $exception->getMessage());

    }

}
