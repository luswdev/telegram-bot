<?php
    $day = isset($_POST['day']) ? $_POST['day'] : '';
    $res = [];

    if ($day != '') {
        $res["ok"] = true;
        $res["message"] = "OK";

        $config = json_decode(file_get_contents("../data/config.json"));
        $DBHOST = $config->db->host;
        $DBUSER = $config->db->user;
        $DBPASS = $config->db->password;
        $DBNAME = $config->db->table ;

        $mysqli = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);
        $sql = "SELECT `id`, `time` FROM `update_log` WHERE `day` = ? ORDER BY `id` DESC";
        $stmt = $mysqli->prepare($sql);
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

        $mysqli = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);
        $sql = "SELECT `id`, `time`, `api` FROM `exec_log` WHERE `day` = ? ORDER BY `id` DESC";
        $stmt = $mysqli->prepare($sql);
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
        $mysqli->close();
    } else {
        $res["ok"] = false;
        $res["message"] = "Please pick a day.";
    }

    $json = json_encode($res, JSON_PRETTY_PRINT);
    echo $json;
?>