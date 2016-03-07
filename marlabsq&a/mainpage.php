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
require_once('includes/mysqli_connect.php');

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

?>

    <style>
        /* Remove the navbar's default margin-bottom and rounded borders */
        .navbar {
            margin-bottom: 0;
            border-radius: 0;
        }

        /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
        .row.content {height: 450px}

        /* Set gray background color and 100% height */
        .sidenav {
            padding-top: 20px;
            background-color: #f1f1f1;
            height: 100%;
        }

        /* Set black background color, white text and some padding */
        footer {
            background-color: #15703c;
            color: white;
            padding: 15px;
        }

        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }
            .row.content {height:auto;}
        }
    </style>

<script type="text/javascript">
    // Global indicates
    var folders = []; // will be used to store all the folders of this class added by the trainer.
    var posts = [];
    var focused_post = '-1'; // Indicates the selected post id.

    function load_welcome_page() {
        $("#dash-board").empty();
        $("#dash-board").append('<h1>Welcome To Marlabs Q & A</h1>');
        $("#dash-board").append('<p>This is an online Question and Answer Gathering place For both trainers and trainees ' +
            'to communicate with each other, ask questions and exchange ideas. You as a trainee can post any of your questions' +
            'to be seen by the trainer and other trainees of your class. You can also follow up other people\'s posts to solve' +
            'their problem as well as discuss a certain topic. They can also search for a certain post by its title and filter' +
            'for certain posts by the folder added by the trainer.</p>');
        $("#dash-board").append('<hr><h3>Class At A Glance</h3>');
        $("#dash-board").append('<p>Posts, Questions and Followups</p>');
    }

    $('body').on('click', function (e) {
        $('.trigger').each(function () {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });

    function load_post_list(posts_in_json) {
        focused_post = '-1'; // reset focused_post to -1.
        posts = JSON.parse(posts_in_json);
        posts.reverse();  // ordered by datetime from latest to earlier.

        // remove all the child elements except post_and_search.
        var post_list = $('#post-list');
        post_list.empty();
        posts.forEach(function(post) {
            var li = $("<li>");
            li.attr('id', post.post_id)
                .addClass("list-group-item")
                .addClass("list-group-item-success")
                .addClass("post_li")
                .addClass("noselect")
                .css({"text-align":"left"})
                .html("<label>"+post.post_summary+"</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");

            li.append("<span style='color:slategrey;'>"+post.post_date+"</span><br>" + post.post_details);
            post_list.append(li);
        });
    }

    $(document).ready(function() {
        // load the welcome page
        load_welcome_page();

        // get the trainer id
        var trainer_id = '-1';
        trainer_id = <?php echo (string)(isset($trainer) ? $trainer->user_id : -1);?>;
        var current_user_id = '-1';
        current_user_id = <?php echo (string)($current_user->user_id);?>;

        // retrieve all the folders added by the trainer
        $.ajax({
            url:'actions.php',
            type:'post',
            data:{'action':'retrieve_folders', 'added_by':trainer_id},
            success:function(updated_folders_in_json) {
                folders = JSON.parse(updated_folders_in_json);
                $("#folder_list").empty();
                $("#folder_list").append('<li id="all"><a href="#">All</a></li>');
                $("#folder_list").append('<li class="dropdown-header">Filter posts by folder</li>');
                folders.forEach(function(folder) {
                    var li = $("<li>");
                    li.attr('id', folder.folder_id).html("<a href='#'>" + folder.folder_name + "</a>");
                    $("#folder_list").append(li);
                });

            },
            error:function(exception) {
                alert("No folder retrieved!");
            }
        });

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

        // hit the add new folder button
        $(document).on("click", "#add_folder_btn", function () {
            var added_by = '-1';
            added_by = <?php echo (string)(isset($trainer) ? $trainer->user_id : -1);?>;
            var f_name = $(".popover #folder_name_input").val();

            $.ajax({
                url:'actions.php',
                type:'post',
                data:{'action':'add_folder','folder_name':f_name, 'added_by':added_by},
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
            // get current user first name
            var current_user_first_name = "";
            current_user_first_name = <?php echo json_encode($current_user->first_name);?>;

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
            var posted_by = '-1';
            posted_by = <?php echo (string)($current_user->user_id);?>;

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
                    'posted_by':posted_by,
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
    });

    function load_post_details() {
        // get post object from back side
        $.ajax({
            url:'actions.php',
            type:'post',
            data:{'action':'get_post_object', 'focused_post':focused_post},
            success:function(post_in_json) {
                var post_obj = JSON.parse(post_in_json);
                // get the poster object from back side.
                $.ajax({
                    url:'actions.php',
                    type:'post',
                    data:{'action':'get_user_object', 'user_id':post_obj.posted_by},
                    success: function (user_in_json) {
                        var poster_obj = JSON.parse(user_in_json);
                        $("#dash-board").empty();
                        $("#dash-board").append('' +
                            '<div class="panel panel-success" style="margin-top: 10px;">'+
                            '<div class="panel-heading"><label>'+post_obj.post_summary+'<label></div>'+
                            '<div class="panel-body" style="height: 100px;;"><pre>'+post_obj.post_details+'</pre></div>' +
                            '<div class="panel-footer" style="text-align: right;">' +
                            '<span style="font-style: italic;color:slategrey;">Posted on '+post_obj.post_date+' by '+ poster_obj.first_name +'</span>' +
                            '</div>'+
                            '</div>');
                    },
                    error:function(exception) {
                        $("#dash-board").empty();
                        $("#dash-board").append("<h1>Failed to load the post.</h1>");
                    }
                });
            },
            error:function(exception) {
                $("#dash-board").empty();
                $("#dash-board").append("<h1>Failed to load the post.</h1>");
            }
        });
    }

    // hit a post list item
    $(document).on("click", ".post_li", function () {
        $("#" + focused_post).removeClass("list-group-item-warning");
        $("#" + focused_post).addClass("list-group-item-success");

        $(this).removeClass("list-group-item-success");
        $(this).addClass("list-group-item-warning");
        focused_post = $(this).attr("id");

        // refresh the main-board of the new focused post.
        load_post_details();

        return false;
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
                            <input type="text" class="form-control input-lg" placeholder="Search posts..." />
                            <span class="input-group-btn">
                                <button class="btn btn-info btn-lg" type="button">
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
        </div>
    </div>
</div>

<?php
include ('includes/footer.html');
?>