<?php
// Include config file
require_once "connection.php";
 
// Define variables and initialize with empty values
$serial = $description = $warranty = "";
$serial_err = $description_err = $warranty_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
    // Validate name
    $input_serial = trim($_POST["serial"]);
    if(empty($input_serial)){
        $serial_err = "Please enter a serial.";
    } 
    //else{
      //  $name = $input_name;
   // }
    {
    // Validate address
    $input_description = "";
    //trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter description.";     
    } else{
        $description = $input_description;
    }
    
    // Validate salary
    $input_warranty = "";
    //trim($_POST["warranty"]);
    if(empty($input_warranty)){
        $warranty_err = "Please enter warranty.";     
    
    } else{
        $warranty = $input_warranty;
    }
    
    // Check input errors before inserting in database
    if(empty($serial_err) && empty($description_err) && empty($warranty_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO products (serial, description, warranty) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_serial, $param_description, $param_warranty);
            
            // Set parameters
            $param_serial = $serial;
            $param_description = $description;
            $param_warranty = $warranty;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
    // mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add product record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Serial</label>
                            <input type="text" name="serial" class="form-control <?php echo (!empty($serial_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $serial; ?>">
                            <span class="invalid-feedback"><?php echo $serial_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>description</label>
                            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Warranty</label>
                            <input type="number" name="warranty" class="form-control <?php echo (!empty($warranty_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $warranty; ?>">
                            <span class="invalid-feedback"><?php echo $warranty_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>