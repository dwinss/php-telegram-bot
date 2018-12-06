<?php

if($_USER['id'] != $_CHAT['id'] && ($_TEXT == 'цитата' | $_TEXT == 'цитатка' | $_TEXT == 'цтт'))
	{
		if(empty($_MESS['reply_to_message']['message_id']))
			{
		
				$qr = mysql_query("SELECT * FROM `quotes` WHERE `id_chat` = ".$_CHAT['id']." ORDER BY RAND() LIMIT 1");
				
				if(mysql_num_rows($qr) != 1)
					{
						$message = 'В этом чате пока нет цитат';
					}
				else
					{
						$quote = mysql_fetch_assoc($qr);
						$message = '<b>«'.$quote['text'].'»</b> <i>© '.$quote['username'].'</i>';
					}
			}
		else
			{
				if(!empty($_MESS['reply_to_message']['text']))
					{
						$text = mysql_real_escape_string($_MESS['reply_to_message']['text']);
						$username = empty($_MESS['reply_to_message']['from']['username']) ? $_MESS['reply_to_message']['from']['first_name'] : '@'.$_MESS['reply_to_message']['from']['username'];
						
						if(isset($_MESS['reply_to_message']['forward_from']))
							{
								$username = empty($_MESS['reply_to_message']['forward_from']['username']) ? $_MESS['reply_to_message']['forward_from']['first_name'] : '@'.$_MESS['reply_to_message']['forward_from']['username'];
							}
						
						if(mysql_query("INSERT INTO `quotes`(`id_chat`, `username`, `text`, `time`) VALUES (".$_CHAT['id'].", '".$username."', '".$text."', ".time().")"))
							{
								$username = str_replace('@', '', $username);
								$message = '<b>«'.$text.'»</b> <i>©'.$username.'</i>';
							}
						else
							{
								$message = mysql_error();
							}
					}
				else
					{
						$message = 'И как я эту хуйню цитировать буду?';
					}
			}
		sendMessage($_CHAT['id'], $message, 'HTML', $_MESS['message_id']);
	}

if(preg_match('#^цитата ([a-zA-Z0-9\.\-_@]{3,})$#iu', $_MESS['text'], $res))
	{
		$nickname = trim($res[1]);
		
		$q = mysql_query("SELECT * FROM `quotes` WHERE `id_chat` = ".$_CHAT['id']." AND `username` = '".$nickname."' ORDER BY RAND() LIMIT 1");
		
		if(mysql_num_rows($q) != 1)
			{
				$message = '<i>Пока нечего цитировать</i>';
			}
		else
			{
				$quote = mysql_fetch_assoc($q);
				$quote['username'] = str_replace('@', '', $quote['username']);
				$message = '<b>«'.$quote['text'].'»</b> <i>© '.$quote['username'].'</i>';
			}
		
		sendMessage($_CHAT['id'], $message, 'HTML', $_MESS['message_id']);
	}

if($_USER['username'] == ADMIN)
	{
		if($_TEXT == 'сколько цитат')
			{
				$qc = mysql_num_rows(mysql_query("SELECT * FROM `quotes` WHERE `id_chat` = ".$_CHAT['id']));
				sendMessage($_CHAT['id'], $qc, '', $_MESS['message_id']);
			}
	}