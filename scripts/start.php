<?php

if(preg_match('#^/start#iu', $_TEXT))
	{
		// sendMessage($_CHAT['id'], '+');
		$message = 'Здравствуйте!'.PHP_EOL;
		$message .= 'Доступный функционал описан в меню (знак <code>/</code> справа от поля ввода текста)'.PHP_EOL;
		
		$message .= 'Автор - @D13410N3. Исходники бота доступны <a href="https://github.com/ICQFan4ever/php-telegram-bot">здесь</a>';
		
		sendMessage($_CHAT['id'], $message, '', $_MESS['message_id']);
	}