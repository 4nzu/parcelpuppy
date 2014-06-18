<div class="form-wrapper row">
    <div class="col-xs-6 col-xs-offset-3">
        <?
        if ((isset($_GET['badlogin']) && $_GET['badlogin'] == 1)) {
            $error_display = 'inline';
        } else {
            $error_display = 'none';
        }
        ?>
        <div class="alert alert-dismissable alert-danger" style="display: <?= $error_display ?>;" id="login-error">
            Invalid Email Address or Password
        </div>


        <button class='facebook-button'>Log in with Facebook</button>

        <div class="form-option-divider">OR</div>

        <form role="form" action="/login" method="POST" id="login-form">
            <div class="form-group">
                <input type="email" placeholder="Email" class="form-control" name="email" id="email"
                       pattern="[^ @]*@[^ @]*">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Password" class="form-control" name="pass" id="pass">
            </div>
            <button id='login-button' class='gray-button' style="width: 100%">Log in with email</button>
            <div class="form-alternative-action">
                <a href="/reset">Forgot your password?</a>
            </div>
            <div class="form-alternative-action">
                New User? <a href="/signup">Sign Up</a>
            </div>

        </form>
    </div>
</div>