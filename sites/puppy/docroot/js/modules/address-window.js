'use strict';
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
