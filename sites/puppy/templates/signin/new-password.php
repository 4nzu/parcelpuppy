<form class="form-horizontal" action="/newpassword" method="POST" id="new-pass">
    <fieldset>
        <div class="well">
            <div class="control-group">
                <label for="password" class="control-label">New Password</label>
                <div class="controls">
                    <input type="password" name="password" id="password"/>
                </div>
            </div>
            <div class="control-group">
            </div>
            <div class="control-group">
                <div class="controls">
                    <input type="hidden" name="t" value="<?= $_GET['t'] ?>"/>
                    <input type="hidden" name="v" value="<?= $_GET['v'] ?>"/>
                    <button class="ladda-button set-new-pass" id="login" data-color="mint" data-size="s">Set New Password</button>
                </div>
            </div>
        </div>
    </fieldset>
</form>
<script>
$(document).ready(function () {
    $('#password').val('');
});
</script>
