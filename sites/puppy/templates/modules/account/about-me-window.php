<? if ($about_me_user) { ?>
    <div class="about-me-frame">
        <div class="row">
            <div class="col-xs-2">
                <img src="<?= empty($about_me_user->profile_image) ? '/img/placeholderAvatar.png' : $about_me_user->profile_image ?>"
                     class="about-me-image">
            </div>
            <div class="col-xs-10">
                <div class="about-me-title">
                    <h3 class="about-me-full-name"><?= $about_me_user->full_name ?></h3>
                    <span class="glyphicon glyphicon-map-marker"></span>
                <span class="about-me-location">
                    <?= $about_me_user->city ?>, <?= $about_me_user->country ?>
                </span>
                    <? if ($about_me_user->id === $_SESSION['user']->id) { ?>
                        <a href="/edit-profile"><i>edit profile</i></a>
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
    </div>
<? } ?>
