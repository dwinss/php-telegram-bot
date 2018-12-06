<?php

$speech = recognizeVoice($_MESS);
if($speech != false OR preg_match("#^напомни мне(.*)#iu", $_TEXT, $resss))
	{
		// sendMessage($_CHAT['id'], 'Обработка...');
		if(!empty($resss))
			{
				$speech = 'напомни мне '.trim($resss[1]);
			}
		if(preg_match("#^напомни мне(.*)#iu", $speech, $res))
			{
				$words = trim($res[1]);
				# СТРАШНЫЙ СУД АД НАСИЛИЕ КРОВЬ КЛАДБИЩЕ СМЕРТЬ
				if(preg_match("#через ([\.\,\d]+) (?:час|часов|часа)#iu", $words, $ress))
					{
						$hours = (float)str_replace(',', '.', $ress[1]);
						$insec = $hours * 3600;
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 3;
					}
				elseif(preg_match("#через полчаса#iu", $words, $ress))
					{
						$insec = 1800;
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 2;
					}
				elseif(preg_match("#через час#iu", $words, $ress))
					{
						$insec = 3600;
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 2;
					}
				elseif(preg_match("#через день#iu", $words, $ress))
					{
						$insec = 86400;
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 2;
					}
				elseif(preg_match("#через неделю#iu", $words, $ress))
					{
						$insec = 86400 * 7;
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 2;
					}
				elseif(preg_match("#через полтора часа#iu", $words, $ress))
					{
						$insec = 5400;
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 3;
					}
				elseif(preg_match("#через ([0-9\.\,]{1,}) (?:час|часа|часов)#iu", $words, $ress))
					{
						$insec = 3600 * $ress[1];
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 3;
					}
				elseif(preg_match("#через ([0-9\.\,]{1,}) (?:час|часа|часов) (\d+) (?:минут|минуты|минуту)#iu", $words, $ress))
					{
						$insec = 3600 * $ress[1] + 60 * $ress[2];
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 5;
					}
				elseif(preg_match("#через ([0-9\.\,]{1,}) (?:час|часа|часов) и (\d+) (?:минут|минуты|минуту)#iu", $words, $ress))
					{
						$insec = 3600 * $ress[1] + 60 * $ress[2];
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 6;
					}
				elseif(preg_match("#через (\d+)\:(\d+)#iu", $words, $ress))
					{
						$insec = 3600 * $ress[1] + 60 * $ress[2];
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 2;
					}
				elseif(preg_match("#через (\d+) (?:минут|минуты|минуту)#iu", $words, $ress))
					{
						$minutes = (int)$ress[1];
						$insec = $minutes * 60;
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 3;
					}
				elseif(preg_match("#через минуту#iu", $words, $ress))
					{
						$insec = 60;
						$dd = date('d.m.Y.H.i', time() + $insec);
						$offset = 2;
					}
				elseif(preg_match("#сегодня в ([0-9]{1,2} [0-9]{1,2})#iu", $words, $ress))
					{
						$data = str_replace(' ', '.', $ress[1]);
						$dd = date('d.m.Y').'.'.$data;
						$offset = 4;
					}
				elseif(preg_match("#сегодня в ([0-9]{1,2}\:[0-9]{1,2})#iu", $words, $ress))
					{
						$data = str_replace(':', '.', $ress[1]);
						$dd = date('d.m.Y').'.'.$data;
						$offset = 3;
					}
				elseif(preg_match("#завтра в ([0-9]{1,2} [0-9]{1,2})#iu", $words, $ress))
					{
						$data = str_replace(' ', '.', $ress[1]);
						$dd = date('d.m.Y', time() + 86400).'.'.$data;
						$offset = 4;
					}
				elseif(preg_match("#завтра в ([0-9]{1,2}\:[0-9]{1,2})#iu", $words, $ress))
					{
						$data = str_replace(':', '.', $ress[1]);
						$dd = date('d.m.Y', time() + 86400).'.'.$data;
						$offset = 3;
					}
				elseif(preg_match("#завтра в ([0-9]{1,2}) (?:час|часов|часа)#iu", $words, $ress))
					{
						$data = (int)$ress[1].'.00';
						$dd = date('d.m.Y', time() + 86400).'.'.$data;
						$offset = 4;
					}
				elseif(preg_match("#завтра в полдень#iu", $words, $ress))
					{
						$dd = date('d.m.Y', time() + 86400).'.12.00';
						$offset = 3;
					}
				elseif(preg_match("#сегодня в ([0-9]{1,2}) (?:час|часов|часа)#iu", $words, $ress))
					{
						$data = (int)$ress[1].'.00';
						$dd = date('d.m.Y').'.'.$data;
						$offset = 4;
					}
				elseif(preg_match("#в ([0-9]{1,2}) (?:час|часов|часа)#iu", $words, $ress))
					{
						$data = (int)$ress[1].'.00';
						$dd = date('d.m.Y').'.'.$data;
						$offset = 3;
					}
				elseif(preg_match("#сегодня в полдень#iu", $words, $ress))
					{
						$dd = date('d.m.Y').'.12.00';
						$offset = 3;
					}
				elseif(preg_match("#в полдень#iu", $words, $ress))
					{
						$dd = date('d.m.Y').'.12.00';
						$offset = 2;
					}
				elseif(preg_match("#послезавтра в ([0-9]{1,2} [0-9]{1,2})#iu", $words, $ress))
					{
						$data = str_replace(' ', '.', $ress[1]);
						$dd = date('d.m.Y', time() + 172800).'.'.$data;
						$offset = 4;
					}
				elseif(preg_match("#послезавтра в ([0-9]{1,2}\:[0-9]{1,2})#iu", $words, $ress))
					{
						$data = str_replace(':', '.', $ress[1]);
						$dd = date('d.m.Y', time() + 172800).'.'.$data;
						$offset = 3;
					}
				elseif(preg_match("#послезавтра в ([0-9]{1,2}) (?:час|часов|часа)#iu", $words, $ress))
					{
						$data = (int)$ress[1].'.00';
						$dd = date('d.m.Y', time() + 172800).'.'.$data;
						$offset = 4;
					}
				elseif(preg_match("#послезавтра в полдень#iu", $words, $ress))
					{
						$dd = date('d.m.Y', time() + 172800).'.12.00';
						$offset = 3;
					}
				elseif(preg_match("#через день#iu", $words, $ress))
					{
						$dd = date('d.m.Y', time() + 86400).'.12.00'; // дефолтно в полдень
						$offset = 2;
					}
				elseif(preg_match("#через (\d+) (?:день|дня|дней) в ([0-9]{1,2} [0-9]{1,2})#iu", $words, $ress))
					{
						$days = (int)$ress[1];
						$time = str_replace(' ', '.', $ress[2]);
						$dd = date('d.m.Y', time() + 86400 * $days).'.'.$time; // дефолтно в полдень
						$offset = 6;
					}
				elseif(preg_match("#через (\d+) (?:день|дня|дней) в ([0-9]{1,2}\:[0-9]{1,2})#iu", $words, $ress))
					{
						$days = (int)$ress[1];
						$time = str_replace(':', '.', $ress[2]);
						$dd = date('d.m.Y', time() + 86400 * $days).'.'.$time; // дефолтно в полдень
						$offset = 5;
					}
				elseif(preg_match("#через (\d+) (?:день|дня|дней)#iu", $words, $ress))
					{
						$days = (int)$ress[1];
						$dd = date('d.m.Y', time() + 86400 * $days).'.12.00'; // дефолтно в полдень
						$offset = 3;
					}
				elseif(preg_match("#(\d+) (января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря) в ([0-9]{1,2} [0-9]{1,2})#iu", $words, $ress))
					{
						# 0 - вся строка, 1 - первое вхождение, 2 - второе вхождение...
						$day = (int)$ress[1];
						$a1 = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
						$a2 = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
						$month = str_replace($a1, $a2, $ress[2]);
						if($month >= date('m'))
							{
								$year = date('Y');
							}
						else
							{
								$year = date('Y') + 1;
							}
						$time = str_replace(' ', '.', $ress[3]);
						$dd = $day.'.'.$month.'.'.$year.'.'.$time;
						$offset = 5;
					}
				elseif(preg_match("#(\d+) (января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря) в ([0-9]{1,2}\:[0-9]{1,2})#iu", $words, $ress))
					{
						# 0 - вся строка, 1 - первое вхождение, 2 - второе вхождение...
						$day = (int)$ress[1];
						$a1 = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
						$a2 = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
						$month = str_replace($a1, $a2, $ress[2]);
						if($month >= date('m'))
							{
								$year = date('Y');
							}
						else
							{
								$year = date('Y') + 1;
							}
						$time = str_replace(':', '.', $ress[3]);
						$dd = $day.'.'.$month.'.'.$year.'.'.$time;
						$offset = 4;
					}
				elseif(preg_match("#(\d+) (января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря) в ([0-9]{1,2}) (?:час|часа|часов)#iu", $words, $ress))
					{
						# 0 - вся строка, 1 - первое вхождение, 2 - второе вхождение...
						$day = (int)$ress[1];
						$a1 = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
						$a2 = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
						$month = str_replace($a1, $a2, $ress[2]);
						if($month >= date('m'))
							{
								$year = date('Y');
							}
						else
							{
								$year = date('Y') + 1;
							}
						$time = (int)$ress[3].'.00';
						$dd = $day.'.'.$month.'.'.$year.'.'.$time;
						$offset = 5;
					}
				elseif(preg_match("#(\d+) (января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря)#iu", $words, $ress))
					{
						# 0 - вся строка, 1 - первое вхождение, 2 - второе вхождение...
						$day = (int)$ress[1];
						$a1 = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
						$a2 = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
						$month = str_replace($a1, $a2, $ress[2]);
						if($month >= date('m'))
							{
								$year = date('Y');
							}
						else
							{
								$year = date('Y') + 1;
							}
						$dd = $day.'.'.$month.'.'.$year.'.12.00';
						$offset = 2;
					}
				elseif(preg_match("#в ([0-9]{1,2} [0-9]{1,2})#iu", $words, $ress))
					{
						$data = str_replace(' ', '.', $ress[1]);
						$dd = date('d.m.Y').'.'.$data;
						$offset = 3;
					}
				elseif(preg_match("#в ([0-9]{1,2}\:[0-9]{1,2})#iu", $words, $ress))
					{
						$data = str_replace(':', '.', $ress[1]);
						$dd = date('d.m.Y').'.'.$data;
						$offset = 2;
					}
				else
					{
						$err = true;
					}
				
				if(!isset($err))
					{
						$str = explode(' ', $speech);
						$c = count($str);
						$d = explode('.', $dd);
						//$__send = 'Распознано: дата события - '.$d['0'].'.'.$d['1'].'.'.$d['2'].' '.$d['3'].':'.$d['4'].', текст события - ';
						$txt = '';
						# +2 упирается в "напомни мне". Можно сделать вариации
						for($i = $offset + 2; $i < $c; $i++)
							{
								//$_send .= $str[$i].' ';
								$txt .= $str[$i].' ';
							}
						// $__send .= '. Исходное сообщение: '.$speech;
						//sendMessage($_CHAT['id'], $__send);
						// $txt = mysql_real_escape_string($txt);
						$d[0] = addNull($d[0]);
						$d[3] = addNull($d[3]);
						$d[4] = addNull($d[4]);
						if(mysql_query("INSERT INTO `voice_reminders`(`id_user`, `time`, `day`, `month`, `year`, `hour`, `minute`, `txt`, `done`) VALUES ('".$_CHAT['id']."', ".time().", '".$d[0]."', '".$d[1]."', '".$d[2]."', '".$d[3]."', '".$d[4]."', '@".$_USER['username'].": ".trim($txt)."', 0)"))
							{
								$__id = mysql_insert_id();
								$_send = 'Успешно создано: *'.$txt.'*, я напомню _'.$d[0].'.'.$d[1].'.'.$d[2].'_ в _'.$d[3].':'.$d[4].'_.';
							}
						else
							{
								$_send = 'Ошибка базы данных';
							}
					}
				else
					{
						$_send = 'Напоминание не создано: не распознано время события ('.$speech.')';
					}
				
				sendMessage($_CHAT['id'], $_send, 'Markdown');
			}
		elseif(preg_match("#(?:удали|удалить) напоминание (\d+)#iu", $speech, $res))
			{
				$id = (int)$res[1];
				if(mysql_query("DELETE FROM `voice_reminders` WHERE `id_user` = '".$_USER['id']."' AND `id` = ".$id))
					{
						$_send = 'Напоминание удалено';
					}
				else
					{
						$_send = 'Напоминание не найдено';
					}
				sendMessage($_CHAT['id'], $_send);
			}
		elseif(preg_match("#(?:удали|удалить) напоминание номер (\d+)#iu", $speech, $res))
			{
				$id = (int)$res[1];
				if(mysql_query("DELETE FROM `voice_reminders` WHERE `id_user` = '".$_USER['id']."' AND `id` = ".$id))
					{
						$_send = 'Напоминание удалено';
					}
				else
					{
						$_send = 'Напоминание не найдено';
					}
				sendMessage($_CHAT['id'], $_send);
			}
		elseif(preg_match("#(?:удали|удалить) все напоминани#iu", $speech))
			{
				if(mysql_query("DELETE FROM `voice_reminders` WHERE `id_user` = '".$_USER['id']."'"))
					{
						$_send = 'Напоминания удалены';
					}
				else
					{
						$_send = 'Ошибка базы данных';
					}
				sendMessage($_CHAT['id'], $_send);
			}
		elseif(preg_match("#^очисти все напоминания#iu", $speech) && preg_match("#<censored>#iu", $speech) && $_USER['username'] == ADMIN)
			{
				mysql_query("TRUNCATE `voice_reminders`");
				sendMessage($_CHAT['id'], 'Как прикажешь');
			}
		elseif(preg_match("#(покажи|список) (все|всех) напоминани#iu", $speech))
			{
				$q = mysql_query("SELECT * FROM `voice_reminders` WHERE `id_user` = ".$_USER['id']." AND `done` != 1 ORDER BY `id` ASC");
				$c = mysql_num_rows($q);
				if($c > 0)
					{
						$_send = '*СПИСОК ВСЕХ НАПОМИНАНИЙ*'.PHP_EOL.PHP_EOL.'Используйте голосовую команду _удали напоминание (ID)_ для удаления.'.PHP_EOL.PHP_EOL;
						while($rem = mysql_fetch_assoc($q))
							{
								$_send .= 'ID '.$rem['id'].': "'.$rem['txt'].'" (_'.$rem['day'].'.'.$rem['month'].'.'.$rem['year'].'_ в _'.$rem['hour'].':'.$rem['minute'].'_)'.PHP_EOL.PHP_EOL;
							}
					}
				else
					{
						$_send = 'Нет напоминаний';
					}
				sendMessage($_CHAT['id'], $_send, 'Markdown');
			}
	}