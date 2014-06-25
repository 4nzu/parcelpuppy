<div class="form-group">
    <input type="text" placeholder="Description of Request (i.e. pet treats and toys)" class="form-control"
           name="description"
           id="request-form-description" value="<?= $request->description ?>">
    <span class="help-block" style="display: none;">Description cannot be blank</span>
</div>

<div class="form-group" style="width: 300px;">
    <label class="control-label" for="request-form-country">Where can these items be found?</label>
    <select class='form-control' name='country' id="request-form-country">
        <? foreach ($countries as &$country) { ?>
            <option value="<?= $country['country_code'] ?>"
                <? if ($country['country_code'] === $_SESSION['user']->country) { ?> selected <? } ?>
                ><?= $country['country_name'] ?></option>
        <? } ?>
    </select>
</div>

<div class="form-group">
    <label class="request-form-shipping-label control-label" for="request-form-shipping">Shipping and handling
        timeframe:</label>

    <div class="form-hint">?</div>
    <div class="btn-group" data-toggle="buttons">
        <label class="shipping-btn btn btn-primary">
            <input type="radio" name="shipping" id="request-form-shipping-standard">STANDARD
        </label>
        <label class="shipping-btn btn btn-primary">
            <input type="radio" name="shipping" id="request-form-shipping-express">EXPRESS
        </label>
    </div>
</div>