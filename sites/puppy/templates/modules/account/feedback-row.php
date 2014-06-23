<? if ($feedback_item) { ?>
    <div class="feedback-row">
        <?
        if (empty($feedback_item[author]->profile_image)) {
            $feedback_img_src = '/img/placeholderAvatar.png';
        } else {
            $feedback_img_src = $feedback_item[author]->profile_image;
        }
        ?>
        <img src="<?= $feedback_img_src ?>" class="feedback-row-image">

        <div class="feedback-row-body">
            <div class="feedback-row-title-line">
                <span>
                    <a href='#'><?= $feedback_item[author]->first_name." ".substr($feedback_item[author]->last_name,0,1) ?></a>
                    (<?= $feedback_item[author_type] ?>)
                </span>
                <span>-</span>
                <span>
                    Feedback:
                    <? if ($feedback_item[feedback_type] == 'positive') { ?>
                        <span class="feedback-row-positive">Positive</span>
                    <? } else { ?>
                        <span class="feedback-row-negative">Negative</span>
                    <? } ?>

            </div>
            <div class="feedback-row-message">
                <?= $feedback_item[message] ?>
            </div>
        </div>
    </div>
<? } ?>
