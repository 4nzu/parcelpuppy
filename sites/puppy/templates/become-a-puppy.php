<div id="masthead" class="puppy-masthead" style="background-image: url('../img/masthead-puppy.jpg');">
    <div class="row-fluid">
        <div class="col-xs-10 col-xs-offset-1">
            <span class="masthead-main-line">
                Earn money on your shopping trips.<br>
                Share your finds with people around the world.
            </span>

            <div class="masthead-call-to-action">
                I'm looking for:
                <form role="form" action="/signup" method="POST">
                    <input type="email" placeholder="yourname@email.com" name="email" id="masthead-email"
                           pattern="[^ @]*@[^ @]*">

                    <button type="submit">Sign Up</button>
                </form>
            </div>
        </div>

        <div class='call-to-action'>
            <div class="row-fluid">
                <div class="col-xs-2 col-xs-offset-1"></div>
            </div>

        </div>
    </div>
</div>

<div class="content-body" style="padding-bottom: 10px;">
    <div class="how-it-works">
        <div class="section-header">Steps to signing up:</div>

        <div class="row">
            <div class="col-xs-4">
                <div class="signup-step">
                    <img src="/img/step-icon.png">
                    <b>Create an account.</b>
                    Fill out a profile and tell us a little about yourself.
                </div>
            </div>
            <div class="col-xs-4">
                <div class="signup-step">
                    <img src="/img/step-icon.png">
                    <b>Bid on requests.</b>
                    Once your application is accepted, bid on requests for items from your country.
                </div>
            </div>
            <div class="col-xs-4">
                <div class="signup-step">
                    <img src="/img/step-icon.png">
                    <b>Get paid when you ship.</b>
                    Enter the tracking information of your package and get paid when the item ships.
                </div>
            </div>
        </div>
    </div>
</div>

<? include_once(MODULES_PATH . "featured-puppies-window.php"); ?>

<div class="content-body">
    <? include_once(MODULES_PATH . "recommendation-row.php"); ?>
</div>