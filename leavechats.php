<?php

include 'api.php';

$q = mysql_query("SELECT * FROM `tg_chats`");

while($chat = mysql_fetch_assoc($q))
	{
		/*sendMessage($chat['id_chat'], '"― Он улетел... Но он обещал вернуться. Милый. Милый..."

_Закрыто на реконструкцию... а может и навсегда._', 'Markdown');
*/
	leaveChat($chat['id_chat']);
	sleep(1);
	}