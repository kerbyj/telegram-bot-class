<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

date_default_timezone_set('Europe/Moscow');

include 'telegram.php';

$api_key="your telegram bot api key getting from @botfather";
$bot=new Telegram($api_key);

if($bot->type=="text"){
  if($bot->text=="/start"){
    $keyboard=[["Button one"], ["Button two"]];
    $reply_markup=$bot->keyboard($keyboard);
    $bot->sendMessage(['text'=>"Hello.", 'parse_mode'=>"Markdown", "reply_markup"=>$reply_markup]);
  }
  elseif($bot->text=="Inline"){
    $reply_markup=$bot->inline_keyboard([[["text"=>"Try", "callback_data"=>"test"]]]);
    $bot->sendMessage(['text'=>"Inline", 'parse_mode'=>"Markdown", "reply_markup"=>$reply_markup]);
  }
  elseif($bot->text=="photo"){
    $bot->sendPhoto(["path"=>"andy.png", "caption"=>"Test"]);
    //Also
    //$bot->sendPhoto(["photo"=>file_id, "caption"=>"Test"]);
  }
  elseif($bot->text=="document"){
    $bot->sendDocument(["path"=>"pdf.pdf"]);
    //Also
    //$bot->sendDocument(["document"=>file_id, "caption"=>"Test"]);
  }
  else{
    $bot->sendMessage(['text'=>"What is it?", 'parse_mode'=>"Markdown"]);
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
