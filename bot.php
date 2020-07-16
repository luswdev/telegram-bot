<?php
class TgBot {
    private $token;
    private $api;
    private $owner;

    public $ChatID;
    public $FromID;
    public $MsgID;

    public $data;

    private $curl = false;

    public function __construct($usrToken, $owner) {
        $this->token = $usrToken;
        $this->api   = "https://api.telegram.org/bot". $usrToken ."/";
        $this->owner = $owner;

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

        if (!is_dir(dirname(__FILE__) . "/log/" . date("Y-m-d"))) {
            mkdir(dirname(__FILE__) . "/log/" . date("Y-m-d"));
        }

        file_put_contents(dirname(__FILE__) . "/log/" . date("Y-m-d") . "/updated.txt", date("H:i:s ") . json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n", FILE_APPEND);
    }

    private function getTgApi (string $method, array $query = []): array  {
        $url = $this->api.$method;

        $json = json_encode($query);

        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json."\n",
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8'
            ]
        ]);
        $data = curl_exec($this->curl);

        $result = json_decode($data, true);

        file_put_contents(dirname(__FILE__) . "/log/" . date("Y-m-d") . "/exec.txt" , $method . date( " H:i:s ")  .  json_encode($query, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n" . json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n\n", FILE_APPEND);

        return $result;
    }

    public function sendMessage(array $option): array {
        /* check message is empty */
        if (!isset($option['text'])) {
            return [];
        } 

        /* check reply id is set */
        if (!isset($option['chat_id'])) {
            $option['chat_id'] = $this->ChatID;
        }

        return $this->getTgApi("sendMessage", $option);
    }
}
?>
