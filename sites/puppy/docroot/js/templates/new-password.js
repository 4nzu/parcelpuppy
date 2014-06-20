'use strict';
ParcelPuppy.NewPassword = {};

jQuery(function () {
    ParcelPuppy.NewPassword.setNewPasswordClickHandler = function () {
        $('#new-password-save-button').click(ParcelPuppy.NewPassword.handleNewPasswordClick);
    };

    ParcelPuppy.NewPassword.handleNewPasswordClick = function (e) {
        e.preventDefault();

        if (ParcelPuppy.NewPassword.validateForm()) {
            $('#new-password-form').submit();
        }
    };

    ParcelPuppy.NewPassword.validateForm = function () {
        var isValid = ParcelPuppy.NewPassword.validatePassword();
        isValid = ParcelPuppy.NewPassword.confirmPassword() && isValid;
        return isValid;
    };
    
    ParcelPuppy.NewPassword.validatePassword = function () {
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut($('#new-password-pass'));
    };

    ParcelPuppy.NewPassword.confirmPassword = function () {
        return ParcelPuppy.Validators.validateFormFieldsMatch($('#new-password-pass-conf'), $('#new-password-pass'));
    };

    ParcelPuppy.NewPassword.setPasswordChangeHandler = function () {
        $('#new-password-pass').change(ParcelPuppy.NewPassword.handlePasswordChanged);
    };

    ParcelPuppy.NewPassword.setPasswordConfirmationCheck = function () {
        $('#new-password-pass-conf').change(ParcelPuppy.NewPassword.confirmPassword);
    };

    ParcelPuppy.NewPassword.handlePasswordChanged = function (e) {
        ParcelPuppy.NewPassword.validatePassword();

        if ($('#new-password-pass-conf').val().length > 0) {
            ParcelPuppy.NewPassword.confirmPassword();
        }
    };

    // Execute setup functions
    ParcelPuppy.NewPassword.setNewPasswordClickHandler();
    ParcelPuppy.NewPassword.setPasswordChangeHandler();
    ParcelPuppy.NewPassword.setPasswordConfirmationCheck();
});
