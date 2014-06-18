<div class="form-wrapper row">
    <div class="col-xs-6 col-xs-offset-3">
        <button class='facebook-button'>Sign up with Facebook</button>

        <div class="form-option-divider">OR</div>

        <form role="form" action="/extras" method="POST" id="signup-form">
            <div class="form-group" id="signup-email-form-group">
                <input type="email" placeholder="Email" class="form-control" name="email" id="signup-email"
                       pattern="[^ @]*@[^ @]*">
                <span class="help-block" id="signup-email-help-block" style="display: none;">Invalid email address</span>
            </div>
            <div class="form-group" id="signup-pass-form-group">
                <input type="password" placeholder="Password" class="form-control" name="pass" id="signup-pass">
                <span class="help-block" id="signup-pass-help-block" style="display: none;">Cannot be blank</span>
            </div>
            <div class="form-group" id="signup-pass-conf-form-group">
                <input type="password" placeholder="Password Confirmation" class="form-control" name="pass-conf" id="signup-pass-conf">
                <span class="help-block" id="signup-pass-conf-help-block" style="display: none;">Must match password</span>
            </div>
            <button id='signup-button' class='gray-button' style="width: 100%">Sign up with email</button>
            <div class="form-alternative-action">
                Have an account? <a href="/signin">Log In</a>
            </div>

        </form>
    </div>
</div>