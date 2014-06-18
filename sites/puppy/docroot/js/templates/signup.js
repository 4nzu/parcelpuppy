'use strict';
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