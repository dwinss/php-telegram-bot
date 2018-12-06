<?php

if($_USER['username'] == ADMIN && $_USER['id'] != $_CHAT['id'])
	{
		if($_TEXT == '/dis')
			{
				$q = mysql_query("SELECT * FROM `blacklist_chats` WHERE `id_chat` = ".$_CHAT['id']);
				
				if(mysql_num_rows($q) == 0)
					{
						if(mysql_query("INSERT INTO `blacklist_chats`(`id_chat`) VALUES (".$_CHAT['id'].")"))
							{
								$message = '+';
							}
						else
							{
								$message = 'error';
							}
					}
				else
					{
						$message = 'already';
					}
				
				sendMessage($_CHAT['id'], $message, 'Markdown', $_MESS['message_id']);
				
			}
		
		if($_TEXT == '/en')
			{
				mysql_query("DELETE FROM `blacklist_chats` WHERE `id_chat` = ".$_CHAT['id']);
				sendMessage($_CHAT['id'], '+', 'Markdown', $_MESS['message_id']);
			}
	}