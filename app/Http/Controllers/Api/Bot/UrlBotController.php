<?php

namespace App\Http\Controllers\Api\Bot;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\UpdateLog;

use Storage;

class UrlBotController extends BotController
{
    public function __construct() {
        $config = json_decode(Storage::disk('local')->get('bot.json'));
        parent::__construct($config->bots->url, $config->owner, $config->debuger);
    }

    public function main() {
        $res = [];
        $msgs = [];
        $inline = false;
    
        if (isset($this->data["message"]["text"])) {
            $msgs = explode(" ", $this->data["message"]["text"]);
        } else if (isset($this->data["inline_query"]["query"])) {
            $msgs = explode(" ", $this->data["inline_query"]["query"]);
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
                        $res = $this->sendInlineMessage([
                            "inline_query_id" => $this->MsgID,
                            "results" => array(
                                $inlineQuery = array(
                                    "type" => "article",
                                    "title" => "Your Telegram ID is:",
                                    "description" => $this->ChatID,
                                    "input_message_content" => array(
                                        "message_text" => $this->ChatID
                                    ),
                                    "id" => time(),
                                )
                            )
                        ]);
                        break;
                    }
                }
    
                case "url": {
                    if (count($msgs) > 1 && $this->validate_url($msgs[1]) && $inline) {
                        $res = $this->sendInlineMessage([
                            "inline_query_id" => $this->MsgID,
                            "results" => array( 
                                $inlineQuery = array(
                                    "type" => "article",
                                    "title" => "is.gd",
                                    "description" => $this->is_gd($msgs[1]),
                                    "input_message_content" => array(
                                        "message_text" => $this->is_gd($msgs[1])
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
    
                        $res = $this->sendMessage([
                            "parse_mode" => "HTML",
                            "chat_id" => $this->ChatID,
                            "text" => $mannual
                        ]);
                    } else {
                        $res = $this->sendInlineMessage([
                            "inline_query_id" => $this->MsgID,
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
            $res = $this->sendInlineMessage([
                "inline_query_id" => $this->MsgID,
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
    
        return json_encode($res, JSON_PRETTY_PRINT);
    }

    private function is_gd(string $url) : string {
        return file_get_contents("https://is.gd/create.php?format=simple&url={$url}");
    }

    private function validate_url($url) {
        $path         = parse_url($url, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $url          = str_replace($path, implode('/', $encoded_path), $url);
    
        return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
    }
}