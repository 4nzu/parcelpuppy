'use strict';
ParcelPuppy.RequestForm = {};

jQuery(function () {

    ParcelPuppy.RequestForm.setSubmitBtnClickHandler = function () {
        $('#request-form-button').click(ParcelPuppy.RequestForm.handleSubmitBtnClick);
    };

    ParcelPuppy.RequestForm.handleSubmitBtnClick = function (e) {
        e.preventDefault();

        if (ParcelPuppy.RequestForm.validateForm()) {
            var postParams = ParcelPuppy.RequestForm.generatePostParams();
            $.post('/api/v1/new_request', postParams, function (r) {
                if (r.request === 'OK') {
                    window.location = '/account';
                } else {
                    $('#request-form-error').show();
                }
            });
        }

    };

    ParcelPuppy.RequestForm.generatePostParams = function () {
        var form = $('#request-form'),
            postParams = {
                description: $('#request-form').find('#request-general-form-description').val(),
                region_id: $('#request-form').find('#request-general-form-region').val(),
                shipping: $('#request-form').find('#request-general-form-shipping-group').find('.active').attr('value'),
                items: []
            };

        $.each($('#request-form-items').children(), function (index, item) {
            postParams.items.push({
                name: $(item).find('input[name=name]').val(),
                brand: $(item).find('input[name=brand]').val(),
                quantity: $(item).find('input[name=quantity]').val(),
                details: $(item).find('textarea[name=details]').val(),
                image_url: null
            });
        });

        return postParams;
    };

    // Validations
    ParcelPuppy.RequestForm.validateForm = function () {
        var isValid = ParcelPuppy.RequestGeneralForm.validateSubform();
        isValid = ParcelPuppy.RequestForm.validateFormItems();
        return isValid;
    };

    ParcelPuppy.RequestForm.validateFormItems = function () {
        var isValid = true;
        $.each($('#request-form-items').children(), function (index, itemFrame) {
            isValid = ParcelPuppy.RequestItemForm.validateSubform($(itemFrame)) && isValid;
        });
        return isValid;
    };

    // Add button logic
    ParcelPuppy.RequestForm.setAddItemBtnClickHandler = function () {
        $('#request-form-add-item-btn').click(ParcelPuppy.RequestForm.handleAddItemBtnClick);
    };

    ParcelPuppy.RequestForm.handleAddItemBtnClick = function (e) {
        e.preventDefault();

        var newItem = $('#request-form-items').children().first().clone(true),
            numberOfItems = $('#request-form-items > .request-item-form-frame').length;

        ParcelPuppy.RequestItemForm.clearItemForm(newItem);
        newItem.find('.item-number').html(numberOfItems + 1);
        $('#request-form-items').append(newItem);

        ParcelPuppy.RequestForm.updateItems();

        if ((numberOfItems + 1) === 5) {
            $('#request-form-add-item-btn').hide();
        }
    };

    ParcelPuppy.RequestForm.numberOfItems = function () {
        var totalVisible = 0;
        for (var i = 0; i < 5; i++) {
            if (($('#request-item-' + i).is(':visible'))) {
                totalVisible++;
            }
        }

        return totalVisible;
    };

    ParcelPuppy.RequestForm.updateItems = function () {
        var itemsCount = $('#request-form-items').children().length;

        $.each($('#request-form-items').children(), function (index, itemFrame) {
            $(itemFrame).find('.item-number').html(index + 1);

            if (itemsCount === 1) {
                $(itemFrame).find('.request-item-form-delete-btn').hide();
            } else {
                $(itemFrame).find('.request-item-form-delete-btn').show();
            }
        });
    };

    // Execute setup functions
    ParcelPuppy.RequestForm.setAddItemBtnClickHandler();
    ParcelPuppy.RequestForm.setSubmitBtnClickHandler();
    ParcelPuppy.RequestForm.updateItems();
});
