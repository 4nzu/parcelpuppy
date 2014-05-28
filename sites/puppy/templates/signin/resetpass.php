<div class="alert-center">
<? if ($reset_completed == 0) { ?>
    You will recieve an email with instructions how to reset your passoword.
<? } elseif ($reset_completed == 1) { ?>
    Your new password was saved successfully.<br>&nbsp;<p>Now you can <a href="/">login</a> using your email and password.
<? } ?>
</div>
