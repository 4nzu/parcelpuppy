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