'use strict';
// Source: sites/puppy/docroot/js/init.js
var ParcelPuppy = {};

// Source: sites/puppy/docroot/js/templates/signin.js
ParcelPuppy.Signin = {};

jQuery(function () {
    ParcelPuppy.Signin.validateLoginFields = function () {
        return ParcelPuppy.Validators.validateEmailAddress($('#email')) && $('#pass').val().length > 0;
    };

    ParcelPuppy.Signin.handleSigninClick = function (e) {
        e.preventDefault();
        // var l = Ladda.create( document.querySelector( '.sign-btn' ) );
        // l.start();

        if (ParcelPuppy.Signin.validateLoginFields()) {
            $('#login-form').attr('action', '/thankyou');
            $.post('/api/v1/verify_login', {'email' : $('#email').val(), 'pass' : $('#pass').val()}, function(r) {
                if (r.request != 'OK') {
                    ParcelPuppy.Signin.showLoginError();
                }
                else {
                    $('#login-form').submit();
                }
            });
        } else {
            ParcelPuppy.Signin.showLoginError();
        }
    };

    ParcelPuppy.Signin.showLoginError = function () {
        $('#login-error').show();
    };

    ParcelPuppy.Signin.setSigninClickHandler = function () {
        $('#login-button').click(ParcelPuppy.Signin.handleSigninClick);
    };


    // Execute setup functions
    ParcelPuppy.Signin.setSigninClickHandler();
});

// Source: sites/puppy/docroot/js/templates/validators.js
ParcelPuppy.Validators = {};

jQuery(function () {
    ParcelPuppy.Validators.validateEmailAddress = function (email) {
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        return reg.test($(email).val());
    };
});