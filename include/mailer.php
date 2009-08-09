<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'swift' . DIRECTORY_SEPARATOR . 'swift_required.php';

class MyMailer
{
	private static $mailer = null;
	
	static function getMailer()
	{
		if (is_null(self::$mailer))
		{
			switch (_MAILER_TYPE)
			{
				case 'sendmail':
					$transport = Swift_SendmailTransport::newInstance(_MAILER_SENDMAIL_CMD);
					break;
					
				case 'smtp':
					$transport = Swift_SmtpTransport::newInstance(_MAILER_SMTP_HOST);
					break;
					
				default:
					throw new Exception('Unknown mailer type: ' . _MAILER_TYPE);
			}
			
			self::$mailer = Swift_Mailer::newInstance($transport);
		}
		
		return self::$mailer;
	}
}

