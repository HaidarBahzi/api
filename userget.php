<?php

require_once("connection.php");

if (empty($_GET)) {
    $query = mysqli_query($connection, "SELECT * FROM table_user");
    $result = array();

    while ($row = mysqli_fetch_array($query)) {
        array_push($result, array(
            'id' => $row['id'],
            'nama_lengkap' => $row['nama_lengkap'],
            'email' => $row['email'],
            'nomor_hp' => $row['nomor_hp'],
            'password' => $row['password'],
            'date_create' => $row['date_create'],
        ));
    }

    header('Content-Type: application/json');
    echo json_encode(
        array(['result' => $result, 'status code' => 200])
    );
} else {
    $params = array();
    $sql = "SELECT * FROM table_user WHERE ";
    foreach ($_GET as $key => $value) {
        switch ($key) {
            case 'id':
                $sql .= "id = ? AND ";
                $params[] = $value;
                break;
            case 'nama_lengkap':
                $sql .= "nama_lengkap LIKE ? AND ";
                $params[] = "%" . $value . "%";
                break;
            case 'email':
                $sql .= "email LIKE ? AND ";
                $params[] = "%" . $value . "%";
                break;
            case 'nomor_hp':
                $sql .= "nomor_hp LIKE ? AND ";
                $params[] = "%" . $value . "%";
                break;
        }
    }
    $sql = rtrim($sql, "AND ");
    $stmt = $connection->prepare($sql);
    if ($stmt) {
        if ($params) {
            $types = str_repeat("s", count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $query = $stmt->get_result();
        if ($query->num_rows > 0) {
            $result = array();
            while ($row = $query->fetch_assoc()) {
                array_push($result, array(
                    'id' => $row['id'],
                    'nama_lengkap' => $row['nama_lengkap'],
                    'email' => $row['email'],
                    'nomor_hp' => $row['nomor_hp'],
                    'password' => $row['password'],
                    'date_create' => $row['date_create'],
                ));
            }
            header('Content-Type: application/json');
            echo json_encode(
                array(['result' => $result, 'status code' => 200])
            );
        } else {
            header('Content-Type: application/json');
            echo json_encode(
                array(['result' => 'Data not found', 'status code' => 404])
            );
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(
            array('result' => 'Error: ' . $connection->error)
        );
    }
}
