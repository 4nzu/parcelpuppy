<div class="form-group">
    <input type="text" placeholder="Description of Request (i.e. pet treats and toys)" class="form-control"
           name="description"
           id="request-general-form-description" value="<?= $request->description ?>">
    <span class="help-block" style="display: none;">Description cannot be blank</span>
</div>

<div class="form-group" style="width: 300px;">
    <label class="control-label" for="request-general-form-region">Where can these items be found?</label>
    <select class='form-control' name='region_id' id="request-general-form-region">
        <? foreach ($countries as &$country) { ?>
            <option value="<?= $country['region_id'] ?>"
                <? if ($country['region_id'] === $request->region_id) { ?> selected <? } ?>
                ><?= $country['country_name'] ?></option>
        <? } ?>
    </select>
</div>

<div class="form-group">
    <label class="request-form-shipping-label control-label" for="request-form-shipping">Shipping and handling
        timeframe:</label>

    <div class="form-hint">?</div>
    <div class="btn-group" data-toggle="buttons" id="request-general-form-shipping-group" data-default="<?= $request->shipping ?>">
        <label class="shipping-btn btn btn-primary" id="request-general-form-shipping-standard" value="standard">
            <input type="radio" name="shipping">STANDARD
        </label>
        <label class="shipping-btn btn btn-primary" id="request-general-form-shipping-express" value="express">
            <input type="radio" name="shipping">EXPRESS
        </label>
    </div>
</div>