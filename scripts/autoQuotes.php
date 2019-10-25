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
				if(!empty($_MESS['reply_to_message']['text']) OR !empty($_MESS['reply_to_message']['caption']))
					{
						$nonfiltertext = !empty($_MESS['reply_to_message']['text']) ? $_MESS['reply_to_message']['text'] : $_MESS['reply_to_message']['caption'];
						$text = mysql_real_escape_string($nonfiltertext);
						$username = empty($_MESS['reply_to_message']['from']['username']) ? $_MESS['reply_to_message']['from']['first_name'] : '@'.$_MESS['reply_to_message']['from']['username'];
						
						if(isset($_MESS['reply_to_message']['forward_from']))
							{
								$username = empty($_MESS['reply_to_message']['forward_from']['username']) ? $_MESS['reply_to_message']['forward_from']['first_name'] : '@'.$_MESS['reply_to_message']['forward_from']['username'];
							}
						
						$check = str_replace('@', '', $username);
						
						$q = mysql_query("SELECT * FROM `quotes_bl` WHERE `username` = '".$check."'");
						
						if(mysql_num_rows($q) == 1)
							{
								$message = 'Цитирование пользователя запрещено';
							}
						else
							{
								if(mysql_query("INSERT INTO `quotes`(`id_chat`, `username`, `text`, `time`) VALUES (".$_CHAT['id'].", '".$username."', '".$text."', ".time().")"))
									{
										$username = str_replace('@', '', $username);
										$message = '<b>«'.$nonfiltertext.'»</b> <i>©'.$username.'</i>';
									}
								else
									{
										$message = mysql_error();
									}
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
		// $q = mysql_query("SELECT * FROM `quotes` WHERE `username` = '".$nickname."' ORDER BY RAND() LIMIT 1");
		
		#if($_USER['username'] == ADMIN && $nickname == '@GreenKovsh')
		#	{
		#		$q = mysql_query("SELECT * FROM `quotes` WHERE `id` = 189");
		#	}
		
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
		if(preg_match('#^/quotes disable @([a-zA-Z0-9\.\-\_]{1,})#iu', $_TEXT, $res))
			{
				$username = $res[1];
				
				$q = mysql_query("SELECT * FROM `quotes_bl` WHERE `username` = '".$username."'");
				
				if(mysql_num_rows($q) == 1)
					{
						$message = 'Цитирование уже запрещено';
					}
				else
					{
						if(mysql_query("INSERT INTO `quotes_bl`(`username`) VALUES ('".$username."')"))
							{
								$message = 'Цитирование запрещено';
							}
					}
				
				sendMessage($_CHAT['id'], $message, '', $_MESS['message_id']);
			}

		if(preg_match('#^/quotes enable @([a-zA-Z0-9\.\-\_]{1,})#iu', $_TEXT, $res))
			{
				$username = $res[1];
				$q = mysql_query("SELECT * FROM `quotes_bl` WHERE `username` = '".$username."'");
				
				if(mysql_num_rows($q) == 0)
					{
						$message = 'Цитирование не запрещено';
					}
				else
					{
						mysql_query("DELETE FROM `quotes_bl` WHERE `username` = '".$username."'");
						$message = 'Цитирование вновь разрешено';
					}
				
				sendMessage($_CHAT['id'], $message, '', $_MESS['message_id']);
			}
	}

if(preg_match('#^(\d+) (?:цитат|цитаты)$#iu', $_TEXT, $res))
	{
		$num = (int)$res[1];
		$num = ($num < 1 | $num > 10) ? 1 : $num;
		
		$qr = mysql_query("SELECT * FROM `quotes` WHERE `id_chat` = ".$_CHAT['id']." ORDER BY RAND() LIMIT ".$num);
		
		if(mysql_num_rows($qr) > 0)
			{
				$message = '';
				
				while($quote = mysql_fetch_assoc($qr))
					{
						$quote['username'] = str_replace('@', '', $quote['username']);
						$message .= '<b>«'.$quote['text'].'»</b> <i>© '.$quote['username'].'</i>'.PHP_EOL.PHP_EOL;
					}
			}
		else
			{
				$message = 'Пока нет цитат';
			}
			
		sendMessage($_CHAT['id'], $message, 'HTML', $_MESS['message_id']);
	}

if(preg_match('#^(\d+) (?:цитат|цитаты) ([a-zA-Z0-9\.\-_@]{3,})$#iu', $_TEXT, $res))
	{
		$num = (int)$res[1];
		$num = ($num < 1 | $num > 10) ? 1 : $num;
		$username = $res[2];
		
		$qr = mysql_query("SELECT * FROM `quotes` WHERE `id_chat` = ".$_CHAT['id']." AND `username` = '".$username."' ORDER BY RAND() LIMIT ".$num);
		
		if(mysql_num_rows($qr) > 0)
			{
				$message = '';
				
				while($quote = mysql_fetch_assoc($qr))
					{
						$quote['username'] = str_replace('@', '', $quote['username']);
						$message .= '<b>«'.$quote['text'].'»</b> <i>© '.$quote['username'].'</i>'.PHP_EOL.PHP_EOL;
					}
			}
		else
			{
				$message = 'Пока нет цитат';
			}
			
		sendMessage($_CHAT['id'], $message, 'HTML', $_MESS['message_id']);
	}


if(preg_match('#^/quote_stats(.*)?#iu', $_TEXT, $res))
	{
		if(!empty($res))
			{
				if(preg_match('#^_#iu', $res[1]))
					{
						$query[1] = "SELECT `username`, count(`text`) AS ccc from `quotes` GROUP BY `username` ORDER BY `ccc` DESC LIMIT 10";
						$query[2] = "SELECT * FROM `quotes`";
						$title = 'Самые цитируемые пользователи (глобально)';
					}
				else
					{
						$query[1] = "SELECT `username`, count(`text`) AS ccc from `quotes` WHERE `id_chat` = ".$_CHAT['id']." GROUP BY `username` ORDER BY `ccc` DESC LIMIT 10";
						$query[2] = "SELECT * FROM `quotes` WHERE `id_chat` = ".$_CHAT['id'];
						$title = 'Самые цитируемые пользователи чата';
					}
			}
		else
			{
				$query[1] = "SELECT `username`, count(`text`) AS ccc from `quotes` WHERE `id_chat` = ".$_CHAT['id']." GROUP BY `username` ORDER BY `ccc` DESC LIMIT 10";
				$query[2] = "SELECT * FROM `quotes`";
				$title = 'Самые цитируемые пользователи чата';
			}
		
		$q = mysql_query($query[1]);
		
		$str = '<b>'.$title.'</b>:'.PHP_EOL.PHP_EOL;
		
		while($ress = mysql_fetch_assoc($q))
			{	
				$str .= '<code>'.str_replace('@', '', $ress['username']).'</code>: '.$ress['ccc'].PHP_EOL;
			}
		
		$c = mysql_num_rows(mysql_query($query[2]));
		$str .= '<i>Всего цитат</i>: '.$c.PHP_EOL;
		
		sendMessage($_CHAT['id'], $str, 'HTML');
	}

if(preg_match('#^/quotes#iu', $_TEXT))
	{
		$message = 'Система цитат в пределах чата'.PHP_EOL.PHP_EOL;
		
		$message .= '/quote или "цитата" - вывести случайную цитату'.PHP_EOL;
		$message .= '<code>цитата @username</code> - вывести случайную цитату пользователя'.PHP_EOL;
		$message .= '<code>(1-10) цитат </code> - вывести 1-10 случайных цитат'.PHP_EOL;
		$message .= '<code>(1-10) цитат @username</code> - вывести 1-10 случайных цитат пользователя'.PHP_EOL;
		$message .= '/quote_stats - статистика цитат в пределах чата'.PHP_EOL;
		$message .= '/quote_stats_global - статистика цитат (глобальаная)'.PHP_EOL;
		
		$message .= PHP_EOL;
		$message .= 'Для создания цитаты ответьте на сообщение словом <code>цитата</code>'.PHP_EOL;
		
		sendMessage($_CHAT['id'], $message, 'HTML', $_MESS['message_id']);
	}