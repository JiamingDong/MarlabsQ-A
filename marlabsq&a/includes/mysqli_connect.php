<?php # # Script 9.2 - mysqli_connect.php
/**
 * Created by PhpStorm.
 * User: jiamingdong
 * Date: 3/1/16
 * Time: 11:20 PM
 */

// This file contains the database access information.
// This file also establishes a connection to MySQL,
// selects the database, and sets the encoding.

DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', '');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'marlabsdb');

// Make the connection:
$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to
MySQL: ' . mysqli_connect_error() );
// Set the encoding...
mysqli_set_charset($dbc, 'utf8');

class User {
    public $user_id;
    public $first_name;
    public $last_name;
    public $user_type;
    public $class_type;
    public $email;
}

class Folder {
    public $folder_id;
    public $folder_name;
    public $added_by;
}

class Post {
    public $post_id;
    public $post_summary;
    public $post_details;
    public $post_date;
    public $post_privacy;
    public $post_to;
    public $post_type;
    public $class_type;
    public $posted_by;
}

function getUserById($dbc, $user_id) {
    $q = "SELECT user_id, first_name, last_name, user_type, class_type, email FROM Users WHERE user_id='$user_id'";
    $r = @mysqli_query ($dbc, $q);  // Run the query.
    $user = mysqli_fetch_object($r, 'User');
    return $user;
}

function getPostById($dbc, $post_id) {
    $q = "SELECT * FROM Posts WHERE post_id = '$post_id'";
    if ($r = @mysqli_query($dbc, $q)) {
        $post = mysqli_fetch_object($r, 'Post');
        return $post;
    } else {
        return "{}";
    }
}


function retrieve_folders ($dbc, $added_by) {
    $q = "SELECT folder_id, folder_name, added_by FROM Folders WHERE added_by = '$added_by';";
    $r = @mysqli_query ($dbc, $q);

    $folders = array();
    while ($folder = mysqli_fetch_object($r, 'Folder')) {
        array_push($folders, $folder);
    }
    return $folders;
}

function add_new_folder ($dbc, $folder_name, $added_by) {
    $check_q = "SELECT * FROM Folders WHERE folder_name = '$folder_name' AND added_by = '$added_by';";
    $r = @mysqli_query($dbc, $check_q);
    if ($r && mysqli_num_rows($r) > 0) {
        // already have a folder with the same name added by this particular trainer
        return array();
    } else {
        $insert_q = "INSERT INTO Folders(folder_name, added_by) VALUES ('$folder_name', '$added_by');";
        @mysqli_query($dbc, $insert_q);
        return retrieve_folders($dbc, $added_by);
    }
}

function getTrainer ($dbc, $user_id) {
    $user = getUserById($dbc, $user_id);
    if ($user == NULL) return NULL;

    if ($user->user_type == '1') {
        // trainer himself
        return $user;
    }
    else {
        // trainee
        $q = "SELECT user_id, first_name, last_name, user_type, class_type, email
              FROM Users
              WHERE class_type = '$user->class_type' AND user_type = '1';";
        $r = @mysqli_query ($dbc, $q);  // Run the query.
        return mysqli_fetch_object($r, 'User');
    }
}

function is_visible ($post, $viewer) {
    if (empty($folders_selected)) { // no filter, select all visible folders
        if ($viewer->user_type == '0') { // the viewer is a trainee
            if ($post->post_to == '0' || $viewer->user_id == $post->posted_by) {
                // if the post is posted to the entire class or by the viewer himself.
                return true;
            }
        } else { // the viewer is a trainer
            return true;
        }
    }

    return false;
}

// retrieve all the posts that is visible to the viewer (user_id)
function retrieve_posts ($dbc, $user_id, $filter_folder) {
    $viewer = getUserById($dbc, $user_id);
    if ($filter_folder == "-1") {  //no filter
        $q = "SELECT *
              FROM Posts
              WHERE class_type = '$viewer->class_type'";
    } else {
        $q = "SELECT *
              FROM Posts
              WHERE class_type = '$viewer->class_type'
              AND post_id IN (SELECT post_id
                              FROM PostFolderMap
                              WHERE folder_id = '$filter_folder')";
    }

    $r = @mysqli_query($dbc, $q);

    $visible_posts = array();
    while ($post = mysqli_fetch_object($r, 'Post')) {
        if (is_visible($post, $viewer)) {
            array_push($visible_posts, $post);
        }
    }

    return $visible_posts;
}

function add_new_post($dbc, $summary, $details, $privacy, $post_to, $post_type, $posted_by, $folders_selected) {
    $poster = getUserById($dbc, $posted_by);

    // add the new post
    $post_date = date('Y-m-d H:i:s');
    $insert_post_q = "INSERT INTO Posts(post_summary, post_details, post_date, post_privacy, post_to, post_type, class_type, posted_by)
                 VALUES ('$summary', '$details', '$post_date', '$privacy', '$post_to', '$post_type', '$poster->class_type', '$posted_by')";

    @mysqli_query($dbc, $insert_post_q);

    // fill in the post-folder map.
    if ($folders_selected != 'no_filter') {
        $inserted_post_id = mysqli_insert_id($dbc); // get the new post id
        $folders = json_decode($folders_selected);  // get the folders selected by the poster.
        for ($i = 0; $i < count($folders); $i++) {
            $insert_map_q = "INSERT INTO PostFolderMap(post_id, folder_id)
                         VALUES ('$inserted_post_id', '$folders[$i]')";
            @mysqli_query($dbc, $insert_map_q);
        }
    }


    return retrieve_posts($dbc, $posted_by, "-1");
}


?>
