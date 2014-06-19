<h4 class="address-title">Shipping Address</h4>
<p class='address-subtitle'>Only the Parcel Puppy you choose will see your shipping address</p>

<div class="form-group" id="address-name-form-group">
    <div class="row">
        <div class="col-xs-6">
            <input type="text" placeholder="First Name" class="form-control" name="first_name" id="address-first-name">
        </div>
        <div class="col-xs-6">
            <input type="text" placeholder="Last Name" class="form-control" name="last_name" id="address-last-name">
        </div>
    </div>
    <span class="help-block" id="address-name-help-block" style="display: none;">You must provide your first and last name</span>
</div>

<div class="form-group" id="address-address-form-group">
    <input type="text" placeholder="Address Line 1" class="address-field form-control" name="address_1"
           id="address-address-1">
    <input type="text" placeholder="Address Line 2" class="address-field form-control" name="address_2"
           id="address-address-2">
    <input type="text" placeholder="Address Line 3" class="address-field form-control" name="address_3"
           id="address-address-3">
    <span class="help-block" id="address-address-help-block" style="display: none;">Address cannot be blank</span>
</div>

<div class="form-group" id="signup-pass-conf-form-group">
    <select class='form-control' name='country' id="address-country">
        <? foreach ($countries as &$country) { ?>
            <option value="<?= $country['country_code'] ?>"><?= $country['country_name'] ?></option>
        <? } ?>
    </select>
</div>

<div class="form-group" id="address-city-state-form-group">
    <div class="row">
        <div class="col-xs-4">
            <input type="text" placeholder="City" class="form-control" name="city" id="address-city">
        </div>
        <div class="col-xs-4">
            <input type="text" placeholder="State / Province" class="form-control" name="state" id="address-state">
        </div>
        <div class="col-xs-4">
            <input type="text" placeholder="Zip Code" class="form-control" name="zip_code" id="address-zip-code">
        </div>
    </div>
    <span class="help-block" id="address-name-help-block" style="display: none;">You must provide your city, state/province, and zip code</span>
</div>