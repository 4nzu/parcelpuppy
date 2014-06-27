<div class="content-body">
    <div class="form-frame">
        <div>
            <h4 id="request-form-title">Make a Request</h4>
            <div class='request-form-tooltip' data-toggle="tooltip" data-placement="right"
                 title="Please review if there are import restrictions for your requested items in your country (e.g. alcohol, cigarettes, prescription drugs, raw food, etc.).">
                <span id="request-form-subtitle">
                Restricted item policy </span>
                <div class="form-hint">?</div>
            </div>
        </div>

        <div class="panel panel-danger" style="display: none;" id="request-form-error">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Sorry, an error occurred while <?= (empty($request)) ? 'making' : 'updating' ?> your request
                </h3>
            </div>
            <div class="panel-body">
                Please try again. If this issue continue to occur please contact <a href="/contactus">Parcel Puppy</a>
                and we will help resolve it as quickly as possible.
            </div>
        </div>

        <form role="form" id="request-form">
            <? include_once(MODULES_PATH . "requests/general-form.php"); ?>
            <div id="request-form-items">
                <? include(MODULES_PATH . "requests/item-form.php"); ?>
            </div>
            <button id="request-form-add-item-btn"><span class="glyphicon glyphicon-plus"></span> Add item to request</button>
        </form>
    </div>
    <span class="form-fixed-button affix-top">
        <button id='request-form-button'>Submit Request</button>
        <p class="form-button-subtitle">View requests in your Dashboard</p>
    </span>
</div>
