<?php

namespace App\Http\Controllers\Api\Bot;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\UpdateLog;

use Storage;

use \Crypter\Crypter;

require ('/home/skywalkwer/crypt/main.php');

class NdhuPayBotController extends BotController
{
    private $userStatus;
    private $crypter;

    public function __construct() {
        $config = json_decode(Storage::disk('local')->get('bot.json'));
        parent::__construct($config->bots->pay, $config->owner, $config->debuger);
        
        $this->userStatus = $this->getStatus();

        $this->crypter = new Crypter();
    }

    public function test() {
        $status = json_decode(file_get_contents('/home/skywalkwer/ndhu-pay/config.json'));
        print_r($status->users);
    }

    // TODO: 
    // - update user list.
    // - add a action to delete data
    // - add a action to show info
    // - add help info
    public function main() {
        $res = [];
        $msgs = [];

        if (isset($this->data["message"]["text"])) {
            $msgs = explode(" ", $this->data["message"]["text"]);
        } else if (isset($this->data["inline_query"]["query"])) {
            $msgs = explode(" ", $this->data["inline_query"]["query"]);
            $inline = true;
        } else {
            $res = array(
                "ok" => false,
                "detail" => "Please send message correctly."
            );
        }
    
        if (count($msgs) > 0) {
            switch ($msgs[0]) {
                case "/start": {
                    if (!$this->userStatus) {
                        $msg = "這是一個可以幫你快速查看有沒有新的一筆入入帳資訊，我會幫你登入系統並查看最新的一筆資料是否為新入帳。\n"
                        ."本系統將會在每日的9點與17點刷新資訊，若一次有超過一筆資訊將只會顯示最新的一筆。\n\n";
    
                        $res = $this->sendMessage([
                            "parse_mode" => "HTML",
                            "chat_id" => $this->ChatID,
                            "text" => $msg
                        ]);
                        
                        $msg = "[免責聲明] 本系統會儲存你的帳號密碼與最新一筆的資訊於伺服器，並確保資料不會外流，若有疑慮請勿使用。";

                        $res = $this->sendMessage([
                            "parse_mode" => "HTML",
                            "chat_id" => $this->ChatID,
                            "text" => $msg
                        ]);

                        $msg = "請輸入身分證字號:";
                        $this->updateStatus("waitAccount");
                        $this->addSetting();
                    } else if ($this->userStatus=="waitPassword" || $this->userStatus=="waitNewPassword") {
                        $msg = "請輸入密碼。";
                    } else {
                        $msg = "請輸入身分證字號。";
                    }
                break;
                }

                case "/account": {
                    if ($this->userStatus!="finishSetting") {
                        $msg = "請先完成設定，再更改帳號。";
                    } else {
                        $msg = "請輸入新的身分證字號:";
                        $this->updateStatus("waitNewAccount");
                    }
                break;
                }

                case "/password": {
                    if ($this->userStatus!="finishSetting") {
                        $msg = "請先完成設定，再更改密碼。";
                    } else {
                        $msg = "請輸入新的密碼:";
                        $this->updateStatus("waitNewPassword");
                    }
                break;
                }

                case "/info": {
                    if ($this->userStatus!="finishSetting") {
                        $msg = "您尚未完成設定，";
                        if ($this->userStatus=="waitPassword" || $this->userStatus=="waitNewPassword") {
                            $msg .= "請輸入密碼。";
                        } else if ($this->userStatus) {
                            $msg .= "請輸入身分證字號。";
                        } else {
                            $msg .= "請輸入身分證字號。";
                            $this->updateStatus("waitAccount");
                            $this->addSetting();
                        }
                    } else {
                        $info = $this->getSetting();
                        $msg = "您的帳戶資訊為:"
                             . "\n身分證字號:" . $info->account
                             . "\n密碼:" . $this->crypter->decrypt($info->password);
                    }
                break;
                }

                case "/update": {
                    if ($this->userStatus!="finishSetting") {
                        $msg = "您尚未完成設定，";
                        if ($this->userStatus=="waitPassword" || $this->userStatus=="waitNewPassword") {
                            $msg .= "請輸入密碼。";
                        } else if ($this->userStatus) {
                            $msg .= "請輸入身分證字號。";
                        } else {
                            $msg .= "請輸入身分證字號。";
                            $this->updateStatus("waitAccount");
                            $this->addSetting();
                        }
                    } else {
                        $msg = "將為您檢查資料...";
                        $res = $this->sendMessage([
                            "parse_mode" => "HTML",
                            "chat_id" => $this->ChatID,
                            "text" => $msg
                        ]);
                        unset($msg);
                        ini_set('user_agent','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
                        $contents = file_get_contents("https://lusw.dev/n-pay/".$this->ChatID);
                    }
                break;
                }

                case "/delete": {
                    if ($this->userStatus!="finishSetting") {
                        $msg = "您尚未完成設定，";
                        if ($this->userStatus=="waitPassword" || $this->userStatus=="waitNewPassword") {
                            $msg .= "請輸入密碼。";
                        } else if ($this->userStatus) {
                            $msg .= "請輸入帳號。";
                        } else {
                            $msg .= "請輸入帳號。";
                            $this->updateStatus("waitAccount");
                            $this->addSetting();
                        }
                    } else {
                        $msg = "將刪除您的所有資訊。";
                        $this->deleteStatus();
                        $this->deleteSetting();
                    }
                break;
                }

                case "/help": {
                    $msg = "這是一個可以幫你快速查看有沒有新的一筆入入帳資訊，我會幫你登入系統並查看最新的一筆資料是否為新入帳。\n"
                    ."本系統將會在每日的9點與17點刷新資訊，若一次有超過一筆資訊將只會顯示最新的一筆。\n\n";

                    $res = $this->sendMessage([
                        "parse_mode" => "HTML",
                        "chat_id" => $this->ChatID,
                        "text" => $msg
                    ]);

                    $msg = "[免責聲明] 本系統會儲存你的帳號密碼與最新一筆的資訊於伺服器，並確保資料不會外流，若有疑慮請勿使用。";

                    $res = $this->sendMessage([
                        "parse_mode" => "HTML",
                        "chat_id" => $this->ChatID,
                        "text" => $msg
                    ]);

                    $msg = "以下是可用的操作:\n"
                        ."/start - 啟動本服務\n"
                        ."/account - 重設帳號\n"
                        ."/password - 重設密碼\n"
                        ."/info - 顯示設定資訊\n"
                        ."/update - 檢查新資料\n"
                        ."/delete - 清除帳戶資訊\n"
                        ."/help - 顯示說明\n";
                break;
                }

                default: {  
                    switch ($this->userStatus) {
                        case "waitAccount": {   
                            $msg = "請輸入密碼:";
                            $this->updateStatus("waitPassword");                            
                            $this->updateSetting('account', $msgs[0]);
                        break;
                        }
                        case "waitPassword": {
                            $msg = "您已完成設定。";
                            $this->updateStatus("finishSetting");
                            $this->updateSetting('password', $this->crypter->encrypt($msgs[0]));
                        break;
                        } 
                        case "waitNewAccount": {
                            $msg = "已重設身分證字號。";
                            $this->updateStatus("finishSetting");
                            $this->updateSetting('account', $msgs[0]);
                        break;
                        }
                        case "waitNewPassword": {
                            $msg = "已重設密碼。";
                            $this->updateStatus("finishSetting");
                            $this->updateSetting('password', $this->crypter->encrypt($msgs[0]));
                        break;
                        }
                        case "finishSetting": {
                            $msg = "您已完成設定。";
                        break;
                        }
                        default: {
                            $msg = "操作錯誤，請重新輸入身分證字號:";
                            $this->updateStatus("waitAccount"); 
                            break;
                        }
                    }
                break;
                }
            }     
        } else {
            $res = array(
                "ok" => false,
                "detail" => "Please send message correctly."
            );
        }

        if (isset($msg)) {
            $res = $this->sendMessage([
                "parse_mode" => "HTML",
                "chat_id" => $this->ChatID,
                "text" => $msg
            ]);
        }
    
        return json_encode($res, JSON_PRETTY_PRINT);
    }

    public function notiNew(string $id, string $date, string $name, string $pay) {
        $msg = "有一筆新存款入帳!\n"
                .  "時間: $date\n"
                .  "明細: $name\n"
                .  "金額: $pay";

        $res = $this->sendMessage([
            "parse_mode" => "HTML",
            "chat_id" => intval($id),
            "text" => $msg
        ]);

        return json_encode($res, JSON_PRETTY_PRINT);
    }

    public function notiOld(string $id) {
        $msg = "已檢查，未有新入帳。\n";

        $res = $this->sendMessage([
            "parse_mode" => "HTML",
            "chat_id" => intval($id),
            "text" => $msg
        ]);

        return json_encode($res, JSON_PRETTY_PRINT);
    }

    private function getStatus() : string {
        $id = $this->ChatID;
        $status = json_decode(Storage::disk('local')->get('status.json'));
        return $status->$id ?? "";
    }

    private function updateStatus(string $status) {
        $id = $this->ChatID;
        $statusFP = json_decode(Storage::disk('local')->get('status.json'));
        $statusFP->$id = $status;
        Storage::disk('local')->put('status.json', json_encode($statusFP, JSON_PRETTY_PRINT));
    }

    private function deleteStatus() {
        $id = $this->ChatID;
        $statusFP = json_decode(Storage::disk('local')->get('status.json'));
        unset($statusFP->$id);
        Storage::disk('local')->put('status.json', json_encode($statusFP, JSON_PRETTY_PRINT));
    }

    private function getSetting() {
        $config = json_decode(file_get_contents('/home/skywalkwer/ndhu-pay/config.json'));
        foreach ($config->users as $user) {
            if ($user->id === $this->ChatID) {
                return $user;
            }
        }

        return null;
    }

    private function addSetting() {
        $config = json_decode(file_get_contents('/home/skywalkwer/ndhu-pay/config.json'));
        $config->users[] = array(
            "account" => '',
            'password' => '',
            'id' => $this->ChatID
        );
        file_put_contents('/home/skywalkwer/ndhu-pay/config.json', json_encode($config, JSON_PRETTY_PRINT));
    }

    private function updateSetting($key, $value) {
        $config = json_decode(file_get_contents('/home/skywalkwer/ndhu-pay/config.json'));
        foreach ($config->users as $user) {
            if ($user->id === $this->ChatID) {
                $user->$key = $value;
            }
        }
        file_put_contents('/home/skywalkwer/ndhu-pay/config.json', json_encode($config, JSON_PRETTY_PRINT));
    }

    private function deleteSetting() {
        $config = json_decode(file_get_contents('/home/skywalkwer/ndhu-pay/config.json'));
        foreach ($config->users as $key => $user) {
            if ($user->id === $this->ChatID) {
                unset($config->users[$key]);
            }
        }
        file_put_contents('/home/skywalkwer/ndhu-pay/config.json', json_encode($config, JSON_PRETTY_PRINT));
    }
}