$(function() {

    $('#login-form-link').click(function(e) {
        $("#login-form").delay(100).fadeIn(100);
        $("#register-form").fadeOut(100);
        $('#register-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    $('#register-form-link').click(function(e) {
        $("#register-form").delay(100).fadeIn(100);
        $("#login-form").fadeOut(100);
        $('#login-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });

    $("[data-toggle=popover]").popover({
        html: true,
        content: function() {
            return $('#popover-content').html();
        }
    });

});

function timeSince(date) {

    var seconds = Math.floor((new Date() - date) / 1000);

    var interval = Math.floor(seconds / 31536000);
    if (interval > 1) {
        return interval + " years ago";
    } else if (interval == 1) {
        return "1 year ago";
    }

    interval = Math.floor(seconds / 2592000);
    if (interval > 1) {
        return interval + " months ago";
    } else if (interval == 1) {
        return "1 month ago";
    }

    interval = Math.floor(seconds / 86400);
    if (interval > 1) {
        return interval + " days ago";
    } else if (interval == 1) {
        return "1 day ago";
    }

    interval = Math.floor(seconds / 3600);
    if (interval > 1) {
        return interval + " hours ago";
    } else if (interval == 1) {
        return "1 hour ago";
    }

    interval = Math.floor(seconds / 60);
    if (interval > 1) {
        return interval + " minutes ago";
    } else if (interval == 1) {
        return "1 minute ago";
    }
    return "just now";
}

