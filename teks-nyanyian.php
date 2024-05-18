<?php
require('fpdf.php');

// Fungsi untuk menambahkan watermark logo ke setiap halaman PDF
function addWatermark($pdf, $logoPath) {
    $pdf->Image($logoPath, 70, 50, 80); 
}

// Path ke watermark
$logoPath = 'watermark.png'; 

// Koneksi ke database
$conn = new mysqli("localhost", "kolektan_nyanyian", "Kolekta1", "kolektan_nyanyian");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Periksa apakah lirik dipilih
if(isset($_POST['selected_lyrics'])) { // Ubah 'lyrics' menjadi 'selected_lyrics'
    $lyrics = $_POST['selected_lyrics'];

    // Query untuk mendapatkan lirik lagu yang dipilih
    $sql = "SELECT title, type, lyrics FROM lyrics WHERE id IN (" . implode(",", $lyrics) . ")";
    $result = $conn->query($sql);

    if ($result) {
        // Mulai membuat file PDF
        $pdf = new FPDF('P','mm','A5');
        
        // Set font
        $pdf->SetFont('Arial', '', 12);

        // Set warna teks menggunakan kode warna 
        $pdf->SetTextColor(7, 98, 90); 

        // Tambahkan halaman baru dan watermark untuk kali pertama
        $pdf->AddPage();
        addWatermark($pdf, $logoPath);

        while ($row = $result->fetch_assoc()) {
            // Perkiraan jumlah baris berdasarkan panjang teks lirik
            $jumlahBaris = ceil(strlen($row['lyrics']) / 80); 

            // Cek apakah cukup ruang di halaman ini
            if ($pdf->GetY() + $jumlahBaris * 8 > $pdf->GetPageHeight() - 40) {
                // Tidak cukup ruang, tambahkan halaman baru dan menampilkan watermark kembali
                $pdf->AddPage();
                addWatermark($pdf, $logoPath);
            }

            // Tambahkan judul lagu (dalam teks yang bold)
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 2, $row['title'], 0, 1);

            // Tambahkan jenis lagu (dalam teks miring dan termasuk dalam tanda kurung)
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->Cell(0, 6, "(" . $row['type'] . ")", 0, 1);

            // Tambahkan lirik lagu
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(0, 4, $row['lyrics']); // Mengatur jarak antar baris menjadi 7
            $pdf->Ln();
        }

        $pdf->Output('D', 'nyanyian_' . date("Y-m-d") . '.pdf'); // 'D' akan mengarahkan unduhan file
        exit; // Menghentikan eksekusi skrip setelah menghasilkan PDF
    } else {
        die("Query error: " . $conn->error);
    }
} else {
    echo "Tidak ada lirik yang dipilih.";
}

$conn->close();
?>
