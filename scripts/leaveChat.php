<?php
if($_USER['username'] == ADMIN)
	{
		if($_CHAT['id'] != $_USER['id'])
			{
				if(preg_match("#(ада уходи|уебывай отсюда)#iu", $_TEXT))
					{
						sendMessage($_CHAT['id'], 'Простите :(');
						sleep(3);
						leaveChat($_CHAT['id']);
					}
			}
	}