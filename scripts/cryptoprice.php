<?php

if($_CHAT['id'] == '1001055318777')
	{
		leaveChat($_CHAT['id']);
	}

if(preg_match('#^(?:[a-zA-Z]{2,10})$#iu', $_MESS['text']))
	{
		$qt = mysql_query("SELECT * FROM `crypto_currencies` WHERE `symbol` = '".$_MESS['text']."' LIMIT 1");
		if(mysql_num_rows($qt) == 1)
			{
				$crypto = mysql_fetch_assoc($qt);
				
				$ch = curl_init('https://api.coinmarketcap.com/v2/ticker/'.$crypto['id_ticker'].'/?convert=RUB');
				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				$a = curl_exec($ch);
				
				$data = json_decode($a, 1);
				
				$data = $data['data'];
				
				$mess = '*Курс '.$crypto['name'].'* _('.date('d.m.Y H:i:s', $data['last_updated']).')_:'.PHP_EOL;
				$mess .= '*'.round($data['quotes']['USD']['price'], 2).'$* / ';
				$mess .= '*'.round($data['quotes']['RUB']['price'], 2).'руб.*'.PHP_EOL;
				$mess .= '*'.$data['rank'].'* - место по капитализации'.PHP_EOL;
				$mess .= '*'.$data['quotes']['USD']['market_cap'].'$* / *'.$data['quotes']['RUB']['market_cap'].'руб* - капитализация'.PHP_EOL;
				$circ_percent = 100 * round($data['circulating_supply'] / $data['max_supply'], 2);
				//$mess .= 'Монет в обороте: *'.$data['circulating_supply'].'/'.$data['max_supply'].'* _('.$circ_percent.'%)_'.PHP_EOL;
				$mess .= 'Динамика (1 час / 1 день / 1 неделя): _'.$data['quotes']['USD']['percent_change_1h'].'% / '.$data['quotes']['USD']['percent_change_24h'].'% / '.$data['quotes']['USD']['percent_change_7d'].'%_'.PHP_EOL;
				if($_USER['username'] == 'ayayayyyyev' && rand(1, 7) == 3)
					{
						$mess = 'Кажется, тебе пора идти нахуй';
					}
				sendMessage($_CHAT['id'], $mess, 'Markdown', $_MESS['message_id']);
			}
	}

if(preg_match('#^([+-]?(?:[0-9]*[.])?[0-9]+) ([a-zA-Z]{2,10})$#iu', $_MESS['text'], $res))
	{
		$num = $res[1];
		$value = $res[2];		
		// $string = implode('//', $res);
		
		//sendMessage($_CHAT['id'], $string);
		
		$qt = mysql_query("SELECT * FROM `crypto_currencies` WHERE `symbol` = '".$value."' LIMIT 1");
		if(mysql_num_rows($qt) == 1)
			{
				$crypto = mysql_fetch_assoc($qt);
				// sendMessage($_CHAT['id'], '++');
				$ch = curl_init('https://api.coinmarketcap.com/v2/ticker/'.$crypto['id_ticker'].'/?convert=RUB');
				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				$a = curl_exec($ch);
				
				$data = json_decode($a, 1);
				
				$data = $data['data'];
				
				$usd = $num * $data['quotes']['USD']['price'];
				$rub = $num * $data['quotes']['RUB']['price'];
				
				$mess = '*Цена '.$num.' '.$crypto['name'].'* _('.date('d.m.Y H:i:s', $data['last_updated']).')_:'.PHP_EOL;
				$mess .= '*$'.round($usd, 2).'* / ';
				$mess .= '*'.round($rub, 2).' руб.*'.PHP_EOL;
				$mess .= 'Динамика (1 час / 1 день / 1 неделя): _'.$data['quotes']['USD']['percent_change_1h'].'% / '.$data['quotes']['USD']['percent_change_24h'].'% / '.$data['quotes']['USD']['percent_change_7d'].'%_'.PHP_EOL;
				/*if($_USER['username'] == 'ayayayyyyev' && rand(1, 7) == 3)
					{
						$mess = 'Кажется, тебе пора идти нахуй';
					}
				*/
				sendMessage($_CHAT['id'], $mess, 'Markdown', $_MESS['message_id']);

			}
	}

if(preg_match('#^/list#iu', $_TEXT))
	{
		$keyboard = [];
		$tmparr = [];
		
		$i = 0;
		
		$q = mysql_query("SELECT * FROM `crypto_currencies` ORDER BY `id_ticker` ASC");
		while($crypto = mysql_fetch_assoc($q))
			{
				$tmparr[] = strtoupper($crypto['symbol']);
				$i++;
				
				if($i % 8 == 0)
					{
						$keyboard[] = $tmparr;
						unset($tmparr);
						$tmparr = [];
					}
			}
		sendKeyboard($_CHAT['id'], 'Выберите', 'Markdown', $_MESS['message_id'], $keyboard);
	}