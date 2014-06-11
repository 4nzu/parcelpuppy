<div class="landing">

	<? if ($_SESSION['logged_in']) { ?>
	<br><br><br><br>
	signed in as: <?= $_SESSION['user']->email ?>
	<? } else { ?>
	<br><br>
	Landing page
	<? } ?>
</div>