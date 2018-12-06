<?php

if(isset($_MESS['voice']) && $_USER['username'] == ADMIN)
	{
		if($_MESS['voice']['duration'] > 15)
			{
				$send = 'Сложнааааа';
			}
		else
			{
				$send = recognizeVoice($_MESS);
				$send = 'Doesnt work';
			}
		//$send = 'Слушать тебя не хочу, тварь';
		sendMessage($_CHAT['id'], $send, 'HTML', $_MESS['message_id']);
	}