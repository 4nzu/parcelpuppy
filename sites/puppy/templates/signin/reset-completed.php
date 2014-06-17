<div class="content-body">
    <div style="text-align: center">
        <? if ($reset_completed == 0) { ?>
            <h3>You will receive an email with instructions how to reset your password.</h3>
        <? } elseif ($reset_completed == 1) { ?>
            <h3>
                Your new password was saved successfully.<br>
                <small>Now you can login in using your email and password.</small>
            </h3>
            <? include_once(MODULES_PATH."signin-window.php"); ?>
        <? } ?>
    </div>
</div>