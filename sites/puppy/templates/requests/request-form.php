<div class="content-body">
    <div class="form-frame">
        <h3>Make Request</h3>
    </div>

    <form role="form" id="request-form">
        <div class="form-group">
            <input type="text" placeholder="Address Line 1" class="address-field form-control" name="address_1"
                   id="address-form-street-1" value="<?= $_SESSION['user']->address_1 ?>">
            <span class="help-block" style="display: none;">Address cannot be blank</span>
        </div>
    </form>
</div>