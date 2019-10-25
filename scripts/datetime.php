<?php

if($_TEXT == 'время' | preg_match('#^/date#iu', $_TEXT))
	{
		$d = date('d.m.Y H:i:s');
		sendMessage($_CHAT['id'], $d);
	}