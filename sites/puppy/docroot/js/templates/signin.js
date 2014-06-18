'use strict';
ParcelPuppy.Signup = {};

jQuery(function () {
    ParcelPuppy.Signup.validateLoginFields = function () {
        return ParcelPuppy.Validators.validateEmailAddress($('#email')) && $('#pass').val().length > 0;
    };

    ParcelPuppy.Signup.handleSigninClick = function (e) {
        e.preventDefault();

        if (ParcelPuppy.Signup.validateLoginFields()) {
            $('#login-form').attr('action', '/thankyou');
            $.post('/api/v1/verify_login', {'email' : $('#email').val(), 'pass' : $('#pass').val()}, function(r) {
                if (r.request !== 'OK') {
                    ParcelPuppy.Signup.showLoginError();
                }
                else {
                    $('#login-form').submit();
                }
            });
        } else {
            ParcelPuppy.Signup.showLoginError();
        }
    };

    ParcelPuppy.Signup.showLoginError = function () {
        $('#login-error').show();
    };

    ParcelPuppy.Signup.setSigninClickHandler = function () {
        $('#login-button').click(ParcelPuppy.Signup.handleSigninClick);
    };


    // Execute setup functions
    ParcelPuppy.Signup.setSigninClickHandler();
});