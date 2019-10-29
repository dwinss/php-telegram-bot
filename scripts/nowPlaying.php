<?php

# now playing

if(preg_match("#^/np ([a-zA-Z0-9\-\_]{5,})$#iu", $_TEXT, $cbb) || $_TEXT == 'np' || mb_substr($_TEXT, 0, 3, 'utf-8') == '/np')
	{
		if($_TEXT == 'np' || mb_substr($_TEXT, 0, 3, 'utf-8') == '/np')
			{
				$q = mysql_query("SELECT * FROM `lastfm_users` WHERE `id_user` = ".$_USER['id']);
				if(mysql_num_rows($q) == 1)
					{
						$tmp = mysql_fetch_assoc($q);
						$cbb[1] = $tmp['lastfm_username'];
					}
				else
					{
						$error = true;
						sendMessage($_CHAT['id'], 'Я не знаю твой никнейм на LastFM. Напиши команду `/iam YourUsername`', 'Markdown', $_MESS['message_id']);
					}
			}
		
		if(@$error !== true)
			{
				$user = $cbb[1];
				$url = 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user='.$user.'&api_key=1c15cbf8b0b1ec524d3633e8c55ad934&format=json&limit=1';
				$f = file_get_contents($url);
				
				$array = json_decode($f, TRUE);
				
				
				if(isset($array['error']))
					{
						$mess = 'Ошибка';
					}
				else
					{
						if(isset($array['recenttracks']['track'][0]['@attr']['nowplaying']))
							{
								$np = 'Now playing';
							}
						else
							{
								$np = 'Last played';
							}
						$mess = $np.': <b>'.$array['recenttracks']['track'][0]['artist']['#text'].'</b> - <a href="'.$array['recenttracks']['track'][0]['url'].'">'.$array['recenttracks']['track'][0]['name'].'</a>';
						
						$mess .= PHP_EOL;
						$mess .= '/last_'.$user.' - последние 10 треков'.PHP_EOL;
					}
						
				
				
				// Грузим страницу на LastFm и ищем линки 
				
				$ch = curl_init($array['recenttracks']['track'][0]['url']);
				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_REFERER, 'https://lastfm.ru/');
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla2/2.8');
				
				$a = curl_exec($ch);
				
				// Ищем линки на сервисы, жиесть
				
				$dynamicPart = '(?:[а-яА-Яa-zA-Z0-9\?\.\-\_\/\=\%\;\&\#\|\+\~\[\]\:]{3,200})';
				$patterns = array(
					'appleMusic' => array('(https\://(itunes|geo\.itunes|music)\.apple\.com/'.$dynamicPart.')', 'Apple Music'),
					'spotify' =>  array('(https\://open\.spotify\.com/'.$dynamicPart.')', 'Spotify'),
					'youtube' =>  array('(https\://(?:www\.)?youtube\.com/'.$dynamicPart.')', 'YouTube'),
					'pandora' =>  array('(https\://(?:www\.)?pandora\.com/'.$dynamicPart.')', 'Pandora'),
					'google' =>  array('(https\://play\.google\.com/music/'.$dynamicPart.')', 'Google Music'),
					'deezer' =>  array('(https\://(?:www\.)?deezer\.com/'.$dynamicPart.')', 'Deezer'),
					'amazonMusic' =>  array('(https\://music\.amazon\.com/'.$dynamicPart.')', 'Amazon'),
					'tidal' =>  array('(https\://listen\.tidal\.com/'.$dynamicPart.')', 'Tidal'),
					'napster' =>  array('(http\://napster\.com/'.$dynamicPart.')', 'Napster'),
					'yandex' =>  array('(https\://music\.yandex\.ru/'.$dynamicPart.')', 'Yandex Music'),
					'soundcloud' => array('(https\://soundcloud\.com/'.$dynamicPart.')', 'SoundCloud')
					
					);
				
				foreach($patterns as $service => $pattern)
					{
						if(preg_match('#'.$pattern[0].'#iu', $a, $res))
							{
								$_FOUND = true;
								$sourceLink = trim($res[1]);
								
								$url = 'https://api.song.link/v1-alpha.1/links?url='.urlencode($sourceLink);
						
								$ch = curl_init($url);

								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($ch, CURLOPT_REFERER, 'https://github.com/songlink/docs/blob/master/api-v1-alpha.1.md');

								$a = curl_exec($ch);
								
								$data = @json_decode($a, true);
								
								
								if($data === false)
									{
										sendMessage($_CHAT['id'], 'API error');
									}
								else
									{
										if(!empty($data['linksByPlatform']))
											{
												// Соберем линки на сервисы в отдельный массив - это удобнее
												foreach($data['linksByPlatform'] as $key => $value)
													{
														// включаем в массив только те сервисы, которые нас интересуют
														if(isset($patterns[$key]))
															{
																$links[$key] = $value['url'];
															}
													}
												
												// Необходимо выяснить название трека и исполнителя
												
												foreach($data['entitiesByUniqueId'] as $key => $value)
													{
														// Мы достигли первого элемента, больше нам и не надо
														$song['artist'] = isset($value['artistName']) ? $value['artistName'] : 'Unknown Artist';
														$song['title'] = isset($value['title']) ? $value['title'] : 'Unknown Track';
														break;
													}
												
												// Начинаем формировать сообщение
												// $message = 'Трек <b>'.$song['artist'].'</b> - <b>'.$song['title'].'</b> на стриминговых платформах:';
												
												$keyboard = [];
												$tmparr = [];
												$buttonsCounter = 0;
												
												$c_links = count($links);
												foreach($links as $service => $link)
													{
														$buttonsCounter++;
														$tmparr[] = array('text' => $patterns[$service][1], 'url' => $link);
														$needToSend = true;
														
														if($buttonsCounter % 3 == 0 OR $buttonsCounter == $c_links)
															{
																$keyboard[] = $tmparr;
																unset($tmparr);
															}
													}
												
												# проверяем, нашли ли ваще чего то кроме исходного сервиса
												if(@$needToSend)
													{
														//$txt = implode("\r\n", $links);
														
														//sendMessage($_CHAT['id'], $txt, 'HTML', $_MESS['message_id']);
														
														sendInlineKeyboard($_CHAT['id'], $mess, 'HTML', $_MESS['message_id'], $keyboard);
													}
											}
										else
											{
												sendMessage($_CHAT['id'], $mess, 'HTML', $_MESS['message_id']);
											}
										
									}
									
								break;
							}
					}
				
				if(@$_FOUND != true)
					{
						sendMessage($_CHAT['id'], $mess, 'HTML', $_MESS['message_id']);
					}
			}
		
	}

if(preg_match("#^/last_([a-zA-Z0-9\-\_]{5,})$#iu", $_TEXT, $cbb) OR preg_match("#^/last_([a-zA-Z0-9\-\_]{5,})@ADARefactorBot$#iu", $_TEXT, $cbb))
	{
		$user = $cbb[1];
		
		$url = 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user='.$user.'&api_key=1c15cbf8b0b1ec524d3633e8c55ad934&format=json&limit=10';
		
		$f = file_get_contents($url);
		
		$array = json_decode($f, TRUE);
		
		if(isset($array['error']))
			{
				$mess = 'Ошибка';
			}
		else
			{
				$info = $array['recenttracks']['@attr'];
				$mess = 'Последние 10 треков, которые слушал пользователь <a href="http://last.fm/user/'.$info['user'].'">'.$info['user'].'</a> (всего прослушиваний: <b>'.$info['total'].'</b>)'.PHP_EOL.PHP_EOL;
				$tracks = $array['recenttracks']['track'];
				foreach($tracks as $key => $value)
					{
						$mess .= '<b>'.$value['artist']['#text'].'</b> - <a href="'.$value['url'].'">'.$value['name'].'</a>';
						if(isset($value['@attr']['nowplaying']))
							{
								$mess .= ' <i>(играет сейчас)</i>';
							}
						$mess .= PHP_EOL;
					}
			}
		
		sendMessage($_CHAT['id'], $mess, 'HTML', $_MESS['message_id']);
	}

if(preg_match("#^/iam ([a-zA-Z0-9\-\_]{5,})$#iu", $_TEXT, $res))
	{
		// sendMessage($_CHAT['id'], '+');
		$username = trim($res[1]);
		
		mysql_query("DELETE FROM `lastfm_users` WHERE `id_user` = ".$_USER['id']);
		
		if(mysql_query("INSERT INTO `lastfm_users` (`id_user`, `lastfm_username`) VALUES (".$_USER['id'].", '".$username."')"))
			{
				sendMessage($_CHAT['id'], 'Сохранено', '', $_MESS['message_id']);
			}
		else
			{
				sendMessage($_CHAT['id'], mysql_error(), '', $_MESS['message_id']);
			}
	}