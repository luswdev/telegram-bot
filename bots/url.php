<?php
    include_once("bot.php");
    $config = json_decode(file_get_contents("../data/config.json"));

    $bot = new TgBot($config->lusw, $config->owner, $config->debuger);

    $res = [];
    $msgs = [];
    $inline = false;

    if (isset($bot->data["message"]["text"])) {
        $msgs = explode(" ", $bot->data["message"]["text"]);
    } else if (isset($bot->data["inline_query"]["query"])) {
        $msgs = explode(" ", $bot->data["inline_query"]["query"]);
        $inline = true;
    } else {
        $res = array(
            "ok" => false,
            "detail" => "Please send message corrently."
        );
    }

    if (count($msgs) > 0) {
        switch ($msgs[0]) {
            case "myid": {
                if ($inline) {
                    $res = $bot->sendInlineMessage([
                        "inline_query_id" => $bot->MsgID,
                        "results" => array(
                            $inlineQuery = array(
                                "type" => "article",
                                "title" => "Your Telegram ID is:",
                                "description" => $bot->ChatID,
                                "input_message_content" => array(
                                    "message_text" => $bot->ChatID
                                ),
                                "id" => time(),
                            )
                        )
                    ]);
                    break;
                }
            }

            case "url": {
                if (count($msgs) > 1 && filter_var($msgs[1], FILTER_VALIDATE_URL) && $inline) {
                    $res = $bot->sendInlineMessage([
                        "inline_query_id" => $bot->MsgID,
                        "results" => array( 
                            array(
                                "type" => "article",
                                "title" => "is.gd",
                                "description" => is_gd($msgs[1]),
                                "input_message_content" => array(
                                    "message_text" => is_gd($msgs[1])
                                ),
                                "id" => time(),
                            )
                        )
                    ]);
                    break;
                } 
            }

            case "/help":
            default: {
                if (!$inline) {
                    $mannual = "<b>LuSkywalker Bot</b>\n"
                    . "/help Print this mannual\n\n"
                    . "This is a Bot for inline mode:\n\n"
                    . "Shorten URL (using is.gd):\n"
                    . "<code>url your_link</code>\n"
                    . "Example: \n"
                    . "<code>@luswbot url https://google.com</code>\n"
                    . "This will output: https://is.gd/jAxBiv\n\n"
                    . "Get your Telegram ID:\n"
                    . "<code>myid</code>\n"
                    . "Example: \n"
                    . "<code>@luswbot myid</code>\n"
                    . "This will output: (your id)\n\n"
                    . "Learning more about Telegram inline mode, there is offical doc.\n"
                    . "https://core.telegram.org/bots/inline";

                    $res = $bot->sendMessage([
                        "parse_mode" => "HTML",
                        "chat_id" => $bot->ChatID,
                        "text" => $mannual
                    ]);
                } else {
                    $res = $bot->sendInlineMessage([
                        "inline_query_id" => $bot->MsgID,
                        "results" => array(
                            $inlineQuery = array(
                                "type" => "article",
                                "title" => "Usage",
                                "description" => "Shorten URL by is.gd: url link\nGet your id: myid",
                                "input_message_content" => array(
                                    "message_text" => "Shorten URL by is.gd: url link\nGet your id: myid"
                                ),
                                "id" => time(),
                            )
                        )
                    ]);
                }
                break;
            }
        }     
    } else if ($inline) {
        $res = $bot->sendInlineMessage([
            "inline_query_id" => $bot->MsgID,
            "results" => array(
                $inlineQuery = array(
                    "type" => "article",
                    "title" => "Usage",
                    "description" => "Shorten URL by is.gd: url link\nGet your id: myid",
                    "input_message_content" => array(
                        "message_text" => "Shorten URL by is.gd: url link\nGet your id: myid"
                    ),
                    "id" => time(),
                )
            )
        ]);
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