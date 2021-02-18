<?php

namespace App\Http\Controllers\Api\Log;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Get logs row
     *
     * @param  Request  $request
     * @return Response
     */
    public function getRow(string $year, string $month, string $day)
    {
        if (isset($year) && isset($month) && isset($day)) {   
            $res["ok"] = true;
            $res["message"] = "OK";
            $res["request day"] = $year . '-' . sprintf("%02d", $month) . '-' . sprintf("%02d", $day);
            $res["request time"] = date("Y:m:d H:i:s");

            $rows = DB::table('update_log')->where('day', $res["request day"])
                            ->orderBy('id', 'desc')
                            ->get();

            $res["result"]["update"] = [];
            foreach ($rows as $row) {
                $res["result"]["update"][] = array(
                    "id" => $row->id,
                    "day" => $row->day,
                    "time" => $row->time,
                    "payload" => json_decode($row->payload, JSON_PRETTY_PRINT)
                );
            }

            $rows = DB::table('exec_log')->where('day', $res["request day"])
                            ->orderBy('id', 'desc')
                            ->get();
                            
            $res["result"]["exec"] = [];
            foreach ($rows as $row) {
                $res["result"]["exec"][] = array(
                    "id" => $row->id,
                    "day" => $row->day,
                    "time" => $row->time,
                    "api" => $row->api,
                    "payload" => json_decode($row->payload, JSON_PRETTY_PRINT),
                    "result" => json_decode($row->result, JSON_PRETTY_PRINT)
                );
            }
        } else {
            $res["ok"] = false;
            $res["message"] = "Please pick a day.";
        }

        $json = json_encode($res, JSON_PRETTY_PRINT);
        return $json;
    }

    /**
     * Get logs row
     *
     * @param  Request  $request
     * @return Response
     */
    public function getPayload(string $type, string $id) 
    {
        if (isset($type) && isset($id)) {   
            $res["ok"] = true;
            $res["message"] = "OK";
            $res["request type"] = $type;
            $res["request time"] = date("Y:m:d H:i:s");

            switch ($type) {
                case 'update': {
                    $jsons = DB::table('update_log')->where('id', $id)->get(['payload', 'time']);
                break;
                }
                case 'exec': {
                    $jsons = DB::table('exec_log')->where('id', $id)->get(['payload', 'result', 'time']);
                break;
                }
            }

            $res['time'] = $jsons[0]->time;

            if (isset($jsons[0]->payload)) {
                $res['result'][] = json_decode($jsons[0]->payload, JSON_PRETTY_PRINT);
            }

            if (isset($jsons[0]->result)) {
                $res['result'][] = json_decode($jsons[0]->result, JSON_PRETTY_PRINT);
            }
        } else {
            $res["ok"] = false;
            $res["message"] = "Please pick a day.";
        }

        $json = json_encode($res, JSON_PRETTY_PRINT);
        return $json;
    }
}
