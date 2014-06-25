<div class="content-body">
    <div class="form-frame">
        <h3>Make Request</h3>
    </div>

    <form role="form" id="request-form">
        <div class="form-group">
            <input type="text" placeholder="Description of Request (i.e. pet treats and toys)" class="form-control" name="description"
                   id="request-form-description" value="<?= $request->description ?>">
            <span class="help-block" style="display: none;">Description cannot be blank</span>
        </div>
    </form>
</div>