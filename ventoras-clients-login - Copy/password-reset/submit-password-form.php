<?php
require_once "../php/config.php";

$form_status = "";
$valid_link = false;
$expired_link = true;

//'Enter a new password' form gets submitted
if (isset($_POST['password1']) && isset($_POST['password2']) && isset($_POST['reset_key'])) {


    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    $valid_link = true;
    //verify the reset key
    $reset_key_url = $_POST['reset_key'];
    $sql = "SELECT id, user_id, email, created_date FROM password_reset_links WHERE reset_key = ?";


    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $reset_key_url);


        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $reset_entry_id, $user_id, $user_email, $created_date);
                if (mysqli_stmt_fetch($stmt)) {

                    //Check if the reset link is expired
                    // $expired_link = is_link_expired($created_date);

                    $minutes_the_link_is_valid = 60;
                    $created_date = new DateTime($created_date);
                    $current_date = new DateTime();

                    $interval = $current_date->diff($created_date);
                    $minutesDifference = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i; // Convert to minutes

                    // $form_status .=  $minutesDifference . "----" . $current_date->format('Y-m-d H:i:s');
                    // Check if the second date is 60 or more minutes into the past
                    if ($created_date < $current_date && $minutesDifference >= $minutes_the_link_is_valid) {
                        $expired_link =  true;
                    } else {
                        $expired_link =  false;
                    }
                }
            }
        }
    }






    //Check if the reset key is valid and not expired
    if ($valid_link == true) {
        if ($expired_link == false) {
           
            //proceed to check if both passwords are the same
            if (!empty($password1)) {
                if ($password1 == $password2) {
                    //check if the password is shorter than 8 characters
                    if (strlen($password1) > $MINIMUM_PASSWORD_STR_LENGTH) {
                        //proceed to update the database records
                        // Prepare an update statement
                        $sql = "UPDATE users SET password = ? WHERE id = ?";

                        if ($stmt = mysqli_prepare($link, $sql)) {
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_id);

                            // $new_password = $_POST['password1'];
                            // Set parameters
                            $param_password = password_hash($password1, PASSWORD_DEFAULT);
                            $param_id = $user_id;


                            // Attempt to execute the prepared statement
                            if (mysqli_stmt_execute($stmt)) {
                                $sql = "DELETE FROM password_reset_links WHERE id = ?";
                                //Delete the password_reset_key entry
                                if ($stmt_2 = mysqli_prepare($link, $sql)) {
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt_2, "i", $reset_entry_id);
                                    

                                    // Attempt to execute the prepared statement
                                    if (mysqli_stmt_execute($stmt_2)) {
                                        //Successfully Deleted the Password Reset Link Entry
                                        print_update_status_basic_layout(true, "Password Changed Successfully");
                                        
                                    } 

                                    // Close statement
                                    mysqli_stmt_close($stmt_2);
                                }
                            }

                            // Close statement
                            mysqli_stmt_close($stmt);
                        }
                    }
                } 
            }
        } else {
            print_update_status_basic_layout(false, "Reset Key is Expired, Try 'Reset Password' again");
        }
    } 
}


print_update_status_basic_layout(false, "Something went wrong!");



function is_link_expired($created_date)
{
    $minutes_the_link_is_valid = 60;
    $created_date = new DateTime($created_date);
    $current_date = new DateTime();

    $interval = $current_date->diff($created_date);
    $minutesDifference = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i; // Convert to minutes

    $form_status =  $minutesDifference . "----" . $current_date->format('Y-m-d H:i:s');
    // Check if the second date is 60 or more minutes into the past
    if ($created_date < $current_date && $minutesDifference >= $minutes_the_link_is_valid) {
        return true;
    } else {
        return false;
    }
}
