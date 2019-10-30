<?php

if(isset($_MESS['location']))
	{
		$lat = $_MESS['location']['latitude'];
		$lon = $_MESS['location']['longitude'];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?lat=".$lat."&lon=".$lon."&appid=".WEATHER_TOKEN."&units=metric&lang=ru");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);  

		$response = json_decode($response, true);
		$weather   =  $response['weather'][0]['main'];
		$description = $response['weather'][0]['description'];
		$icon    = $response['weather'][0]['icon'];
		$temp    =  $response['main']['temp'];
		$pressure  = $response['main']['pressure'];
		$humidity  = $response['main']['humidity'];
		$temp_min  = $response['main']['temp_min'];
		$temp_max  = $response['main']['temp_max'];
		$speed   = $response['wind']['speed'];
		$name    = $response['name'];

		$resultweather = "[".$name."]\nПогода: *".$description."*\nТемпература: *".round($temp)."*ᵒC\nВлажность: *".$humidity."*%\nВетер: *".$speed."*м/с";
		
		sendMessage($_CHAT['id'], $resultweather, 'Markdown', $_MESS['message_id']);
	}

if(preg_match('#^/weather#iu', $_TEXT))
	{
		sendMessage($_CHAT['id'], 'Отправьте местоположение в чат, чтобы получить краткую сводку о погоде', '', $_MESS['message_id']);
	}