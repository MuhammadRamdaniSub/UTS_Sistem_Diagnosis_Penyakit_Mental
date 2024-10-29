<?php
class Gejala {
    public $nama;
    public $cfExpert;

    public function __construct($nama, $cfExpert) {
        $this->nama = $nama;
        $this->cfExpert = $cfExpert;
    }
}

class Penyakit {
    public $nama;
    public $gejalas = [];
    public $cfCombine;

    public function __construct($nama) {
        $this->nama = $nama;
    }

    public function tambahGejala($gejala) {
        $this->gejalas[] = $gejala;
    }

    public function hitungCF($cfUser) {
        $this->cfCombine = 0;

        foreach ($this->gejalas as $index => $gejala) {
            if (isset($cfUser[$index])) {
                $cfGejala = $gejala->cfExpert * $cfUser[$index];

                if ($index == 0) {
                    $this->cfCombine = $cfGejala;
                } else {
                    $this->cfCombine = $this->cfCombine + ($cfGejala * (1 - $this->cfCombine));
                }
            }
        }
    }

}

$skizofrenia = new Penyakit("Skizofrenia");
$skizofrenia->tambahGejala(new Gejala("G1: Sulit tidur", 0.2));
$skizofrenia->tambahGejala(new Gejala("G2: Mendengar suara aneh", 0.3));
$skizofrenia->tambahGejala(new Gejala("G5: Emosi menjadi datar", 0.6));
$skizofrenia->tambahGejala(new Gejala("G7: Menjauh dari lingkungan sosial", 0.2));
$skizofrenia->tambahGejala(new Gejala("G8: Pikiran dan berbicara kacau", 0.1));
$skizofrenia->tambahGejala(new Gejala("G12: Mempercayai sesuatu yang tidak nyata", 0.8));


$ptsd = new Penyakit("PTSD");
$ptsd->tambahGejala(new Gejala("G1: Sulit tidur", 0.4));
$ptsd->tambahGejala(new Gejala("G4: kehilangan minat untuk melakukan aktivitas", 0.2));
$ptsd->tambahGejala(new Gejala("G6: Ingatan terganggu", 0.5));
$ptsd->tambahGejala(new Gejala("G9: Rasa takut dan khawatir berlebihan", 0.1));
$ptsd->tambahGejala(new Gejala("G10: Mimpi buruk", 0.4));
$ptsd->tambahGejala(new Gejala("G13: Sulit mengendalikan emosi", 0.4));
$ptsd->tambahGejala(new Gejala("G16: Menghindari sebuah tempat/objek tertentu", 0.2));


$depresi = new Penyakit("Depresi");
$depresi->tambahGejala(new Gejala("G1: Sulit tidur", 0.5));
$depresi->tambahGejala(new Gejala("G4: Kehilangan minat untuk melakukan aktivitas ", 0.2));
$depresi->tambahGejala(new Gejala("G11: Sering merasa sedih", 0.6));
$depresi->tambahGejala(new Gejala("G14: Diliputi perasaan bersalah berlebihan ", 0.1));
$depresi->tambahGejala(new Gejala("G20: Perasaan putus asa ", 0.6));
$depresi->tambahGejala(new Gejala("G16: Menghindari sebuah tempat/objek tertentu", 0.4));


$bipolar = new Penyakit("Bipolar");
$bipolar->tambahGejala(new Gejala("G14: Diliputi perasaan bersalah berlebihan", 0.6));
$bipolar->tambahGejala(new Gejala("G17: Kehilangan motivasi", 0.3));
$bipolar->tambahGejala(new Gejala("G19: Moody", 0.8));
$bipolar->tambahGejala(new Gejala("G21: Sering/mudah menangis", 0.2));
$bipolar->tambahGejala(new Gejala("G6: Daya ingat menurun", 0.5));
$bipolar->tambahGejala(new Gejala("G22: Bicara terlalu cepat", 0.2));
$bipolar->tambahGejala(new Gejala("G12: Mempercayai hal yang tidak nyata", 0.1));


$paranoid = new Penyakit("Paranoid");
$paranoid->tambahGejala(new Gejala("G7: Menjauh dari lingkungan sosial", 0.3));
$paranoid->tambahGejala(new Gejala("G9: Rasa takut dan khawatir berlebihan", 0.6));
$paranoid->tambahGejala(new Gejala("G15: Perasaan bermusuhan", 0.1));
$paranoid->tambahGejala(new Gejala("G18: Sering cemas", 0.1));
$paranoid->tambahGejala(new Gejala("G23: Gangguan pernafasan", 0.4));
$paranoid->tambahGejala(new Gejala("G24: Gerakan tubuh dan pikiran yang lambat", 0.2));



// Ambil data dari form
$cfUserSkizofrenia = isset($_POST['cfUserSkizofrenia']) ? $_POST['cfUserSkizofrenia'] : [];
$cfUserPTSD = isset($_POST['cfUserPTSD']) ? $_POST['cfUserPTSD'] : [];
$cfUserDepresi = isset($_POST['cfUserDepresi']) ? $_POST['cfUserDepresi'] : [];
$cfUserBipolar = isset($_POST['cfUserBipolar']) ? $_POST['cfUserBipolar'] : [];
$cfUserParanoid = isset($_POST['cfUserParanoid']) ? $_POST['cfUserParanoid'] : [];

// Hitung CF untuk setiap penyakit hanya jika array tidak kosong
if (!empty($cfUserSkizofrenia)) {
    $skizofrenia->hitungCF($cfUserSkizofrenia);
}
if (!empty($cfUserPTSD)) {
    $ptsd->hitungCF($cfUserPTSD);
}
if (!empty($cfUserDepresi)) {
    $depresi->hitungCF($cfUserDepresi);
}
if (!empty($cfUserBipolar)) {
    $bipolar->hitungCF($cfUserBipolar);
}
if (!empty($cfUserParanoid)) {
    $paranoid->hitungCF($cfUserParanoid);
}

// Simpan hasil dalam array
$penyakitList = [$skizofrenia, $ptsd, $depresi, $bipolar, $paranoid];

// Urutkan berdasarkan CF
usort($penyakitList, function($a, $b) {
    return $a->cfCombine <=> $b->cfCombine;
});

// Tampilkan hasil
echo "<h2>Hasil Diagnosis</h2>";
foreach ($penyakitList as $penyakit) {
    echo "<p>{$penyakit->nama}: " . round($penyakit->cfCombine * 100, 2) . "%</p>";
}