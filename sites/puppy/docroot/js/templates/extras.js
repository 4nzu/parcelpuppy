'use strict';
ParcelPuppy.Extras = {};

jQuery(function () {
    ParcelPuppy.Extras.setExtrasButtonHandler = function () {
        $('#extras-button').click(ParcelPuppy.Extras.handleExtrasButtonClick);
    };

    ParcelPuppy.Extras.handleExtrasButtonClick = function (e) {
        if (ParcelPuppy.Address.validateAddressFields()) {
            var postParams = ParcelPuppy.Utils.generatePostParamsForForm($('#extras-form'));
            $.post('/api/v1/update_extras', postParams, function (r) {
                if (r.request === 'OK') {
                    window.location = '/account';
                } else {
                    $('#extras-error').show();
                }
            });
        }
    };

    // Execute setup functions
    ParcelPuppy.Extras.setExtrasButtonHandler();
});