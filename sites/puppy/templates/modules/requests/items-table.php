<div class="request-items-table-container">
    <div class="request-items-table-header">
        <span>BRAND - ITEM NAME</span>
        <span class="request-items-table-quantity-col pull-right">QTY</span>
    </div>

    <? foreach ($request['items'] as $item) {
        include(MODULES_PATH . "requests/item-row.php");
    } ?>
</div>