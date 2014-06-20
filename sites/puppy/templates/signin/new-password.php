<div class="content-body">
    <div class="form-wrapper row">
        <div class="col-xs-6 col-xs-offset-3">
            <form role="form" action="/newpassword" method="POST" id="new-password-form">
                <div class="form-group" id="new-password-pass-form-group">
                    <input type="password" placeholder="Password" class="form-control" name="pass" id="new-password-pass">
                    <span class="help-block" id="new-password-pass-help-block" style="display: none;">Cannot be blank</span>
                </div>
                <div class="form-group" id="new-password-pass-conf-form-group">
                    <input type="password" placeholder="Password Confirmation" class="form-control" name="pass-conf"
                           id="new-password-pass-conf">
                    <span class="help-block" id="signup-pass-conf-help-block" style="display: none;">Must match password</span>
                </div>
                <input type="hidden" name="t" value="<?= $_GET['t'] ?>"/>
                <input type="hidden" name="v" value="<?= $_GET['v'] ?>"/>
                <button id='new-password-save-button' class='gray-button' style="width: 100%">Save New Password</button>
            </form>
        </div>
    </div>
</div>
