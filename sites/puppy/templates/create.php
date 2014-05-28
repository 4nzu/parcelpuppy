<script type="text/javascript" src="/js/create.js"></script>
<div class="create">

<input id="pr_name" type="edit" placeholder="product name"><br>
<input id="pr_price" type="edit" placeholder="product price"><br>
<select id="pr_country">
<? foreach($countries as $c) { ?>
<option value="<?= $c['region_id'] ?>"><?= $c['region_name'] ?></option>
<? } ?>
</select><br>
<input id="pr_city" type="edit" placeholder="city"><br>
<input id="pr_description" type="edit" placeholder="description"><br>
<button class="ladda-button submit-new" data-color="green" data-style="zoom-in" data-size="s">Submit</button>
</div>