<?php
    $type = $_POST['type'] ?? null;
    $id   = $_POST['id'] ?? -1;
    $res  = [];

    if ($type && $id > 0) {
        $res["ok"] = true;
        $res["message"] = "OK";

        $res['payload'] = [];
        $res['result']  = [];

        $config = json_decode(file_get_contents("../data/config.json"));
        $DBHOST = $config->db->host;
        $DBUSER = $config->db->user;
        $DBPASS = $config->db->password;
        $DBNAME = $config->db->table;
        $conn = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);

        switch ($type) {
            case 'update': {
                $query = "SELECT `time`, `payload` FROM `update_log` WHERE `id` = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $stmt->bind_result($time, $payload);
                if ($stmt->fetch()) {
                    $res["time"] = $time;
                    $res["payload"][] = json_decode($payload, JSON_PRETTY_PRINT);
                }
                $stmt->close();
            break;
            }
            case 'exec': {
                $query = "SELECT `time`, `payload`, `result` FROM `exec_log` WHERE `id` = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $stmt->bind_result($time, $payload, $result);
                if ($stmt->fetch()) {

                    $res["time"] = $time;
                    $res["payload"][] = json_decode($payload, JSON_PRETTY_PRINT);
                    $res["payload"][] = json_decode($result, JSON_PRETTY_PRINT);
                }
                $stmt->close();
            break;
            }
            default: {
                $res["ok"] = false;
                $res["message"] = "Please choose a type.";
            break;
            }
        }

        $conn->close();

    } else {
        $res["ok"] = false;
        $res["message"] = "Please choose a type and id.";
    }

    $json = json_encode($res, JSON_PRETTY_PRINT);
    echo $json;
?>