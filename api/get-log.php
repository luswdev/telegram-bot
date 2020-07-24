<?php
    $day = $_POST['day'] ?? null;
    $res = [];

    if ($day) {
        $res["ok"] = true;
        $res["message"] = "OK";

        $config = json_decode(file_get_contents("../data/config.json"));
        $DBHOST = $config->db->host;
        $DBUSER = $config->db->user;
        $DBPASS = $config->db->password;
        $DBNAME = $config->db->table;
        $conn = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);

        $query = "SELECT `id`, `time` FROM `update_log` WHERE `day` = ? ORDER BY `id` DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s",$day);
        $stmt->execute();
        $stmt->bind_result($id, $time);

        $res["update"] = [];
        while ($stmt->fetch()) {
            $res["update"][] = array(
                "id" => $id,
                "time" => $time
            );
        }
        $stmt->close();

        $query = "SELECT `id`, `time`, `api` FROM `exec_log` WHERE `day` = ? ORDER BY `id` DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s",$day);
        $stmt->execute();
        $stmt->bind_result($id, $time, $api);

        $res["exec"] = [];
        while ($stmt->fetch()) {
            $res["exec"][] = array(
                "id" => $id,
                "time" => $time,
                "api" => $api
            );
        }
        $stmt->close();

        $conn->close();
    } else {
        $res["ok"] = false;
        $res["message"] = "Please pick a day.";
    }

    $json = json_encode($res, JSON_PRETTY_PRINT);
    echo $json;
?>