<?php # mainpage.php
/**
 * Created by PhpStorm.
 * User: jiamingdong
 * Date: 3/2/16
 * Time: 9:51 AM
 */

// The user is redirected here from login.php.

session_start(); // Start the session.

// If no cookie is present, redirect the user:
if (!isset($_SESSION['user_id'])) {
    // Need the functions:
    require('includes/functions_needed.php');
    redirect_user();
}

// Set the page title and include the HTML header:
$page_title = 'Main Page';
include_once ('includes/header.html');
require_once('mysqli_connect.php');

$current_user = getUserById($dbc, $_SESSION['user_id']);
switch($current_user->class_type){
    case "0":
        $current_class = "PHP";
        break;
    case "1":
        $current_class = "Java";
        break;
    case "2":
        $current_class = "BigData";
        break;
    case "3":
        $current_class = ".NET";
}

// get the trainer of current user's class.
$trainer = getTrainer($dbc, $_SESSION['user_id']);

// pass global variables from php to javascript in this page.
echo "<script type=\"text/javascript\">".
       "var class_name = '".$current_class."';".
       "var current_user_id = '".$current_user->user_id."';".
       "var current_user_first_name = '".$current_user->first_name."';".
       "var current_user_type = '".$current_user->user_type."';".
       "var trainer_id = '".(isset($trainer) ? $trainer->user_id:'-1')."';".
     "</script>";
?>


<link rel="stylesheet" type="text/css" href="css/mainpage_style.css">

<script src="javascript/mainpage_script.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // load the welcome page
        load_welcome_page();

        // load folders
        load_folders();

        // retrieve all the posts current user can view
        $.ajax({
            url:'actions.php',
            type:'post',
            data:{'action':'retrieve_posts', 'user_id':current_user_id, 'filter_folder':'-1'},
            success:function(posts_in_json) {
                load_post_list(posts_in_json);

                $(".trigger").popover('hide');
            },
            error:function(exception) {
                alert("No post retrieved!");
            }
        });

        $('.trigger').popover({
            html: true,
            title: 'Add a new folder',
            content: function () {
                return $(this).parent().find('.content').html();
            }
        });

        // Event Handlers

        // dismiss the folder list if hit elsewhere of the page
        $('body').on('click', function (e) {
            $('.trigger').each(function () {
                //the 'is' for buttons that trigger popups
                //the 'has' for icons within a button that triggers a popup
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });

        // hit the add new folder button
        $(document).on("click", "#add_folder_btn", function () {
            var f_name = $(".popover #folder_name_input").val();

            $.ajax({
                url:'actions.php',
                type:'post',
                data:{'action':'add_folder','folder_name':f_name, 'added_by':trainer_id},
                success:function(folders_in_json) {
                    folders = JSON.parse(folders_in_json);
                    if (folders.length == 0) {
                        alert("You already have the folder named " + f_name + ".");
                    } else {
                        // successfully added, update the folder list
                        $("#folder_list").empty();
                        $("#folder_list").append('<li id="all"><a href="#">All</a></li>');
                        $("#folder_list").append('<li class="dropdown-header">Filter posts by folder</li>');
                        folders.forEach(function(folder) {
                            var li = $("<li>");
                            li.attr('id', folder.folder_id).html("<a href='#'>" + folder.folder_name + "</a>");
                            $("#folder_list").append(li);
                        });

                        alert("You've added a new folder named \"" + f_name + "\".");
                    }
                    $(".trigger").popover('hide');
                },
                error:function(exception) {
                    alert("Failed to add new folder.");
                }
            });

            return false;
        });

        // hit the new post button
        $(document).on("click", "#new_post_btn", function () {
            $("#dash-board").empty();
            // load the new post submit form...
            $("#dash-board").append('<h4><label style="color:darkgreen; font-style: italic">New Post</label></h4>');
            $("#dash-board").append('' +
                '<div class="row">' +
                '<div class="col-xs-12">' +
                '<hr><label style="color:darkgreen; font-style: italic">Post Type:</label>' +
                '<div class="btn-group" data-toggle="buttons">' +
                '<label class="btn active">' +
                '<input type="radio" name="post_type" value="0" checked><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-check-circle-o fa-2x"></i><span> Question</span>' +
                '</label>' +
                '<label class="btn">' +
                '<input type="radio" name="post_type" value="1"><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-check-circle-o fa-2x"></i><span> Note</span>' +
                '</label>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-xs-12">' +
                '<label style="color:darkgreen; font-style: italic">Post To:&nbsp;&nbsp;&nbsp;&nbsp;</label>' +
                '<div class="btn-group" data-toggle="buttons">' +
                '<label class="btn active">' +
                '<input type="radio" name="post_to" value="0" checked><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-check-circle-o fa-2x"></i><span> Entire Class</span>' +
                '</label>' +
                '<label class="btn">' +
                '<input type="radio" name="post_to" value="1"><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-check-circle-o fa-2x"></i><span> Only The Trainer</span>' +
                '</label>' +
                '</div>' +
                '</div>' +
                '</div>');

            // folder selector
            $("#dash-board").append('' +
                '<div class="row">' +
                '<div class="col-xs-12">' +
                '<label style="color:darkgreen; font-style: italic">Select Folder(s)</label>' +
                '<div id="folder_selector" class="btn-group" data-toggle="buttons">' +
                '</div>' +
                '</div>' +
                '<div/>');

            folders.forEach(function(folder) {
                $("#folder_selector").append('' +
                    '<label class="btn">' +
                    '<input type="checkbox" name="folder_selected" value="'+ folder.folder_id +'"><i class="fa fa-square-o fa-2x"></i><i class="fa fa-check-square-o fa-2x"></i><span> ' + folder.folder_name+ '</span>' +
                    '</label>');
            });

            // summary input
            $("#dash-board").append('' +
                '<div class="row">' +
                '<div class="col-xs-12">' +
                '<hr>' +
                '<div class="form-group">' +
                '<label style="color:darkgreen; font-style: italic" for="usr">Summary: </label>' +
                '<input type="text" class="form-control" id="post_summary">' +
                '</div>'+
                '</div>' +
                '<div/>');

            // details input
            $("#dash-board").append('' +
                '<div class="row">' +
                '<div class="col-xs-12">' +
                '<div class="form-group">' +
                '<label style="color:darkgreen; font-style: italic" for="usr">Details: </label>' +
                '<textarea type="text" class="form-control" rows="8" id="post_details"></textarea>' +
                '</div>'+
                '</div>' +
                '<div/>');

            // privacy

            $("#dash-board").append('' +
                '<div class="row">' +
                '<div class="col-xs-3"><label for="sel1" style="padding-top:6px; color:darkgreen; font-style: italic">Show my name as: </label></div>' +
                '<div class="col-xs-4">' +
                    '<select class="form-control" id="privacy_select" style="margin-left: -60px;">' +
                        '<option value="0">' + current_user_first_name +'</option>' +
                        '<option value="1">Anonymous to all</option>' +
                        '<option value="2">Anonymous to classmates</option>' +
                    '</select>' +
                '</div>' +
                '<div class="col-xs-5">' +
                    '<button id="add_post_submit" type="submit" class="btn btn-success">Add my post to the class</button>' +
                '</div>' +
                '<div/>');
        });

        // hit the new post submitting button
        $(document).on("click", "#add_post_submit", function () {
            var summary = $('#post_summary').val();
            var details = $('#post_details').val();
            var privacy = $('#privacy_select').val();
            var post_to = $('input[name="post_to"]:checked').val();
            var post_type = $('input[name="post_type"]:checked').val();

            var checkboxes = document.querySelectorAll('input[name="folder_selected"]:checked');

            var folders_selected = [];
            Array.prototype.forEach.call(checkboxes, function(el) {
                folders_selected.push(el.value);
            });

            $.ajax({
                url:'actions.php',
                type:'post',
                data:{'action':'add_post',
                    'summary':summary,
                    'details':details,
                    'privacy':privacy,
                    'post_to':post_to,
                    'post_type':post_type,
                    'posted_by':current_user_id,
                    'folders_selected':folders_selected.length == 0?"no_filter":JSON.stringify(folders_selected)},
                success:function(updated_posts_in_json) {
                    load_welcome_page();
                    load_post_list(updated_posts_in_json);

                    $(".trigger").popover('hide');
                    alert("You have added a new post successfully.");
                },
                error:function(exception) {
                    alert("Failed to add new folder.");
                }
            });

        });

        // hit the search button
        $(document).on("click", "#search-btn", function () {
            var input_val = $("#search-input").val();
            $('#post-list').empty();
            posts.forEach(function(post) {
                var index = post.post_summary.toLowerCase().indexOf(input_val.toLowerCase());
                if (index > -1) {  // post is a search result.
                    var search_result = input_val.length == 0? post.post_summary : post.post_summary.substring(0, index) +
                    '<mark>' + post.post_summary.substring(index, index + input_val.length) + '</mark>' +
                    post.post_summary.substring(index + input_val.length);
                    render_post(post, search_result);
                }
            });
        });

        // hit enter on the keyboard when the cursor is in the searchbox input
        $('#search-input').keypress(function (e) {
            if (e.which == 13) { // 13 represents enter
                var input_val = $("#search-input").val();
                $('#post-list').empty();
                posts.forEach(function(post) {
                    var index = post.post_summary.toLowerCase().indexOf(input_val.toLowerCase());
                    if (index > -1) {  // post is a search result.
                        var search_result = input_val.length == 0? post.post_summary : post.post_summary.substring(0, index) +
                            '<mark>' + post.post_summary.substring(index, index + input_val.length) + '</mark>' +
                            post.post_summary.substring(index + input_val.length);
                        render_post(post, search_result);
                    }
                });
            }
        });

        // hit a post list item
        $(document).on("click", ".post_li", function () {
            $("#" + focused_post).removeClass("list-group-item-focused");
            $(this).addClass("list-group-item-focused");
            focused_post = $(this).attr("id");

            // refresh the main-board of the new focused post.
            load_post_details();

            return false;
        });

        // hit the edit/save button
        $(document).on("click", "#edit_post_btn", function () {
            if ($(this).text() == "Edit") {
                $(this).text("Save");
            } else {
                $(this).text("Edit");
            }
        });

        // focus the textarea of followup box
        $(document).on("focus", "#followup_input", function () {
            if (!$("#temp_div").length) {
                $(this).animate({rows: '5'}, "fast", function () {
                    var followup_privacy_select = $("<select></select>");
                    followup_privacy_select.attr("id", "followup_privacy_select")
                        .addClass("form-control")
                        .css("margin-top", "20px")
                        .append('<option value="0">' + current_user_first_name + '</option>' +
                            '<option value="1">Anonymous to all</option>' +
                            '<option value="2">Anonymous to classmates</option>');

                    var submit_btn = $('<button>Add a new discussion</button>');
                    submit_btn.attr('id', 'followup_submit_btn').attr('type', 'button').css('margin-top', '20px').addClass('btn btn-primary');

                    var cancel_btn = $('<button>Cancel</button>');
                    cancel_btn.attr({
                        'id': 'followup_cancel_btn',
                        'type': 'button'
                    }).css({'float':'right', 'margin-top':'20px'}).addClass('btn btn-link');

                    var temp_div = $("<div id='temp_div' lass='row'></div>");
                    temp_div.append("<div id='1' class='col-md-2'><label style='margin-top: 27px'>Follow up as: </label></div>" +
                                    "<div id='2' class='col-md-3'></div>" +
                                    "<div id='3' class='col-md-2'></div>" +
                                    "<div id='4' class='col-md-4'></div>" +
                                    "<div id='5' class='col-md-1'></div>");

                    temp_div.find("#2").append(followup_privacy_select);
                    temp_div.find("#3").append(submit_btn);
                    temp_div.find("#5").append(cancel_btn);


                    $(this).after(temp_div);
                });
            }
        });

        // hit the cancel button in the followup box
        $(document).on("click", "#followup_cancel_btn", function () {
            $("#followup_input").animate({rows: '1'}, "fast", function() {
                $("#followup_input").val("");
                $("#temp_div").remove();
            });
        });

        // hit the add-new-followup button
        $(document).on("click", "#followup_submit_btn", function () {
            var details = $("textarea#followup_input").val();
            var privacy = $("select#followup_privacy_select").val();

            var followup_form = $("#followup_form");
            var followup_board = $("#followup_board");

            $.ajax({
                url:'actions.php',
                type:'post',
                data:{'action':'add_followup',
                      'followup_details':details,
                      'followup_privacy':privacy,
                      'post_following':focused_post,
                      'added_by':current_user_id},
                success:function(updated_followups_in_json) {
                    var followups = JSON.parse(updated_followups_in_json);
                    followup_form.siblings().remove();
                    followups.forEach(function(followup) {
                        var followup_panel = $("<div></div>");
                        var displayed_name = isAnonymous(followup.added_by, followup.followup_privacy) ? "Anonymous" : "<strong><a href='#'>" + followup.added_by_name + "</a></strong>";
                        followup_panel.attr("id", followup.followup_id).addClass("panel panel-primary")
                            .append('<div class="panel-body">' +
                                displayed_name +
                                '<span style="color: lightslategrey;font-style: italic;">  '+ timeSince(new Date(followup.followup_date))+'</span>' +
                                '<p>' + followup.followup_details + '</p>' +
                                '</div>');
                        followup_board.prepend(followup_panel);
                    });

                    $("#followup_input").animate({rows: '1'}, "fast", function() {
                        $("#followup_input").val("");
                        $("#temp_div").remove();
                    });
                },
                error:function(exception) {
                    alert("Failed to add new followup.");
                }
            });
        });
    });
</script>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img src="images/logo.jpg" style="padding-top:8px; width:190px; height:50px;"/>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul style="padding-left: 10px" class="nav navbar-nav">
                <li class="active"><a href="#">Q & A</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Folders
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" id="folder_list">

                    </ul>
                </li>
                <?php
                    if ($current_user->user_type == '1') { // current user is a trainer
                        echo '<li><a href="#" class="trigger">Add folder</a>
                                <div class="head hide">Lorem Ipsum</div>
                                <div class="content hide">
                                    <div class="form-group" style="width:200px;">
                                        <input type="text" id="folder_name_input" class="form-control" placeholder="Enter new folder\'s name...">
                                    </div>
                                    <button type="submit" id="add_folder_btn" class="btn btn-success btn-block">Submit</button>
                                </div>
                                <div class="footer hide">test</div>
                             </li>';
                    } else { // current user is a trainee.
                        if (isset($trainer) && !empty($trainer)) {
                            echo '<li><a href="#">'.$trainer->first_name.' ( your trainer )</a></li>';
                        } else {
                            echo '<li><a href="#" style="pointer-events: none;cursor: default;">No trainer yet</a></li>';
                        }
                    }
                ?>
                <li><a href="#"><?php echo $current_class?> Class</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="#"><?php echo $current_user->first_name;?></a>
                </li>
                <li style="padding-top: 8px">
                    <div class="dropdown">
                        <button style="background: #f8fff8" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-th-list"></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">Your Options</li>
                            <li><a href="#">Account Settings</a></li>
                            <li><a href="#">Coming soon...</a></li>
                            <li><a href="#">About</a></li>
                            <li class="divider" ></li>
                            <li><a href="logout.php"><span class="glyphicon glyphicon glyphicon-log-out"></span> <strong>Log Out</strong></a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php
// Print a customized message:
echo "<div id=\"loggedinnotice\" class=\"alert alert-info\">
        <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
        <strong>Info!</strong> You are now logged in, {$current_user->first_name}
      </div>";
?>

<script type="text/javascript">
    window.setTimeout(function() {
        $("#loggedinnotice").fadeTo(1500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 1500);
</script>

<div class="container-fluid text-center">
    <div class="row content" style="height:630px;">
        <div id="post-board" class="col-sm-4 sidenav">
            <div id="post_and_search" class="row" style="margin-bottom: 20px;">
                <div class="col-md-3">
                    <button id="new_post_btn" type="button" class="btn btn-success" style="margin-top: 1px;">
                        <span class="glyphicon glyphicon-file"></span> New Post
                    </button>
                </div>
                <div class="col-md-9">
                    <div id="custom-search-input" style="margin-left: 5px;">
                        <div class="input-group col-md-12">
                            <input id="search-input"type="text" class="form-control input-lg" placeholder="Search posts..." />
                            <span class="input-group-btn">
                                <button id="search-btn" class="btn btn-info btn-lg" type="button">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <ul id="post-list" class="list-group">

                </ul>
            </div>
        </div>
        <div id="dash-board" class="col-sm-7 text-left">

        </div>
        <div class="col-sm-1 sidenav">
            <div class="well">
                <p>ADS</p>
            </div>
            <div class="well">
                <p>ADS</p>
            </div>
            <div class="well">
                <p>ADS</p>
            </div>
        </div>
    </div>
</div>

<?php
include ('includes/footer.html');
?>