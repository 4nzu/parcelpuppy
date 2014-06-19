<div class="content-body">
    <div class="form-wrapper">
        <div class="panel panel-danger" style="display: none;" id="extras-error">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Sorry, an error occurred while updating your account
                </h3>
            </div>
            <div class="panel-body">
                Please try again. If this issue continue to occur please contact <a href="/contactus">Parcel Puppy</a>
                and we will help resolve it as quickly as possible.
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <form role="form" id="extras-form">
                    <? include_once(MODULES_PATH . "address-window.php"); ?>
                </form>
            </div>
            <div class="col-xs-3 pull-right" style="padding-top:20px;">
                <button id='extras-button' style="width: 100%">Done</button>
            </div>
        </div>
    </div>
</div>
