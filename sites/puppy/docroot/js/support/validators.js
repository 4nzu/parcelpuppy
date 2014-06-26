'use strict';
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

    ParcelPuppy.Validators.validateIntegerFieldIsFilledOutAndPositive = function (formField) {
        var isFilledOut = ParcelPuppy.Utils.isFilledOut(formField),
            value = formField.val(),
            isInteger = (Math.floor(value) == value && $.isNumeric(value)),
            isPositive = value > 0,
            isValid = isFilledOut && isInteger && isPositive;
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
