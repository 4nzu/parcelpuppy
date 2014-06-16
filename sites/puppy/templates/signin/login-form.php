<div class="content-body">
    <div class="login-form row">
        <div class="col-xs-6 col-xs-offset-3">
            <? if (isset($_GET['badlogin']) && $_GET['badlogin'] == 1) { ?>
                <div class="panel panel-danger" style="color: red; font-size: 12px;">The email or password you entered is incorrect.</span>
            <? } ?>

            <button class='facebook-login'>Log in with Facebook</button>

            <div class="login-form-option-divider">OR</div>

            <form role="form" action="/login" method="post">
                <div class="form-group">
                    <input type="email" placeholder="Email" class="form-control" name="email" id="email"
                           pattern="[^ @]*@[^ @]*">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" class="form-control" name="pass" id="pass">
                </div>
                <button type="submit" class='gray-button' style="width: 100%">Log in with email</button>
                <div class="login-form-alternative-action">
                    <a href="/reset">Forgot your password?</a>
                </div>
                <div class="login-form-alternative-action">
                    New User? <a href="/signup">Sign Up</a>
                </div>

            </form>
        </div>
    </div>

</div>

