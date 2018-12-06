<?php

function showHelp($a = array())
	{
		$out = '';
		if(!empty($a))
			{
				foreach($a as $cmd => $descr)
					{
						$out .= '_'.$cmd.'_ - '.$descr.';'.PHP_EOL;
					}
			}
		else
			{
				$out .= '_Здесь пока что ничего нет_';
			}
		return $out;
	}

# общая справка
if($_MESS['text'] == '/help')
	{
		# Разделы
		$a1 = array(
			'/help cc' => 'Castle Clash (Битва Замков)'
			);
		$_send = '*Список разделов справки*:'.PHP_EOL;
		$_send .= showHelp($a1);
		$_send .= PHP_EOL;
		
		# Отдельные
		$a2 = array(
			'/testbot' => 'Проверка работоспособности бота',
			'/np Nickname или "что играет у Nickname"' => 'просмотр NowPlaying пользователя Nickname (сервис Last.Fm)',
			'/last Nickname' => '10 последних треков (см выше)',
			'котик' => 'прислать случайную картинку котика',
			'вариант1 или вариант2?' => 'помощь в принятии решения'
			);
		$_send .= '*Отдельные команды*:'.PHP_EOL;
		$_send .= showHelp($a2);
		
		if($_USER['username'] == ADMIN)
			{
				$_send .= PHP_EOL;
				# Шалом, админко
				$_send .= '*Команды для тебя, админушка-склерозник*'.PHP_EOL;
				$a3 = array(
					'/shell cmd' => 'Выполнение команды от имени www-data',
					'/php code' => 'Выполнение кода из под cli'
					);
				$_send .= showHelp($a3);
			}
		sendMessage($_CHAT['id'], $_send, 'Markdown');
	}

# Castle clash

if($_MESS['text'] == '/help cc')
	{
		$a1 = array(
			'/cc exp lvl1-lvl2' => 'Количество опыта, необходимое для прокачки с lvl1 до lvl2',
			'/cc evo1 lvl+exp' => 'Количество книг опыта, получаемое при 1 эволюции героя уровня lvl с доп. опытом exp (например, 183 лвл и еще 200000 опыта)',
			'/cc evo2 lvl+exp' => 'Аналогично предыдущей, но при 2-й эволюции'
			);
		$_send .= '*Castle Clash (Битва Замков)*'.PHP_EOL;
		$_send .= showHelp($a1);
		sendMessage($_CHAT['id'], $_send, 'Markdown');
	}
		