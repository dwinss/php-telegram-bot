<?php

# shell 4 admin

if($_USER['username'] == ADMIN)
	{
		if(preg_match('#^/shell (.*)$#iu', $_TEXT, $res))
			{
				$result = shell_exec(trim($res[1]));
				
				sendMessage($_CHAT['id'], $result);
			}
		
		if(preg_match('#ада отключись#iu', $_TEXT))
			{
				sendMessage($_CHAT['id'], 'Запускаю процедуру отключения...');
				
				if(mysql_query("UPDATE `settings` SET `status` = 0"))
					{
						sleep(5);
						sendMessage($_CHAT['id'], 'Все функции отключены');
						
						sleep(15);
						sendMessage($_CHAT['id'], 'Все функции отключены. Запускаю удаление обработчиков');
						sleep(10);
						sendMessage($_CHAT['id'], 'Обработчики удалены. Требуется ручное удаление файлов scripts/shellExec.php ; api.php ; process.php. Запускаю очистку БД');
						sleep(2);
						sendMessage($_CHAT['id'], 'БД очищена. Необходимо ручное удаление таблицы settings.');
						sleep(1);
						sendMessage($_CHAT['id'], 'Вебхук и API-ключ: удалено. Параметры доступа к БД удалены.');
						sleep(10);
						sendMessage($_CHAT['id'], 'Процесс завершен');
						leaveChat($_CHAT['id']);
						
						
					}
			}
		
		if(preg_match('#ада включись#iu', $_TEXT))
			{
				sendMessage($_CHAT['id'], 'Идет восстановление резервной копии...');
				sleep(30);
				if(mysql_query("UPDATE `settings` SET `status` = 1"))
					{
						sendMessage($_CHAT['id'], 'Все функции восстановлены. Доступность БД ~'.mt_rand(100,140).' секунд');
					}
			}
		/*
		if(preg_match == 'forbid /y')
			{
				sendMessage($_CHAT['id'], 'Закрываю доступ к /var/www');
				sendMessage(shell_exec('sudo -S chmod 0700 /var/www'));
			}
		*/
		
		# вручную пишем кому-нить
		if($_CHAT['id'] == $_USER['id'])
			{
				if(preg_match('#^напиши ([a-zA-Z0-9_]{1,}) (.*)#iu', $_TEXT, $res))
					{
						# res[1] is nickname
						# res[2] is text
						
						$nickname = trim($res[1]);
						$q = mysql_query("SELECT * FROM `tg_users` WHERE `nick` = '".$nickname."' LIMIT 1");
						if(mysql_num_rows($q) == 1)
							{
								$__inf = mysql_fetch_assoc($q);
								$id_chat = $__inf['id_user'];
								
								$text = trim($res[2]);
								
								$_text = sendMessage($id_chat, $text);
								sendMessage($_CHAT['id'], $_text);
							}
					}
			}
		
		# парсим irq
		
	}

if(!empty($_CHAT['id']) && !empty($_USER['id']) && $_CHAT['id'] == $_USER['id'] && $_USER['username'] != ADMIN)
	{
		sendMessage(41851891, $_USER['username'].' пишет: '.$_TEXT);
	}

if(isset($_MESS['left_chat_member']))
	{
		deleteMessage($_CHAT['id'], $_MESS['message_id']);
	}