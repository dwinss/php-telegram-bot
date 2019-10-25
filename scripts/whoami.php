<?php

if(preg_match('#^/whoami#iu', $_TEXT))
	{
		$mess = '';
		$mess .= '<code>'.$_USER['id'].'</code>: '.$_USER['username'];
		
		if($_CHAT['id'] != $_USER['id'])
			{
				$mess .= PHP_EOL.'<code>'.$_CHAT['id'].'</code>: '.$_CHAT['title'];
			}
		
		sendMessage($_CHAT['id'], $mess, 'HTML', $_MESS['message_id']);
	}
