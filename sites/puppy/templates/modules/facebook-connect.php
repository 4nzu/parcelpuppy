<div id="fb-root"></div>
<script src="https://connect.facebook.net/en_US/all.js"></script>
<script type="text/javascript">
    (function() {
      var po = document.createElement('script');
      po.type = 'text/javascript'; po.async = true;
      po.src = 'https://apis.google.com/js/client:plusone.js?onload=render';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(po, s);
    })();

    function pc_fb_login() {
        FB.api('/me', function(response) {
            $.post('/api/v1/fb_login', response, function(r) {
                if (r.request == 'OK') {
                    <? if (!$_SESSION['logged_in']) { ?>
                    window.location = '/explore/life-science';
                    <? } ?>
                }
                else {
                    console.log(r);
                }
            });
        });
    }
    
    function signInCallback(authResult) {
        if (authResult['code']) {
            $.post('/api/v1/g_login', {"code":authResult['code']}, function(result) {
                    if (result.request == 'OK') {
                        window.location = '/explore/recommended';
                    }
                    console.log(result);
                });
        } else if (authResult['error']) {
            console.log('There was an error: ' + authResult['error']);
        }
    }

    $(document).ready(function(e) {
        FB.init({
            appId      : '<?= PUBCHASE_FB_APPID ?>',
            channelUrl : '<?= SITE_URL ?>/channel.html',
            status     : true,
            cookie     : true,
            xfbml      : true
        });

        $('.fb_connect_btn').click(function(e) {
            FB.login(function(response) {
                if (response.status === 'connected') {
                    pc_fb_login();
                }
            }, {scope: 'email'});
        });

        $('#google-signin').click(function(e) {
            var additionalParams = {
                'clientid'     : '<?= PUBCHASE_GOOGLE_CLIENTID ?>',
                'callback'     : signInCallback,
                'cookiepolicy' : 'single_host_origin',
                'scope'        : 'https://www.googleapis.com/auth/userinfo.email'
            };

            gapi.auth.signIn(additionalParams);
        })
    });
</script>