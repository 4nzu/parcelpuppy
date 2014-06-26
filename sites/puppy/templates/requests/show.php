<div class="content-body">
    <? if (!empty($request)) { ?>
        <div id="request-show-frame">
            <div class="request-show-title-line">
                <h3 class="request-show-description"><?= $request['description'] ?></h3>
                <span class="request-show-region">From <?= $request['region_name'] ?></span>
                <? if ($_SESSION['user']->id === $request['owner']->id || true) { ?>
                    <a href="/requests/<?= $request['request_id'] ?>/edit">Edit request</a>
                <? } ?>
            </div>
            <div class="request-show-subtitle-line">
                <span><b>Requested on:</b> <?= 'Jan 3rd, 2014' ?></span>
                <span><b>Shipped to:</b> <?= $request['region_name'] ?> (City, Zip)</span>
                <span><?= ucfirst($request['shipping']) ?> shipping</span>
            </div>

            <? include_once(MODULES_PATH . "requests/items-table.php"); ?>
        </div>

        <button id='request-show-bids-button' class="form-fixed-button affix-top">View Bids</button>
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

