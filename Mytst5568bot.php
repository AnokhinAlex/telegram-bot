<?php
require_once('dbinc.php');
$link = mysqli_connect(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_NAME);
echo mysqli_error($link);
$access_token = '393757007:AAEg2nokJasgly-ku_lY3vM50IhR8ZKKXpI';
$api = 'https://api.telegram.org/bot' . $access_token;
$output = json_decode(file_get_contents('php://input'), TRUE);
$chat_id = $output['message']['chat']['id'];
$first_name = $output['message']['chat']['first_name'];
$message = $output['message']['text'];

$tolower = mb_strtolower($message, 'UTF-8');
$str = substr($message,0,21); 
$str_canonical = "View all finish user:10";
if((substr($str_canonical,0,21) == substr($message,0,21)) ){
    $st = $message; 
    list($number) = sscanf($st, "View all finish user:%d");
        if(is_int($number) && ($number > 0) ){
            $go = mysqli_query($link,"SELECT username, usid, bool  FROM  MYDB LIMIT $number  ");
            sendMessage($chat_id," Запрошено записей: {$number} ");
            $i = 0;
            while ($gor = mysqli_fetch_array($go,MYSQLI_ASSOC)){
               $i++; 
               if($gor['bool']){
            sendMessage($chat_id," {$i}. имя: {$gor['username']}; userid: {$gor['usid']}; "  );
               }
            }
        }
} else {
     
switch( $tolower) {

case ('xnode-хочу работать!'):

$go = mysqli_query($link,"SELECT usid FROM  MYDB"); 
while ($gor = mysqli_fetch_array($go,MYSQLI_ASSOC)){
if($gor['usid'] == $chat_id){
	$alreadyexist = $gor['usid'];
}
}
if(!$alreadyexist){
mysqli_query($link,"INSERT INTO MYDB (usid) VALUES ('$chat_id')");    
sendMessage($chat_id, 'Мы тебя слушаем');	
}else{
 sendMessage($chat_id, 'Тебя уже услышали');   
}


  break;  

case ('люблю php, жить не могу без него!'):

$res = mysqli_query($link,"SELECT * FROM  MYDB ");
 while ($row = mysqli_fetch_array($res,MYSQLI_ASSOC)){
    if($row['usid'] == $chat_id ){
        $tot = $row['usid'];
		$same = $row['bool'];
    } 
 };

if($tot && $same){
	sendMessage($chat_id, 'Уже верим');
}elseif($tot){
	mysqli_query($link,"INSERT INTO MYDB (usid, bool, username) VALUES ('$chat_id',TRUE,'$first_name')");
	sendMessage($chat_id, 'Мы верим в тебя');
}else{
	sendMessage($chat_id, 'Всё равно игнорирует');
}	

break;

default:

sendMessage($chat_id, 'Похоже, что бот игнорирует Вас.' );

break;

}
}

function sendMessage($chat_id, $message) {
file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}