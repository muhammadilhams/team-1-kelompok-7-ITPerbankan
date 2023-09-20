<?php
include "koneksi.php";



// Tangkap ID akun dari permintaan GET
if (isset($_GET['id_akun'])) {
  $id_akun = $_GET['id_akun'];
  $id_akun= 1;
  // Kueri basis data untuk mendapatkan saldo dan id_akun berdasarkan ID akun
$query = "SELECT * FROM akun WHERE id_akun = '$id_akun'";
$stmt = mysqli_query($conn, $query);

// Periksa apakah akun ditemukan
if ($stmt) {
    // Akun ditemukan, kembalikan saldo dalam format JSON
    while ($data = mysqli_fetch_array($stmt)) {
        $response = [
            'id_akun' => $data['id_akun'],
            'nama' => $data['nama'],
            'balance' => $data['saldo']
        ];
    }
    echo json_encode($response);
} else {
    // Akun tidak ditemukan
    http_response_code(404);
    echo json_encode(['message' => 'Akun tidak ditemukan']);
}

} else {
  // ID akun tidak disediakan dalam permintaan
  http_response_code(400);
  echo json_encode(['message' => 'ID akun tidak valid']);
}
?>