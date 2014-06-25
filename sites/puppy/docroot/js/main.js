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
            fieldGroup.addClass('has-error');
            helpBlock.show();
        }
    };

    ParcelPuppy.Utils.removeErrorForField = function (formField) {
        var fieldGroup = formField.closest('.form-group'),
            helpBlock = fieldGroup.find('.help-block');

        if (formField && fieldGroup && helpBlock) {
            fieldGroup.removeClass('has-error');
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

    ParcelPuppy.Utils.enableAffixFormButtons = function () {
        $('.form-fixed-button').affix({
            offset: {
                bottom: function () {
                    return (this.bottom = $('.footer').outerHeight(true))
                }
            }
        });
    };

    // Execute setup functions
    ParcelPuppy.Utils.enableAffixFormButtons();
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

    ParcelPuppy.Validators.validateIntegerFieldIsFilledOut = function (formField) {
        var isFilledOut = ParcelPuppy.Utils.isFilledOut(formField),
            value = formField.val(),
            isInteger = (Math.floor(value) == value && $.isNumeric(value)),
            isValid = isFilledOut && isInteger;
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

    // It is assumed that the error should be set for field one
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
        e.preventDefault();

        if (ParcelPuppy.AddressForm.validateFields()) {
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

// Source: sites/puppy/docroot/js/templates/new-password.js
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


// Source: sites/puppy/docroot/js/templates/profile-form.js
ParcelPuppy.ProfileForm = {};

jQuery(function () {
    ParcelPuppy.ProfileForm.setProfileFormButtonHandler = function () {
        $('#profile-button').click(ParcelPuppy.ProfileForm.handleProfileFormButtonClick);
    };

    ParcelPuppy.ProfileForm.handleProfileFormButtonClick = function (e) {
        e.preventDefault();

        if (ParcelPuppy.ProfileForm.validateForm()) {
            var postParams = ParcelPuppy.Utils.generatePostParamsForForm($('#profile-form'));
            $.post('/api/v1/save_settings', postParams, function (r) {
                if (r.request === 'OK') {
                    window.location = '/account';
                } else {
                    $('#profile-form-error').show();
                }
            });
        }
    };

    ParcelPuppy.ProfileForm.validateForm = function() {
        var isValid = ParcelPuppy.AddressForm.validateFields();
        isValid = ParcelPuppy.AboutMeForm.validateFields() && isValid;
        return isValid;
    }

    // Execute setup functions
    ParcelPuppy.ProfileForm.setProfileFormButtonHandler();
});

// Source: sites/puppy/docroot/js/templates/request-form.js
ParcelPuppy.RequestForm = {};

jQuery(function () {

    ParcelPuppy.RequestForm.setSubmitBtnClickHandler = function () {
        $('#request-form-button').click(ParcelPuppy.RequestForm.handleSubmitBtnClick);
    };

    ParcelPuppy.RequestForm.handleSubmitBtnClick = function (e) {
        e.preventDefault();

        if (ParcelPuppy.RequestForm.validateForm()) {
            var postParams = ParcelPuppy.RequestForm.generatePostParams();
            $.post('/api/v1/new_request', postParams, function (r) {
                if (r.request === 'OK') {
                    window.location = '/account';
                } else {
                    $('#request-form-error').show();
                }
            });
        }

    };

    ParcelPuppy.RequestForm.generatePostParams = function () {
        var form = $('#request-form'),
            postParams = {
                description: $('#request-form').find('#request-general-form-description').val(),
                region_id: $('#request-form').find('#request-general-form-region').val(),
                shipping: $('#request-form').find('#request-general-form-shipping-group').find('.active').attr('value'),
                items: []
            };

        $.each($('#request-form-items').children(), function (index, item) {
            postParams.items.push({
                name: $(item).find('input[name=name]').val(),
                brand: $(item).find('input[name=brand]').val(),
                quantity: $(item).find('input[name=quantity]').val(),
                details: $(item).find('textarea[name=details]').val(),
                image_url: null
            });
        });

        return postParams;
    };

    // Validations
    ParcelPuppy.RequestForm.validateForm = function () {
        var isValid = ParcelPuppy.RequestGeneralForm.validateSubform();
        isValid = ParcelPuppy.RequestForm.validateFormItems();
        return isValid;
    };

    ParcelPuppy.RequestForm.validateFormItems = function () {
        var isValid = true;
        $.each($('#request-form-items').children(), function (index, itemFrame) {
            isValid = ParcelPuppy.RequestItemForm.validateSubform($(itemFrame)) && isValid;
        });
        return isValid;
    };

    // Add button logic
    ParcelPuppy.RequestForm.setAddItemBtnClickHandler = function () {
        $('#request-form-add-item-btn').click(ParcelPuppy.RequestForm.handleAddItemBtnClick);
    };

    ParcelPuppy.RequestForm.handleAddItemBtnClick = function (e) {
        e.preventDefault();

        var newItem = $('#request-form-items').children().first().clone(true),
            numberOfItems = $('#request-form-items > .request-item-form-frame').length;

        ParcelPuppy.RequestItemForm.clearItemForm(newItem);
        newItem.find('.item-number').html(numberOfItems + 1);
        $('#request-form-items').append(newItem);

        ParcelPuppy.RequestForm.updateItems();

        if ((numberOfItems + 1) === 5) {
            $('#request-form-add-item-btn').hide();
        }
    };

    ParcelPuppy.RequestForm.numberOfItems = function () {
        var totalVisible = 0;
        for (var i = 0; i < 5; i++) {
            if (($('#request-item-' + i).is(':visible'))) {
                totalVisible++;
            }
        }

        return totalVisible;
    };

    ParcelPuppy.RequestForm.updateItems = function () {
        var itemsCount = $('#request-form-items').children().length;

        $.each($('#request-form-items').children(), function (index, itemFrame) {
            $(itemFrame).find('.item-number').html(index + 1);

            if (itemsCount === 1) {
                $(itemFrame).find('.request-item-form-delete-btn').hide();
            } else {
                $(itemFrame).find('.request-item-form-delete-btn').show();
            }
        });
    };

    // Execute setup functions
    ParcelPuppy.RequestForm.setAddItemBtnClickHandler();
    ParcelPuppy.RequestForm.setSubmitBtnClickHandler();
    ParcelPuppy.RequestForm.updateItems();
});


// Source: sites/puppy/docroot/js/templates/request-item-form.js
ParcelPuppy.RequestItemForm = {};

jQuery(function () {
    ParcelPuppy.RequestItemForm.setDeleteBtnClickHandler = function () {
        $('.request-item-form-delete-btn').click(ParcelPuppy.RequestItemForm.handleDeleteItemBtnClick);
    };

    ParcelPuppy.RequestItemForm.handleDeleteItemBtnClick = function (e) {
        e.preventDefault();

        var itemFrame = $(this).closest('.request-item-form-frame');
        itemFrame.remove();
        ParcelPuppy.RequestForm.updateItems();
        $('#request-form-add-item-btn').show();
    };

    ParcelPuppy.RequestItemForm.clearItemForm = function (formFrame) {
        formFrame.find('input[name=name]').val('');
        formFrame.find('input[name=brand]').val('');
        formFrame.find('input[name=quantity]').val(1);
        formFrame.find('input[name=details]').val('');
    };

    // Execute setup functions
    ParcelPuppy.RequestItemForm.setDeleteBtnClickHandler();

});


// Source: sites/puppy/docroot/js/modules/about-me-form.js
ParcelPuppy.AboutMeForm = {};

jQuery(function () {
    ParcelPuppy.AboutMeForm.validateFields = function () {
        var isValid = ParcelPuppy.AboutMeForm.validateNameFields();
        isValid = ParcelPuppy.AboutMeForm.validateEmailField() && isValid;
        return isValid;
    };

    ParcelPuppy.AboutMeForm.validateNameFields = function () {
        return ParcelPuppy.Validators.validateFormFieldsAreFilledOut([$('#about-me-form-first-name'), $('#about-me-form-last-name')]);
    };

    ParcelPuppy.AboutMeForm.validateEmailField = function () {
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut($('#about-me-form-email'));
    };
});




// Source: sites/puppy/docroot/js/modules/account-tabs-window.js
ParcelPuppy.AccountTabs = {};

jQuery(function () {
    ParcelPuppy.AccountTabs.setTab = function () {
        if ($('#account-tabs').length) {
            // Defaults to profile tab
            var hash = document.location.hash || '#profile';
            $('#account-tabs a[href='+hash+']').tab('show');
        }
    };

    ParcelPuppy.AccountTabs.setHashChangeHandler = function () {
        if ($("#account-tabs").length) {
            $(window).on('hashchange', ParcelPuppy.AccountTabs.setTab);
        }
    };

    // Execute setup functions
    ParcelPuppy.AccountTabs.setTab();
    ParcelPuppy.AccountTabs.setHashChangeHandler();
});


// Source: sites/puppy/docroot/js/modules/address-form.js
ParcelPuppy.AddressForm = {};

jQuery(function () {
    ParcelPuppy.AddressForm.validateFields = function () {
        var isValid = ParcelPuppy.AddressForm.validateNameFields();
        isValid = ParcelPuppy.AddressForm.validateStreetAddressFields() && isValid;
        isValid = ParcelPuppy.AddressForm.validateStateField() && isValid;
        isValid = ParcelPuppy.AddressForm.validateCityZipFields() && isValid;
        return isValid;
    };

    ParcelPuppy.AddressForm.validateNameFields = function () {
        if ($('#address-form-first-name').length && $('#address-form-last-name').length){
            return ParcelPuppy.Validators.validateFormFieldsAreFilledOut([$('#address-form-first-name'), $('#address-form-last-name')]);
        }
        return true;
    };

    ParcelPuppy.AddressForm.validateStreetAddressFields = function () {
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut($('#address-form-street-1'));
    };

    ParcelPuppy.AddressForm.validateStateField = function () {
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut($('#address-form-state'));
    };

    ParcelPuppy.AddressForm.validateCityZipFields = function () {
        return ParcelPuppy.Validators.validateFormFieldsAreFilledOut([$('#address-form-city'), $('#address-form-zip-code')]);
    };
});


// Source: sites/puppy/docroot/js/modules/request-general-form.js
ParcelPuppy.RequestGeneralForm = {};

jQuery(function () {
    ParcelPuppy.RequestGeneralForm.validateSubform = function () {
        var isValid = ParcelPuppy.RequestGeneralForm.validateDescription();
        return isValid;
    };
    
    ParcelPuppy.RequestGeneralForm.validateDescription = function () {
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut($('#request-general-form-description'));
    };

    ParcelPuppy.RequestGeneralForm.setShipping = function () {
        var shippingGroup = $('#request-general-form-shipping-group'),
            defaultSelection = shippingGroup.attr('data-default');

        if (defaultSelection === 'express') {
            $('#request-general-form-shipping-express').button('toggle');
        } else {
            $('#request-general-form-shipping-standard').button('toggle');
        }
    };

    // Execute setup functions
    ParcelPuppy.RequestGeneralForm.setShipping();
});


// Source: sites/puppy/docroot/js/modules/request-item-form.js
ParcelPuppy.RequestItemForm = {};

jQuery(function () {
    ParcelPuppy.RequestItemForm.validateSubform = function (itemForm) {
        var isValid = ParcelPuppy.RequestItemForm.validateDetails(itemForm);
        isValid = ParcelPuppy.RequestItemForm.validateName(itemForm) && isValid;
        isValid = ParcelPuppy.RequestItemForm.validateQuantity(itemForm) && isValid;
        return isValid;
    };


    ParcelPuppy.RequestItemForm.validateName = function (itemForm) {
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut(itemForm.find('input[name=name]'));
    };

    ParcelPuppy.RequestItemForm.validateDetails = function (itemForm) {
        return ParcelPuppy.Validators.validateFormFieldIsFilledOut(itemForm.find('textarea[name=details]'))
    };

    ParcelPuppy.RequestItemForm.validateQuantity = function (itemForm) {
        return ParcelPuppy.Validators.validateIntegerFieldIsFilledOut(itemForm.find('input[name=quantity]'))
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