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
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut($('#signup-email'));
    };

    ParcelPuppy.Signup.validatePassword = function () {
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut($('#signup-pass'));
    };

    ParcelPuppy.Signup.confirmPassword = function () {
        return ParcelPuppy.Validators.validateFormFieldsMatch($('#signup-pass'), $('#signup-pass-conf'));
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