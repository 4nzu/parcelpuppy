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
        return ParcelPuppy.Validators.validateIntegerFieldIsFilledOutAndPositive(itemForm.find('input[name=quantity]'))
    };

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
