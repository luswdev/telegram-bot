<?php
    include_once("bot.php");

    $bot = new TgBot("868385679:AAHea69gcXkC19t85sCx7BUbgFhWWCqpdQc", 460873343, -1001156114274);    

    if (isset($bot->data["incident"])) {
        $msg = "<b>A incident raise or update</b>\n"
            . $bot->data["incident"]["incident_updates"][0]["status"] . " - "
            . $bot->data["incident"]["incident_updates"][0]["body"] . "\n"
	    . "<code>" . $bot->data["incident"]["incident_updates"][0]["updated_at"] . "</code>\n"
            . "<a href='https://www.githubstatus.com/'>see more detail</a>";
    } else if (isset($bot->data["component"])) {
        $msg = "<b>Component updates</b>\n"
            . $bot->data["component_update"]["new_status"] . " &#8592; "
            . $bot->data["component_update"]["old_status"] . "\n"
            . "<code>" . $bot->data["component_update"]["created_at"] . "</code>\n"
            . "<a href='https://www.githubstatus.com'>see more detail</a>";
    } else {
        return;
    }

    $option = array(
        "parse_mode" => "HTML",
        "text" => $msg
    );
    
    $bot->sendMessage($option);
?>
