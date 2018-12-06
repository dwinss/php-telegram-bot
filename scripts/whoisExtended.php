<?php

if(preg_match('#^/(networks|mikrotik) ([a-zA-Z0-9\.\-]{1,})$#iu', $_TEXT, $res))
	{
		$domain = $res[2];
		$type = $res[1];
		$mess = '';
		# using nslookup to find some IP
		
		$tmp[0] = shell_exec('host '.$domain);
		
		if(preg_match('#has address ([0-9\.]{7,15})#iu', $tmp[0], $res))
			{
				$ip = $res[1];
				
				# using whois to find AS
				
				$tmp[1] = shell_exec('whois '.$ip);
				
				if(preg_match('#AS(\d+)#iu', $tmp[1], $res))
					{
						$AS = $res[1];
						
						# using whois to find AS-prefixes
						
						$tmp[2] = shell_exec("whois -h whois.radb.net '!gAS".$AS."' | grep /");
						
						preg_match_all('#([0-9\./]{9,18})#iu', $tmp[2], $res);
						if(!empty($res[1]))
							{
								# res[1] is array of networks
								
								
								foreach($res[1] as $network)
									{
										$mess .= trim($network).PHP_EOL;
									}
								
								
								if($type == 'mikrotik')
									{
										$mess .= PHP_EOL.PHP_EOL;
										foreach($res[1] as $network)
											{
												$mess .= '/ip firewall address-list add list='.trim($domain).' address='.trim($network).PHP_EOL;
											}
										$mess .= PHP_EOL;
										$mess .= '/ip firewall filter add chain=forward action=reject reject-with=icmp-network-unreachable dst-address-list='.$domain;
										
									}
								
							}
						else
							{
								$mess .= 'Ошибка поиска сетей в AS'.$AS.' - '.$tmp[2];
							}
					}
				else
					{
						$mess .= 'Не найден номер AS';
					}
			}
		else
			{
				$mess .= 'Ошибка ресолвинга IP-адреса';
			}
		sendMessage($_CHAT['id'], $mess, '', $_MESS['message_id']);
	}