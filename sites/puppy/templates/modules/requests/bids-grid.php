<div class="bids-grid-frame">
    <? for ($i = 0; $i < 7; $i++) {
        if ($i % 3 == 0) {
            if ($i != 0) { ?>
                </div>
            <? } ?>
                <div class="bids-grid-row">
        <? }
        include(MODULES_PATH . "requests/bid-window.php");
    } ?>
</div>
</div>
