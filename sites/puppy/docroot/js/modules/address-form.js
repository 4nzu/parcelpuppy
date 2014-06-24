'use strict';
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
