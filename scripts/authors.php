<?php

if(preg_match("#^/contacts$#iu", $_TEXT))
	{
		$message = '';
		$message .= 'Telegram: @D13410N3'.PHP_EOL;
		$message .= 'VK: https://vk.com/id19518934'.PHP_EOL;
		$message .= 'ICQ: 373160'.PHP_EOL.PHP_EOL;
		$message .= 'You can find sources of this bot on my GitHub: https://github.com/ICQFan4ever';
		sendMessage($_CHAT['id'], $message);
	}