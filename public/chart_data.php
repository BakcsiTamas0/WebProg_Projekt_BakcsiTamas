<?php
session_start();
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "phpprojekt";

    $conn = new mysqli($server, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $query = "SELECT symbol, price, date FROM chart";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $data = $result->fetch_all();
            echo json_encode($data);
        }
    }

    $conn->close();
?>
