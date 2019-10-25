<?php
if(mb_substr($_TEXT, 0, 6, 'utf-8') == '/stats')
	{
		if($_CHAT['id'] == $_USER['id'])
			{
				sendMessage($_CHAT['id'], 'Статистика доступна только в чатах');
			}
		else
			{
				if(preg_match('#^/stats (\d+)$#iu', $_TEXT, $res))
					{
						$seconds = (int)$res[1] * 3600;
						$q = mysql_query("SELECT `user_nick`, count(`message`) AS ccc from `messages` WHERE `id_chat` = ".$_CHAT['id']." AND `time` > ".(time() - $seconds)." GROUP BY `user_nick` ORDER BY `ccc` DESC LIMIT 10");
						$c1 = mysql_num_rows(mysql_query("SELECT * FROM `messages` WHERE `id_chat` = ".$_CHAT['id']." AND `time` > ".(time() - $seconds)));
					}
				else
					{
						$q = mysql_query("SELECT `user_nick`, count(`message`) AS ccc from `messages` WHERE `id_chat` = ".$_CHAT['id']." GROUP BY `user_nick` ORDER BY `ccc` DESC LIMIT 10");
					}
				
				$str = '<i>10 самых активных пользователей чата'.(isset($seconds) ? ' за '.$res[1].' ч' : '').'</i>'.PHP_EOL;
				
				while($ress = mysql_fetch_assoc($q))
					{
						$str .= '<code>'.$ress['user_nick'].'</code>: <b>'.$ress['ccc'].'</b>'.PHP_EOL;
						
					}
				$q2 = mysql_query("SELECT * FROM `messages` WHERE `id_chat` = ".$_CHAT['id']);
				$c2 = mysql_num_rows($q2);
				
				$str .= PHP_EOL;
				$str .= isset($c1) ? '<i>Всего сообщений за '.$res[1].' ч:</i> '.$c1.PHP_EOL : '';
				$str .= '<i>Всего сообщений в этом чате:</i> '.$c2.PHP_EOL;
				$str .= '<code>/stats N</code> - статистика за последние N часов'.PHP_EOL;
				$str .= 'Глобальная статистика - /globalstats'.PHP_EOL;
				
				sendMessage($_CHAT['id'], $str, 'HTML', $_MESS['message_id']);
			}
	}

if(preg_match('#^/globalstats#iu', $_TEXT))
	{
		$q = mysql_query("SELECT `user_nick`, count(`message`) AS ccc from `messages` GROUP BY `user_nick` ORDER BY `ccc` DESC LIMIT 10");
		$str = '<i>10 самых активных пользователей (по всем чатам)</i>'.PHP_EOL;
		while($ress = mysql_fetch_assoc($q))
			{
				$str .= '<code>'.$ress['user_nick'].'</code>: <b>'.$ress['ccc'].'</b>'.PHP_EOL;
			}
		
		sendMessage($_CHAT['id'], $str, 'HTML', $_MESS['message_id']);
	}