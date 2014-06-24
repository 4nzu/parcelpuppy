<? if ($about_me_user) { ?>
    <div class="about-me-frame">
        <img
            src="<?= empty($about_me_user->profile_image) ? '/img/placeholderAvatar.png' : $about_me_user->profile_image ?>"
            class="about-me-image">

        <div class="about-me-body">
            <div class="about-me-title">
                <h3 class="about-me-full-name"><?= $about_me_user->full_name ?></h3>
                <span class="glyphicon glyphicon-map-marker" id="about-me-map-marker"></span>
                <span class="about-me-location">
                    <?= $about_me_user->city ?>, <?= $about_me_user->country ?>
                </span>
                <? if ($about_me_user->id === $_SESSION['user']->id) { ?>
                    <a href="/edit_account"><i>edit profile</i></a>
                <? } ?>
            </div>
            <div class="about-me-feedback">
                Feedback: <?= empty($about_me_user->feedback_count) ? 0 : $about_me_user->feedback_count ?>
            </div>
            <div class="about-me-bio">
                About Me:<br>
                <?= empty($about_me_user->bio) ? 'No bio provided' : $about_me_user->bio ?>
            </div>
        </div>
    </div>
<? } ?>
