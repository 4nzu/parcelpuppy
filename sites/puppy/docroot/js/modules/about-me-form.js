'use strict';
ParcelPuppy.AboutMeForm = {};

jQuery(function () {
    ParcelPuppy.AboutMeForm.validateFields = function () {
        var isValid = ParcelPuppy.AboutMeForm.validateNameFields();
        return isValid;
    };

    ParcelPuppy.AboutMeForm.validateNameFields = function () {
        return ParcelPuppy.Validators.validateFormFieldsAreFilledOut([$('#about-me-form-first-name'), $('#about-me-form-last-name')]);
    };
});


