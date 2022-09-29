<?php
date_default_timezone_set('Asia/Tehran');

error_reporting(E_ALL);

ini_set('display_errors', '1');
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');
ini_set('display_startup_errors', '1');

if (!is_dir('photo')) {
    mkdir('photo');
}

if (!is_dir('files')) {
    mkdir('files');
}

if (!file_exists('files/data.json')) {
    file_put_contents('files/data.json', '{"timename1":{"on":"off"},"timename2":{"on":"off"}');
}

if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}


include 'madeline.php';

use \danog\MadelineProto\API;
use \danog\Loop\Generic\GenericLoop;
use \danog\MadelineProto\EventHandler;
use \danog\MadelineProto\Shutdown;

class XHandler extends EventHandler
{
    const Report = 'Log_mi_el';

    public function getReportPeers() {
        return [self::Report];
    }
    public function genLoop() {

        @$data = json_decode(file_get_contents("files/data.json"), true);

        //=====================================================================//

        if($data['timename1']['on'] == "on"){

            date_default_timezone_set('Asia/Tehran');
            $fonts =  [["𝟎", "𝟏", "𝟐", "𝟑", "𝟒", "𝟓", "𝟔", "𝟕", "𝟖", "𝟗"],["𝟎", "𝟏", "𝟐", "𝟑", "𝟒", "𝟓", "𝟔", "𝟕", "𝟖", "𝟗"]];
            $time2 = str_replace(range(0, 9), $fonts[array_rand($fonts)], date("H:i"));

            yield $this->account->updateProfile(['last_name' => " $time2 "]);

        }

        if($data['timename2']['on'] == "on"){

            date_default_timezone_set('Asia/Tehran');
            $fonts = [["𝟎", "𝟏", "𝟐", "𝟑", "𝟒", "𝟓", "𝟔", "𝟕", "𝟖", "𝟗"],["𝟎", "𝟏", "𝟐", "𝟑", "𝟒", "𝟓", "𝟔", "𝟕", "𝟖", "𝟗"]];
            $time2 = str_replace(range(0, 9), $fonts[array_rand($fonts)], date("H:i"));
            $Emoji2 = ["❤️","🧡","💛","💚","💙","💜","🤍","🤎","❤️‍🔥","❤️‍🩹","❣️","💕","💞","💓","💗","💖","💘","💝","♥️","✨","🌚","☄️","💥","🔥","🌈","❄️","💫","🗿","🤖","👾","🫀","🐙","🍀","🪐","☃️","🥀","🦧","🐚","🪨","🍓","🍫","🥂","🗽","🎠","🌋","🎡","🎢","⛲️","🏰","📡","💡","💎","🧯","💸","⚙️","⛓","🦠","🧸","🪄","🪅","🔗","🖇","📍","⚠️","🃏","🏴‍☠"];
            $Emoji1 = array_rand($Emoji2);
            $Emoji = $Emoji2[$Emoji1];

            yield $this->account->updateProfile(['last_name' => " $time2 $Emoji"]);

        }

        //=====================================================================//


        return 60000;
    }
    public function onStart() {
        $genLoop = new GenericLoop([$this, 'genLoop'], 'update Status');
        $genLoop->start();
    }

    public function onUpdateNewChannelMessage($update) {
        yield $this->onUpdateNewMessage($update);
    }

    public function onUpdateNewMessage($update) {
        if (time() - $update['message']['date'] > 2) {
            return;
        }
        try {



            //============================================//

            $me         =  yield $this->getSelf();
            $admin      =  $me['id'];
            @$data      =  json_decode(file_get_contents("files/data.json"), true);
            $userID     =  $update['message']['from_id']['user_id']?? 0;
            $msg        =  isset( $update[ 'message' ][ 'message' ] ) ? $update[ 'message' ][ 'message' ] : '';
            $chatID     =  yield $this->getID($update);
            $msg_id     =  $update['message']['id']?? 0;
            $replyToId  =  $update['message']['reply_to']['reply_to_msg_id']?? 0;
            $info       =  yield $this->getInfo($update);
            $AAA        =  $info[ 'bot_api_id' ];
            $type2      =  $info['type'];
            $messageId  =  $update['message']['id'] ?? 0;

            //============================================//

            if ($userID == $admin) {

                if (preg_match("/^[\/\#\!]?(timename1) (on|off)$/i", $msg)) {

                    preg_match("/^[\/\#\!]?(timename1) (on|off)$/i", $msg, $m);
                    $data['timename1']['on'] = "$m[2]";
                    file_put_contents("files/data.json", json_encode($data));
                    yield $this->messages->editMessage(['peer' => $chatID, 'id' => $messageId, 'message' => "❗️ Ok Time Name 1 is $m[2] ✔"]);

                }
                if (preg_match("/^[\/\#\!]?(timename2) (on|off)$/i", $msg)) {

                    preg_match("/^[\/\#\!]?(timename2) (on|off)$/i", $msg, $m);
                    $data['timename2']['on'] = "$m[2]";
                    file_put_contents("files/data.json", json_encode($data));
                    yield $this->messages->editMessage(['peer' => $chatID, 'id' => $messageId, 'message' => "❗️ Ok Time Name 2 is $m[2] ✔"]);

                }
            }

        } catch (\Throwable $e) {
            yield $this->report("Surfaced: $e");
        }
    }

}


$settings['db']['type'] = 'memory';

$settings = [

    'serialization' => [
        'cleanup_before_serialization' => true,
    ],

    'logger'        => [
        'max_size' => 1*1024*1024,
    ],

    'peer'          => [
        'full_fetch'  => false,
        'cache_all_peers_on_startup' => false,
    ],

    'app_info'      => [
        'api_id'      => 9230109,
        'api_hash'    => "9a2e3fc99d4e19a4705fb4df295cf082"
    ],

];

$bot = new \danog\MadelineProto\API('X.session', $settings);
$bot->startAndLoop(XHandler::class);

?>