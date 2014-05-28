<?php

import('Zappy.User');
import('Zappy.MDB');
require '../../../Frameworks/aws.phar';
use Aws\Common\Aws;
use Aws\Common\Enum\Region;

class amazonAPI {

  public $aws = null;
  public $s3 = null;
    public $s_stub = null;
    public $u_stub = null;
    public $l_stub = null;
    public $manage_settings = null;

  function __construct($version = null) {
    $AWSregion = Region::SA_EAST_1;
    $this->aws = Aws::factory(array( 'key' => AWS_KEY, 'secret' => AWS_SECRET_KEY, 'base_url' => PDF_BASE_URL));
    $this->s3 = $this->aws->get('s3');

    $this->db = DB::instance();
        
        $this->s_stub = '***mononoke-s***';
        $this->u_stub = '***mononoke-u***';
        $this->l_stub = '***mononoke-l***';

        $this->manage_settings = '<a href="https://www.pubchase.com/settings?email&t=token" style="font-size: 11px; color:#3f556d;">Manage email settings</a>';

        $this->l_pubchase = '<a href="https://www.pubchase.com">
                                <img src="https://www.pubchase.com/img/pubchase-logo.png" width="166" height="33" alt="PubChase" border="0" />
                            </a>';
        $this->l_passageo = '<a href="'.SITE_URL.'">
                                <img src="'.SITE_URL.'/img/parcelpuppy.png" width="135" height="76" alt="Puppy!" border="0" />
                            </a>';
        $this->l_zappylab = '<a href="http://www.zappylab.com">
                                <img src="http://www.zappylab.com/img/logo_transparent.png" width="141" height="35" alt="ZappyLab" border="0" />
                            </a>';
        $this->e1 = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <style>
  
  body {
    width:100%!important;
    -webkit-text-size-adjust:100%;
    -ms-text-size-adjust:100%;
    margin:0;
    padding:0;
  }
  
  span{
    font-family: Helvetica, Arial, sans-serif;
    font-size:15px;
    line-height:20px;
    color:#555;
  }
  
  @media only screen and (max-width: 600px) {

    table[class="content_wrap"] {
      width: 94%!important;
    }
    
    table[class="full_width"] {
      width: 100%!important;
    }
    
    table[class="hide"], img[class="hide"], td[class="hide"] {
      display: none !important;
    }
    
    a[class="button"] {
      border-radius:2px;
      -moz-border-radius:2px;
      -webkitborder-radius:2px;
      background-color:#3f556d;
      color:#fff!important;
      padding: 5px;
      display:block;
      text-decoration: none;
      text-transform: uppercase;
      margin: 10px 0 30px 0;
    }
    
  }
  </style>
</head>

<body bgcolor="#f1f2f2" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="-webkit-font-smoothing:antialiased;width:100% !important;background-color:#f1f2f2;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;-webkit-text-size-adjust:none;">

  <span style="display: none;font-size: 0px; color:#ECF0F5; line-height: 0;">'.$this->s_stub.'</span>
  
  <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f1f2f2" style="font-family: Helvetica, Arial, sans-serif;font-size:15px;line-height:20px;color:#555;">
    <tr>
      <td bgcolor="#f1f2f2" width="100%"> 
        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="content_wrap">
          <tr>
            <td width="100%" height="10" bgcolor="#f1f2f2"></td>
          </tr>
          <tr>
            <td bgcolor="#f1f2f2">
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td bgcolor="#f1f2f2" width="100%" class="text-center">
                    <table width="20" cellpadding="0" cellspacing="0" border="0" align="right" class="hide">
                      <tr>
                        <td width="100%">
                          &nbsp;
                        </td>
                      </tr>
                    </table> 
                    <table style="margin: 10px 10px 0px 10px; 0" cellpadding="0" cellspacing="0" border="0" class="full_width">
                      <tr>
                        <td width="100%" class="text-center">'.
                        $this->l_stub
                        .'</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td width="100%" height="10" bgcolor="#f1f2f2"></td>
          </tr>
          <tr>
            <td width="100%">
              <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="background:#fff;">
                <tr>
                  <td bgcolor="#fff" width="100%" valign="top">
                    <table width="100%" cellpadding="15" cellspacing="0" border="0" bgcolor="#fff" style="border-collapse:collapse;border-bottom-width: 1px; border-right-width: 1px; border-top-width: 1px; border-left-style: solid; border-bottom-style: solid; border-right-style: solid; border-top-style: solid; border-left-color: #e1e1e1; border-bottom-color: #e1e1e1; border-right-color: #e1e1e1; border-top-color: #e1e1e1; border-left-width: 1px;">
                      <tr>
                        <td bgcolor="#fff" style="background:#fff;">
                          <table width="100%" cellpadding="0" cellspacing="0" border="0" align="right">
                            <tr>
                              <td width="100%" width="100%" style="font-family:Helvetica, Arial, sans-serif;font-size:15px;line-height:20px;color:#222;">';
    $this->e2 = '</td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>          
          <tr>
            <td width="100%">
              <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr height="10">
                  <td width="100%" bgcolor="#f1f2f2">
                    <!--Footer-->
                    <table width="100%" cellpadding="15" cellspacing="0" border="0" bgcolor="#f1f2f2">
                      <tr>
                        <td bgcolor="#f1f2f2" style="font-family: Helvetica, Arial, sans-serif;font-size:12px;line-height:17px;color:#555;background:#ECF0F5;">
                          Parcelpuppy, Inc. &copy; '.date("Y").'<br /><br />'.
                         $this->u_stub.
                        '</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td width="100%" height="20"></td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>';
  }

  public function sesEmail($user_data) {

    require_once('../../../Frameworks/amazon/services/ses.php');
    $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
    $message = new SimpleEmailServiceMessage();
    $message->addTo($user_data['email']);
    $message->setFrom(DEFAULT_EMAIL);
    $message->setSubject('Welcome to Parcelpuppy!');
    
        $message_text = str_replace($this->l_stub, $this->l_passageo, str_replace($this->s_stub, 'Welcome to Parcelpuppy!', $this->e1)).
                    '<h1 style="font-size: 20px; color: #111; line-height: 30px;font-weight: normal;">Your Parcelpuppy account is ready!</h1>'.
                    '<br>&nbsp;<br>Click this link to activate your account: '.SITE_URL.'/confirm?v='.$user_data['verification_token'].'&t='.$user_data['token'].
                    '<br>&nbsp;<br>Enjoy!
                    <br>Parcelpuppy Team.
                    <br>&nbsp;'.
                    str_replace($this->u_stub, '', $this->e2);

    $message->setMessageFromString(null, $message_text);
    $ses->sendEmail($message);
  }

    public function sesEmailAll($data) {

        require_once('../../../Frameworks/amazon/services/ses.php');
        $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
        $message = new SimpleEmailServiceMessage();
        $message->addTo($data['email']);
        $message->setFrom('noreply@zappylab.com');

        if (isset($data['subject']) && !empty($data['subject']) && $data['subject'] != 'empty' &&
            isset($data['body']) && !empty($data['body']) && $data['body'] != 'empty') {
          
            $message->setSubject($data['subject']);
            $message_text = str_replace($this->s_stub, $data['subject'], $this->e1).$data['body'].'<br>&nbsp;'.
                            str_replace($this->u_stub, str_replace('token', $data['token'], $this->manage_settings), $this->e2);
        }
        else {
            $message->setSubject('TEST');
            $message_text = str_replace($this->l_stub, $this->l_pubchase, str_replace($this->s_stub, 'TEST', $this->e1)).
                        '<h1 style="font-size: 20px; color: #111; line-height: 30px;font-weight: normal">Hello,</h1>'.
                        '<br>This is a test mass-email. Please check the unsubscribe link.<br><br><br>'.
            str_replace($this->u_stub, str_replace('token', $data['token'], $this->manage_settings), $this->e2);
        }
        $message->setMessageFromString(null, $message_text);
        // echo $message_text;
        $ses->sendEmail($message);
    }

  public function sesNewEssay($data) {

    require_once('../../../Frameworks/amazon/services/ses.php');
    $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
    $message = new SimpleEmailServiceMessage();
    $message->addTo('"PubChase" <'.$data['email'].'>');
    $message->addBcc("lenny@zappylab.com");
    $message->setFrom(DEFAULT_EMAIL);
    $message->setSubject('Essay published!');

    $message_text = str_replace($this->l_stub, $this->l_pubchase, str_replace($this->s_stub, 'Essay published!', $this->e1)).
                '<h1 style="font-size: 20px; color: #111; line-height: 30px;font-weight: normal;">Dear '.$data['full_name'].'</h1>
                Your new essay <b>'.$data['essay_title'].'</b> is published successfully!'.
                '<br>&nbsp;<br>'.SITE_URL.'/essay/'.$data['uri'].
                '<br>&nbsp;<br>Thank you for the submission!'.
                '<br>PubChase Team.'.
                '<br>&nbsp;<br>P.S. Please e-mail us at pubchase@zappylab.com if you need to edit anything in this essay.
                <br>&nbsp;'.
                str_replace($this->u_stub, str_replace('token', $data['token'], $this->manage_settings), $this->e2);
                              
    $message->setMessageFromString(null, $message_text);
    $ses->sendEmail($message);
  }

  public function sesOnFollow($data) {

    require_once('../../../Frameworks/amazon/services/ses.php');
    $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
    $message = new SimpleEmailServiceMessage();
    $message->addTo('"PubChase" <'.$data['target_user_email'].'>');
    $message->setFrom(DEFAULT_EMAIL);
    $message->setSubject('A new person is following you on PubChase');

    if (empty($data['follower_user_name']) || $data['follower_user_name'] == ' ') $data['follower_user_name'] = 'Someone (they did not provide their name)';
    else $data['follower_user_name'] = '<b>'.$data['follower_user_name'].'</b>';
    
    $message_text = str_replace($this->l_stub, $this->l_pubchase, str_replace($this->s_stub, 'A new person is following you on PubChase', $this->e1)).
                  '<h1 style="font-size: 18px; color: #111; line-height: 30px;font-weight: normal;">Dear '.$data['target_user_name'].'</h1>
                  '.$data['follower_user_name'].' started following you on PubChase!'.
                  '<br>PubChase Team.
                  <br>&nbsp;'.
                  str_replace($this->u_stub, str_replace('token', $data['token'], $this->manage_settings), $this->e2);
    $message->setMessageFromString(null, $message_text);
    $ses->sendEmail($message);
  }

  public function sesOnStopFollowing($data) {

    require_once('../../../Frameworks/amazon/services/ses.php');
    $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
    $message = new SimpleEmailServiceMessage();
    $message->addTo('"PubChase" <'.$data['target_user_email'].'>');
    $message->setFrom(DEFAULT_EMAIL);
    $message->setSubject('No longer following');

    $message_text = str_replace($this->s_stub, 'No longer following', $this->e1).
                    '<h1 style="font-size: 18px; color: #111; line-height: 30px;font-weight: normal;">Dear '.$data['target_user_name'].'</h1>
                    We are just letting you know that '.$data['follower_user_name'].' stopped following you on PubChase'.
                    '<br>PubChase Team.
                    <br>&nbsp;'.
                    str_replace($this->u_stub, str_replace('token', $data['token'], $this->manage_settings), $this->e2);

    $message->setMessageFromString(null, $message_text);
    $ses->sendEmail($message);
  }

  public function btEmail($user_data) {

    require_once('../../../Frameworks/amazon/services/ses.php');
    $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
    $message = new SimpleEmailServiceMessage();
    $message->addTo($user_data['email']);
    $message->setFrom('noreply@zappylab.com');
    $message->setSubject('Welcome to ZappyLab!');

        if (HOST_ROLE == HOST_DEV) $www = 'dev'; else $www = 'www';

        $message_text = str_replace($this->l_stub, $this->l_zappylab, str_replace($this->s_stub, 'Welcome to ZappyLab!', $this->e1)).
                    '<h1 style="font-size: 20px; color: #111; line-height: 30px;font-weight: normal;">Your ZappyLab account is ready!</h1>
                    Click this link to activate your account: <a href="http://'.$www.'.zappylab.com/confirm?v='.$user_data['verification_token'].'&t='.$user_data['token'].'">http://www.zappylab.com/confirm?v='.$user_data['verification_token'].'&t='.$user_data['token'].'</a>'.
                    '<br>&nbsp;<br>Enjoy!
                    <br>ZappyLab Team.
                    <br>&nbsp;'.
                    str_replace($this->u_stub, '', $this->e2);

    $message->setMessageFromString(null, $message_text);
    $ses->sendEmail($message);
  }

    public function chatInviteEmail($data) {

        require_once('../../../Frameworks/amazon/services/ses.php');
        $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
        $message = new SimpleEmailServiceMessage();
        $message->addTo($data['email']);
        $message->setFrom('noreply@zappylab.com');
        $message->setSubject($data['from_name'].' invites you to join LabChat');

        $message_text = str_replace($this->l_stub, $this->l_zappylab, str_replace($this->s_stub, $data['from_name'].' invites you to join LabChat!', $this->e1)).
                    '<h1 style="font-size: 20px; color: #111; line-height: 30px;font-weight: normal;"><b>'.$data['from_name'].'</b> invites you to join LabChat!</h1>
                    LabChat is a real time messaging utility inside the Bench Tools mobile suite. This suite is a free platform to aid in Life Science research.
                    <br><br>Bench Tools is available for <a href="https://play.google.com/store/apps/details?id=com.zappylab.benchtools">Android</a> or <a href="https://itunes.apple.com/us/app/zappylab-bench-tools/id731295151?mt=8">iOS</a>.
                    <br><br>If you already have Bench Tools installed, navigate to LabChat and tap "Enable"
                    <br><br>Utilities inside Bench Tools also include:
                    <br>Protocols (beta) - checklist utility to run Life Science protocols
                    <br>PubChase - personolized biomedical literature recommendations
                    <br>Bench Utilities - Lab Counter, Timer, Molarity calculator and more'.
                    '<br>&nbsp;<br>Enjoy!
                    <br>ZappyLab Team.
                    <br><span style="font-size: 10px"><a href="http://www.protocols.io">www.protocols.io</a> & <a href="https://www.pubchase.com">www.pubchase.com</a></span>'.
                    str_replace($this->u_stub, 'Do not want to receive LabChat invitation emails from collegues? <a href="http://www.zappylab.com/unsubscribe?t=lab_chat&e='.$data['email'].'" style="font-size: 11px; color:#3f556d;">Unsubscribe</a>', $this->e2);

        $message->setMessageFromString(null, $message_text);
        $ses->sendEmail($message);
    }

    public function invEmail($note, $emails) {

        require_once('../../../Frameworks/amazon/services/ses.php');
        $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
        import('Zappy.Cache');
        
        $_c = new Cache();

        $emails_array = explode(',', $emails);
        foreach($emails_array as $e) {
            $cache_key = 'invited_'.$e;
            if (!$_c->get($cache_key)) {
                $message = new SimpleEmailServiceMessage();
                $message->addTo('"PubChase" <'.$e.'>');
                $message->setFrom(DEFAULT_EMAIL);
                $message->setSubject($_SESSION['user']->first_name.' invites you to join PubChase!');

                $message_text = str_replace($this->l_stub, $this->l_pubchase, str_replace($this->s_stub, $_SESSION['user']->first_name.' invites you to join PubChase!', $this->e1)).
                                '<h1 style="font-size: 20px; color: #111; line-height: 30px;font-weight: normal;">Hello!</h1>
                                '.$_SESSION['user']->first_name.' '.$_SESSION['user']->last_name.' thought you would enjoy using PubChase. You can create a library of scientific articles and based on its content, PubChase will recommend you new literature most relevant to your interests.<br>'.
                                $note.
                                'Follow the link below to create your <b>free</b> account!<br>'.
                                '<br><a href="https//www.pubchase.com">www.pubchase.com</a><p><br>'.
                                '<br>&nbsp;<br>PubChase Team.<br>&nbsp;'.
                                str_replace($this->u_stub, '<a href="https://www.pubchase.com/unsubscribe?t=friend_invite&e='.$e.'" style="font-size: 11px; color:#3f556d;">Unsubscribe</a>', $this->e2);

                $message->setMessageFromString(null, $message_text);
                $ses->sendEmail($message);
                $_c->set($cache_key, 1, 3600*12);
                usleep(200001);
            }
        }
    }

    public function sesOnComment($noti_type_id, $data) {
        require_once('../../../Frameworks/amazon/services/ses.php');
        $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
        $message = new SimpleEmailServiceMessage();
        $message->addTo($data['target_user_email']);
        $message->setFrom(DEFAULT_EMAIL);

        if ($noti_type_id == NOTI_NEW_COMMENT) {
            $h0 = str_replace($this->l_stub, $this->l_spectro, str_replace($this->s_stub, 'New comment posted!', $this->e1));
            $h1 = '<h1 style="font-size: 20px; color: #111; line-height: 30px;font-weight: normal;">New comment awaits your approval!</h1>';
            $h2 = $data['follower_user_name'].' wrote:<br>&nbsp;<br><span style="font-style: italic; color: #000; font-size: 12px">'.$data['comment'].'</span><br>&nbsp;<br>You can approve or delete this comment here: <a href="'.SITE_URL.'/essay/'.$data['essay_uri'].'">'.SITE_URL.'/essay/'.$data['essay_uri'].'</a>';
            $message->setSubject('New comment posted on your post');
        }
        elseif ($noti_type_id == NOTI_NEW_RESPONSE) {
            $h0 = str_replace($this->l_stub, $this->l_pubchase, str_replace($this->s_stub, 'Author posted response to your comment!', $this->e1));
            $h1 = '<h1 style="font-size: 20px; color: #111; line-height: 30px;font-weight: normal;">Author posted a response</h1>';
            $h2 = $data['follower_user_name'].' wrote:<br>&nbsp;<br><span style="font-style: italic; color: #000; font-size: 12px">'.$data['comment'].'</span><br>&nbsp;<br>View the conversation here: <a href="'.SITE_URL.'/essay/'.$data['essay_uri'].'">'.SITE_URL.'/essay/'.$data['essay_uri'].'</a>';
            $message->setSubject('Aauthor posted response to your comment');
        }
        elseif ($noti_type_id == NOTI_QUESTION_ANSWERED) {
            $h0 = str_replace($this->l_stub, $this->l_pubchase, str_replace($this->s_stub, 'Your question received another answer!', $this->e1));
            $h1 = '<h1 style="font-size: 20px; color: #111; line-height: 30px;font-weight: normal;">You have a new answer to a Career Advice question on PubChase!</h1>';
            $h2 = '<br>View the answer on your question page: <a href="'.SITE_URL.'/career/question/'.$data['comment_uri'].'">'.SITE_URL.'/career/question/'.$data['comment_uri'].'</a>';
            $message->setSubject('New answer for your Career Advice question on PubChase');
        }

        $message_text = $h0.$h1.$h2.
                  '<br>PubChase Team.
                  <br>&nbsp;'.
                  str_replace($this->u_stub, str_replace('token', $data['token'], $this->manage_settings), $this->e2);
        $message->setMessageFromString(null, $message_text);
        $ses->sendEmail($message);
    }

    private function encodeObject($obj) {
      return str_replace('%2F', '/', rawurlencode($obj));
    }

    public function removeS3Object($object, $bucket=PDF_S3_BUCKET) {
      return $this->s3->deleteObject(array("Key" => $object, "Bucket" => $bucket)) ? true : false;
    }

    public function putS3Object($key, $object_path, $object_data = null, $bucket=PDF_S3_BUCKET, $acl='private') {
      if (!isset($object_data)) {
        
        $opts = array(
          'http'=>array(
            'method'=>"GET"
          )
        );

        $context = stream_context_create($opts);
        return $this->s3->putObject(array('Key' => $key, 'Bucket' => $bucket, 'Body' => fopen($object_path, 'r', false, $context), 'ACL' => $acl)) ? true : false;
      }
      else
        return $this->s3->putObject(array('Key' => $key, 'Bucket' => $bucket, 'Body' => $object_data, 'ACL' => $acl)) ? true : false;
    }

    public function queryStringAuthentication($object, $bucket=PDF_S3_BUCKET, $expires=600) {

      $command = $this->s3->getCommand('GetObject', array('Bucket' => $bucket,
                                     'Key' => $object,
                             'ResponseContentType' => 'application/pdf',
                        'ResponseContentDisposition' => 'inline; filename="pdf.pdf"'));

      return $command->createPresignedUrl('+10 minutes');
    }

    public function send_info_email() {
        require_once('../../../Frameworks/amazon/services/ses.php');
        $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);

        $sql = 'SELECT email, salutation FROM emails WHERE email_sent=0';
        $res = $this->db->query($sql);
        date_default_timezone_set('UTC');
        $now = date("Y-m-d H:m:00");

        foreach($res as $r) {
            $message = new SimpleEmailServiceMessage();
            $message->setFrom('lenny@zappylab.com');
            $message->addTo($r['email']);
            $message->setSubject('Central protocol repository for the life sciences');
            $message->setMessageFromString(null, 'Dear '.$r['salutation'].',
<br><br>
Please pardon the unsolicited e-mail.
<br><br>
My name is Lenny Teytelman, and for two years now, I have devoted all of my energy to building a <b>crowdsourced</b>, open protocol repository for the life sciences. I spent a decade as a graduate student and postdoctoral researcher, re-discovering knowledge that others have not had the time to publish and improving existing methods without the ability to share the improvements with the world. I co-founded ZappyLab to change this. 
<br><br>
We have developed and released many free tools to aid the life science researchers. All of them are part of the protocol repository effort - we are building a crowd for the crowdsourcing of the protocols.
<br><br>
To support the work, we just launched a Kickstarter campaign.
<br><br>
<a href="https://www.kickstarter.com/projects/1881346585/protocolsio-life-sciences-protocol-repository?ref=home_location">https://www.kickstarter.com/projects/1881346585/protocolsio-life-sciences-protocol-repository?ref=home_location</a>
<br><br>
If you can back our effort, it should take you 2-3 minutes to do this. And the amount is not nearly as important as the act of contributing. The more backers we have, the higher the chance that we succeed. Also, the way Kickstarter works, we get either all or nothing. So if you contribute, you will only be charged if we raise >=$50K. And if we do, all the Kickstarter rewards aside, you will have an amazing protocol repository and mobile app.
<br><br>
Most of all, if you can forward our campaign to other scientists, that can make all the difference.
<br><br>
Thank you!
<br><br>
Lenny
<br><br>
P.S. Just a quick summary of the free tools and resources that we have built:
<br><a href="http://www.pubchase.com/">PubChase</a> - personalized research literature recommendations
<br><a href="http://www.zappylab.com/benchtools">Bench Tools</a> - mobile platform to help in benchwork (on <a href="https://itunes.apple.com/US/app/id731295151?mt=8">iOS</a> and <a href="https://play.google.com/store/apps/details?id=com.zappylab.benchtools">Android</a>) 
<br><a href="https://www.pubchase.com/essays?mostviewed">Essay platform</a> - article-level blog for telling your story behind the research
<br><a href="https://www.pubchase.com/career/ask">Career advice forum</a> - crowdsourced mentoring for scientists');
            $ses->sendEmail($message);
            
            unset($message);
            $sql = 'UPDATE emails SET email_sent = 1, sent_on=? WHERE email=?';
            $res = $this->db->execute($sql, array($now, $r['email']));
        }

    }

    public function forward_question_to_lenny($question_url) {
        require_once('../../../Frameworks/amazon/services/ses.php');
        $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
        $message = new SimpleEmailServiceMessage();
        $message->setFrom('pubchase@zappylab.com');
        $message->addTo('lenny@zappylab.com');
        $message->addTo('alexei@zappylab.com');
        $message->setSubject('New question posted on PubChase Carreer Advice');
        $message->setMessageFromString(null, $question_url);
        $ses->sendEmail($message);
        return true;
    }

    public function sesJobDone($data) {
        require_once('../../../Frameworks/amazon/services/ses.php');
        $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);
        $message = new SimpleEmailServiceMessage();
        $message->addTo('alexei@zappylab.com');
        $message->setFrom(DEFAULT_EMAIL);

        $subj = $data['job_name'].' completed';
        $message->setSubject($subj);

        $message_text = str_replace($this->l_stub, $this->l_zappylab, str_replace($this->s_stub, $subj, $this->e1)).
                    '<h1 style="font-size: 20px; color: #111; line-height: 30px;font-weight: normal;">'.$subj.'</h1>'.
                    '<br>'.$data['info'].'<br>'.
                    '<br>ZappyLab House Elves.'.
                    '<br>&nbsp;'.
                    str_replace($this->u_stub, '', $this->e2);
                                  
        $message->setMessageFromString(null, $message_text);
        $ses->sendEmail($message);
  }
}
?>