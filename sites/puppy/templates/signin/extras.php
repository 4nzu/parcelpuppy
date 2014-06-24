<div class="content-body">
    <div class="form-frame">
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

            <form role="form" id="extras-form">
                <? $address_form_include_name = true; include_once(MODULES_PATH . "account/address-form.php"); ?>
                <button id='extras-button' class='form-fixed-button' style="width: 100%">Done</button>
            </form>
        </div>
    </div>
</div>
