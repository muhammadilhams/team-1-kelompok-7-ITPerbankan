<?php
include "koneksi.php";

// Pastikan permintaan adalah metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tangkap data yang diperlukan dari permintaan POST
    $id_pengirim = isset($_POST['id_pengirim']) ? $_POST['id_pengirim'] : null;
    $id_penerima = isset($_POST['id_penerima']) ? $_POST['id_penerima'] : null;
    $nominal = isset($_POST['nominal']) ? $_POST['nominal'] : null;

    // Validasi data yang diperlukan
    if ($id_pengirim && $id_penerima && $nominal) {
        // Cek apakah akun pengirim dan penerima ada
        $query_pengirim = "SELECT * FROM akun WHERE id_akun = '$id_pengirim'";
        $query_penerima = "SELECT * FROM akun WHERE id_akun = '$id_penerima'";
        $stmt_pengirim = mysqli_query($conn, $query_pengirim);
        $stmt_penerima = mysqli_query($conn, $query_penerima);

        if ($stmt_pengirim && $stmt_penerima) {
            $pengirim = mysqli_fetch_assoc($stmt_pengirim);
            $penerima = mysqli_fetch_assoc($stmt_penerima);

            // Cek apakah saldo mencukupi untuk transfer
            if ($pengirim['saldo'] >= $nominal) {
                // Lakukan transfer
                $saldo_pengirim = $pengirim['saldo'] - $nominal;
                $saldo_penerima = $penerima['saldo'] + $nominal;

                // Update saldo akun pengirim
                $update_pengirim_query = "UPDATE akun SET saldo = '$saldo_pengirim' WHERE id_akun = '$id_pengirim'";
                $update_pengirim_result = mysqli_query($conn, $update_pengirim_query);

                // Update saldo akun penerima
                $update_penerima_query = "UPDATE akun SET saldo = '$saldo_penerima' WHERE id_akun = '$id_penerima'";
                $update_penerima_result = mysqli_query($conn, $update_penerima_query);

                if ($update_pengirim_result && $update_penerima_result) {
                    // Transfer berhasil
                    // Insert data transaksi ke tabel "transaksi"
                    $waktu = date("Y-m-d H:i:s"); // Waktu saat ini
                    $insert_transaksi_query = "INSERT INTO transaksi (id_pengirim, id_penerima, nominal, waktu) VALUES ('$id_pengirim', '$id_penerima', '$nominal', '$waktu')";
                    $insert_transaksi_result = mysqli_query($conn, $insert_transaksi_query);

                    if ($insert_transaksi_result) {
                        http_response_code(200);
                        echo json_encode(['message' => 'Transfer berhasil']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['message' => 'Gagal menambahkan data transaksi']);
                    }
                } else {
                    // Gagal mengupdate saldo akun
                    http_response_code(500);
                    echo json_encode(['message' => 'Gagal melakukan transfer']);
                }
            } else {
                // Saldo tidak mencukupi
                http_response_code(400);
                echo json_encode(['message' => 'Saldo tidak mencukupi untuk transfer']);
            }
        } else {
            // Akun pengirim atau penerima tidak ditemukan
            http_response_code(404);
            echo json_encode(['message' => 'Akun pengirim atau penerima tidak ditemukan']);
        }
    } else {
        // Data yang diperlukan tidak lengkap
        http_response_code(400);
        echo json_encode(['message' => 'Data yang diperlukan tidak lengkap']);
    }
} else {
    // Metode tidak diizinkan
    http_response_code(405);
    echo json_encode(['message' => 'Metode tidak diizinkan']);
}
?>
