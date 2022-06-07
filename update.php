<?php
// Include config file
require_once "connection.php";
 
// Define variables and initialize with empty values
$serial = $description = $warranty = "";
$serial_err = $description_err = $warranty_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_serial = trim($_POST["serial"]);
    if(empty($input_serial)){
        $name_err = "Please enter serial.";
    } 
    } 
    
    else{
        $serial = $input_serial;
   }
    
    // Validate address address
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter description.";     
    } else{
        $description = $input_description;
    }
    
    // Validate salary
    $input_warranty = trim($_POST["warranty"]);
    if(empty($input_warranty)){
        $warranty_err = "Please enter warranty.";     
    } 
    else{
        $warranty = $input_warranty;
    }
    
    // Check input errors before inserting in database
    if(empty($serial_err) && empty($description_err) && empty($warranty_err)){
        // Prepare an update statement
        $sql = "UPDATE products SET serial=?, description=?, warranty=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_serial, $param_description, $param_warranty, $param_id);
            
            // Set parameters
            $param_serial = $serial;
            $param_description = $description;
            $param_warranty = $warranty;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);


    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM productss WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $serial = $row["serial"];
                    $description = $row["description"];
                    $warranty = $row["warranty"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }




?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the product record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Serial</label>
                            <input type="number" name="serial" class="form-control <?php echo (!empty($serial_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $serial; ?>">
                            <span class="invalid-feedback"><?php echo $serial_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Warranty</label>
                            <input type="text" name="warranty" class="form-control <?php echo (!empty($warranty_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $warranty; ?>">
                            <span class="invalid-feedback"><?php echo $warranty_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>