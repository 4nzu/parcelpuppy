'use strict';
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
