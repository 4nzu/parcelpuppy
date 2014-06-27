<div class="question-row-frame">
    <div class="question-row-question">
        Q: <?= $question['question'] ?>
    </div>
    <div class="question-row-answer">
        <? if ($question['answer']) { ?>
            A: <?= $question['answer'] ?>
        <? } elseif ($request['owner']->id === $_SESSION['user']->id || true) { ?>
        <!--     @TODO replace with actual 'is owner' logic        -->
            <button class="question-row-reply blue-button">Reply</button>
        <? } ?>
    </div>
</div>