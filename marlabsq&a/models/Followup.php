<?php

/**
 * Created by PhpStorm.
 * User: jiamingdong
 * Date: 3/10/16
 * Time: 10:58 AM
 */
class Followup
{
    public $followup_id;
    public $followup_details;
    public $followup_date;
    public $followup_privacy;
    public $post_following;
    public $added_by;
    public $added_by_name;

    public static function retrieve_followups($dbc, $post_following) {
        $q = "SELECT Followups.followup_id,
                     Followups.followup_details,
                     Followups.followup_date,
                     Followups.followup_privacy,
                     Followups.post_following,
                     Followups.added_by,
                     Users.first_name AS added_by_name
              FROM Followups INNER JOIN Users ON Followups.added_by = Users.user_id
              WHERE Followups.post_following = '$post_following'";

        $r = @mysqli_query($dbc, $q);

        $visible_followups = array();
        while ($followup = mysqli_fetch_object($r, 'Followup')) {
            array_push($visible_followups, $followup);
        }

        return $visible_followups;
    }

    public static function add_new_followup($dbc, $followup_details, $followup_privacy, $post_following, $added_by) {
        $followup_date = date('Y-m-d H:i:s');
        $q = "INSERT INTO Followups(followup_details, followup_date, followup_privacy, post_following, added_by)
              VALUES ('$followup_details', '$followup_date', '$followup_privacy', '$post_following', '$added_by')";
        @mysqli_query($dbc, $q);

        return Followup::retrieve_followups($dbc, $post_following);
    }
}

?>