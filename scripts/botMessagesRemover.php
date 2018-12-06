<?php

if($_USER['username'] == ADMIN && $_USER['id'] != $_CHAT['id'])
	{
		if($_TEXT == 'удоли' && !empty($_MESS['reply_to_message']))
			{
				deleteMessage($_CHAT['id'], $_MESS['reply_to_message']['message_id']);
			}
	}