<?php
session_start();

// Membuat angka rahasia hanya sekali
if (!isset($_SESSION['angka'])) {
    $_SESSION['angka'] = rand(1, 5);
    $_SESSION['percobaan'] = 0;
}

$x = $_SESSION['angka'];
$pesan = "";
$jenis_pesan = "";
$jumlah_percobaan = $_SESSION['percobaan'];
$game_selesai = false;

if (isset($_POST['tebak'])) {

    $tebakan = $_POST['tebak'];

    // Validasi agar angka hanya 1 sampai 5
    if ($tebakan < 1 || $tebakan > 5) {

        $pesan = "⚠️ Masukkan angka antara 1 sampai 5.";
        $jenis_pesan = "salah";

    } else {

        $_SESSION['percobaan']++;

        $percobaan = $_SESSION['percobaan'];
        $jumlah_percobaan = $percobaan;

        if ($tebakan == $x) {

            $pesan = "🎉 Tebakan Anda Benar!<br>
                      Angka yang benar adalah <strong>$x</strong>";
            $jenis_pesan = "benar";
            $game_selesai = true;

        } elseif ($percobaan >= 3) {

            $pesan = "😢 Tebakan Anda Salah!<br>
                      Kesempatan Anda sudah habis.<br>
                      Angka yang benar adalah <strong>$x</strong>";
            $jenis_pesan = "salah";
            $game_selesai = true;

        } elseif ($tebakan < $x) {

            $pesan = "❌ Tebakan Anda Salah!<br>
                      💡 Petunjuk: Angka rahasia <strong>lebih besar</strong>.";
            $jenis_pesan = "salah";

        } else {

            $pesan = "❌ Tebakan Anda Salah!<br>
                      💡 Petunjuk: Angka rahasia <strong>lebih kecil</strong>.";
            $jenis_pesan = "salah";
        }
    }
}

// Memulai permainan baru
if (isset($_POST['main_lagi'])) {

    $_SESSION['angka'] = rand(1, 5);
    $_SESSION['percobaan'] = 0;

    $x = $_SESSION['angka'];
    $jumlah_percobaan = 0;
    $pesan = "";
    $jenis_pesan = "";
    $game_selesai = false;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Game Tebak Angka</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .container {
            width: 400px;
            background: white;
            padding: 35px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .icon {
            font-size: 55px;
            margin-bottom: 10px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .deskripsi {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .aturan {
            background: #f3f4ff;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            color: #555;
            font-size: 14px;
            line-height: 1.6;
        }

        .percobaan {
            background: #eef2ff;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            color: #4f46e5;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            text-align: center;
            outline: none;
            margin-bottom: 12px;
        }

        input:focus {
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.9;
        }

        .hasil {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            line-height: 1.7;
        }

        .benar {
            background: #dcfce7;
            color: #166534;
        }

        .salah {
            background: #fee2e2;
            color: #991b1b;
        }

        .main-lagi {
            margin-top: 12px;
            background: #22c55e;
        }

        .main-lagi:hover {
            background: #16a34a;
        }

        .footer {
            margin-top: 20px;
            color: #999;
            font-size: 12px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="icon">🎯</div>

    <h1>Game Tebak Angka</h1>

    <p class="deskripsi">
        Tebak angka rahasia dari 1 sampai 5!
    </p>

    <div class="aturan">

        <strong>📌 Aturan Permainan</strong><br>

        • Angka berada di antara 1–5<br>
        • Kamu memiliki 3 kesempatan<br>
        • Angka rahasia tidak akan berubah<br>
        • Gunakan petunjuk untuk membantu menebak

    </div>

    <div class="percobaan">

        Percobaan:
        <?php echo $jumlah_percobaan; ?>/3

    </div>

    <?php if (!$game_selesai) { ?>

        <form method="post">

            <input
                type="number"
                name="tebak"
                min="1"
                max="5"
                placeholder="Masukkan angka 1 - 5"
                required
            >

            <button type="submit">
                🎲 Tebak Sekarang
            </button>

        </form>

    <?php } ?>

    <?php if ($pesan != "") { ?>

        <div class="hasil <?php echo $jenis_pesan; ?>">

            <?php echo $pesan; ?>

        </div>

    <?php } ?>

    <?php if ($game_selesai) { ?>

        <form method="post">

            <button
                type="submit"
                name="main_lagi"
                class="main-lagi"
            >
                🔄 Main Lagi
            </button>

        </form>

    <?php } ?>

    <div class="footer">

        Game Tebak Angka • PHP

    </div>

</div>

</body>

</html>