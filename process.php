<?php
// exit;
require_once 'api.php'; // всякие функции отправки сообщений, обработки и так далее + задание констант типа API_TOKEN и коннект к бд

# сеттим вебхук, если скрипт выполняется из оболочки системы
if(php_sapi_name() == 'cli' OR isset($_GET['webhook']))
	{
		setWebhook(WEBHOOK);	
	}
# процессим входящую ебалу

$content = file_get_contents("php://input"); // всё, что пришло на вебхук ПОСТом - идет в $content
$update = @json_decode($content, true); // декодим из джсона в ассоциативный массив
ob_start();
print_r($update);
$dat = ob_get_contents();
file_put_contents('data/debug.log', $dat);
ob_end_clean();

// die;

if(!$update)
	{
		// кривой JSON, значит левый запрос или что-то такое
		die;
	}
else
	{
		if(isset($update['message']))
			{
				# делаем псевдоглобальные переменные
				$_MESS = $update['message']; // массив с содержанием самого сообщения (полезная информация то есть)
				$_TEXT = mb_strtolower($_MESS['text'], 'utf-8'); // для нерегистрозависимости сразу текст в нижнее подчеркивание
				$_CHAT = $_MESS['chat']; // информация о том, какой это чат (если это личка, части переменных не будет)
				$_USER = $_MESS['from']; // информация о юзере-отправителе
				$_USER['username'] = empty($_USER['username']) ? $_USER['first_name'].' '.$_USER['last_name'] : $_USER['username'];
				

				$_CHAT['title'] = empty($_CHAT['title']) ? 'ЛС' : $_CHAT['title'];

				// пишем в базу
				mysql_query("INSERT INTO `messages`(`id_chat`, `id_message`, `id_user`, `time`, `message`, `user_nick`, `chat_name`) VALUES ('".$_CHAT['id']."', '".$_MESS['message_id']."', '".$_USER['id']."', '".time()."', '".$_MESS['text']."', '".$_USER['username']."', '".$_CHAT['title']."')");

				// собираем базу юзеров
				$q_u = mysql_query("SELECT * FROM `tg_users` WHERE `id_user` = '".$_USER['id']."'");
				if(mysql_num_rows($q_u) < 1)
					{				
						mysql_query("INSERT INTO `tg_users`(`id_user`, `nick`) VALUES ('".$_USER['id']."', '".$_USER['username']."')");
					}
				
				// проверяем, чят или личка, если чят - пишем чят
				if($_USER['id'] != $_CHAT['id'])
					{
						$q_c = mysql_query("SELECT * FROM `tg_chats` WHERE `id_chat` = '".$_CHAT['id']."'");
						if(mysql_num_rows($q_c) < 1)
							{
								mysql_query("INSERT INTO `tg_chats`(`id_chat`, `title`) VALUES ('".$_CHAT['id']."', '".$_CHAT['title']."')");
							}
					}


				if($_USER['username'] == ADMIN)
					{
						if($_USER['id'] == $_CHAT['id'])
							{
								if(!empty($_MESS['document']['file_name']))
									{
										getFile($_MESS);
									}
							}
					}
					
				$qt = mysql_query("SELECT * FROM `blacklist_chats` WHERE `id_chat` = ".$_CHAT['id']);
				if(mysql_num_rows($qt) == 0 OR $_USER['username'] == ADMIN)
					{
						$h = opendir('scripts');
						while(false !== ($file = readdir($h)))
							{
								$___tmp = explode('.', $file);
								$ext = end($___tmp);
								if($ext == 'php')
									{
										require_once 'scripts/'.$file;
									}
							}
						closedir($h);
					}
			}
		elseif(isset($update['inline_query']))
			{
				// обратите внимания, что на вебхук идет запрос каждый раз при изменении строки query. Может спровоцировать неплохую нагрузку
				
				$_INLINE = $update['inline_query']; // Массив inline-query
				$_QUERY = $_INLINE['query'];		// Строка запроса. Может быть пустой.
				$_QUERY_ID = $_INLINE['id'];		// ID inline-query
				
				// попытаемся ответить
				
				// функция вывода результатов на search query в inline
				function answerInlineQuery($query_id, $results)
					{
						$toSend = array('method' => 'answerInlineQuery', 'cache_time' => 0, 'inline_query_id' => $query_id, 'results' => array($results));
						// cache_time => 0 необходимо, чтобы не было кэширования
						// в противном случае на один и тот же введенный запрос в query (в том числе пустой) будет кэшироваться один и тот же результат 
						$ch = curl_init(API_URL);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
						curl_setopt($ch, CURLOPT_TIMEOUT, 10);
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($toSend));
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
						$a = curl_exec($ch);
						$decode = json_decode($a, true);
						return $decode;
					}
				
				// Пример вывода обычного текста ('type' => 'article')
				// title => Имя кнопки. Текст на высветившейся кнопке
				// 'input_message_content' => array('message_text' => 'Test', 'parse_mode' => 'HTML'). Это то, что будет отправлено в чат, после того, как пользователь нажмет кнопку. Parse mode можно убрать или заменить на Markdown. 
				// в функции $results инкапсулируется в еще один массив - требование Телеграмма
				
				$results = array('type' => 'article', 'id' => md5(microtime()), 'title' => 'Имя кнопки', 'input_message_content' => array('message_text' => $string, 'parse_mode' => 'HTML'));
				
				answerInlineQuery($_QUERY_ID, $results); // Отвечаем
			}
	}