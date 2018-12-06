<?php

$q_ttt = mysql_query("SELECT * FROM `tasks_acl` WHERE `id_user` = ".$_USER['id']);

if(!$q_ttt)
	{
		# oops
	}
else
	{
		$c_ttt = mysql_num_rows($q_ttt);

		if($c_ttt == 1)
			{
				if(preg_match("#^/tasks add permanent#iu", $_MESS['text']))
					{
						# /tasks add permanent "Прими таблетки" 1111100  12:30
						#                                       дн. нед. ч.:мин.
						$error = array();
						# ищем текст
						preg_match("#\"(.*)\"#iu", $_MESS['text'], $matches);
						if(!isset($matches[1]))
							{
								$error[] = 'Ошибка в запросе: не указан текст';
							}
						else
							{
								$db['text'] = trim($matches[1]);
								
								# ищем строку времени выполнения
								if(preg_match("#( [01]{7} [0-9\*]{1,2}\:[0-9\*]{1,2})$#iu", $_MESS['text'], $matches))
									{
										# дни недели
										preg_match("# ([01]{7}) #iu", $matches[1], $matches1);
										# время
										preg_match("# ([0-9\*]{1,2}\:[0-9\*]{1,2})$#iu", $matches[1], $matches2);
										
										
										$dd = str_split($matches1[1]);
										list($db['hour'], $db['minute']) = explode(':', $matches2[1]);
										
										$db['hour'] = str_replace('**', '*', $db['hour']);
										$db['minute'] = str_replace('**', '*', $db['minute']);
										
										if(!($db['hour'] > 0 || $db['hour'] < 23 || $db['hour'] == '*') || !($db['minute'] > 0 || $db['minute'] < 59 || $db['minute'] =='*'))
											{
												$error[] = 'Ошибка в запросе: неверно указано время';
											}
									}
								else
									{
										$error[] = 'Некорректный формат строки времени';
									}
							}
						if(empty($error))
							{
								#writing
								foreach($dd as $key => $value)
									{
										$dd[$key] = $value == 1 ? 1 : 0;
									}
								
								if(mysql_query("INSERT INTO `tasks_permanent`(`id_user`, `time_added`, `text`, `en_mon`, `en_tue`, `en_wed`, `en_thu`, `en_fri`, `en_sat`, `en_sun`, `hour`, `minute`, `times_executed`) VALUES (".$_USER['id'].", ".time().", '".$db['text']."', ".$dd[0].", ".$dd[1].", ".$dd[2].", ".$dd[3].", ".$dd[4].", ".$dd[5].", ".$dd[6].", '".$db['hour']."', '".$db['minute']."', 0)"))
									{
										$_send = 'Успешно добавлено';
									}
								else
									{
										$_send = 'Ошибка: '.mysql_error();
									}
							}
						else
							{
								$_send = '';
								foreach($error as $value)
									{
										$_send .= $value.PHP_EOL;
									}
								
							}
						
						sendMessage($_CHAT['id'], $_send, 'Markdown');
					}
				
				# задания по дате
				if(preg_match("#^/tasks add date#iu", $_MESS['text']))
					{
						# /tasks add permanent "Прими таблетки" 01.01.2017  12:30
						#                                       дн. нед. ч.:мин.
						$error = array();
						# ищем текст
						$xuj = $_MESS['text'];
						preg_match("#\"(.*)\"#iu", $_MESS['text'], $matches3);
						if(!isset($matches3[1]))
							{
								$error[] = 'Ошибка в запросе: не указан текст';
							}
						else
							{
								$db['text'] = trim($matches3[1]);
								
								# ищем строку времени выполнения

								if(preg_match("#(\s+(?:[0-9]{1,2}|\*{1})\.(?:[0-9]{1,2}|\*{1})\.(?:[0-9]{4}|\*{1})\s+(?:[0-9]{1,2}|\*{1})\:(?:[0-9]{1,2}|\*{1}))#iu", $_MESS['text'], $matches))
									{
										#
										
										
										# дата
										preg_match("#((?:[0-9]{1,2}|\*{1})\.(?:[0-9]{1,2}|\*{1})\.(?:[0-9]{4}|\*{1}))#iu", $matches[1], $matches1);
										
										# время
										preg_match("#((?:[0-9]{1,2}|\*{1})\:(?:[0-9]{1,2}|\*{1}))#iu", $matches[1], $matches2);
										// sendMessage($_CHAT['id'], 'Я пони! Дата - '.$matches1[1].', время - '.$matches2[1]);
									
										list($db['day'], $db['month'], $db['year']) = explode('.', $matches1[1]);
										list($db['hour'], $db['minute']) = explode(':', $matches2[1]);
										
										
										/*if(($db['day'] > 31 || $db['day'] != '*') || ($db['month'] > 12 || $db['month'] != '*'))
											{
												$error[] = 'Некорректный формат даты';
											}
										else
											{
												if(($db['hour'] > 23 || $db['hour'] != '*') || ($db['minute'] > 59 || $db['minute'] != '*'))
													{
														$error[] = 'Некорректный формат времени';
													}
											}
										*/
									
									}
								else
									{
										$error[] = 'Некорректный формат строки времени:';
										// вот если сюда сунуть $_MESS['text'] или $xuj - вообще не выполняется. 
									}
							}
						if(empty($error))
							{
								# пишем
								if(mysql_query("INSERT INTO `tasks_date`(`id_user`, `time_added`, `text`, `day`, `month`, `year`, `hour`, `minute`, `times_executed`, `enabled`) VALUES (".$_USER['id'].", ".time().", '".$db['text']."', '".$db['day']."', '".$db['month']."', '".$db['year']."', '".$db['hour']."', '".$db['minute']."', 0, TRUE)"))
									{
										$_send = 'Успешно сохранено';
									}
								else
									{
										$_send = 'Ашипка:'.mysql_error();
									}
							}
						else
							{
								$_send = '';
								foreach($error as $value)
									{
										$_send .= $value.PHP_EOL;
									}
							}
						sendMessage($_CHAT['id'], $_send, 'Markdown');
					}
				
				# лист заданий
				if(preg_match("#^/tasks list#iu", $_MESS['text']))
					{
						# 1) перманентные
						
						
						$q = mysql_query("SELECT * FROM `tasks_permanent` WHERE `id_user` = ".$_USER['id']." ORDER BY `id` ASC");
						$c = mysql_num_rows($q);
						
						if($c > 0)
							{
								$arr = array('mon' => 'пн', 'tue' => 'вт', 'wed' => 'ср', 'thu' => 'чт', 'fri' => 'пт', 'sat' => 'сб', 'sun' => 'вс');
								$_mess = '*Задания по дням недели ('.$c.')*:'.PHP_EOL;
								while($_t = mysql_fetch_assoc($q))
									{
										$_mess .= 'Задание №*'.$_t['id'].'*'.PHP_EOL;
										$_mess .= '*'.$_t['text'].'*'.PHP_EOL;
										$_mess .= 'Дни недели: *';
										foreach($arr as $en => $ru)
											{
												if($_t['en_'.$en] == 1)
													{
														$_mess .= $ru.',';
													}
											}
										$_mess .= '*'.PHP_EOL;
										$_mess .= 'Время: *'.($_t['hour'] == '*' ? 'каждый час' : $_t['hour']).':'.($_t['minute'] == '*' ? 'каждая минута' : $_t['minute']).'*'.PHP_EOL;
										$_mess .= 'Выполнено *'.$_t['times_executed'].'* раз(-а)'.PHP_EOL;
										$_mess .= 'Состояние: *'.($_t['enabled'] == 1 ? 'включено' : 'выключено').'*'.PHP_EOL.PHP_EOL;
										
										
									}
								sendMessage($_CHAT['id'], $_mess, 'Markdown');
								sleep(1);
							}
						
						# по дате
						$q2 = mysql_query("SELECT * FROM `tasks_date` WHERE `id_user` = ".$_USER['id']." ORDER BY `id` ASC");
						$c2 = mysql_num_rows($q2);
						if($c2 > 0)
							{
								$_mess = '*Задания по датам ('.$c2.')*:'.PHP_EOL;
								while($_t = mysql_fetch_assoc($q2))
									{
										$_mess .= 'Задание №*'.$_t['id'].'*'.PHP_EOL;
										$_mess .= '*'.$_t['text'].'*'.PHP_EOL;
										$_mess .= 'Дата: *'.($_t['day'] == '*' ? 'каждое число' : $_t['day']).'/'.($_t['month'] == '*' ? 'каждый месяц' : $_t['month']).'/'.($_t['year'] == '*' ? 'каждый год' : $_t['year']).'*'.PHP_EOL;
										$_mess .= 'Время: *'.($_t['hour'] == '*' ? 'каждый час' : $_t['hour']).':'.($_t['minute'] == '*' ? 'каждая минута' : $_t['minute']).'*'.PHP_EOL;
										$_mess .= 'Выполнено *'.$_t['times_executed'].'* раз(-а)'.PHP_EOL;
										$_mess .= 'Состояние: *'.($_t['enabled'] == 1 ? 'включено' : 'выключено').'*'.PHP_EOL.PHP_EOL;
									}
								sendMessage($_CHAT['id'], $_mess, 'Markdown');
							}
					}
			}
	}