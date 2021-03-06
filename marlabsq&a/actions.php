<?php
/**
 * Created by PhpStorm.
 * User: jiamingdong
 * Date: 3/4/16
 * Time: 8:36 PM
 */
include_once('mysqli_connect.php');


switch ($_POST['action']) {
    case 'add_folder':
        echo json_encode(add_new_folder($dbc, $_POST['folder_name'], $_POST['added_by']));
        break;
    case 'retrieve_folders':
        echo json_encode(retrieve_folders($dbc, $_POST['added_by']));
        break;
    case 'retrieve_posts':
        echo json_encode(retrieve_posts($dbc, $_POST['user_id'], $_POST['filter_folder']));
        break;
    case 'add_post':
        echo json_encode(add_new_post($dbc,
            $_POST['summary'],
            $_POST['details'],
            $_POST['privacy'],
            $_POST['post_to'],
            $_POST['post_type'],
            $_POST['posted_by'],
            $_POST['folders_selected'])
        );
        break;
    case 'get_post_object':
        echo json_encode(getPostById($dbc, $_POST['focused_post']));
        break;
    case 'get_user_object':
        echo json_encode(getUserById($dbc, $_POST['user_id']));
        break;
    case 'get_related_folders':
        echo json_encode(getFolderNamesByPostId($dbc, $_POST['post_id']));
        break;
    case 'check_post_folder_mapping':
        echo isMapped($dbc, $_POST['post_id'], $_POST['folder_id']);
        break;
    case 'toggle_question_type':
        echo toggleQuestionType($dbc, $_POST['post_id'], $_POST['post_type']);
        break;
    case 'retrieve_followups':
        echo json_encode(Followup::retrieve_followups($dbc, $_POST['post_following']));
        break;
    case 'add_followup':
        echo json_encode(Followup::add_new_followup($dbc,
            $_POST['followup_details'],
            $_POST['followup_privacy'],
            $_POST['post_following'],
            $_POST['added_by']));
        break;
    default:
        break;
}

?>