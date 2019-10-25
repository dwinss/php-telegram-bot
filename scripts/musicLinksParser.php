<?php

# Динамическая часть query string
$dynamicPart = '(?:[а-яА-Яa-zA-Z0-9\?\.\-\_\/\=\%\;\&\#\|\+\~\[\]\:]{3,200})';

# паттерны сервисов и их имена
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

# ищем в сообщении линки по паттерну
foreach($patterns as $service => $pattern)
	{
		if(preg_match('#'.$pattern[0].'#iu', $_MESS['text'], $res))
			{
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
						$message = 'Трек <b>'.$song['artist'].'</b> - <b>'.$song['title'].'</b> на стриминговых платформах:';
						
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
								print_r($keyboard);
								// sendMessage($_CHAT['id'], $mess, 'HTML', $_MESS['message_id']);
								sendInlineKeyboard($_CHAT['id'], $message, 'HTML', $_MESS['message_id'], $keyboard);
							}
						
					}
			}
	}

if(preg_match('#^/music#iu', $_TEXT))
	{
		$message = 'Отправьте в чат ссылку на трек в стриминговом сервисе, в ответ бот пришлет ссылки на другие сервисы'.PHP_EOL;
		$message .= 'Поддерживаемые: <code>Apple Music, Spotify, Youtube, Pandora, Google Play Music, Amazon Music, Deezer, Tidal, Napster, Яндекс.Музыка, Soundcloud</code>'.PHP_EOL;
		
		$message .= PHP_EOL;
		
		$message .= '<i>via API odesli.co</i>';
		
		sendMessage($_CHAT['id'], $message, 'HTML', $_MESS['message_id']);
	}