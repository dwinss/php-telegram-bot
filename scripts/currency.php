<?php

/*
1) парсинг цифра - слово
2) 3000 рублей в долларах

*/

$words = array(
			'\$', ' доллар', ' бак', ' бач', ' зелен', ' зелён', ' usd', ' презид', ' мёртв', ' мертв', ' енот',
			' €', ' евр', ' euro ', ' eur ',
			' тенг', ' тугрик', 'kzt',
			' фунт', ' £', ' gbp',
			' UZS', ' сум',
			' руб', ' rub',
			' грив', ' UAH'
			);

$string = implode('|', $words);

if(preg_match('#((?:[0-9]*[.])?[0-9]+)\s*('.$string.')#iu', $_TEXT, $res))
	{
		// sendMessage($_CHAT['id'], 'debug1');
		if(!empty($res))
			{
				$sum = $res[1];
				$input_value = $res[2];
			}
		else
			{
				$sum = $res2[2];
				$input_value = $res2[1];
			}

		# получим список валют
		
		$ch = curl_init('https://www.cbr-xml-daily.ru/daily_json.js');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, 'https://www.cbr-xml-daily.ru/');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0');
		
		$a = curl_exec($ch);
		
		$json = json_decode($a, 1);
		
		switch($input_value)
			{
				case ' $': case ' доллар': case ' бак': case ' бач': case ' зелен': case ' зелён': case ' usd': case ' презид': case ' мёртв': case ' мертв': case ' енот':
					$value = 'USD';
				break;
				
				case ' €': case ' евр': case ' euro ': case ' eur ':
					$value = 'EUR';
				break;
				
				case ' фунт': case ' £': case ' gbp':
					$value = 'GBP';
				break;
				
				case ' тенг': case ' тугрик': case 'kzt':
					$value = 'KZT';
				break;
				
				case ' uzs': case ' сум':
					$value = 'UZS';
				break;
				
				case ' руб': case ' rub':
					$value = 'RUB';
				break;
				
				case ' грив': case ' UAH':
					$value = 'UAH';
				break;
				
				default:
					$value = 'USD';
				break;
			}
		
		if($value != 'RUB')
			{
				$info = $json['Valute'][$value];
				
				
				$rub = round($sum * $info['Value'] / $info['Nominal'], 2);
				
				
				
				$message = '<i>'.$sum.' '.$value.'</i> - <b>'.$rub.'</b>руб.';
			}
		else
			{
				$one['usd'] = $json['Valute']['USD']['Value'];
				$rub['usd'] = round($sum / $one['usd'], 2);
				
				$one['eur'] = $json['Valute']['EUR']['Value'];
				$rub['eur'] = round($sum / $one['eur'], 2);
				
				$one['gbp'] = $json['Valute']['GBP']['Value'];
				$rub['gbp'] = round($sum / $one['gbp'], 2);
				
				$one['kzt'] = $json['Valute']['KZT']['Value'];
				$rub['kzt'] = round($sum / $one['kzt'] * 100, 2);
				
				$one['uzs'] = $json['Valute']['UZS']['Value'];
				$rub['uzs'] = round($sum / $one['uzs'] * 10000, 2);
				
				$one['uah'] = $json['Valute']['UAH']['Value'];
				$rub['uah'] = round($sum / $one['uah'] * 10, 2);
				
				$message = '<i>'.$sum.' '.$value.'</i> - <b>'.$rub['usd'].'</b> USD / <b>'.$rub['eur'].'</b> EUR / <b>'.$rub['gbp'].'</b> GBP / <b>'.$rub['kzt'].'</b> KZT / <b>'.$rub['uzs'].'</b> UZS / <b>'.$rub['uah'].'</b> UAH';
			}
		
		
		sendMessage($_CHAT['id'], $message, 'HTML', $_MESS['message_id']);
		
	}

if(preg_match('#^/value#iu', $_TEXT))
	{
		sendMessage($_CHAT['id'], 'Если в сообщении указана некая денежная сумма, бот попытается сконвертировать ее.'.PHP_EOL.'Примеры: `1000 рублей`, `20$`, `30 евро`'.PHP_EOL.'Поддерживаемые валюты: рубль, доллар, евро, фунты стерлингов, тенге, узбекские сумы, гривны', 'Markdown', $_MESS['message_id']);
	}