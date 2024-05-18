<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOLEKTAN :: Koleksi Teks & Audio Nyanyian</title>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin-top: 80px;
            margin-bottom: 60px;
            font-family: 'Poppins', sans-serif;
            color: #07625A;
            background-color: #E5F5E8;
        }

        .header {
            background-color: #07625A;
            color: #fff;
            height: 50px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            font-family: 'Anton', sans-serif;
            font-size: 38px;
            text-decoration: none;
            color: #dda622;
            display: flex;
            align-items: center;
        }

        .logo span {
            margin: 0 0px;
        }

        @media(max-width: 768px) {
            .header {
                height: auto;
                padding: 10px 20px;
            }

            .logo {
                font-size: 24px;
            }
        }

        /* CSS untuk tampilan list */
        .lyrics-list {
            list-style: none;
            padding: 0;
            margin-top: 25px; /* Adjust the top margin to make space for the fixed header */
            text-align: left; /* Align text to the left */
        }

        .lyrics-item {
            margin-bottom: 5px;
            padding: 10px;
            border: 2px solid #07625a;
            border-radius: 5px;
            background-color: #fdf6b6;
            color: #07625a;
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Align items to the left */
            cursor: pointer; /* Change cursor to pointer for the whole item */
        }

        .lyrics-item.selected {
            background-color: #07625a;
            color: #fdf6b6;
        }

        /* Sembunyikan checkbox */
        input[type="checkbox"] {
            display: none;
        }

        button[type="submit"] {
            background-color: #07625a;
            color: #fdf6b6;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
            font-size: 18px;
        }

        button[type="submit"]:hover {
            background-color: #05493f;
        }

        .play-button {
            background-color: #07625a;
            color: #fdf6b6;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }

        .play-button:hover {
            background-color: #05493f;
        }

        .footer {
            background-color: #07625A;
            color: #fff;
            height: 50px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer a {
            color: #dda622;
            text-decoration: none;
            font-size: 18px;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            margin: 0 10px;
        }

        .footer a:hover {
            color: #fff;
        }

        .footer img {
            margin-right: 5px;
        }

        .social-links {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

<header class="header">
    <div class="logo">
        <span style="color: #fdf6b6;">KOLEK</span>
        <span style="color: #E5F5E8;">SI</span>
        <span style="color: #fdf6b6;">T</span>
        <span style="color: #E5F5E8;">EKS&</span>
        <span style="color: #fdf6b6;">A</span>
        <span style="color: #E5F5E8;">UDIO</span>
        <span style="color: #fdf6b6;">N</span>
        <span style="color: #E5F5E8;">YANYIAN</span>
    </div>
</header>

<h1 style="font-size: 14px; text-align: center;">Pilih Beberapa Judul Nyanyian</h1>

<form id="export-form" action="teks-nyanyian.php" method="POST">
    <ul class="lyrics-list">
        <?php
        // Koneksi ke database
        $conn = new mysqli("localhost", "kolektan_nyanyian", "Kolekta1", "kolektan_nyanyian");

        // Periksa koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Query untuk mengambil semua data lagu dari tabel lyrics
        $sql = "SELECT * FROM lyrics";
        $result = $conn->query($sql);

        // Simpan hasil query ke dalam array
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<li class="lyrics-item" onclick="toggleCheckbox(event, ' . $row['id'] . ')">
                        <button type="button" class="play-button" onclick="window.open(\'' . $row['audio'] . '\', \'_blank\')">MP3</button>
                        <label for="lyric_' . $row['id'] . '">
                            <input type="checkbox" id="lyric_' . $row['id'] . '" name="selected_lyrics[]" value="' . $row['id'] . '">
                            <strong>' . $row['title'] . '</strong> (' . $row['type'] . ')
                        </label>
                      </li>';
            }
        } else {
            echo "<p>Tidak ada lagu yang tersedia.</p>";
        }

        // Tutup koneksi
        $conn->close();
        ?>
    </ul>
    <center><button type="submit" name="export_to_pdf">Unduh File PDF</button></center>
</form>

<footer class="footer">
    <div class="social-links">
        <a href="https://wa.me/michael" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/WhatsApp_icon.png" alt="WhatsApp" width="24" height="24">MICHAEL
        </a>
        
        <a href="https://www.instagram.com/kolektan" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram" width="24" height="24">KOLEKTAN
        </a>
    </div>
</footer>

<script>
    function toggleCheckbox(event, id) {
        // Hentikan bubbling jika target adalah tombol play
        if (event.target.classList.contains('play-button')) {
            event.stopPropagation();
            return;
        }

        // Toggle checkbox dan kelas selected pada elemen
        const checkbox = document.getElementById('lyric_' + id);
        checkbox.checked = !checkbox.checked;
        const item = checkbox.closest('.lyrics-item');
        item.classList.toggle('selected');
    }

    // Pastikan label tidak menghentikan event bubbling
    document.querySelectorAll('.lyrics-item label').forEach(label => {
        label.addEventListener('click', event => {
            event.stopPropagation();
        });
    });
</script>

</body>
</html>
