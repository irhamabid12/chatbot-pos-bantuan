<?php
    use BotMan\BotMan\BotMan;
    use BotMan\BotMan\BotManFactory;
    use BotMan\BotMan\Drivers\DriverManager;
    use BotMan\Drivers\Telegram\TelegramDriver;
    
    require_once "vendor/autoload.php";
    require_once "database/config.php";
    require_once "models/user_interface.php";
    require_once "models/messageModel.php";

    $configs = [
        "telegram" => [
            "token" => file_get_contents("private/token.txt")
        ]
    ];

    DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);

    $botman = BotManFactory::create($configs);
    $botman->hears('/start', function (BotMan $bot) {
        $user = $bot->getUser();
        insertUserIfNecessary($user);   // Daftarkan user
        $bot->reply("Hai Kak " . $user->getFirstName() . " 😊 Selamat datang di PosBantuan, asisten virtual Pos Indonesia yang akan membantu Kamu memenuhi segala keluhan produk dan layanan Pos Indonesia.");
        $bot->reply("Kami informasikan pengecekan nomor resi dapat dilakukan dengan mengetik “Cek resi [input no. resi]”. Terimakasih Kakak 😊");
        $message = "Apabila membutuhkan pelayaanan lain silahkan ketikkan /Lainnya";
        $bot->reply($message);

    });


    $botman->hears("/lainnya", function (BotMan $bot) {
        insertUserIfNecessary($bot->getUser());
        $massage = "Silahkan ketik 'Menu [nomor menu]' nomor menu layanan sesuai yang anda butuhkan \n1. Lacak Status Pengaduan\n2. Ajukan Pengaduan";
        $bot->reply($massage);
    });

    
    $botman->hears('Menu ([0-9]+)', function (BotMan $bot, $message) {
        insertUserIfNecessary($bot->getUser());
        $bot->reply(getResponse($bot->getUser(), $message));
    });

    $botman->hears('cek resi {id}', function ($bot, $id) {
        insertUserIfNecessary($bot->getUser());
        $bot->reply(getResi($bot->getUser(), $id));
    });

    $botman->hears('Cek Pengaduan {id}', function ($bot, $id) {
        insertUserIfNecessary($bot->getUser());
        $bot->reply(getStatus($bot->getUser(), $id));
    });

    $botman->hears('Pengaduan ([0-9]+)', function (BotMan $bot, $idPengaduan) {
        insertUserIfNecessary($bot->getUser());
        // getComplaint($bot->getUser(), $idPengaduan);
        $message = "Selamat datang di Customer Service Pos Indonesia.". PHP_EOL . PHP_EOL;
        $message .= "Untuk dapat kami tindak lanjuti mohon dapat mengirimkan data sesuai dengan format berikut:" . PHP_EOL . PHP_EOL;
        $message .= "# $idPengaduan". PHP_EOL;      
        $message .= "# Nama_Lengkap". PHP_EOL;
        $message .= "# Email". PHP_EOL;
        $message .= "# Nomor_Telp". PHP_EOL;
        $message .= "# Nomor_resi". PHP_EOL;
        $message .= "# Jenis_Produk". PHP_EOL;
        $message .= "# Permasalahan". PHP_EOL . PHP_EOL;

        $message .="Terima Kasih 😊";
        $bot->reply($message);
        // $bot->reply("Apakah data yang anda kirimkan sudah benar dan sesuai format? (/Ya|/Tidak)");
    });

    $botman->hears('#{message}', function ($bot, $idPengaduan){
        insertUserIfNecessary($bot->getUser());
        $message=$bot->getMessage()->getText();
        $bot->reply(getComplaint($bot->getUser(), $idPengaduan, $message));
    });

    $botman->hears('/ya', function ($bot){
        insertUserIfNecessary($bot->getUser());
        $message = "Tingkat kepuasan layanan Customer Service:". PHP_EOL; 
        $message .= "5 = Sangat puas". PHP_EOL; 
        $message .= "1 = Sangat tidak puas". PHP_EOL. PHP_EOL;
        $message .= "Ketik jawaban Anda dengan angka 1 s.d. 5"; 
        $bot->reply($message);
    });
    
    $botman->hears('([1-5]+)', function ($bot, $penilaian){
        insertUserIfNecessary($bot->getUser());
        $closing = "Anda bisa menyapa saya kapan pun. Semoga hari Anda menyenangkan.😊";

        $bot->reply(feedback($bot->getUser(), $penilaian));
        $bot->reply($closing);
    });

    $botman->hears('>{message}', function ($bot, $kritikSaran){
        insertUserIfNecessary($bot->getUser());
        // $message=$bot->getMessage()->getText();
        $bot->reply(kritikSaran($bot->getUser(), $kritikSaran));
    });
    
    $botman->fallback(function (BotMan $bot) {
        insertUserIfNecessary($bot->getUser());
        $message  = "Maaf format pesan " . $bot->getMessage()->getText(). " tidak diketahui" . PHP_EOL . PHP_EOL;
        // $message .= "Mungkin anda kurang input argumen perintah? Cek /help atau /help@api_2020_bot";
        $bot->reply($message);
    });    
      
    $botman->listen();
?>