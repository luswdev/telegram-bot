<?php

/**
 *  @brief  Telegram bot Class.
 */
class TgBot {
    /* Bot meta information */
    private $token;
    private $api;
    private $owner;
    private $debugTo;

    /* receive data information */
    public $ChatID;
    public $FromID;
    public $MsgID;
    public $data;

    /* execute sending needed data */
    private $curl = false;

    /**
     *  @brief  Bot class constructor function.
     * 
     *  @param  $usrToken    This bot token.
     *  @param  $owner       This bot owner, send message to here if sendMessage's chat_id isn't set.
     *  @param  $debuger     This bot debuger, send message to here if didn't execute corrently.
     */
    public function __construct($usrToken, $owner, $debuger) {
        $this->token = $usrToken;
        $this->api   = "https://api.telegram.org/bot". $usrToken ."/";
        $this->owner = $owner;
        $this->debugTo = $debuger;

        $this->curl = curl_init();

        $data = file_get_contents('php://input');
        $this->data = json_decode($data, true);

        $this->ChatID = $this->data['message']['migrate_to_chat_id'] ??
            $this->data['message']['chat']['id'] ??
            $this->data['edited_message']['chat']['id'] ??
            $this->data['inline_query']['from']['id'] ??
            $this->data['chosen_inline_result']['from']['id'] ??
            $this->data['callback_query']['message']['chat']['id'] ??
            $this->data['callback_query']['message']['from']['id'] ??
            $this->data['callback_query']['chat']['id'] ??
            $this->data['callback_query']['from']['id'] ??
            $this->data['channel_post']['chat']['id'] ??
            $this->data['edited_channel_post']['chat']['id'] ??
            $owner;

        $this->FromID = $this->data['message']['from']['id'] ??
            $this->data['edited_message']['from']['id'] ??
            $this->data['inline_query']['from']['id'] ??
            $this->data['chosen_inline_result']['from']['id'] ??
            $this->data['callback_query']['from']['id'] ??
            $this->data['channel_post']['from']['id'] ??
            $this->data['edited_channel_post']['from']['id'] ??
           $owner;

        $this->MsgID = $this->data['message']['message_id'] ??
            $this->data['edited_message']['message_id'] ??
            $this->data['inline_query']['id'] ??
            $this->data['chosen_inline_result']['id'] ??
            $this->data['callback_query']['message']['message_id'] ??
            $this->data['callback_query']['inline_message_id'] ??
            $this->data['callback_query']['id'] ??
            $this->data['channel_post']['message_id'] ??
            $this->data['edited_channel_post']['message_id'] ??
            $owner;

        /* create day directory if isn't existed */
        if (!is_dir(dirname(__FILE__)."/../log/".date("Y"))) {
            mkdir(dirname(__FILE__)."/../log/".date("Y"));
        }
        
        if (!is_dir(dirname(__FILE__)."/../log/".date("Y")."/".date("m"))) {
            mkdir(dirname(__FILE__)."/../log/".date("Y")."/".date("m"));
        }

        if (!is_dir(dirname(__FILE__)."/../log/".date("Y")."/".date("m")."/".date("d"))) {
            mkdir(dirname(__FILE__)."/../log/".date("Y")."/".date("m")."/".date("d"));
        }

        /* record input data into logfile */
        file_put_contents(dirname(__FILE__)."/../log/".date("Y")."/".date("m")."/".date("d")."/updated.txt",
                        date("H:i:s ").
                        json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n\n", 
                        FILE_APPEND);
    }

    /**
     *  @brief  Execute telegram bot APIs.
     * 
     *  @param  $method    Which methods should execute.
     *  @param  $query     Executing data.
     * 
     *  @return $result    Executed result.
     */
    private function getTelegramAPI (string $method, array $query = []): array  {
        $json = json_encode($query);

        /* setup curl */
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $this->api.$method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json."\n",
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8'
            ]
        ]);

        $data = curl_exec($this->curl);
        $result = json_decode($data, true);

        /* record execute data and result into logfile */
        file_put_contents(dirname(__FILE__)."/../log/".date("Y")."/".date("m")."/".date("d")."/exec.txt" , 
                        $method.date( " H:i:s ")
                        .json_encode($query, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n"
                        .json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n\n\n", 
                        FILE_APPEND);

        /* sending debug message if result isn't ok */
        if (!$result['ok']) {
            $this->_debug($result, $query);
        }

        return $result;
    }

    /**
     *  @brief  Sending message.
     * 
     *  @param  $option    Sending option, see more detail from telegram bot APIs doc.
     * 
     *  @return $result    Sending result.
     */
    public function sendMessage(array $option): array {
        /* check message is empty */
        if (!isset($option['text'])) {
            return [];
        } 

        /* check reply id is set */
        if (!isset($option['chat_id'])) {
            $option['chat_id'] = $this->ChatID;
        }

        return $this->getTelegramAPI("sendMessage", $option);
    }

    /**
     *  @brief  Sending inline message.
     * 
     *  @param  $option    Sending option, see more detail from telegram bot APIs doc.
     * 
     *  @return $result    Sending result.
     */
    public function sendInlineMessage(array $option): array {
        /* check message is empty */
        if (!isset($option['results'])) {
            return [];
        } 

        /* check reply id is set */
        if (!isset($option['inline_query_id'])) {
            return [];
        }

        return $this->getTelegramAPI("answerInlineQuery", $option);
    }

    /**
     *  @brief  Sending debug message.
     * 
     *  @param  $result    Execute failed result.
     *  @param  $data      Execute failed data.
     */
    private function _debug($result, $data) {
        /* if need to send message for debug */
        if (isset($data)) {
            $this->sendMessage([
                'chat_id' => $this->debugTo,
                'text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ]);
        }

        /* if need to send result for debug */
        $res = json_decode($res);
        if (isset($result)) {
            $this->sendMessage([
                'chat_id' => $this->debugTo,
                'text' => json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            ]);
        }
    }
}
?>
