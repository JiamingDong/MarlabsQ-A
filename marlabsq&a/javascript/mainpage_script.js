// Global indicates
var folders = []; // will be used to store all the folders of this class added by the trainer.
var posts = [];
var focused_post = '-1'; // Indicates the selected post id.

function load_welcome_page() {
    var dash_board = $("#dash-board");
    dash_board.empty();
    dash_board.append('<h1>Welcome To Marlabs Q & A</h1>');
    dash_board.append('<p>This is an online Question and Answer Gathering place For both trainers and trainees ' +
        'to communicate with each other, ask questions and exchange ideas. You as a trainee can post any of your questions' +
        'to be seen by the trainer and other trainees of your class. You can also follow up other people\'s posts to solve' +
        'their problem as well as discuss a certain topic. They can also search for a certain post by its title and filter' +
        'for certain posts by the folder added by the trainer.</p>');
    dash_board.append('<hr><h3>Class At A Glance</h3>');

    dash_board.append('<p>This is <strong>'+ class_name +'</strong> class. Questions, Notes and Followups. Posts and followups marked by "<span class="glyphicion glyphicon glyphicon-user"></span>"' +
        '                       are posted by the trainer of this class. ' +
        '<br><span style="color: red;">Red</span> posts in the lists are unsolved questions. ' +
        '<br><span style="color: green;">Green</span> posts in the lists are solved questions.' +
        '<br><span style="color: blue;">Blue</span> posts in the lists are notes.' +
        '<br>The asker can decide whether or not the question is solved.</p>');
}

function render_post(post, summary) {
    var post_list = $("#post-list");
    var li = $("<li>");
    li.attr('id', post.post_id)
        .addClass("list-group-item")
        .addClass("post_li")
        .addClass("noselect")
        .css({"text-align":"left","margin-left":"15px","margin-right":"15px"})
        .html("<label>"+summary+"</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");

    if (post.post_type == '0') {  // post is an unsolved question
        li.addClass("list-group-item-danger");
    } else if (post.post_type == '1') {  // post is a note
        li.addClass("list-group-item-info");
    } else { // post is a solved question
        li.addClass("list-group-item-success");
    }

    li.append("<span style='color:slategrey;opacity: 0.7; float:right;'>"+post.post_date+"</span><br>" +
        "<span style='opacity: 0.7;'>" + post.post_details.substring(0, 20) + "...</span>");
    post_list.append(li);
}

function load_folders() {
    // retrieve all the folders added by the trainer
    $.ajax({
        url:'actions.php',
        type:'post',
        data:{'action':'retrieve_folders', 'added_by':trainer_id},
        success:function(updated_folders_in_json) {
            folders = JSON.parse(updated_folders_in_json);
            $("#folder_list").empty();
            $("#folder_list").append('<li id="all" class="folder_li"><a href="#">All</a></li>');
            $("#folder_list").append('<li class="dropdown-header">Filter posts by folder</li>');
            folders.forEach(function(folder) {
                var li = $("<li>");
                li.attr('id', folder.folder_id).attr('class', 'folder_li').html("<a href='#'>" + folder.folder_name + "</a>");
                $("#folder_list").append(li);
            });

            $(".folder_li").on("click", function() {
                $("#post-list").empty();
                var folder_id = $(this).attr("id");
                posts.sort(function(post1, post2){
                    return new Date(post2.post_date) - new Date(post1.post_date);
                });

                posts.forEach(function(post) {
                    isMapped(post.post_id, folder_id).done(function (ismapped) {
                        if (ismapped) {
                            render_post(post, post.post_summary);
                        }
                    });
                });
            });

        },
        error:function(exception) {
            alert("No folder retrieved!");
        }
    });
}

function load_post_list(posts_in_json) {
    focused_post = '-1'; // reset focused_post to -1.
    posts = JSON.parse(posts_in_json);
    posts.reverse();  // ordered by datetime from latest to earlier.

    // remove all the child elements except post_and_search.
    $('#post-list').empty();
    posts.forEach(function(post) {
        render_post(post, post.post_summary);
    });
}

// check if post and folder are mapped
function isMapped(post_id, folder_id) {
    return $.ajax({
        url:'actions.php',
        type:'post',
        data:{'action':'check_post_folder_mapping', 'post_id':post_id, 'folder_id':folder_id}
    });
}

// check the visibility of the poster for current user.
function isAnonymous(poster_id, post_privacy) {
    if ((current_user_id == poster_id) ||
        (post_privacy == '0')) {  // current user is the poster or non-anonymous post.
        return false;
    } else { // Anonymous to all or to the classmates
        if (current_user_type == '0') { // current user is a trainee
            return true;
        } else { // current user is a trainer
            if (post_privacy == '1') {  // anonymous to all.
                return true;
            } else {
                return false;
            }
        }
    }
}

// toggle the question type of the currently viewed post (question)
function toggleQuestionType(post_id, post_type) {
    // update global variable posts
    for (var i in posts) {
        if (posts[i].post_id == post_id) {
            posts[i].post_type = post_type == '0' ? '2' : '0';
            break; //Stop this loop, we found it!
        }
    }

    // update html elements
    $("#post-list").find("li#" + post_id).toggleClass("list-group-item-danger list-group-item-success");

    // update database
    $.ajax({
        url:'actions.php',
        type:'post',
        data:{'action':'toggle_question_type', 'post_id':post_id, 'post_type':post_type}
    });
}


// display the details of currently viewed post
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
                    var displayed_name = isAnonymous(poster_obj.user_id, post_obj.post_privacy) ? "Anonymous" : "<strong>"+poster_obj.first_name+"</strong>";
                    var poster_type = poster_obj.user_type == '0' ? "" : "<span class='glyphicon glyphicon-user'></span>";
                    $("#dash-board").empty();
                    $("#dash-board").append('' +
                        '<div class="panel panel-primary" style="margin-top: 10px;">'+
                        '<div class="panel-heading">' +
                        '<h4>'+poster_type+'&nbsp;<label style="margin-left: 10px;">'+ post_obj.post_summary + '<label></h4>' +
                        '</div>'+
                        '<div class="panel-body">' +
                        '<pre><code>' + post_obj.post_details + '</code></pre>' +
                        '</div>' +
                        '<div class="panel-footer" style="text-align: right;">' +
                        '<span style="font-style: italic;color:slategrey;">Posted by '+ displayed_name + ' ' + timeSince(new Date(post_obj.post_date)) + '.</span>' +
                        '</div>'+
                        '</div>');

                    var panel_footer = $("#dash-board").find(".panel-footer");

                    // add edit button to the footer if it's editable (someone's own post)
                    if (current_user_id == poster_obj.user_id) {
                        var edit_button = $("<button></button>");
                        edit_button.attr("type", "button").attr("id", "edit_post_btn").css("float", "left").addClass("btn").addClass("btn-primary").addClass("btn-xs").text("Edit").prependTo(panel_footer);
                    }


                    // retrieve all the folders related
                    $.ajax({
                        url:'actions.php',
                        type:'post',
                        data:{'action':'get_related_folders', 'post_id':post_obj.post_id},
                        success:function(folder_names_in_json){
                            // insert all the folders related to this particular post right after pre tag
                            var folder_names = JSON.parse(folder_names_in_json);
                            var panel_body = $("#dash-board").find(".panel-body");
                            for (var i = 0; i < folder_names.length; i++) {
                                panel_body.append('<span class="label label-primary">' + folder_names[i] + '</span>&nbsp;');
                            }

                            var panel_head = $("#dash-board").find(".panel-heading");

                            if (post_obj.post_type == "0") { // an unsolved question
                                panel_head.prepend('<div class="btn-group" id="toggle_event_editing" style="margin-left: 10px;">'+
                                    '<button type="button" class="btn btn-danger btn-xs locked_active">Unsolved</button>'+
                                    '<button type="button" class="btn btn-default btn-xs unlocked_inactive">Solved</button>'+
                                    '</div>');
                                panel_head.prepend('<span style="color:orange;" class="glyphicon glyphicon-question-sign"></span><span style="font-family: Impact, Haettenschweiler; color:orange;"> Question</span>');

                                var switch_btn = $("#toggle_event_editing button");

                                if (current_user_id == poster_obj.user_id) { // Current user is the poster of this particular post.
                                    switch_btn.click(function(){
                                        toggleQuestionType(post_obj.post_id, post_obj.post_type);

                                        if($(this).hasClass('locked_active') || $(this).hasClass('unlocked_inactive')){
                                            /* code to do when unlocking */
                                            // Unsolved to Solved
                                            switch_btn.eq(0).removeClass('btn-danger');
                                            switch_btn.eq(0).addClass('btn-default');
                                            switch_btn.eq(1).removeClass('btn-default');
                                            switch_btn.eq(1).addClass('btn-success');

                                        }else{
                                            /* code to do when locking */
                                            // Solved to Unsolved
                                            switch_btn.eq(0).removeClass('btn-default');
                                            switch_btn.eq(0).addClass('btn-danger');
                                            switch_btn.eq(1).removeClass('btn-success');
                                            switch_btn.eq(1).addClass('btn-default');
                                        }

                                        /* reverse locking status */
                                        switch_btn.eq(0).toggleClass('locked_inactive locked_active');
                                        switch_btn.eq(1).toggleClass('unlocked_inactive unlocked_active');
                                    });
                                } else {
                                    switch_btn.prop("disabled",true);
                                }
                            } else if (post_obj.post_type == "1") { // a note
                                panel_head.prepend('<span style="color:whitesmoke;" class="glyphicon glyphicon-pencil"></span><span style="font-family: Impact, Haettenschweiler; color:whitesmoke;"> Note</span>');
                            } else { // a solved question
                                panel_head.prepend('<div class="btn-group" id="toggle_event_editing" style="margin-left: 10px;">'+
                                    '<button type="button" class="btn btn-default btn-xs locked_inactive">Unsolved</button>'+
                                    '<button type="button" class="btn btn-success btn-xs unlocked_active">Solved</button>'+
                                    '</div>');
                                panel_head.prepend('<span style="color:orange;" class="glyphicon glyphicon-question-sign"></span><span style="font-family: Impact, Haettenschweiler; color:orange;"> Question</span>');

                                var switch_btn = $("#toggle_event_editing button");

                                if (current_user_id == poster_obj.user_id) { // Current user is the poster of this particular post.
                                    switch_btn.click(function(){
                                        toggleQuestionType(post_obj.post_id, post_obj.post_type);

                                        if($(this).hasClass('locked_active') || $(this).hasClass('unlocked_inactive')){
                                            /* code to do when unlocking */
                                            // Unsolved to Solved
                                            switch_btn.eq(0).removeClass('btn-danger');
                                            switch_btn.eq(0).addClass('btn-default');
                                            switch_btn.eq(1).removeClass('btn-default');
                                            switch_btn.eq(1).addClass('btn-success');

                                        }else{
                                            /* code to do when locking */
                                            // Solved to Unsolved
                                            switch_btn.eq(0).removeClass('btn-default');
                                            switch_btn.eq(0).addClass('btn-danger');
                                            switch_btn.eq(1).removeClass('btn-success');
                                            switch_btn.eq(1).addClass('btn-default');
                                        }

                                        /* reverse locking status */
                                        switch_btn.eq(0).toggleClass('locked_inactive locked_active');
                                        switch_btn.eq(1).toggleClass('unlocked_inactive unlocked_active');
                                    });
                                } else {
                                    switch_btn.prop("disabled",true);
                                }
                            }

                            // load the follow ups
                            load_followups();
                        },
                        error:function(exception) {
                            alert("Failed to load folders.");
                        }
                    });
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

// load followups
function load_followups() {
    var dash_board = $("#dash-board");
    dash_board.append('' +
        '<div class="panel panel-primary" style="margin-top: 10px;">'+
            '<div class="panel-heading">' +
                'Followup discussions <span style="font-style: italic; opacity:0.6; color: antiquewhite">for lingering questions and comments</span>' +
            '</div>'+
            '<div id="followup_board" class="panel-body">' +
                '<div id="followup_form" class="form-group">' +
                    '<label for="comment">Start a new followup discussion</label>' +
                    '<textarea id = "followup_input" class="form-control" rows="1" id="comment"></textarea>' +
                '</div>' +
            '</div>' +
        '</div>');

    var followup_form = $("#followup_form");
    var followup_board = $("#followup_board");
    $.ajax({
        url:'actions.php',
        type:'post',
        data:{'action':'retrieve_followups', 'post_following':focused_post},
        success:function(followups_in_json) {
            var followups = JSON.parse(followups_in_json);
            followup_form.siblings().remove();
            followups.forEach(function(followup) {
                var followup_panel = $("<div></div>");
                var displayed_name = isAnonymous(followup.added_by, followup.followup_privacy) ? "<span style='font-style: italic;'>Anonymous</span>" : "<strong><a href='#'>" + followup.added_by_name + "</a></strong>";
                followup_panel.attr("id", followup.followup_id).addClass("panel panel-primary")
                              .append('<div class="panel-body">' +
                                        displayed_name +
                                        '<span style="color: lightslategrey;font-style: italic;">  '+ timeSince(new Date(followup.followup_date))+'</span>' +
                                        '<p>' + followup.followup_details + '</p>' +
                                      '</div>');
                followup_board.prepend(followup_panel);
            });
        },
        error:function(exception) {
            alert('Failed to retrieve followups of this post.');
        }
    });
}