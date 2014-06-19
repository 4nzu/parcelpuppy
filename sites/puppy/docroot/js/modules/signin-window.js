'use strict';
ParcelPuppy.Signup = {};

jQuery(function () {
    ParcelPuppy.Signup.validateLoginFields = function () {
        return ParcelPuppy.Validators.validateEmailAddress($('#signin-email')) && ParcelPuppy.Utils.isFilledOut($('#signin-pass'));
    };

    ParcelPuppy.Signup.handleSigninClick = function (e) {
        e.preventDefault();

        if (ParcelPuppy.Signup.validateLoginFields()) {
            $.post('/api/v1/verify_login', {'email' : $('#signin-email').val(), 'pass' : $('#signin-pass').val()}, function(r) {
                if (r.request !== 'OK') {
                    ParcelPuppy.Signup.showLoginError();
                }
                else {
                    $('#signin-form').submit();
                }
            });
        } else {
            ParcelPuppy.Signup.showLoginError();
        }
    };

    ParcelPuppy.Signup.showLoginError = function () {
        $('#signin-error').show();
    };

    ParcelPuppy.Signup.setSigninClickHandler = function () {
        $('#signin-button').click(ParcelPuppy.Signup.handleSigninClick);
    };


    // Execute setup functions
    ParcelPuppy.Signup.setSigninClickHandler();
});