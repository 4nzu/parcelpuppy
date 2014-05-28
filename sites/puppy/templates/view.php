<div class="view">
<? foreach($requests as $r) { ?>
	<div class="request">
		<span class="label">Posted by:</span> <?= $r['email'] ?><br>
		<span class="label">Product name:</span> <?= $r['name'] ?><br>
		<span class="label">Product price:</span> <?= number_format($r['price'], 2, '.', ',') ?><br>
		<span class="label">Puppy fee:</span> <?= number_format($r['puppy_fee'], 2, '.', ',') ?><br>
		<span class="label">Request description:</span> <?= $r['description'] ?><br>
		<span class="label">City:</span> <?= $r['city'] ?><br>
		<span class="label">Country:</span> <?= $r['region_name'] ?><br>
		<span class="label">Created on:</span> <?= Util::mysqlDateTimeToPrettyDateTime($r['created_on']) ?><br>
	</div>
<? } ?>
</div>