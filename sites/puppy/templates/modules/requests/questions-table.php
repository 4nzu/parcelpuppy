<div class="request-questions-table-container">
    <div class="request-questions-table-header">
        QUESTIONS
    </div>

    <?
    $request['questions'] = [array(
        question => "What color do you want?",
        answer => "Blue"
    ),
        array(
            question => "No really, what color do you want?"
        )
    ];


    ?>

    <div class="request-questions-table-body">
        <? foreach ($request['questions'] as $question) {
            include(MODULES_PATH . "requests/question-row.php");
        } ?>
    </div>
</div>