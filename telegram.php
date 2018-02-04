<?php

class Telegram{
  public $all_data;
  private $api_key;
  private $api="https://api.telegram.org/bot";

  //Сommon parameters
  public $type;
  private $chat_id;
  private $message_id;

  //For text messages
  public $text;

  //For location type
  public $longitude;
  public $latitude;

  //For callbacks
  public $callback_data;
  public $callback_query_id;

  //For files and stickers
  public $file_id;

  public function keyboard($keyboard){
    return json_encode(array("keyboard"=>$keyboard, 'resize_keyboard' => true), JSON_UNESCAPED_UNICODE);
  }
  public function inline_keyboard($keyboard){
    return json_encode(array("inline_keyboard"=>$keyboard, 'resize_keyboard' => true, 'one_time_keyboard'=>true));
  }

  public function sendMessage($message){
    file_get_contents($this->api.$this->api_key."/sendMessage?chat_id=".$this->chat_id."&".http_build_query($message));
  }

  public function sendSticker($params){
    file_get_contents($this->api.$this->api_key."/sendSticker?chat_id=".$this->chat_id."&".http_build_query($params));
  }

  public function answerCallbackQuery($params){
    file_get_contents($this->api.$this->api_key."/answerCallbackQuery?callback_query_id=".$this->callback_query_id."&".http_build_query($params));
  }

  public function editMessageReplyMarkup($params){
    file_get_contents($this->api.$this->api_key."/editMessageReplyMarkup?chat_id=".$this->chat_id."&message_id=".$this->message_id."&".http_build_query($params));
  }

  public function deleteMessage($message){
    file_get_contents($this->api.$this->api_key."/deleteMessage?chat_id=".$this->chat_id."&message_id=".$this->message_id);
  }

  private function check_type(){
    if(isset($this->all_data["message"]["text"])) $this->type="text";
    elseif(isset($this->all_data["message"]["location"])) $this->type="location";
    elseif(isset($this->all_data["message"]["sticker"])) $this->type="sticker";
    elseif(isset($this->all_data["callback_query"])) $this->type="callback";
    else $this->type="undefined";
  }

  private function extract_data(){
    $this->chat_id=$this->all_data["message"]["chat"]["id"];
    $this->message_id=$this->all_data["message"]["message_id"];

    switch ($this->type) {
      case 'text':
        $this->text=$this->all_data["message"]["text"];
        break;
      case 'location':
        $this->longitude=$this->all_data["message"]["location"]["longitude"];
        $this->latitude=$this->all_data["message"]["location"]["latitude"];
        break;
      case 'callback':
        $this->chat_id=$this->all_data["callback_query"]["message"]["chat"]["id"];
        $this->callback_data=$this->all_data["callback_query"]["data"];
        $this->message_id=$this->all_data["callback_query"]["message"]["message_id"];
        $this->callback_query_id=$this->all_data["callback_query"]["id"];
        break;
      case 'sticker':
        $this->file_id=$this->all_data["message"]["sticker"]["file_id"];
        break;
    }
  }

  public function __construct($api_key){
    $this->api_key=$api_key;
    $this->all_data=json_decode(file_get_contents('php://input'), TRUE);
    $this->check_type();
    $this->extract_data();
  }
}


?>
