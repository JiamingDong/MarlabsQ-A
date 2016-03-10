<?php # register.php
/**
 * Created by PhpStorm.
 * User: jiamingdong
 * Date: 3/3/16
 * Time: 11:13 AM
 */

// This page processes the register form submission.
// Upon successful register, the user is redirected.
// Two included files are necessary.
// The script now uses sessions.

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // For processing the login:
    require ('includes/functions_needed.php');

    // Need the database connection:
    require('mysqli_connect.php');
    // Check the register:
    $register_info = array('first_name'=>$_POST['first_name'],
                           'last_name'=>$_POST['last_name'],
                           'email'=>$_POST['email'],
                           'password'=>$_POST['password'],
                           'confirm_password'=>$_POST['confirm_password'],
                           'class_type'=>$_POST['class_type'],
                           'user_type'=>$_POST['user_type']);

    $errors = check_register($dbc, $register_info);

    if (empty($errors)) { // OK!
        // Insert the new user into Users table
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $class_type = $_POST['class_type'];
        $user_type = $_POST['user_type'];

        $insert_q = "INSERT INTO Users (first_name, last_name, email, password, user_type, class_type)
                     VALUES ('$first_name', '$last_name', '$email', '$password', '$user_type', '$class_type')";

        if (@mysqli_query ($dbc, $insert_q)) {
            $role = ($user_type == '0') ? 'trainee' : 'trainer';
            $class = '';
            switch ($class_type) {
                case "0":
                    $class = "PHP";
                    break;
                case "1":
                    $class = "Java";
                    break;
                case "2":
                    $class = "BigData";
                    break;
                case "3":
                    $class = ".NET";
                    break;
                default:
                    break;
            }
            $success_register = 'You\'ve create a new account successfully as a '.$role.' in '.$class.' class.';
        } else {
            array_push($errors, 'Failed to create a new account: The email you entered has already been registered.');
        }

        // Redirect:
        // redirect_user('mainpage.php');
    }

    mysqli_close($dbc); // Close the database conncection.
} // End of the main submit conditional.

// Create the page:
include ('index.php');
