<div class="content-body">
    <div class="form-frame">
        <div class="panel panel-danger" style="display: none;" id="profile-form-error">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Sorry, an error occurred while updating your account
                </h3>
            </div>
            <div class="panel-body">
                Please try again. If this issue continue to occur please contact <a href="/contactus">Parcel
                    Puppy</a>
                and we will help resolve it as quickly as possible.
            </div>
        </div>

        <h2>Edit Profile</h2>
        <br>

        <form role="form" id="profile-form">
            <? include_once(MODULES_PATH . "account/about-me-form.php"); ?>
            <? $address_form_include_name = false;
            include_once(MODULES_PATH . "account/address-form.php"); ?>
            <button id='profile-button' class="form-fixed-button">Save and Finish</button>
        </form>
    </div>
</div>
