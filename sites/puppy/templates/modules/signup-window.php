<div class="login-form row">
    <div class="col-xs-6 col-xs-offset-3">
        <div class="alert alert-dismissable alert-danger" style="display: none;" id="login-error">
            Invalid Email Address or Password
        </div>


        <button class='facebook-signup'>Sign up with Facebook</button>

        <div class="login-form-option-divider">OR</div>

        <form role="form" action="/login" method="POST" id="login-form">
            <div class="form-group">
                <input type="email" placeholder="Email" class="form-control" name="email" id="email"
                       pattern="[^ @]*@[^ @]*">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Password" class="form-control" name="pass" id="pass">
            </div>
            <button id='login-button' class='gray-button' style="width: 100%">Log in with email</button>
            <div class="login-form-alternative-action">
                <a href="/reset">Forgot your password?</a>
            </div>
            <div class="login-form-alternative-action">
                New User? <a href="/signup">Sign Up</a>
            </div>

        </form>
    </div>
</div>