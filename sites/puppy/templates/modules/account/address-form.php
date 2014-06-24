<div class="form-section-frame">
    <h4 class="form-section-title">Shipping Address</h4>
    <p class='form-section-subtitle'>Only the Parcel Puppy you choose will see your shipping address</p>

    <? if ($address_form_include_name) { ?>
        <div class="form-group">
            <div class="row">
                <div class="col-xs-6">
                    <input type="text" placeholder="First Name" class="form-control" name="first_name" id="address-form-first-name"
                           value="<?= $_SESSION['user']->first_name ?>">
                </div>
                <div class="col-xs-6">
                    <input type="text" placeholder="Last Name" class="form-control" name="last_name" id="address-form-last-name"
                           value="<?= $_SESSION['user']->last_name ?>">
                </div>
            </div>
            <span class="help-block" style="display: none;">You must provide your first and last name</span>
        </div>
    <? } ?>

    <div class="form-group">
        <input type="text" placeholder="Address Line 1" class="address-field form-control" name="address_1"
               id="address-form-street-1" value="<?= $_SESSION['user']->address_1 ?>">
        <input type="text" placeholder="Address Line 2" class="address-field form-control" name="address_2"
               id="address-form-street-2" value="<?= $_SESSION['user']->address_2 ?>">
        <span class="help-block" style="display: none;">Address cannot be blank</span>
    </div>

    <div class="form-group">
        <select class='form-control' name='country' id="address-form-country">
            <? foreach ($countries as &$country) { ?>
                <option value="<?= $country['country_code'] ?>"
                    <? if ($country['country_code'] === $_SESSION['user']->country) { ?> selected <? } ?>
                    ><?= $country['country_name'] ?></option>
            <? } ?>
        </select>
    </div>


    <div class="form-group">
        <input type="text" placeholder="State / Province" class="form-control" name="state" id="address-form-state"
               value="<?= $_SESSION['user']->state ?>">
        <span class="help-block" style="display: none;">You must provide your state/province</span>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-8">
                <input type="text" placeholder="City" class="form-control" name="city" id="address-form-city"
                       value="<?= $_SESSION['user']->city ?>">
            </div>
            <div class="col-xs-4">
                <input type="text" placeholder="Zip Code" class="form-control" name="zip_code" id="address-form-zip-code"
                       value="<?= $_SESSION['user']->zip_code ?>">
            </div>
        </div>
        <span class="help-block" style="display: none;">You must provide your city and zip code</span>
    </div>
</div>
