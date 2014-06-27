<div class="content-body">
    <? if (!empty($request)) { ?>
        <div id="request-show-frame">
            <div class="request-show-title-line">
                <h4 class="request-show-description"><?= $request['description'] ?></h4>
                <span class="request-show-region">From <?= $request['region_name'] ?></span>
                <!--    @TODO fix logic to actually check if they're the owner    -->
                <? if ($_SESSION['user']->id === $request['owner']->id || true) { ?>
                    <a href="/requests/<?= $request['request_id'] ?>/edit">Edit request</a>
                <? } ?>
            </div>
            <div class="request-show-subtitle-line">
                <!--    @TODO fix to show actual date once provided by backend    -->
                <span><b>Requested on:</b> <?= 'Jan 3rd, 2014' ?></span>
                <span><b>Shipped to:</b> <?= $request['region_name'] ?> (City, Zip)</span>
                <span><?= ucfirst($request['shipping']) ?> shipping</span>
            </div>

            <? include_once(MODULES_PATH . "requests/items-table.php"); ?>

            <? include_once(MODULES_PATH . "requests/questions-table.php"); ?>
        </div>

        <!--    @TODO fix logic to actually check if they're the owner    -->
        <? if ($_SESSION['user']->id === $request['owner']->id || true) { ?>
            <a  href="#bid-window"><button id='request-show-view-bids-button'>View Bids</button></a>
        <? } elseif ($_SESSION['user']->isPuppy) { ?>
            <button id='request-show-make-bid-button'>Make Bids</button>
        <? } ?>

        <!--    @TODO fix logic to actually check if they're the owner    -->
        <? if ($_SESSION['user']->id === $request['owner']->id || true) { ?>
            <? include_once(MODULES_PATH . "requests/bids-grid.php"); ?>
        <? } ?>
    <? } else { ?>
        <div class="panel panel-danger" id="request-show-error">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Error: unable to retrieve request
                </h3>
            </div>
            <div class="panel-body">
                The request ID you specified is invalid or you do not have permission to access it. Please contact
                <a href="/contactus">Parcel Puppy</a> with any concerns regarding this error.
            </div>
        </div>
    <? } ?>
</div>

