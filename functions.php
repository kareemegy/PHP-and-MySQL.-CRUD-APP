<?php

function create_item($post)
{
    include 'config/database.php';

    if ($post) {

        try {
            // insert query
            $query = "INSERT INTO products SET name=:name, description=:description, price=:price, created=:created";

            // prepare query for execution
            $stmt = $con->prepare($query);

            // posted values
            $name = htmlspecialchars(strip_tags($_POST['name']));
            $description = htmlspecialchars(strip_tags($_POST['description']));
            $price = htmlspecialchars(strip_tags($_POST['price']));

            if (empty($name) && empty($description) && empty($price)) {
                echo "<div class='alert alert-danger'>please fill the form with right info</div>";
            } else {
                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);

                // specify when this record was inserted to the database
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);

                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
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

// delete message prompt will be here

// select all data
    $query = "SELECT id, name, description, price FROM products ORDER BY id ASC";
    $stmt = $con->prepare($query);
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
            echo "<a href='#' onclick='php-beginner-crud-level-1/delete_user({$id});'class='btn btn-lg btn-danger'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }

// end table
        echo "</table>";

    }

// if no records found
    else {
        echo "<div class='alert alert-danger'>No records found.</div>";
    }

}