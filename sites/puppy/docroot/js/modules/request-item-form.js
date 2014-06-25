'use strict';
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
