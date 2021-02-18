<?php

namespace App\Http\Controllers\Api\Bot;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\UpdateLog;

use Storage;

class C3POBotController extends BotController
{
    public function __construct() {
        $config = json_decode(Storage::disk('local')->get('bot.json'));
        parent::__construct($config->bots->C3PO, $config->owner, $config->debuger);
    }

    public function main() {
        $res = [];
        $msgs = [];

        if (isset($this->data["message"]["text"])) {
            $msgs = explode(" ", $this->data["message"]["text"]);
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
                            $res = $this->sendMessage([
                                "chat_id" => $this->ChatID,
                                "text" => $this->is_gd($msgs[1]),
                                "reply_to_message_id" => $this->MsgID,
                                "disable_web_page_preview" => true
                            ]);
                        } else {
                            $res =  $this->sendMessage([
                                "chat_id" => $this->ChatID,
                                "text" => "bad url",
                                "reply_to_message_id" => $this->MsgID
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

                    $res = $this->sendMessage([
                        "parse_mode" => "HTML",
                        "chat_id" => $this->ChatID,
                        "text" => $mannual
                    ]);
                    break;
                }

                case "/myid": {
                    $res = $this->sendMessage([
                        "chat_id" => $this->ChatID,
                        "text" => $this->ChatID
                    ]);
                    break;
                }

                default: {
                    $res = $this->sendMessage([
                        "chat_id" => $this->ChatID,
                        "text" => $this->data["message"]["text"]
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

        return json_encode($res, JSON_PRETTY_PRINT);
    }

    public function github() {
        $res = [];
        $msg = '';
        
        if (isset($this->data["incident"])) {
            $msg = "<b>A incident raise or update</b>\n"
                . $this->data["incident"]["incident_updates"][0]["status"] . " - "
                . $this->data["incident"]["incident_updates"][0]["body"] . "\n"
            . "<code>" . $this->data["incident"]["incident_updates"][0]["updated_at"] . "</code>\n"
                . "<a href='https://www.githubstatus.com/'>see more detail</a>";
        } else if (isset($this->data["component"])) {
            $msg = "<b>Component updates</b>\n"
                . $this->data["component_update"]["new_status"] . " &#8592; "
                . $this->data["component_update"]["old_status"] . "\n"
                . "<code>" . $this->data["component_update"]["created_at"] . "</code>\n"
                . "<a href='https://www.githubstatus.com'>see more detail</a>";
        } else {
            $res = array(
                "ok" => false,
                "detail" => "Please send message corrently."
            );
        }
    
        if ($msg != '') {
            $option = array(
                "parse_mode" => "HTML",
                "text" => $msg
            );
            $res = $this->sendMessage($option);
        }
    
        return json_encode($res, JSON_PRETTY_PRINT);
    }

    private function is_gd(string $url) : string {
        return file_get_contents("https://is.gd/create.php?format=simple&url={$url}");
    }
}