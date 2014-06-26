<div class="item-row-frame">
    <div class="item-row-header">
        <span><?= ($item['brand']) ? $item['brand'].' - ' : '' ?><?= $item['name'] ?></span>
        <span class="request-items-table-quantity-col pull-right"><?= $item['quantity'] ?></span>
    </div>
    <div class="item-row-details" style="display: none;">
        <?= $item['details'] ?>
    </div>
</div>