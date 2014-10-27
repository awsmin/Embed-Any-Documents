<?php
if ( 'POST' == $_SERVER['REQUEST_METHOD']) {
  $str = parse_str($_POST['contact']);
  if (  !$name   OR !$email OR  !$message ){
    $json['status'] = false;
    $json['message'] = 'All Fields Required';
    echo json_encode( $json);
    die(0);
  }
  $mailmessage  = sprintf(__('New Support request from:'), get_option('blogname')) . "\r\n\r\n";
  $mailmessage .= '<table width="99%" cellspacing="0" cellpadding="1" border="0" bgcolor="#EAEAEA">
  <tbody>
    <tr>
      <td><table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="#FFFFFF">
        <tbody>
          <tr bgcolor="#EAF2FA">
            <td colspan="2"><font style="font-family:sans-serif;font-size:12px"><strong>Site Name:</strong></font></td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td width="20">&nbsp;</td>
            <td><font style="font-family:sans-serif;font-size:12px">'.$site_name .'</font></td>
          </tr>
          <tr bgcolor="#EAF2FA">
            <td colspan="2"><font style="font-family:sans-serif;font-size:12px"><strong>Email ID:</strong></font></td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td width="20">&nbsp;</td>
            <td><font style="font-family:sans-serif;font-size:12px">'.$email_id .'</font></td>
          </tr>
          <tr bgcolor="#EAF2FA">
            <td colspan="2"><font style="font-family:sans-serif;font-size:12px"><strong>Problem</strong></font></td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td width="20">&nbsp;</td>
            <td><font style="font-family:sans-serif;font-size:12px">'.$problem.'</font></td>
          </tr>
        </tbody>
        </table>
      </td>
    </tr>
    </tbody>
</table>';
add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
$tomail='hello@awsm.in';
$subject =  "New Suppot Request from ". get_option('blogname');
$headers = "Reply-To: <" . $tomail . ">\n";
if (wp_mail( $tomail, $subject, $mailmessage, $headers )) {
   $json['status'] = true;
   $json['message'] = 'Succes Message';
  } else {
    $json['status'] = false;
    $json['message'] = 'Failed Message';
  }
echo json_encode($json);
}else{
  $json['status'] = false;
  $json['message'] = 'Cheating ?';
  echo json_encode($json);
}
die(0);
?>
       