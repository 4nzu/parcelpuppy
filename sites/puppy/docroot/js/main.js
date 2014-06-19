'use strict';
// Source: sites/puppy/docroot/js/init.js
var ParcelPuppy = {};


// Source: sites/puppy/docroot/js/support/utils.js
ParcelPuppy.Utils = {};

jQuery(function() {
    ParcelPuppy.Utils.isFilledOut = function (formField) {
        return formField.val() && formField.val().length > 0;
    };

    ParcelPuppy.Utils.displayErrorForField = function (formField) {
        var fieldGroup = formField.closest('.form-group'),
            helpBlock = fieldGroup.find('.help-block');

        if (formField && fieldGroup && helpBlock) {
            fieldGroup.attr('class', 'form-group has-error');
            helpBlock.show();
        }
    };

    ParcelPuppy.Utils.removeErrorForField = function (formField) {
        var fieldGroup = formField.closest('.form-group'),
            helpBlock = fieldGroup.find('.help-block');

        if (formField && fieldGroup && helpBlock) {
            fieldGroup.attr('class', 'form-group');
            helpBlock.hide();
        }
    };

    ParcelPuppy.Utils.setErrorForField = function (isValid, formField) {
        if (isValid) {
            ParcelPuppy.Utils.removeErrorForField(formField);
        } else {
            ParcelPuppy.Utils.displayErrorForField(formField);
        }
    };


    ParcelPuppy.Utils.generatePostParamsForForm = function (form) {
        var formFields = form.serializeArray();
        var postParams = {};

        $.each(formFields, function (index, formField) {
            postParams[formField.name] = formField.value;
        });

        return postParams;
    };
});


// Source: sites/puppy/docroot/js/support/validators.js
ParcelPuppy.Validators = {};

jQuery(function () {
    ParcelPuppy.Validators.validateEmailAddress = function (email) {
        var reg = /^([A-Za-z0-9_\-\.+])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        return reg.test($(email).val());
    };

    ParcelPuppy.Validators.validateFormFieldIsFilledOut = function (formField) {
        var isValid = ParcelPuppy.Utils.isFilledOut(formField);
        ParcelPuppy.Utils.setErrorForField(isValid, formField);
        return isValid;
    };

    // It is assumed that all formFields are part of the same group
    ParcelPuppy.Validators.validateFormFieldsAreFilledOut = function (formFields) {
        var isValid = true;
        $.each(formFields, function (index, formField) {
            isValid = ParcelPuppy.Utils.isFilledOut(formField) && isValid;
        });

        ParcelPuppy.Utils.setErrorForField(isValid, formFields[0]);
        return isValid;
    };

    // It is assumed that both fields are part of the same group for showing errors
    ParcelPuppy.Validators.validateFormFieldsMatch = function (fieldOne, fieldTwo) {
        var isValid = fieldOne.val() === fieldTwo.val();
        ParcelPuppy.Utils.setErrorForField(isValid, fieldOne);
        return isValid;
    };
});

// Source: sites/puppy/docroot/js/templates/extras.js
ParcelPuppy.Extras = {};

jQuery(function () {
    ParcelPuppy.Extras.setExtrasButtonHandler = function () {
        $('#extras-button').click(ParcelPuppy.Extras.handleExtrasButtonClick);
    };

    ParcelPuppy.Extras.handleExtrasButtonClick = function (e) {
        if (ParcelPuppy.Address.validateAddressFields()) {
            var postParams = ParcelPuppy.Utils.generatePostParamsForForm($('#extras-form'));
            $.post('/api/v1/update_extras', postParams, function (r) {
                if (r.request === 'OK') {
                    window.location = '/account';
                } else {
                    $('#extras-error').show();
                }
            });
        }
    };

    // Execute setup functions
    ParcelPuppy.Extras.setExtrasButtonHandler();
});

// Source: sites/puppy/docroot/js/modules/address-window.js
ParcelPuppy.Address = {};

jQuery(function () {
    ParcelPuppy.Address.validateAddressFields = function () {
        var isValid = ParcelPuppy.Address.validateNameFields();
        isValid = ParcelPuppy.Address.validateStreetAddressFields() && isValid;
        isValid = ParcelPuppy.Address.validateStateField() && isValid;
        isValid = ParcelPuppy.Address.validateCityZipFields() && isValid;
        return isValid;
    };

    ParcelPuppy.Address.validateNameFields = function () {
        return ParcelPuppy.Validators.validateFormFieldsAreFilledOut([$('#address-first-name'), $('#address-last-name')]);
    };

    ParcelPuppy.Address.validateStreetAddressFields = function () {
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut($('#address-street-1'));
    };

    ParcelPuppy.Address.validateStateField = function () {
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut($('#address-state'));
    };

    ParcelPuppy.Address.validateCityZipFields = function () {
        return ParcelPuppy.Validators.validateFormFieldsAreFilledOut([$('#address-city'), $('#address-zip-code')]);
    };
});


// Source: sites/puppy/docroot/js/modules/signin-window.js
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

// Source: sites/puppy/docroot/js/modules/signup-window.js
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