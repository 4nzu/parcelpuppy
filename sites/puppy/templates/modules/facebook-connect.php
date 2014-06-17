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

    function finalize_fb_connect() {
        FB.api('/me', function(response) {
            $.post('/api/v1/fb_login', response, function(r) {
                if (r.request == 'OK') {
                    <? if (!$_SESSION['logged_in']) { ?>
                    window.location = '/';
                    <? } ?>
                }
                else {
                    // console.log(r);
                }
            });
        });
    }
    
    function finalize_google_connect(authResult) {
        if (authResult['code']) {
            $.post('/api/v1/g_login', {"code":authResult['code']}, function(result) {
                    if (result.request == 'OK') {
                        window.location = '/';
                    }
                    console.log(result);
                });
        } else if (authResult['error']) {
            console.log('There was an error: ' + authResult['error']);
        }
    }

    $(document).ready(function(e) {
        FB.init({
            appId      : '<?= FACEBOOK_APPID ?>',
            channelUrl : '<?= SITE_URL ?>/channel.html',
            status     : true,
            cookie     : true,
            xfbml      : true
        });

        $('.fb_connect_btn').click(function(e) {
            FB.login(function(response) {
                if (response.status === 'connected') {
                    finalize_fb_connect();
                }
            }, {scope: 'email'});
        });

        $('#google-signin').click(function(e) {
            var additionalParams = {
                'clientid'     : '<?= GOOGLE_CLIENTID ?>',
                'callback'     : finalize_google_connect,
                'cookiepolicy' : 'single_host_origin',
                'scope'        : 'https://www.googleapis.com/auth/userinfo.email'
            };

            gapi.auth.signIn(additionalParams);
        })
    });
</script>