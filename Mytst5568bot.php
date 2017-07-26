<?php
require_once('dbinc.php');
$link = mysqli_connect(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_NAME);
echo mysqli_error($link);
$access_token = '393757007:AAEg2nokJasgly-ku_lY3vM50IhR8ZKKXpI';
$api = 'https://api.telegram.org/bot' . $access_token;
$output = json_decode(file_get_contents('php://input'), TRUE); // Получим то, что передано скрипту ботом в POST-сообщении и распарсим
$chat_id = $output['message']['chat']['id']; // Выделим идентификатор чата
$first_name = $output['message']['chat']['first_name']; // Выделим имя собеседника
$message = $output['message']['text']; // Выделим сообщение собеседника ыяыва

$str = substr($message,0,21); 
$str_canonical = "View all finish user:10";
if((substr($str_canonical,0,21) == substr($message,0,21)) ){
     sendMessage($chat_id, 'прошла проверка' );
    $st = $message; 
    list($number) = sscanf($st, "View all finish user:%d");
        if(is_int($number) && ($number > 0) ){
            $go = mysqli_query($link,"SELECT username, usid  FROM  `one` LIMIT $number  ");
            sendMessage($chat_id, $number );
            $i = 0;
            while ($gor = mysqli_fetch_array($go,MYSQLI_ASSOC)){
               $i++; 
            sendMessage($chat_id," {$i}. имя: {$gor['username']}; userid: {$gor['usid']}; "  );
            }
        }
} else {
    
switch(strtolower_ru($message)) {

case ('xnode-хочу работать!'):
    
mysqli_query($link,"INSERT INTO one (usid,username,message) VALUES ('$chat_id','$first_name', '$message')");    
sendMessage($chat_id, 'Мы тебя слушаем');

  break;  

case ('люблю php, жить не могу без него!'):

$res = mysqli_query($link,"SELECT * FROM  `one` ");
 while ($row = mysqli_fetch_array($res,MYSQLI_ASSOC)){
    if($row['usid'] == $chat_id ){
        $tot = $row['usid'];
    } 
 };

if($tot){
sendMessage($chat_id, "Мы верим в тебя!");
} else{
   sendMessage($chat_id, "Всё равно игнорирует"); 
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

/**
* Функция перевода символов в нижний регистр, учитывающая кириллицу
*/

function strtolower_ru($text) {

   $alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');

     $alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');

return str_replace($alfavitupper,$alfavitlover,strtolower($text));

}