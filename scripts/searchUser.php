<?php

if($_USER['username'] == ADMIN | $_USER['username'] == 'AyayayYyyev')
	{
		
		if(preg_match("#^/search (.*)#iu", $_TEXT, $res))
			{
				$string = mysql_real_escape_string($res[1]);
				# by ID
				
				$mess = '';
				
				$q = mysql_query("SELECT * FROM `tg_users` WHERE `id_user` LIKE '%".$string."%'");
				
				if(mysql_num_rows($q) != 0)
					{
						$mess .= '_Поиск по ID:_'.PHP_EOL;
						while($inf = mysql_fetch_assoc($q))
							{
								$mess .= 'Ник: *'.$inf['nick'].'*, ID: `'.$inf['id_user'].'`'.PHP_EOL;
								
								if(mb_strlen($mess, 'utf-8') >= 900)
									{
										sendMessage($_CHAT['id'], $mess, 'Markdown');
										$mess = '';
									}
							}
					}
				else
					{
						$mess .= 'Поиск по ID ничего не вернул'.PHP_EOL;
					}
				
				
				# флюшим
				sendMessage($_CHAT['id'], $mess, 'Markdown');
				$mess = '';
				
				# by nick
				
				$q = mysql_query("SELECT * FROM `tg_users` WHERE `nick` LIKE '%".$string."%'");
				if(mysql_num_rows($q) != 0)
					{
						$mess .= '_Поиск по никнейму:_'.PHP_EOL;
						while($inf = mysql_fetch_assoc($q))
							{
								$mess .= 'Ник: *'.$inf['nick'].'*, ID: `'.$inf['id_user'].'`'.PHP_EOL;
								if(mb_strlen($mess, 'utf-8') >= 900)
									{
										sendMessage($_CHAT['id'], $mess, 'Markdown');
										$mess = '';
									}
							}
					}
				else
					{
						$mess .= 'Поиск по никнейму ничего не вернул';
					}
				
				sendMessage($_CHAT['id'], $mess, 'Markdown');
			}
	}