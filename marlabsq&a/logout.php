<?php # logout.php
/**
 * Created by PhpStorm.
 * User: jiamingdong
 * Date: 3/2/16
 * Time: 10:02 AM
 */

// This page lets the user logout.
// It uses sessions.

session_start(); // Access the existing session.

// If no session is present, redirect the user:
if (!isset($_SESSION['user_id'])) {
    // Need the function:
    require('includes/functions_needed.php');
    redirect_user();
} else { // Cancel the session:
    $_SESSION = array(); // Clear the variables.
    session_destroy(); // Destroy the session itself.
    setcookie('PHPSESSID', '', time() - 3600, '/', '', 0, 0); // Destroy the cookie.
}

// Set the page title and include the HTML header:
$page_title = 'Logged Out!';
include_once ('includes/header.html');

// Print a message to inform the user:
echo "<div id=\"logoutnotice\" class=\"alert alert-warning\">
        <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
        <strong>Attention!</strong> You are now logged out!
      </div>";

?>

<script type="text/javascript">
    window.setTimeout(function() {
        $("#logoutnotice").fadeTo(1500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 1500);
</script>

<?php

include ('index.php');

include_once ('includes/footer.html');

?>