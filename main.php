<?php
    include_once("bot.php");

    $bot = new TgBot("868385679:AAHea69gcXkC19t85sCx7BUbgFhWWCqpdQc", 460873343);

    $option = array(
        "chat_id" => $bot->data["message"]["chat"]["id"],
        "text" => $bot->data["message"]["text"]
    );
    
    $bot->sendMessage($option);
?>