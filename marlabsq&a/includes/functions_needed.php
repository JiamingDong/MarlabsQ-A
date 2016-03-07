<?php # Script 12.2 - functions_needed.php
/**
 * Created by PhpStorm.
 * User: jiamingdong
 * Date: 3/1/16
 * Time: 10:47 PM
 */

// This page defines functions needed.

/* This function determines an absolute URL and redirects the user there.
 * The function takes one argument: the page to be redirected to.
 * The argument defaults to index.php.
 */

function redirect_user ($page = 'index.php') {
    // Start defining the URL...
    // URL is http:// plus the host name plus the current directory:
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

    // Remove any trailing slashes:
    $url = rtrim($url, '/\\');

    // Add the page:
    $url .= '/' . $page;

    // Redirect the user:
    header("Location: $url");
    exit(); // Quit the script.
}

/* This function validates the form data (the email address and password).
 * If both are present, the database is queried.
 * The function requires a database connection.
 * The function returns an array of information, including:
 * - a TRUE/FALSE variable indicating success
 * - an array of either errors or the database result
 */

function check_login($dbc, $email = '', $pass = '') {
    $errors = array(); // Initialize error array.
    // Validate the email address
    if (empty($email)) {
        array_push($errors,'You forgot to enter your email address.');
    } else {
        $e = mysqli_real_escape_string($dbc, trim($email));
    }

    // Validate the password:
    if (empty($pass)) {
        array_push($errors, 'You forgot to enter your password.');
    } else {
        $p = mysqli_real_escape_string($dbc, trim($pass));
    }

    if (empty($errors)) {
        // If everything's OK.

        // Retrieve the user_id and first_name for that email/password combination:
        $q = "SELECT user_id, first_name FROM Users WHERE email='$e' AND password='$p'";

        $r = @mysqli_query ($dbc, $q);  // Run the query.

        // Check the result:
        if (mysqli_num_rows($r) == 1) {
            // Fetch the record:
            $row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
            // Return true and the record:
            return array(true, $row);
        } else { // Not a match!
            array_push($errors, 'The email address and password entered do not match those on file.');
        }
    } // End of empty($errors) IF.

    // Return false and the errors:
    return array(false, $errors);
} // End of check_login() function.

function check_register($dbc, $register_info = array()) {
    $errors = array(); // Initialize error array.

    $email = $register_info['email'];
    $first_name = $register_info['first_name'];
    $last_name = $register_info['last_name'];
    $password = $register_info['password'];
    $confirm = $register_info['confirm_password'];
    $class_type = $register_info['class_type'];
    $user_type = $register_info['user_type'];

    if (empty($email)) {
        array_push($errors, 'You forgot to enter your email address.');
    }

    if (empty($first_name)) {
        array_push($errors, 'You forgot to enter your first name.');
    }

    if (empty($last_name)) {
        array_push($errors, 'You forgot to enter your last name.');
    }

    if (empty($password)) {
        array_push($errors, 'You forgot to enter your password.');
    } else {
        // validate the password and confirm
        if (strcmp($password, $confirm) != 0) {
            array_push($errors, 'Your password and the confirm password are not equal.');
        }
    }

    // If the new register is a trainer of the class type, check if it's unique
    if ($user_type == "1") {
        $q = "SELECT user_id, first_name FROM Users WHERE class_type='$class_type' AND user_type='1'";
        $r = @mysqli_query ($dbc, $q);  // Run the query.

        // check the result
        if (mysqli_num_rows($r) == 1) {
            array_push($errors, 'This class already has one trainer.');
        }
    }

    return $errors;
}
?>