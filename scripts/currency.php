<?php

/*
1) парсинг цифра - слово
2) 3000 рублей в долларах

*/

$words = array(
			'\$', 'доллар', 'бак', 'бач', 'зелен', 'зелён', 'usd', 'презид', 'мёртв', 'мертв', 'енот',
			'€', 'евр', 'euro ', 'eur ',
			'руб', 'дер'
			);

$string = implode('|', $words);

if(preg_match('#((?:[0-9]*[.])?[0-9]+)\s*('.$string.')#iu', $_TEXT, $res) OR preg_match('#('.$string.')(?:.*?)\s*((?:[0-9]*[.])?[0-9]+)#iu', $_TEXT, $res2))
	{
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
		if(preg_match('#к#iu', $sum))
			{
				$sum = (int)$sum * 1000;
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
				case '$': case 'доллар': case 'бак': case 'бач': case 'зелен': case 'зелён': case 'usd': case 'презид': case 'мёртв': case 'мертв': case 'енот':
					$value = 'USD';
				break;
				
				case '€': case 'евр': case 'euro ': case 'eur ':
					$value = 'EUR';
				break;
				
				case 'руб': case 'дер':
					$value = 'RUB';
				break;
				
				default:
					$value = 'USD';
				break;
			}
		
		if($value != 'RUB')
			{
				$info = $json['Valute'][$value];
				$rub = round($sum * $info['Value'], 2);
				
				$message = '<i>'.$sum.' '.$value.'</i> - <b>'.$rub.'</b>руб.';
			}
		else
			{
				$one['usd'] = $json['Valute']['USD']['Value'];
				$rub['usd'] = round($sum / $one['usd'], 2);
				
				$one['eur'] = $json['Valute']['EUR']['Value'];
				$rub['eur'] = round($sum / $one['eur'], 2);
				
				$message = '<i>'.$sum.' '.$value.'</i> - <b>'.$rub['usd'].'</b> USD / <b>'.$rub['eur'].'</b> EUR';
			}
		
		
		sendMessage($_CHAT['id'], $message, 'HTML', $_MESS['message_id']);
		
	}