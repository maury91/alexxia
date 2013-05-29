<?php
/**
 *	Email sender for ALExxia
 *	
 *	Copyright (c) 2013 Maurizio Carboni. All rights reserved.
 *
 *	This file is part of ALExxia.
 *	
 *	ALExxia is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *	
 *	ALExxia is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with ALExxia.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     alexxia
 * @author      Maurizio Carboni <maury91@gmail.com>
 * @copyright   2013 Maurizio Carboni
 * @license     http://www.gnu.org/licenses/  GNU General Public License
**/
//Require the phpmailer
require __base_path.'levels/3/mail/class.phpmailer.php';

//This class send a email using the current configuration of the cms
class MAIL {
	public static function send($email,$subject,$html,$alt='',$attachments=array()) {
		$mail = new PHPMailer;
		//If the cms is configured to use the smtp
		if (GLOBALS::val('sitesmtp')) {
			//Connect to the smtp
			require __base_path.'config/smtp.php';
			$mail->IsSMTP();         
			/*$mail->SMTPDebug  = 2;
			$mail->Debugoutput = 'html';     */                        
			$mail->Host 	= $email_host;
			$mail->SMTPAuth = true;
			$mail->Username = $email_user;
			$mail->Password = $email_pass;
			$mail->Port 	= $email_port;
			if ($email_ssl!='')
				$mail->SMTPSecure = $email_ssl;
		}
		//Set the variables
		$mail->SetFrom(GLOBALS::val('sitemail'), GLOBALS::val('sitemailn'));
		$mail->AddAddress($email);
		$mail->Subject = $subject;
		$mail->MsgHTML($html);
		$mail->AltBody = $alt;
		foreach ($attachments as $v)
			$mail->AddAttachment($v);
		try {
			$send = $mail->Send();
		} catch (phpmailerException $e) {
		  	return $e->errorMessage(); //Pretty error messages from PHPMailer
		}
		return $send;
	}
}
?>