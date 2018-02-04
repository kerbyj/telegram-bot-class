<?php
include 'telegram.php';

$api_key="your telegram bot api key getting from @botfather";
$bot=new Telegram($api_key);

if($bot->type=="location"){
  $bot->sendMessage($bot->longitude." ".$bot->latitude);
}

if($bot->type=="text"){
  if($bot->text=="/start"){
    $keyboard=[["Button 1"], ["Button 2"]];
    $reply_markup=$bot->keyboard($keyboard);
    $bot->sendMessage(['text'=>"Hello.", 'parse_mode'=>"Markdown", "reply_markup"=>$reply_markup]);
  }
  elseif($bot->text=="Inline"){
    $reply_markup=$bot->inline_keyboard([[["text"=>"Just try", "callback_data"=>"test"]]]);
    $bot->sendMessage(['text'=>"Inline", 'parse_mode'=>"Markdown", "reply_markup"=>$reply_markup]);
  }
  else{
    $bot->sendMessage(['text'=>"I know where you are", 'parse_mode'=>"Markdown"]);
  }
}

if($bot->type=="location") {
  $location=$bot->longitude." ".$bot->latitude;
  $bot->sendMessage(['text'=>"I follow you - <code>".$location."</code>", 'parse_mode'=>"HTML"]);
}

if($bot->type=="callback") {
  if($bot->callback_data=="test"){
    $bot->answerCallbackQuery(['text'=>"Updated"]);
    $reply_markup=$bot->inline_keyboard([[["text"=>"Updated", "callback_data"=>"test"]]]);
    $bot->editMessageReplyMarkup(["reply_markup"=>$reply_markup]);
  }
}

if($bot->type=="sticker") {
  $bot->sendSticker(["sticker"=>$bot->file_id]);
}

/**
 * Some debug message
 */

//$bot->sendMessage(['text'=>json_encode($bot->all_data,true)]);
//$bot->sendMessage(['text'=>$bot->type]);
?>
