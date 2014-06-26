'use strict';
ParcelPuppy.RequestItemRow = {};

jQuery(function () {
    ParcelPuppy.RequestItemRow.setClickHandler = function () {
        $('.item-row-frame').click(ParcelPuppy.RequestItemRow.handleClick);
    };

    ParcelPuppy.RequestItemRow.handleClick = function (e) {
        e.preventDefault();

        $(this).find('.item-row-details').slideToggle();
    };

    // Execute setup functions
    ParcelPuppy.RequestItemRow.setClickHandler();
});
