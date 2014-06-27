'use strict';
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
