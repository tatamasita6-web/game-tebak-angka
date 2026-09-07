<?php
session_start();

// Membuat angka rahasia hanya sekali
if (!isset($_SESSION['angka'])) {
    $_SESSION['angka'] = rand(1, 5);
    $_SESSION['percobaan'] = 0;
    $_SESSION['riwayat'] = [];
}

$x = $_SESSION['angka'];
$pesan = "";
$jenis_pesan = "";
$jumlah_percobaan = $_SESSION['percobaan'];
$sisa_kesempatan = 3 - $jumlah_percobaan;
$game_selesai = false;
$pesan_selesai = "";

// Proses tebakan
if (isset($_POST['tebak'])) {

    $tebakan = $_POST['tebak'];

    // Validasi angka
    if ($tebakan < 1 || $tebakan > 5) {

        $pesan = "⚠️ Masukkan angka antara 1 sampai 5.";
        $jenis_pesan = "salah";

    } else {

        $_SESSION['percobaan']++;

        // Menyimpan tebakan ke riwayat
        $_SESSION['riwayat'][] = $tebakan;

        $percobaan = $_SESSION['percobaan'];
        $jumlah_percobaan = $percobaan;
        $sisa_kesempatan = 3 - $percobaan;

        if ($tebakan == $x) {

            $pesan = "🎉 Tebakan Anda Benar!<br>
                      Angka yang benar adalah <strong>$x</strong>";

            $jenis_pesan = "benar";
            $game_selesai = true;
            $pesan_selesai = "🏆 Selamat! Kamu berhasil menebak angka rahasia!";

        } elseif ($percobaan >= 3) {

            $pesan = "😢 Tebakan Anda Salah!<br>
                      Kesempatan Anda sudah habis.<br>
                      Angka yang benar adalah <strong>$x</strong>";

            $jenis_pesan = "salah";
            $game_selesai = true;
            $pesan_selesai = "🎮 Permainan selesai. Coba lagi untuk mendapatkan angka baru!";

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
    $_SESSION['riwayat'] = [];

    $x = $_SESSION['angka'];
    $jumlah_percobaan = 0;
    $sisa_kesempatan = 3;
    $pesan = "";
    $jenis_pesan = "";
    $game_selesai = false;
    $pesan_selesai = "";
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

        .status-game {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 20px;
        }

        .status-box {
            flex: 1;
            background: #eef2ff;
            padding: 12px 8px;
            border-radius: 10px;
            color: #4f46e5;
            font-weight: bold;
            font-size: 13px;
        }

        .status-box span {
            display: block;
            font-size: 20px;
            margin-top: 5px;
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

        .selesai {
            margin-top: 12px;
            padding: 12px;
            background: #fff7ed;
            color: #9a3412;
            border-radius: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .riwayat {
            margin-top: 20px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 12px;
            text-align: left;
        }

        .riwayat h3 {
            color: #333;
            font-size: 15px;
            margin-bottom: 10px;
            text-align: center;
        }

        .daftar-tebakan {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .angka-tebakan {
            background: #e0e7ff;
            color: #4338ca;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: bold;
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

    <div class="status-game">

        <div class="status-box">
            Percobaan
            <span><?php echo $jumlah_percobaan; ?>/3</span>
        </div>

        <div class="status-box">
            Kesempatan
            <span><?php echo $sisa_kesempatan; ?></span>
        </div>

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

    <?php if ($game_selesai && $pesan_selesai != "") { ?>

        <div class="selesai">

            <?php echo $pesan_selesai; ?>

        </div>

    <?php } ?>

    <?php if (!empty($_SESSION['riwayat'])) { ?>

        <div class="riwayat">

            <h3>📋 Riwayat Tebakan</h3>

            <div class="daftar-tebakan">

                <?php foreach ($_SESSION['riwayat'] as $nomor => $nilai) { ?>

                    <div class="angka-tebakan">

                        Percobaan <?php echo $nomor + 1; ?>:
                        <?php echo $nilai; ?>

                    </div>

                <?php } ?>

            </div>

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