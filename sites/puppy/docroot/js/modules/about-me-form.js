'use strict';
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


