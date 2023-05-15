<?php

require_once("connection.php");

// Cek apakah request method-nya POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari body request
    $data = json_decode(file_get_contents('php://input'), true);

    // Ambil data dari body dan simpan ke variabel
    $namaLengkap = $data['nama_lengkap'];
    $email = $data['email'];
    $nomorHp = $data['nomor_hp'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT); // Enkripsi password
    $tanggal = date("Y-m-d H:i:s"); // Ambil tanggal sekarang

    // Query untuk memasukkan data ke dalam tabel
    $query = "INSERT INTO table_user (nama_lengkap, email, nomor_hp, password, date_create) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);

    // Bind parameter dan eksekusi query
    $stmt->bind_param("sssss", $namaLengkap, $email, $nomorHp, $password, $tanggal);
    if ($stmt->execute()) {
        header('Content-Type: application/json');
        echo json_encode(
            array('result' => 'Data berhasil ditambahkan', 'status code' => 200)
        );
    } else {
        header('Content-Type: application/json');
        echo json_encode(
            array('result' => 'Error: ' . $stmt->error, 'status code' => 400)
        );
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(
        array('result' => 'Method not allowed', 'status code' => 405)
    );
}
