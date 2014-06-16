<div class="content-body">
    <div class="signup-form">
        <form class="form-horizontal form-reg" method="POST" action="/login" id="login-form">
            <input type="email" placeholder="Email" name="email" id="email" pattern="[^ @]*@[^ @]*">
            <input type="password" placeholder="Password" name="pass" id="pass">
            <input type="password" placeholder="Confirm Password" name="pass-confirmation" id="pass-confirmation">
            <? if (isset($_GET['badlogin']) && $_GET['badlogin'] == 1) { ?>
                <span style="color: red; font-size: 12px;">The email or password you entered is incorrect.</span>
            <? } ?>
            <a href="/reset" class="reset-pass pull-right" <? if (isset($_GET['badlogin']) && $_GET['badlogin'] == 1) {
                echo 'style="font-weight: bold; color: #222"';
            } ?>>Reset password</a>

            <div class="clearfix"></div>
            <button class="ladda-button sign-btn" data-color="green" id="sign_up_btn" data-style="zoom-in"
                    data-size="s">GO
            </button>
        </form>
    </div>
</div>