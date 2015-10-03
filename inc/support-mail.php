<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }  
if ( 'POST' == $_SERVER['REQUEST_METHOD']) {
  parse_str($_POST['contact'],$postdata);
  parse_str($_POST['contact']);
  if (  !$site_name   OR !$email_id OR  !$problem ){
    $json['status'] = false;
    $json['message'] = __('Please Fill Required Fields', $this->text_domain);
    echo json_encode( $json);
    die(0);
  }
  $emaildata    =   array('site_name'=>'Name','email_id'=>'Email','problem'=>'Problem');
  $mailmessage  =  'New Support request from: '.get_option('blogname') . "\r\n\r\n";
  $mailmessage .= '<table width="99%" cellspacing="0" cellpadding="1" border="0" bgcolor="#EAEAEA">
  <tbody>
    <tr>
      <td><table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="#FFFFFF">
        <tbody>'.ead_getemailnode($emaildata,$postdata).'</tbody>
        </table>
      </td>
    </tr>
    </tbody>
</table>';
add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
$tomail='hello@awsm.in';
$subject =  "[EAD-free] Support Request From ". get_option('blogname');
$headers = "Reply-To: <" . $email_id . ">\n";
if (wp_mail( $tomail, $subject, $mailmessage, $headers )) {
    $json['status'] = true;
    $json['message'] = __('We will get back to you soon', $this->text_domain); 
  } else {
    $json['status'] = false;
    $json['message'] = __('Something went wrong', $this->text_domain); 
  }
echo json_encode($json);
}else{
  $json['status'] = false;
  $json['message'] = __('Cheating ?', $this->text_domain);
  echo json_encode($json);
}
die(0);
?>
       