<?php

use BotMan\BotMan\Interfaces\UserInterface;

if(mysqli_connect_error()){
    error_log("Error : coult not connect to database");
    exit;
}
/**
 * Fungsi ini mengambil response dari database berdasarkan pesan dari pengguna
 * Fungsi ini juga mencatat berapa kali suatu pengguna menggunakan suatu pesan
 */
function getResponse(UserInterface $user, $message)
{
    // Ambil message
    $queryMessage = "SELECT * FROM messages_response WHERE message = '$message' LIMIT 1";
    global $conn; 
    $resultMessage = $conn->query($queryMessage)->fetch_row();

    if ($resultMessage == null):
        $message = "Maaf Menu $message tidak dikenali";
        return $message;
    endif;

    return $resultMessage[2]; // Kolom response
}

function getStatus(UserInterface $user, $id)
{
    //ubah timezone menjadi jakarta
    date_default_timezone_set("Asia/Jakarta");

    //ambil jam dan menit
    $jam = date('H:i');

    //atur salam menggunakan IF
    if ($jam > '00:01' && $jam < '10:00') {
        $salam = 'Pagi';
    } elseif ($jam >= '10:00' && $jam < '15:00') {
        $salam = 'Siang';
    } elseif ($jam < '18:00') {
        $salam = 'Sore';
    } else {
        $salam = 'Malam';
    }

    // Ambil message
    $queryMessage = "SELECT * FROM data_pengaduan WHERE id_Pengaduan = '$id' LIMIT 1";
    global $conn; 
    $resultMessage = $conn->query($queryMessage)->fetch_row();

    if ($resultMessage == null){
        $message = "Selamat $salam,". PHP_EOL . PHP_EOL;
        $message .= "Mohon maaf, setelah dilakukan pengecekan data, nomor pengaduan $id di data pengaduan kami tidak ditemukan.". PHP_EOL . PHP_EOL;
        $message .= "Terima Kasih 😊". PHP_EOL;
        $message .= "Customer Service Pos Indonesia";

        return $message; 
    }else{
        $message = "Selamat $salam,". PHP_EOL . PHP_EOL;
        $message .= "Setelah dilakukan pengecekan data, progres pengaduan dengan nomor pengaduan $id saat ini pada tahap $resultMessage[13]". PHP_EOL . PHP_EOL;
        $message .= "Terima Kasih 😊". PHP_EOL;
        $message .= "Customer Service Pos Indonesia";

        return $message;

    }
}
function getResi(UserInterface $user, $id){
    $url = 'https://api.binderbyte.com/v1/track?api_key=4274887b56b5a86804ad66344b8b9bb4dbefc66f0d648e36faf8b0f837c58bee&courier=pos&awb='.$id;
    // persiapkan curl
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, $url);

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // mengembalikan hasil curl
    $profile = json_decode($output, TRUE);
    $awb = $profile['data']['summary']['awb'];
    $tanggal = $profile['data']['summary']['date'];
    $status = $profile['data']['summary']['status'];
    $asal = $profile['data']['detail']['origin'];
    $pengirim = $profile['data']['detail']['shipper'];
    $tujuan = $profile['data']['detail']['destination'];
    $penerima = $profile['data']['detail']['receiver'];
    $do = $profile['data']['history'];
    foreach($do as $d){
        $date = $d['date'];
        $desc = $d['desc'];
        $location = $d['location'];
    }

    $data1 = "No Tracking:$awb". PHP_EOL;
    $data1 .= "Status:$status". PHP_EOL;
    $data1 .= "Tanggal Kirim:$tanggal". PHP_EOL;
    $data1 .= "Kota Kirim:$asal". PHP_EOL;
    $data1 .= "Pengirim:$pengirim". PHP_EOL;
    $data1 .= "Kota Tujuan:$tujuan". PHP_EOL;
    $data1 .= "Penerima:$penerima". PHP_EOL. PHP_EOL;

    return "$data1 $date" . PHP_EOL. "$desc" . PHP_EOL ."$location";
}

function getComplaint(UserInterface $user, $idPengaduan, $message){
    global $conn;
    if($idPengaduan >4):
        $message = 'Maaf Pengaduan' .$idPengaduan. 'tidak dikenali.';
        return $message;
    endif;

    $id       = $user->getId(); //user id 
    $queryCheckComplaint = "SELECT * FROM data_pengaduan ORDER BY id_Pengaduan DESC LIMIT 1";
    $getQueryId = $conn->query($queryCheckComplaint)->fetch_array();
    $getId = intval($getQueryId[0])+1;

    //mengubah id pengaduan menjadi string
    if ($idPengaduan == 1) {
        $idPengaduan='PERMINTAAN DATA/BERITA ACARA';
    }elseif($idPengaduan == 2){
        $idPengaduan='KETERLAMBATAN/BELUM TERIMA';
    }elseif($idPengaduan == 3){
        $idPengaduan ='KEHILANGAN';
    }else{
        $idPengaduan ='KERUSAKAN';
    }

    //mengubah kalimat menjadi array
    $complaintValue = "$message";
    // $arr_complaintValue = explode ("# ",$complaintValue);
    $arr_complaintValue = explode ("#",$complaintValue);
    
    //$arr_complaintValue[2] nama pelanggan
    //$arr_complaintValue[3] email pelanggan
    //$arr_complaintValue[4] hp pelanggan 
    //$arr_complaintValue[5] resi
    //$arr_complaintValue[6] jenis produk
    //$arr_complaintValue[7] isi pengaduan

    //insert ke database
    $queryInsert = "INSERT INTO data_pengaduan VALUES ('', 
                                                    '$id',  
                                                    '$arr_complaintValue[2]', 
                                                    '$arr_complaintValue[3]',
                                                    '$arr_complaintValue[4]',
                                                    '$idPengaduan',
                                                    '$arr_complaintValue[6]',
                                                    '$arr_complaintValue[5]',
                                                    'Telegram',
                                                    '',
                                                    '',
                                                    '$arr_complaintValue[7]',
                                                    '',
                                                    'BELUM SELESAI',
                                                    'Pelanggan',
                                                    '',
                                                     current_timestamp(), 
                                                     '')";
    $conn->query($queryInsert);

    $message = "Pengaduan anda sudah tercatat. Anda masih dalam antrian, mohon menunggu. Admin kami akan segera merespon pengaduan anda 😊". PHP_EOL . PHP_EOL;
    $message .= "Nomor tiket pengaduan anda adalah $getId".PHP_EOL;
    $message .= "Terima Kasih."; //pesan return

    return $message;
}
function feedback(UserInterface $user, $penilaian){
    global $conn;
    $id_user      = $user->getId();
    $queryInsert = "INSERT INTO tingkat_kepuasan VALUES ('', '$id_user', '$penilaian', '', current_timestamp(), '')";
    $conn->query($queryInsert);

    $message = "Bagaimana kualitas layanan Customer Service dalam memenuhi permintaan Anda?". PHP_EOL . PHP_EOL;
    $message .= "Silakan tulis komentar Anda.". PHP_EOL;
    $message .= "Format: >[komentar]";
    
    return $message;
}

function kritikSaran(UserInterface $user, $kritikSaran){
    global $conn;
    $id_user       = $user->getId();
    $addKritik = "UPDATE tingkat_kepuasan SET kritik_saran = '$kritikSaran', updated_at = current_timestamp() WHERE user_id = $id_user AND kritik_saran = '' ORDER BY id DESC LIMIT 1";
    $conn->query($addKritik);

    return;

}


    