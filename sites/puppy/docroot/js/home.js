$(document).ready(function () {
    var show_login = get_parameter_by_name('badlogin');
    var email_in_use = get_parameter_by_name('emailinuse');

    if (parseInt(show_login) == 1 || parseInt(email_in_use) == 1) {
        $('.darkwin').show();
        $('.signin-window').show();
    }
});