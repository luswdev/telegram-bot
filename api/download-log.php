<?php
    $day = isset($_GET['day']) ? $_GET['day'] : '';
    $res = [];

    if ($day != '') {
        $res["Log Date"] = $day;
        $res["Download Time"] = date("Y:m:d H:i:s");
        $res["Update Log Counts"] = 0;
        $res["Execute Log Counts"] = 0;
        $config = json_decode(file_get_contents("../data/config.json"));
        $DBHOST = $config->db->host;
        $DBUSER = $config->db->user;
        $DBPASS = $config->db->password;
        $DBNAME = $config->db->table;

        $mysqli = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);
        $sql = "SELECT * FROM `update_log` WHERE `day` = ? ORDER BY `id` DESC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s",$day);
        $stmt->execute();
        $stmt->bind_result($id, $day, $time, $payload);
        $stmt->store_result();
        $res["Update Log Counts"] = $stmt->num_rows;

        $res["Update Log"] = [];
        while ($stmt->fetch()) {
            $res["Update Log"][] = array(
                "Update ID" => $id,
                "Update Day" => $day,
                "Update Time" => $time,
                "Update Payload" => json_decode($payload, JSON_PRETTY_PRINT)
            );
        }
        $stmt->close();
  
        $sql = "SELECT * FROM `exec_log` WHERE `day` = ? ORDER BY `id` DESC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s",$day);
        $stmt->execute();
        $stmt->bind_result($id, $day, $time, $api, $payload, $result);
        $stmt->store_result();
        $res["Execute Log Counts"] = $stmt->num_rows;

        $res["Execute Log"] = [];
        while ($stmt->fetch()) {
            $res["Execute Log"][] = array(
                "Execute ID" => $id,
                "Execute Day" => $day,
                "Execute Time" => $time,
                "Execute API" => $api,
                "Execute Payload" => json_decode($payload, JSON_PRETTY_PRINT),
                "Execute Result" => json_decode($result, JSON_PRETTY_PRINT)
            );
        }
        $stmt->close();

        $mysqli->close();
    } else {
        $res["ok"] = false;
        $res["message"] = "Please pick a day.";
    }

    $json = json_encode($res, JSON_PRETTY_PRINT);
    echo $json;
?>