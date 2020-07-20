<?php
    include_once("bot.php");
    $config = json_decode(file_get_contents("../data/config.json"));

    $bot = new TgBot($config->C3PO, $config->owner, $config->debuger);

    $res = [];
    $msgs = [];

    if (isset($bot->data["message"]["text"])) {
        $msgs = explode(" ", $bot->data["message"]["text"]);
    } else {
        $res = array(
            "ok" => false,
            "detail" => "Please send message corrently."
        );
    }

    if (count($msgs) > 0) {
        switch ($msgs[0]) {
            case "url": {
                if (count($msgs) > 1) {
                    if (filter_var($msgs[1], FILTER_VALIDATE_URL)) { 
                        $res = $bot->sendMessage([
                            "chat_id" => $bot->ChatID,
                            "text" => is_gd($msgs[1]),
                            "reply_to_message_id" => $bot->MsgID,
                            "disable_web_page_preview" => true
                        ]);
                    } else {
                        $res =  $bot->sendMessage([
                            "chat_id" => $bot->ChatID,
                            "text" => "bad url",
                            "reply_to_message_id" => $bot->MsgID
                        ]);
                    }
                    break;
                } 
            }

            case "/help": {
                $mannual = "<b>C-3PO</b>\n"
                 . "/help Print this mannual\n"
                 . "/myid Get your telegram id\n\n"
                 . "Shorten URL (using is.gd):\n"
                 . "<code>url your_link</code>\n"
                 . "Example: \n"
                 . "<code>url https://google.com</code>\n"
                 . "This will output: https://is.gd/jAxBiv";

                $res = $bot->sendMessage([
                    "parse_mode" => "HTML",
                    "chat_id" => $bot->ChatID,
                    "text" => $mannual
                ]);
                break;
            }

            case "/myid": {
                $res = $bot->sendMessage([
                    "chat_id" => $bot->ChatID,
                    "text" => $bot->ChatID
                ]);
                break;
            }

            default: {
                $res = $bot->sendMessage([
                    "chat_id" => $bot->ChatID,
                    "text" => $bot->data["message"]["text"]
                ]);
                break;
            }
        }        
    } else {
        $res = array(
            "ok" => false,
            "detail" => "Please send message corrently."
        );
    }

    echo json_encode($res, JSON_PRETTY_PRINT);
  
    function is_gd(string $url) : string {
        return file_get_contents("https://is.gd/create.php?format=simple&url={$url}");
    }
?>