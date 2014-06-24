'use strict';
ParcelPuppy.ProfileForm = {};

jQuery(function () {
    ParcelPuppy.ProfileForm.setProfileFormButtonHandler = function () {
        $('#profile-button').click(ParcelPuppy.ProfileForm.handleProfileFormButtonClick);
    };

    ParcelPuppy.ProfileForm.handleProfileFormButtonClick = function (e) {
        e.preventDefault();

        if (ParcelPuppy.ProfileForm.validateForm()) {
            var postParams = ParcelPuppy.Utils.generatePostParamsForForm($('#profile-form'));
            $.post('/api/v1/save_settings', postParams, function (r) {
                if (r.request === 'OK') {
                    window.location = '/account';
                } else {
                    $('#profile-form-error').show();
                }
            });
        }
    };

    ParcelPuppy.ProfileForm.validateForm = function() {
        var isValid = ParcelPuppy.AddressForm.validateFields();
        isValid = ParcelPuppy.AboutMeForm.validateFields() && isValid;
        return isValid;
    }

    // Execute setup functions
    ParcelPuppy.ProfileForm.setProfileFormButtonHandler();
});