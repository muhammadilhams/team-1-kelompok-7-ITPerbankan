<?php
include "koneksi.php";


// Pastikan permintaan adalah metode GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Tangkap ID akun dari permintaan GET
    if (isset($_GET['id_akun'])) {
        $id_akun = $_GET['id_akun'];

        // Kueri basis data untuk mendapatkan daftar transaksi terakhir
        $query = "SELECT * FROM transaksi WHERE id_pengirim = '$id_akun' OR id_penerima = '$id_akun' ORDER BY waktu DESC LIMIT 10";
        $stmt = mysqli_query($conn, $query);

        if ($stmt) {
            $transactions = [];

            while ($data = mysqli_fetch_assoc($stmt)) {
                $transactions[] = [
                    'id_transaksi' => $data['id_transaksi'],
                    'id_pengirim' => $data['id_pengirim'],
                    'id_penerima' => $data['id_penerima'],
                    'nominal' => $data['nominal'],
                    'waktu' => $data['waktu']
                ];
            }
 
            echo json_encode($transactions);
        } else {
            // Gagal menjalankan kueri
            http_response_code(500);
            echo json_encode(['message' => 'Gagal mengambil data transaksi']);
        }
    } else {
        // ID akun tidak disediakan dalam permintaan
        http_response_code(400);
        echo json_encode(['message' => 'ID akun tidak valid']);
    }
} else {
    // Metode tidak diizinkan
    http_response_code(405);
    echo json_encode(['message' => 'Metode tidak diizinkan']);
}
?>
