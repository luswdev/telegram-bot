<?php
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $id   = isset($_POST['id'])   ? $_POST['id']   : -1;
    $res  = [];

    if ($type != '' && $id > 0) {
        $res["ok"] = true;
        $res["message"] = "OK";

        $res['payload'] = [];
        $res['result']  = [];

        $config = json_decode(file_get_contents("../data/config.json"));
        $DBHOST = $config->db->host;
        $DBUSER = $config->db->user;
        $DBPASS = $config->db->password;
        $DBNAME = $config->db->table;
        $mysqli = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);

        switch ($type) {
            case 'update': {
                $sql = "SELECT `time`, `payload` FROM `update_log` WHERE `id` = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $stmt->bind_result($time, $payload);
                if ($stmt->fetch()) {
                    $res["time"] = $time;
                    $res["payload"] = json_decode($payload, JSON_PRETTY_PRINT);
                }
                $stmt->close();
            break;
            }
            case 'exec': {
                $sql = "SELECT `time`, `payload`, `result` FROM `exec_log` WHERE `id` = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $stmt->bind_result($time, $payload, $result);
                if ($stmt->fetch()) {
                    $res["time"] = $time;
                    $res["payload"] = json_decode($payload, JSON_PRETTY_PRINT);
                    $res["result"] = json_decode($result, JSON_PRETTY_PRINT);
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

        $mysqli->close();

    } else {
        $res["ok"] = false;
        $res["message"] = "Please choose a type and id.";
    }

    $json = json_encode($res, JSON_PRETTY_PRINT);
    echo $json;
?>