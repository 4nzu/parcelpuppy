<div class="tab-pane active" id="profile">
    <? $about_me_user = $_SESSION["user"];
    include_once(MODULES_PATH . "account/about-me-window.php"); ?>

    <? include_once(MODULES_PATH . "account/feedback-window.php"); ?>
</div>
