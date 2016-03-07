<?php # Script 12.3 - login.php
/**
 * Created by PhpStorm.
 * User: jiamingdong
 * Date: 3/1/16
 * Time: 11:17 PM
 */

// This page processes the login form submission.
// Upon successful login, the user is redirected.
// Two included files are necessary.
// The script now uses sessions.

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // For processing the login:
    require('includes/functions_needed.php');

    // Need the database connection:
    require('includes/mysqli_connect.php');
    // Check the login:
    list ($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);

    if ($check) { // OK!
        // Set the session data:
        session_start();
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['first_name'] = $data['first_name'];

        // Set the remember me if the user click the 'remember me' check box
        if($_POST['remember']) {
            $year = time() + 31536000;
            setcookie('remember_me', $_POST['email'], $year);
        }
        elseif(!$_POST['remember']) {
            if(isset($_COOKIE['remember_me'])) {
                $past = time() - 100;
                $gone = $_COOKIE['remember_me'];
                setcookie('remember_me', $gone, $past);
            }
        }

        // Redirect:
        redirect_user('mainpage.php');
    } else { // Unsuccessful
        // Assign $data to $errors for error reporting
        // in the index.php file.
        $errors = $data;
    }

    mysqli_close($dbc); // Close the database conncection.
} // End of the main submit conditional.

// Create the page:
include ('index.php');

?>