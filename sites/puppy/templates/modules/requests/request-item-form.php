<div class="request-item-form-frame"
    <? if ($i > 0 && empty($request_item)) { ?>
        style="display: none;"
    <? } ?>
    >
    <div class="request-item-form-header">
        Details about item <?= $i + 1 ?>:
        <button class="request-item-form-delete-btn glyphicon glyphicon-remove pull-right"></button>
    </div>
    <div class="request-item-form-body">
        <div class="request-item-form-image">
            <div class="request-item-image-placeholder">
                <div class="glyphicon glyphicon-plus"></div>
                <br><br>
                Upload Photo
            </div>
        </div>
        <div class="request-item-form-fields">
            <div class="form-group">
                <input type="text" placeholder="Name of item" class="form-control"
                       name="item-<?= $i ?>-name" value="<?= $request_item->name ?>">
                <span class="help-block" style="display: none;">Item name cannot be blank</span>
            </div>
            <div class="request-item-form-brand-frame form-group">
                <input type="text" placeholder="Brand Name" class="form-control"
                       name="item-<?= $i ?>-brand" value="<?= $request_item->brand ?>">
            </div>
            <div class="request-item-form-quantity-frame pull-right">
                <label class="request-item-form-quantity-label control-label">Quantity:</label>

                <div class="request-item-form-quantity-field-frame form-group">

                    <input type="number" class="request-item-form-quantity-field form-control"
                           name="item-<?= $i ?>-quantity"
                           value="<?= empty($request_item->quantity) ? 1 : $request_item->quantity ?>">
                    <span class="help-block" style="display: none;">Item quantity cannot be blank</span>
                </div>
            </div>
            <div class="form-group">
                <textarea
                    placeholder="Details: Please provide us with a short description to help Parcel Puppies purchase your item, such as store name, store address, size, color, flavor, link to specific product..."
                    class="request-item-form-details form-control" name="item-<?= $i ?>-details"></textarea>
            </div>

        </div>
    </div>
</div>