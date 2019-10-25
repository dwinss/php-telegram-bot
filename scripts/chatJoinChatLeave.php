<?php
if(isset($_MESS['left_chat_member']))
	{
		mysql_query("INSERT INTO `join_left_log`(`id_chat`, `id_user`, `action`, `time`) VALUES (".$_CHAT['id'].", ".$_USER['id'].", 1, ".time().")");
		switch($_MESS['left_chat_member']['username'])
			{
				case 'D13410N3':
					$text = 'Хозяин, ты куда? :(';
				break;
				
				case 'shocker1012':
					$text = 'Шокерща, ну ты чо?';
				break;
				
				case 'GreenKovsh':
					$text = 'Уебывай, нытик';
				break;
				
				case 'ayayayyyyev':
					$text = 'Слава Богу, ты ушел';
				break;
				
				case 'neuroplane':
					$text = 'Ну и уебывай';
				break;
				
				
				default:
					$text = 'Помянем';
				break;
			}
		
		sendMessage($_CHAT['id'], $text, '', $_MESS['message_id']);
	}

if(isset($_MESS['new_chat_member']))
	{
		mysql_query("INSERT INTO `join_left_log`(`id_chat`, `id_user`, `action`, `time`) VALUES (".$_CHAT['id'].", ".$_USER['id'].", 2, ".time().")");
		switch($_MESS['new_chat_member']['username'])
			{
				case 'D13410N3':
					$text = 'О, хозяин пришел. Привет! :3';
				break;
				
				
				default: 
					$text = 'Приветствую!';
				break;
			}
		sendMessage($_CHAT['id'], $text, '', $_MESS['message_id']);
	}
/*
if($_CHAT['id'] == -1001467803922 AND (isset($_MESS['new_chat_member']) | isset($_MESS['left_chat_member'])))
	{
		$count = getChatMembersCount($_CHAT['id']);
		
		switch($count)
			{
				case '12': 		$title = 'Дюжина';							break;
				case '13':		$title = 'Чертовая дюжина';					break;
				case '14':		$title = 'Чертова дюжина и кто-то еще';		break;
				case '15':		$title = '15 лахторублей';					break;
				case '16':		$title = 'Пуд наркоманов';					break;
				case '17':		$title = '17 мгновений еды';				break;
				case '18':		$title = 'Совершеннолетние';				break;
				case '19':		$title = '19 сантиметров';					break;
				case '20':		$title = '20 тысяч лье на дне';				break;
				case '21':		$title = 'Твенти уан пилотка';				break;
				case '22':		$title = 'Перебор';							break;
				case '23':		$title = 'Носкодень';						break;
				case '24':		$title = '24 часа пиздежь 24 утырков';		break;
				case '25':		$title = '5²';								break;
				case '26':		$title = 'Железный';						break;
				
				default:		$title = 'Наркобыз';						break;
			}
		
		setChatTitle($_CHAT['id'], $title);
	}
*/