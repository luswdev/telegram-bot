<?php

namespace App\Http\Controllers\Api\Bot;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\UpdateLog;

use Storage;

class PunchBotController extends BotController
{
    public function __construct() {
        $config = json_decode(Storage::disk('local')->get('bot.json'));
        parent::__construct($config->bots->punch, $config->owner, $config->debuger);
    }

    public function main() {
        $res = array(
            "ok" => false,
            "detail" => "Please send message corrently."
        );
    
        return json_encode($res, JSON_PRETTY_PRINT);
    }

    public function punchIn(string $code, string $work) {
        if ($code != 'punchIn' || $work != 'luxvisions') {
            $res = array(
                "ok" => false,
                "detail" => "Please send message corrently."
            );
        } else {
            $msg = date('m/d H:i');
            $res = $this->sendMessage([
                "parse_mode" => "HTML",
                "chat_id" => $this->owner,
                "text" => $msg
            ]);
        }

        return json_encode($res, JSON_PRETTY_PRINT);
    }
}