<?php

if(preg_match('#^/authors#iu', $_TEXT))
	{
		$message = 'by @D13410N3'.PHP_EOL;
		$message .= 'Sources: https://github.com/ICQFan4ever/php-telegram-bot';
		
		sendMessage($_CHAT['id'], $message, '', $_MESS['message_id']);
	}