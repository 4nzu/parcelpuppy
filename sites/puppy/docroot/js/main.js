'use strict';
// Source: sites/puppy/docroot/js/init.js
var ParcelPuppy = {};

// Source: sites/puppy/docroot/js/templates/signin.js
ParcelPuppy.Signup = {};

jQuery(function () {
    ParcelPuppy.Signup.validateLoginFields = function () {
        return ParcelPuppy.Validators.validateEmailAddress($('#signin-email')) && $('#signin-pass').val().length > 0;
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

// Source: sites/puppy/docroot/js/templates/signup.js
ParcelPuppy.Signup = {};

jQuery(function () {
    ParcelPuppy.Signup.setSignupClickHandler = function () {
        $('#signup-button').click(ParcelPuppy.Signup.handleSignupClick);
    };

    ParcelPuppy.Signup.handleSignupClick = function (e) {
        e.preventDefault();

        if (ParcelPuppy.Signup.validateSignupForm()) {
            $('#signup-form').submit();
        }
    };

    ParcelPuppy.Signup.validateSignupForm = function () {
        var isValid = ParcelPuppy.Signup.validateEmailAddress();
        isValid = ParcelPuppy.Signup.validatePassword() && isValid;
        isValid = ParcelPuppy.Signup.confirmPassword() && isValid;
        return isValid;
    };

    ParcelPuppy.Signup.validateEmailAddress = function () {
        if (ParcelPuppy.Validators.validateEmailAddress($('#signup-email'))) {
            $('#signup-email-form-group').attr('class', 'form-group');
            $('#signup-email-help-block').hide();
            return true;
        } else {
            $('#signup-email-form-group').attr('class', 'form-group has-error');
            $('#signup-email-help-block').show();
            return false;
        }
    };

    ParcelPuppy.Signup.validatePassword = function () {
        if ($('#signup-pass').val() && $('#signup-pass').val().length > 0) {
            $('#signup-pass-form-group').attr('class', 'form-group');
            $('#signup-pass-help-block').hide();
            return true;
        } else {
            $('#signup-pass-form-group').attr('class', 'form-group has-error');
            $('#signup-pass-help-block').show();
            return false;
        }
    };

    ParcelPuppy.Signup.confirmPassword = function () {
        if ($('#signup-pass').val() === $('#signup-pass-conf').val()) {
            $('#signup-pass-conf-form-group').attr('class', 'form-group has-success');
            $('#signup-pass-conf-help-block').hide();
            return true;
        } else {
            $('#signup-pass-conf-form-group').attr('class', 'form-group has-error');
            $('#signup-pass-conf-help-block').show();
            return false;
        }
    };

    ParcelPuppy.Signup.setEmailChangeHandler = function () {
        $('#signup-email').change(ParcelPuppy.Signup.validateEmailAddress);
    };

    ParcelPuppy.Signup.setPasswordChangeHandler = function () {
        $('#signup-pass').change(ParcelPuppy.Signup.handlePasswordChanged);
    };

    ParcelPuppy.Signup.setPasswordConfirmationCheck = function () {
        $('#signup-pass-conf').change(ParcelPuppy.Signup.confirmPassword);
    };

    ParcelPuppy.Signup.handlePasswordChanged = function (e) {
        ParcelPuppy.Signup.validatePassword();

        if ($('#signup-pass-conf').val().length > 0) {
            ParcelPuppy.Signup.confirmPassword();
        }
    };

    // Execute setup function
    ParcelPuppy.Signup.setSignupClickHandler();
    ParcelPuppy.Signup.setEmailChangeHandler();
    ParcelPuppy.Signup.setPasswordChangeHandler();
    ParcelPuppy.Signup.setPasswordConfirmationCheck();

});

// Source: sites/puppy/docroot/js/templates/validators.js
ParcelPuppy.Validators = {};

jQuery(function () {
    ParcelPuppy.Validators.validateEmailAddress = function (email) {
        var reg = /^([A-Za-z0-9_\-\.+])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        return reg.test($(email).val());
    };
});