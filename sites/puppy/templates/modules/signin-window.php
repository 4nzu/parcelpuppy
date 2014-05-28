<div class="darkwin"></div>
<div class="popover bottom popover-reg signin-window window">
    <i class="window-close icon-small icon-remove"></i>
    <div class="popover-content">
    <h5>Sign in</h5>
    <form class="form-horizontal form-reg" method="POST" action="/login" id="login-form">
        <input type="email" placeholder="Email" name="email" id="email" pattern="[^ @]*@[^ @]*">
        <label class="radio">
            <input type="radio" class="new-user-radio" name="userlogin"> I'm a new user
        </label>
        <label class="radio">
            <input type="radio" name="userlogin" class="user-radio" checked> I'm an existing user
        </label>
        <input type="password" placeholder="Password" name="pass" id="pass">
        <? if (isset($_GET['badlogin']) && $_GET['badlogin'] == 1) { ?>
        <span style="color: red; font-size: 12px;">The email or password you entered is incorrect.</span>
        <? } ?>
        <a href="/reset" class="reset-pass pull-right" <? if (isset($_GET['badlogin']) && $_GET['badlogin'] == 1) { echo 'style="font-weight: bold; color: #222"'; } ?>>Reset password</a>
        <div class="clearfix"></div>
        <button class="ladda-button sign-btn" data-color="green" id="sign_up_btn" data-style="zoom-in" data-size="s">GO</button>
    </form>
    <!-- <div class="clearfix or-connect-btns">or</div>
    <div class="connect-btns">
        <div class="fb_connect_btn"></div>
        <div id="google-signin"></div>
    </div> -->
    </div>
</div>
<script src="/js/validate_credentials.js"></script>
<script src="/js/ladda.min.js"></script>
<script>

$(document).ready(function () {

    $('.signin').click(function(e) {
        e.preventDefault();
        $('.darkwin').show();
        $('.signin-window').show();
    })
 
	$('.darkwin, .window-close').click(function(e) { 
        e.preventDefault();
		$('.darkwin').hide();
		$('.signin-window').hide();
	});

    $(document).keyup(function(e) {
      if (e.keyCode == 27) { 
        $('.darkwin').hide();
        $('.signin-window').hide();
      } 
    });

    $('#sign_up_btn').click(function(e) { 
        e.preventDefault();
        // var l = Ladda.create( document.querySelector( '.sign-btn' ) );
        // l.start();

        if (validate_input(0)) {
            if ($('.new-user-radio').is(':checked')) { 
                $('#login-form').attr('action', '/thankyou');
                $.post('/api/v1/verify_login', {'email' : $('#email').val(), 'pass' : $('#pass').val()}, function(r) {
                    if (r.request != 'OK') {
                         $('#pass').addClass('error');
                    }
                    else {
                        $('#login-form').submit();
                    }
                });
            } else {
                $('#login-form').attr('action', '/login');
                $('#login-form').submit();
            }
        }
    });

});
</script>