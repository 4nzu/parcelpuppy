<div class="form-section-frame">
    <div class="form-group">
        <div class="row">
            <div class="col-xs-6">
                <input type="text" placeholder="First Name" class="form-control" name="first_name" id="about-me-form-first-name"
                       value="<?= $_SESSION['user']->first_name ?>">
            </div>
            <div class="col-xs-6">
                <input type="text" placeholder="Last Name" class="form-control" name="last_name" id="about-me-form-last-name"
                       value="<?= $_SESSION['user']->last_name ?>">
            </div>
        </div>
        <span class="help-block" style="display: none;">You must provide your first and last name</span>
    </div>

    <div class="form-group">
        <input type="text" placeholder="Email Address" class="form-control" name="email" id="about-me-form-email"
               value="<?= $_SESSION['user']->email ?>">
        <span class="help-block" style="display: none;">You must provide your email address</span>
    </div>

    <div class="form-group">
        <label class="control-label" for="about-me-form-bio">Tell us a bit about yourself (Optional)</label>
        <textarea placeholder="e.g. I am a history student who loves art museums, food, and travel."
                  class="form-control"
                  name="bio" id="about-me-form-bio"></textarea>
    </div>

    <div class="form-group">
        <label class="control-label" for="about-me-form-more-info">What do you think people will want to buy from your area?
            (Optional)</label>
        <textarea placeholder="e.g. There is a local woodworker who makes wonderful wooden bowls."
                  class="form-control"
                  name="more_info" id="about-me-form-more-info"></textarea>
    </div>
</div>
