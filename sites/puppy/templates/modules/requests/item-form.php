<div class="request-item-form-frame" id="request-item-<?= $i ?>"
    <? if ($i > 0 && empty($request_item)) { ?>
        style="display: none;"
    <? } ?>
    >
    <div class="request-item-form-header">
        Details about item <span class='item-number'><?= $i + 1 ?></span>:
        <div class="request-item-form-delete-btn glyphicon glyphicon-remove pull-right"></div>
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
                       name="name" value="<?= $request_item->name ?>">
                <span class="help-block" style="display: none;">Item name cannot be blank</span>
            </div>
            <div class="request-item-form-brand-frame form-group">
                <input type="text" placeholder="Brand Name" class="form-control"
                       name="brand" value="<?= $request_item->brand ?>">
            </div>
            <div class="request-item-form-quantity-frame pull-right">
                <div class="request-item-form-quantity-field-frame form-group">
                <label class="request-item-form-quantity-label control-label">Quantity:</label>


                    <input type="number" class="request-item-form-quantity-field form-control"
                           name="quantity" min="1"
                           value="<?= empty($request_item->quantity) ? 1 : $request_item->quantity ?>">
                </div>
            </div>
            <div class="form-group">
                <textarea
                    placeholder="Details: Please provide us with a short description to help Parcel Puppies purchase your item, such as store name, store address, size, color, flavor, link to specific product..."
                    class="request-item-form-details form-control" name="details"></textarea>
                <span class="help-block" style="display: none;">Item description cannot be blank</span>
            </div>

        </div>
    </div>
</div>