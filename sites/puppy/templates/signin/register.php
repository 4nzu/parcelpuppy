<div class="main-part">
	<div class="container">
		<div class="popover bottom popover-reg popover-signup z99">
			<? /* ?><div class="arrow"></div><? */ ?>
			<div class="popover-content">
			  <h2>Sign up for free!</h2>
			  <h4>Discover, Organize, and Search Papers</h4>
			  <form action="/thankyou" method="POST" class="form-horizontal form-reg" id="register" autocomplete="off">
				<fieldset>
					<? if (isset($failed) && is_array($failed)) { ?>
					<div class="alert alert-error">
						<button data-dismiss="alert" class="close" type="button">×</button>
						This email is already in use.
					</div>
				<? } ?>
					<input <? if(isset($failed['email'])) { ?>value="<?= $failed['email'] ?>"<? } ?> name="email" id="email2" type="email" pattern="[^ @]*@[^ @]*" placeholder="Email" class="<? if(isset($failed['email'])) { ?>error<? } ?>">
					<input <? if(isset($failed['pass'])) { ?>value="<?= $failed['pass'] ?>"<? } ?> name="pass" type="password" id="pass2" placeholder="Password">
					<div class="clearfix">
						<button class="ladda-button create_acc_btn register-btn" data-color="green">Create Account</button>
					</div>
					<div class="clearfix or-connect-btns">or</div>
				    <div class="connect-btns">
				        <div class="fb_connect_btn"></div>
				        <div id="google-signin"></div>
				    </div>
				    <div class="clearfix"></div>
				  	<div class="ppcheck_div">
						<span>By continuing, you agree to our <a href="/privacy" style="color: #595959" target="_blank">privacy policy</a> and <a href="/termofservice" style="color: #595959" target="_blank">terms of service.</a></span>
					</div>
					<div style="margin-top: 20px;">
						Already have an account?&nbsp;<a href="/login" class="reset-login">Sign in</a>
					</div>
				</fieldset>
			  </form>
			</div>
		</div>
	</div>
</div>
<script src="/js/validate_credentials.js"></script>
<script>

$(document).ready(function () {
	$('.create_acc_btn').click(function(e) {
		e.preventDefault();
		if (validate_input(2)) $('#register').submit();
	});
});
 </script>