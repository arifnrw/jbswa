<?php

$json       = file_get_contents("php://input");
$array      = json_decode($json, true);
$secret     = ($_GET['secret']) ? : '';
$mysecret   = "my_secret_key";

if ($array['category'] == 'private' && $mysecret == $secret) {
    $wa     = $array["number"];
    $sid    = $array["id"];
    $pesan  = $array["message"];

    if ($array['buttonid'] != null) {
        $pesan = $array['buttonid'];
    }else if($array['rowid'] != null){
        $pesan = $array['rowid'];
    }

    $token  = $array["token"];
    $base_url  = $array["base_url"];
    $waktu  = date("Y-m-d H:i:s", $array["time"]);
    $balas  = "";
    $button = "";
    $buttonlist   = "";

    //format nomor WA
    if (substr($wa,0,2) == '62') {
        $hp = substr($wa,2);
    }

    //hubungkan ke database
    include('config.php');
    $conn = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
    $keys   = explode("#",$pesan);
    $keys   = array_map('trim',$keys);
    $key    = strtolower(trim($keys[0]));

    switch ($key) {
        case '/start':
            
            $balas = "Selamat datang di Channel WhatsApp *$sekolah*\n\nDengan bergabung di Channel ini, anda dapat memperoleh:\n- Laporan Perolehan Nilai, Pembayaran Keuangan, Presensi Kehadiran dan Peminjaman Pustaka yang dilakukan oleh Siswa\n- Informasi, Pengumuman dan Berita dari Sekolah\n- Notifikasi mengenai transaksi keuangan yang dibayarkan, kehadiran siswa di sekolah dan perolehan nilai ujian\n\n------------------------------\nWhatsAppQu 3.0\nwarayang.com";
            $buttonlist = "Lihat Menu";
            $section = "Menu";
            $title = "/registrasi,/keuangan,/nilai,/kehadiran";
            $subtitle = "Registrasi ke Channel WhatsApp Gateway,Laporan Transaksi Keuangan,Laporan Perolehan Nilai,Laporan Presensi Kehadiran";
            $idl = $title;

            break;
        case '/registrasi':
            $balas  = "Silakan memilih menu pendaftaran di bawah ini!";
            $button = "Informasi Peserta Terdaftar";
            $idb    = "/infoterdaftar";
            break;

        case 'daftarbaru':
            if (empty($keys[1])) {
                $balas  = "Silakan memilih jenis peserta di bawah ini!";
                $button = "Siswa dan Orangtua,Calon Siswa dan Orangtua, Guru dan Pegawai";
                $idb    = "daftarbaru#2,daftarbaru#3,daftarbaru#1";
            } else {
                $balas  = "Masih dalam tahap pengembangan.";
            }
            
            break;

        case '/infoterdaftar':
            //cek terdaftar sebagai pegawai
            $sqlp  = "SELECT * FROM jbssdm.pegawai WHERE handphone LIKE '%$hp' AND aktif=1";
            $resp  = $conn->query($sqlp);
            $np    = mysqli_num_rows($resp);
            
            //cek terdaftar sebagai siswa
            $sqls  = "SELECT * FROM jbsakad.siswa WHERE (hpsiswa LIKE '%$hp' OR hportu LIKE '%$hp' OR info1 LIKE '%$hp' OR info2 LIKE '%$hp') AND aktif=1";
            $ress  = $conn->query($sqls);
            $ns    = mysqli_num_rows($ress);

            //cek terdaftar sebagai calon siswa
            $sqlcs  = "SELECT * FROM jbsakad.calonsiswa WHERE (hpsiswa LIKE '%$hp' OR hportu LIKE '%$hp' OR info1 LIKE '%$hp' OR info2 LIKE '%$hp') AND aktif=1";
            $rescs  = $conn->query($sqlcs);
            $ncs    = mysqli_num_rows($rescs);

            if ($np+$ns+$ncs > 0) {
                $balas      = "Nomor Anda terdaftar dengan data sebagai berikut:\n";
                
                if ($np) {
                    $it = 1;
                    $balas .= "\n*Pegawai/Guru* \n";
                    while ($rowp = mysqli_fetch_assoc($resp)) {
                        $balas .= $it++ . ") " . $rowp['nama'] . " (".$rowp['nip'].") \n";
                    }
                }

                if ($ns) {
                    $it = 1;
                    $balas .= "\n*Siswa/Ortunya* \n";
                    while ($rows = mysqli_fetch_assoc($ress)) {
                        $balas .= $it++ . ") " . $rows['nama'] . " (".$rows['nis'].") \n";
                    }
                }

                if ($ncs) {
                    $it = 1;
                    $balas .= "\n*Calon Siswa/Ortunya* \n";
                    while ($rowcs = mysqli_fetch_assoc($rescs)) {
                        $balas .= $it++ . ") " . $rowcs['nama'] . " (".$rowcs['nopendaftaran'].") \n";
                    }
                }

                $buttonlist = "Lihat Menu";
                $section = "Menu";
                $title = "/keuangan,/nilai,/kehadiran";
                $subtitle = "Laporan Transaksi Keuangan,Laporan Perolehan Nilai,Laporan Presensi Kehadiran";
                $idl = $title;

            } else {
                $balas = "Mohon maaf, nomor Anda tidak terdaftar di sistem kami sebagai pegawai, siswa atau ortunya, maupun calon siswa atau ortunya. Silakan menghubungi admin kami untuk melakukan pembaruan data!";
            }
            

            break;

        case 'batalkanpendaftaran':
            $balas = "Menghapus tabel member. Masih dalam tahap pengembangan.";
            break;

        case '/keuangan':
            if (empty($keys[1]) || empty($keys[2])) {
                //cek terdaftar sebagai pegawai
                $sqlp  = "SELECT * FROM jbssdm.pegawai WHERE handphone LIKE '%$hp' AND aktif=1";
                $resp  = $conn->query($sqlp);
                $np    = mysqli_num_rows($resp);
                
                //cek terdaftar sebagai siswa
                $sqls  = "SELECT * FROM jbsakad.siswa WHERE (hpsiswa LIKE '%$hp' OR hportu LIKE '%$hp' OR info1 LIKE '%$hp' OR info2 LIKE '%$hp') AND aktif=1";
                $ress  = $conn->query($sqls);
                $ns    = mysqli_num_rows($ress);
    
                //cek terdaftar sebagai calon siswa
                $sqlcs  = "SELECT * FROM jbsakad.calonsiswa WHERE (hpsiswa LIKE '%$hp' OR hportu LIKE '%$hp' OR info1 LIKE '%$hp' OR info2 LIKE '%$hp') AND aktif=1";
                $rescs  = $conn->query($sqlcs);
                $ncs    = mysqli_num_rows($rescs);
    
                if ($np+$ns+$ncs > 0) {
                    $balas      = "";
                    $title      = "";
                    $subtitle   = "";
                    $idl        = "";
                    
                    if ($np) {
                        while ($rowp = mysqli_fetch_assoc($resp)) {
                            $title      .= "," . hapuskoma($rowp['nama']);
                            $subtitle   .= ",Pegawai " . hapuskoma($rowp['nip']);
                            $idl        .= ",/keuangan#p#" . $rowp['replid'];
                        }
                    }
    
                    if ($ns) {
                        while ($rows = mysqli_fetch_assoc($ress)) {
                            $title      .= "," . hapuskoma($rows['nama']);
                            $subtitle   .= ",Siswa " . hapuskoma($rows['nis']);
                            $idl        .= ",/keuangan#s#" . $rows['replid'];
                        }
                    }
    
                    if ($ncs) {
                        while ($rowcs = mysqli_fetch_assoc($rescs)) {
                            $title      .= "," . hapuskoma($rowcs['nama']);
                            $subtitle   .= ",Calon Siswa " . hapuskoma($rowcs['nopendaftaran']);
                            $idl        .= ",/keuangan#cs#" . $rowcs['replid'];
                        }
                    }
    
                    $balas = "Silakan pilih nama berikut untuk melihat menu keuangan";
                    $buttonlist = "Pilih Nama";
                    $section = "Daftar Nama Terdaftar";
                    $title = substr($title,1);
                    $subtitle = substr($subtitle,1);
                    $idl = substr($idl,1);
    
                } else {
                    $balas = "Mohon maaf, nomor Anda tidak terdaftar di sistem kami sebagai pegawai, siswa atau ortunya, maupun calon siswa atau ortunya. Untuk menggunakan sistem kami, silakan menghubungi admin kami untuk melakukan pembaruan data!";
                }
            }else if(empty($keys[3])){
                $jm = $keys[1]; //jenis member
                $im = $keys[2]; // replid member

                if ($jm == 's') {
                    $sql = "SELECT * FROM jbsakad.siswa WHERE replid='$im'";
                    $res = $conn->query($sql);
                    if (mysqli_num_rows($res)>0) {
                        $row        = mysqli_fetch_assoc($res);
                        // $balas     = "Pilih laporan keuangan siswa yang ingin dilihat dari *".trim($row['nama'])."* (".$row['nis']."):";
                        // $button    = "Pembayaran Non-Tunai,Rekap Non-Tunai";
                        // $idb       = "$pesan#PNT,$pesan#RNT";
                        

                        $balas     = "Pilih laporan keuangan siswa yang ingin dilihat dari *".trim($row['nama'])."* (".$row['nis']."):";
                        $button    = "Iuran Wajib,Iuran Sukarela,Tabungan";
                        $idb       = "$pesan#JTT,$pesan#SKR,$pesan#TBG";
                        // sendbutton($wa, $balas1, $sekolah, $idb1, $button1, $base_url, $token);
                    }
                } else if ($jm == 'cs'){
                    $sql = "SELECT * FROM jbsakad.calonsiswa WHERE replid='$im'";
                    $res = $conn->query($sql);
                    if (mysqli_num_rows($res)>0) {
                        $row        = mysqli_fetch_assoc($res);
                        $balas      = "Pilih laporan keuangan calon siswa yang ingin dilihat dari *".trim($row['nama'])."* (".$row['nopendaftaran']."):";
                        $button     = "Iuran Wajib,Iuran Sukarela";
                        $idb        = "$pesan#CSWJB,$pesan#CSSKR";
                    }
                } else if ($jm == 'p'){
                    $sql = "SELECT * FROM jbssdm.pegawai WHERE replid='$im'";
                    $res = $conn->query($sql);
                    if (mysqli_num_rows($res)>0) {
                        $row        = mysqli_fetch_assoc($res);
                        $balas      = "Pilih laporan keuangan pegawai yang ingin dilihat dari *".trim($row['nama'])."* (".$row['nip']."):";
                        $button     = "Tabungan";
                        $idb        = "$pesan#TBG";
                    }
                }
                


            }else{
                $jm = $keys[1]; // jenis member
                $im = $keys[2]; // replid member
                $km = $keys[3]; // pilihan keuangan

                if ($jm == 's') {
                    // $sql = "SELECT * FROM jbsakad.siswa WHERE replid='$im'";
                    $sql = "SELECT s.nis, s.nama, s.tahunmasuk, a.angkatan, a.departemen, k.kelas, s.tmplahir, s.tgllahir, s.kelamin, s.alamatsiswa, t.tingkat, s.pinsiswa, s.namaayah, s.namaibu, s.hportu as hportu1, s.info1 as hportu2, s.info2 as hportu3, k.replid as idkelas FROM jbsakad.siswa s LEFT JOIN jbsakad.angkatan a ON a.replid=s.idangkatan LEFT JOIN jbsakad.kelas k ON k.replid=s.idkelas LEFT JOIN jbsakad.tingkat t ON t.replid=k.idtingkat WHERE k.aktif=1 AND s.aktif=1 AND a.aktif=1 AND t.aktif=1 AND s.replid='$im' ORDER BY tingkat";
                    $res = $conn->query($sql);
                    if (mysqli_num_rows($res)>0) {
                        $row        = mysqli_fetch_assoc($res);
                        $nis        = $row['nis'];
                        $nama       = $row['nama'];
                        $dept       = $row['departemen'];

                        //tahunbuku aktif
                        $sqltb = "SELECT * FROM jbsfina.tahunbuku WHERE aktif=1 AND departemen='$dept'";
                        $restb = $conn->query($sqltb);
                        if (mysqli_num_rows($restb) > 0) {
                            $rowtb = mysqli_fetch_assoc($restb);
                            $idtb  = $rowtb['replid'];
                            $nmtb  = $rowtb['tahunbuku']; 
                            if ($km == 'JTT' || $km == 'SKR') {
                                $sql        = "SELECT dp.replid as idpenerimaan, dp.nama as nama, kp.kategori as kategori FROM jbsfina.datapenerimaan dp LEFT JOIN jbsfina.kategoripenerimaan kp ON dp.idkategori=kp.kode WHERE dp.idkategori='$km' AND dp.departemen='$dept' AND dp.aktif=1";
                                $res1       = $conn->query($sql);
                                
                                if ($km == 'JTT') {
                                    $katp = "Iuran Wajib";
                                } else if($km == 'SKR') {
                                    $katp = "Iuran Sukarela";
                                }

                                $nPenerimaan = mysqli_num_rows($res1);
                                if ($nPenerimaan > 0) {
                                    $balas = "*Data Pembayaran $katp*\n$nis - $nama\n";
                                    while ($row1 = mysqli_fetch_assoc($res1)) {
                                        $idp = $row1['idpenerimaan'];
                                        $nmp = $row1['nama'];
                                        $ktp = $row1['kategori'];
        
                                        $sql = "SELECT * FROM jbsfina.besarjtt WHERE nis='$nis' AND idpenerimaan='$idp'";
                                        $res = $conn->query($sql);
        
                                        if (mysqli_num_rows($res) > 0) {
                                            $row2 = mysqli_fetch_assoc($res);
                                            $idbesarjtt = $row2['replid'];
                                            $balas .= "\n🗂️ *$nmp*\n";
                                            $balas .= "▪️ Total Kewajiban: " . rupiah($row2['besar']) . "\n";
                                            
                                            // cek datapenerimaanjtt
                                            $sql = "SELECT * FROM jbsfina.penerimaanjtt WHERE idbesarjtt='$idbesarjtt' ORDER BY tanggal DESC";
                                            $res2 = $conn->query($sql);
                                            
                                            $it3 = 1;
                                            $tb = 0;
                                            $td = 0;
                                            $rw = '';
                                            
                                            if (mysqli_num_rows($res2) > 0) {
                                               while ($row3 = mysqli_fetch_assoc($res2)) {
                                                   $rw .= "▪️▪️ " . $row3['tanggal'] . ' | Jml. ' . rupiah($row3['jumlah']) . ' | Disc. ' . rupiah($row3['info1']) . "\n";
                                                   $tb += $row3['jumlah'];
                                                   $td += $row3['info1'];
                                               }
                                               
                                            }else{
                                                $rw .= "Belum ada pembayaran yang dilakukan.";
                                            }
        
                                            $balas .= "▪️ Sudah Dibayar : ".rupiah($tb) . "\n";
                                            $balas .= "▪️ Diskon Diterima : ".rupiah($td) . "\n";
                                            $balas .= "▪️ Sisa Pembayaran : ".rupiah($row2['besar']-$td-$tb) . "\n";
                                            $balas .= "▪️ Riwayat Pembayaran : \n" . $rw . "\n";
                                            
                                        }else{
                                            $balas .= "\n🗂️ Belum ada data $nmp.\n";
                                        }
        
                                    }
        
                                }else{
                                    $balas = "Belum ada data penerimaan di kategori yang dipilih.";
                                    $button = "Kembali";
                                    $idb    = $keys[0].'#'.$keys[1].'#'.$keys[2];
                                }
                            } else if($km == 'TBG') {
                                $sqltab = "SELECT * FROM jbsfina.datatabungan WHERE departemen = '$dept' AND aktif=1 ORDER BY replid";
                                $restab = $conn->query($sqltab);
                                $ndt    = mysqli_num_rows($restab);
                                
                                if ($ndt > 0) {
                                    $balas = "*Laporan Tabungan*\n$nis - $nama\n";
                                    while ($row1 = mysqli_fetch_assoc($restab)) {
                                        $idtabungan = $row1['replid'];
                                        $nmtabungan = $row1['nama'];
                                        $balas .= "\n🗂️ *$nmtabungan*\n";
                                        $sql1 = "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d/%m/%Y %H:%i') as tanggal, p.keterangan, p.debet, p.kredit, p.petugas FROM jbsfina.tabungan p, jbsfina.jurnal j WHERE p.idjurnal = j.replid AND p.nis = '$nis' AND j.idtahunbuku='$idtb' AND p.idtabungan = '$idtabungan' ORDER BY p.replid DESC";
                                        $res1 = $conn->query($sql1);

                                        if (mysqli_num_rows($res1) > 0) {
                                            $setoranAkhir = 0;
                                            $tarikanAkhir = 0;
                                            $setoranTotal = 0;
                                            $tarikanTotal = 0;
                                            while ($row2 = mysqli_fetch_assoc($res1)) {
                                                if ($row2['debet'] >0) {
                                                    $balas .= "🔸 -" . rupiah($row2['debet']);
                                                    if ($tarikanAkhir == 0) {
                                                        $tarikanAkhir = $row2['debet'];
                                                    }
                                                    $tarikanTotal += $row2['debet'];
                                                } else if($row2['kredit'] > 0){
                                                    $balas .= "🔹 +" . rupiah($row2['kredit']);
                                                    if ($setoranAkhir == 0) {
                                                        $setoranAkhir = $row2['kredit'];
                                                    }
                                                    $setoranTotal += $row2['kredit'];
                                                }
                                                $balas .= " | " . $row2['tanggal'] . " | " . $row2['keterangan'] . "\n";
                                            }
                                            $balas .= "⬛ Saldo: " . rupiah($setoranTotal-$tarikanTotal) . "\n";
                                            $balas .= "🟩 Jml Setoran: " . rupiah($setoranTotal) . "\n";
                                            $balas .= "🟪 Jml Penarikan: " . rupiah($tarikanTotal) . "\n";
                                            $balas .= "🟨 Penarikan Terakhir: " . rupiah($tarikanAkhir) . "\n";
                                            $balas .= "🟦 Setoran Terakhir: " . rupiah($setoranAkhir) . "\n";
                                        } else {
                                            $balas .= "Belum ada transaksi tarik / setor tabungan.\n";
                                        }
                                        
                                    }
                                } else {
                                    $balas = "Belum ada jenis tabungan yang tersedia. $ndt";
                                }
                                
                            }
                        } else {
                            $balas = "Tahun buku belum diatur.";
                        }
                        

                        

                    }
                }else if($jm == 'cs'){
                    $balas = "Masih dalam pengembangan.";
                }else if($jm == 'p'){
                    $balas = "Masih dalam pengembangan.";
                }
            }
            

            
            break;

        case 'dev':
            $balas  = "Masih dalam tahap pengembangan.";
            break;

        case '/nilai':
            $balas  = "Silakan memilih menu laporan nilai di bawah ini!";
            $button = "Data Nilai Terakhir,Nilai Per Pelajaran,Nilai dari BCE";
            $idb    = "dev,dev,dev";
            break;

        case '/kehadiran':
            $balas  = "Silakan memilih menu laporan kehadiran di bawah ini!";
            $button = "Presensi Harian (FP),Presensi Kegiatan (FP)";
            $idb    = "dev,dev";
            break;
    
        default:
            # code...
            break;
    }

    if ($balas != "") {
        if ($button != "") {
            sendbutton($wa, $balas, $sekolah, $idb, $button, $base_url, $token);
        } else if ($buttonlist != "") {
            sendlist($wa,$balas,$buttonlist,$section,$title,$subtitle,$idl,$base_url,$token);
        } else {
            sendwa($wa, $balas, $base_url, $token);
        }
        
    }

    
    mysqli_close($conn);
}

