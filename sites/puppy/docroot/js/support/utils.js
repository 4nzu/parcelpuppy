'use strict';
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
