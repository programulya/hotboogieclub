<?php

error_reporting(0);
$emailAddress = 'YOUR EMAIL ADDRESS HERE';

require "form/class.phpmailer.php";

//Min length validation
function validateMinLength($length, $number){
		//if it's NOT valid
		if(strlen($length) < $number)
			return false;
		//if it's valid
		else
			return true;
	}	
//Max length validation
	function validateMaxLength($length, $number){
		//if it's NOT valid
		if(strlen($length) > $number)
			return false;
		//if it's valid
		else
			return true;
	}	
//Email length validation
	function validateEmail($email){		
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}         
//Phone length validation	
	function validatePhone($phone){
		return preg_match("/^[0-9 -]{1,}$/", $phone);
	}

session_start();

$err = array();
//2 = Minimum Number, 21 = Maximum Number
if(!validateMinLength($_POST['name'], 2))$err[]='The name field is too short or empty'; 	
if(!validateMaxLength($_POST['name'], 21))$err[]='The name field must be less than 21 characters'; 	

//if(!validateMinLength($_POST['company'], 2))$err[]='PHP: The company field is too short or empty'; 	
//if(!validateMaxLength($_POST['company'], 21))$err[]='PHP: The company field is too long'; 	

//2 = Minimum Number, 254 = Maximum Number
if(!validateMinLength($_POST['email'], 2))$err[]='The email field is too short or empty'; 	
if(!validateMaxLength($_POST['email'], 254))$err[]='The email field must be less than 254 characters'; 	
if(validateMinLength($_POST['email'], 2) && validateMaxLength($_POST['email'], 254) && !validateEmail($_POST['email']))$err[]='That email address appears to be invalid';  			

//11 = Minimum Number, 23 = Maximum Number
if(!validateMinLength($_POST['phone'], 11))$err[]='The phone number field must be at least 11 numbers'; 			
if(!validateMaxLength($_POST['phone'], 23))$err[]='The phone number field must be less than 22 numbers';
if(validateMaxLength($_POST['phone'], 23) && validateMinLength($_POST['phone'], 11) && !validatePhone($_POST['phone']))$err[]='The phone number is not valid. Numbers and Hyphens (-) only'; 			

//1 = Minimum Number
if(!validateMinLength($_POST['message'], 1))$err[]='The message field is too short or empty'; 			

//5 = Minimum Number, 5 = Maximum Number
if(!validateMinLength($_POST['verify'], 5))$err[]='The security captcha field is too short or empty'; 	
if(!validateMaxLength($_POST['verify'], 5))$err[]='The security captcha field is too long'; 	
if(validateMinLength($_POST['verify'], 5) && validateMaxLength($_POST['verify'], 5) && md5($_POST['verify']) != $_SESSION['verify'])$err[]='The captcha field appears to be incorrect';			

if(count($err)){
foreach($err as $one_er){
echo $one_er . "<br/>";	
}
exit();
}
else
session_destroy();
//Style how the received email will look
$msg='You have been contacted by '.$_POST['name'].'<br /><br />

<table style="padding:0;margin: 0;padding: 3px;width: 100%;border: 1px solid #000000;border-collapse: collapse;border-spacing: 0;">
<tr> 
<td style="width:35%;background:#1775ca;color:#FFF;padding:3px;">Field</td>
<td style="width:65%;background:#1775ca;color:#FFF;padding:3px;">Entry</td>
</tr>
<tr>
<td>Name</td><td>'.$_POST['name'].'</td>
</tr>
<tr>
<td>Email:</td><td>'.$_POST['email'].'</td>
</tr>
<tr>
<td>Company</td><td>'.$_POST['company'].'</td>
</tr>
<tr>
<td>Phone</td><td>'.$_POST['phone'].'</td>
</tr>
<tr>
<td>Message</td><td>'.$_POST['message'].'</td>
</tr>
</table>
<br />
You can contact '.$_POST['name'].' via the email '.$_POST['email'].' <br />
The recorded IP is '.$_SERVER['REMOTE_ADDR'].'';

$mail = new PHPMailer();
$mail->IsMail();
$mail->AddReplyTo($_POST['email'], $_POST['name']);
$mail->AddAddress($emailAddress);
$mail->SetFrom($_POST['email'], $_POST['name']);
$mail->Subject = "You have been contacted by ".$_POST['name']."";
$mail->MsgHTML($msg);
$mail->Send();
unset($_SESSION['post']);
echo "Message sent";
?>