<?php
if($_USER['id'] == $_CHAT['id'] OR $_USER['username'] == ADMIN)
	{
		if(preg_match('#^https\://www\.youtube\.com/watch\?v=([a-zA-Z0-9\-_]{1,})$#iu', $_MESS['text'], $res) OR preg_match('#^https\://youtu\.be/([a-zA-Z0-9\-_]{1,})$#iu', $_MESS['text'], $res))
			{
				$id_video = trim($res[1]);
				# 1) Getting extension
				$cmd = 'youtube-dl --get-filename https://www.youtube.com/watch?v='.$id_video;
				$exec = shell_exec($cmd);
				$tmp = explode('.', $exec);
				$ext = end($tmp);
				
				# 2) Fucking kostyl' - getting video title
				$ch = curl_init('https://www.youtube.com/watch?v='.$id_video);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_REFERER, 'https://www.youtube.com/');
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
				
				$a = curl_exec($ch);
				
				preg_match('#<title>(.*)</title>#iu', $a, $res);
				$title = translit(trim($res[1]));
				$title = str_replace('_-_YouTube', '', $title);
				
				# Downloading a video
				$cmd = 'youtube-dl -f \'best\' -o \'/var/www/4nmv.ru/bot/data/video/'.$title.'.%(ext)s\' https://www.youtube.com/watch?v='.$id_video;
				$result = shell_exec($cmd);
				
				if(preg_match('#\[download\] 100\%#iu', $result))
					{
						sendMessage($_CHAT['id'], 'https://4nmv.ru/bot/data/video/'.$title.'.'.$ext);
					}
				else
					{
						sendMessage($_CHAT['id'], 'Что-то пошло не так...');
						if($_USER['username'] == ADMIN)
							{
								sendMessage($_CHAT['id'], 'cmd: `'.$cmd.'`'.PHP_EOL.'Result: ```'.$result.'```', 'Markdown');
							}
					}
			}
	}
/*
$id_video = trim($res[1]);
				# 1) Getting extension
				$cmd = 'youtube-dl --get-filename https://www.youtube.com/watch?v='.$id_video;
				$exec = shell_exec($cmd);
				$tmp = explode('.', $exec);
				$ext = end($tmp);
				
				# 2) Fucking kostyl' - getting video title
				$ch = curl_init('https://www.youtube.com/watch?v='.$id_video);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_REFERER, 'https://www.youtube.com/');
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
				
				$a = curl_exec($ch);
				
				preg_match('#<title>(.*)</title>#iu', $a, $res);
				$title = translit(trim($res[1]));
				$title = str_replace('_-_YouTube', '', $title);
				
				# Downloading a video
				// $cmd = 'youtube-dl -f \'best\' -o \'/var/www/4nmv.ru/bot/data/video/'.$title.'.%(ext)s\' https://www.youtube.com/watch?v='.$id_video;
				
				// script.php path/to/file link_to_file chat_id message_id title
				
				$mess = sendMessage($_CHAT['id'], 'Загрузка началась...');
				
				
				$cmd = "/usr/bin/php /var/www/4nmv.ru/bot/youtubeDownloader.php /var/www/4nmv.ru/bot/data/video/".$title.".%(ext)s https://www.youtube.com/watch?v=".$id_video." ".$_CHAT['id']." ".$mess['message_id']." ".$title." ".$ext;
				$result = shell_exec($cmd);
				
*/