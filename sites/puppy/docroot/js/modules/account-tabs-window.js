'use strict';
ParcelPuppy.AccountTabs = {};

jQuery(function () {
    ParcelPuppy.AccountTabs.setTab = function () {
        if ($('#account-tabs').length) {
            // Defaults to profile tab
            var hash = document.location.hash || '#profile';
            $('#account-tabs a[href='+hash+']').tab('show');
        }
    };

    ParcelPuppy.AccountTabs.setHashChangeHandler = function () {
        if ($("#account-tabs").length) {
            $(window).on('hashchange', ParcelPuppy.AccountTabs.setTab);
        }
    };

    // Execute setup functions
    ParcelPuppy.AccountTabs.setTab();
    ParcelPuppy.AccountTabs.setHashChangeHandler();
});
