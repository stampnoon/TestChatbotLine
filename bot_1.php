<?

define('LINE_MESSAGE_CHANNEL_ID', '1653962481');
define('LINE_MESSAGE_CHANNEL_SECRET', '352b37a31c81d1582085b1a8e9fc45b4');
define('LINE_MESSAGE_ACCESS_TOKEN', 'WG9rcw4u5ldT0WfwnQX87VL9nDUmRD721vz7gxWO8p6fpo24w62h06gjHU5k+ev4FuukNJY4G3rD2luz05Y+otzmtIZGhXDnJbJSNiaHr9W7As/kJALpLhVl8QLBgpK86g2gZRsPQ/wqjdhvULFbzgdB04t89/1O/w1cDnyilFU=');

// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include composer autoload
require_once 'vendor/autoload.php';


// การตั้งเกี่ยวกับ bot

// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");

///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;

$pushResponse = NULL;

// เชื่อมต่อกับ LINE Messaging API
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));

// คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
$content = file_get_contents('php://input');

// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
$events = json_decode($content, true);
if (!is_null($events)) {
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $typeMessage = $events['events'][0]['message']['type'];
    $userMessage = $events['events'][0]['message']['text'];
    $id = $events['events'][0]['source']['userId'];

    $userMessage = strtolower($userMessage);
    $token = strval($replyToken);
    switch ($typeMessage) {
        case 'text':
            switch ($userMessage) {
                case "1":
                    $textReplyMessage = '111111111111111111111111111';
                    $replyData = new TextMessageBuilder($textReplyMessage);
                    break;
                case "2":
                    $textReplyMessage = '222222222222222222222222222';
                    $replyData = new TextMessageBuilder($textReplyMessage);
                    break;
                case "3":
                    $responseProfile = $bot->getProfile($id);
                    $profile = $responseProfile->getJSONDecodedBody();
                    $textReplyMessage = $profile['displayName']; //can get 'displayName', 'userId', 'pictureUrl', 'statusMessage'
                    $replyData = new TextMessageBuilder($textReplyMessage);
                    break;
                case "4":
                    $pushResponse = 'Push';
                    $textReplyMessage = '44444444444'; 
                    $replyData = new TextMessageBuilder($textReplyMessage);
                    //$response = $bot->replyMessage($replyToken, $replyData);
                    $response = $bot->pushMessage('stampnight', $replyData);
                    break;
                case "เริ่ม":
                    $imageMain = 'https://www.pic2free.com/uploads/20200311/0f2a99163fd6712f73d04da793c78d13e13e6f7a.png?_ignore=';
                    $replyData = new ImagemapMessageBuilder(
                        $imageMain,
                        'test',
                        new BaseSizeBuilder(400, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'สอบถาม',
                                new AreaBuilder(4, 113, 337, 281)
                            ),
                            new ImagemapMessageActionBuilder(
                                'สมัคร',
                                new AreaBuilder(348, 112, 340, 283)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ติดต่อ',
                                new AreaBuilder(693, 111, 338, 283)
                            ),
                        )
                    );
                    break;
            }
            break;
        default:
            $textReplyMessage = json_encode($events);
            $replyData = new TextMessageBuilder($textReplyMessage);
            break;
    }
    //Response message
    //if (is_null($pushResponse)) {
        $response = $bot->replyMessage($replyToken, $replyData);
    //}
    // else
    // {
    //     $response = $bot->pushMessage('stampnight', new TextMessageBuilder('Push success'));
    // }
}
//l ส่วนของคำสั่งตอบกลับข้อความ


/*
switch ($userMessage) {
  case "t":
      $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ";
      $replyData = new TextMessageBuilder($textReplyMessage);
      $response = $bot->replyMessage($replyToken,$replyData);
      break;
  case "i":
      $picFullSize = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower';
      $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower/240';
      $replyData = new ImageMessageBuilder($picFullSize,$picThumbnail);
      $response = $bot->replyMessage($replyToken,$replyData);
      break;
  case "v":
      $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/240';
      $videoUrl = "https://www.mywebsite.com/simplevideo.mp4";                
      $replyData = new VideoMessageBuilder($videoUrl,$picThumbnail);
      $response = $bot->replyMessage($replyToken,$replyData);
      break;
  case "a":
      $audioUrl = "https://www.mywebsite.com/simpleaudio.mp3";
      $replyData = new AudioMessageBuilder($audioUrl,27000);
      break;
  case "l":
      $placeName = "ที่ตั้งร้าน";
      $placeAddress = "แขวง พลับพลา เขต วังทองหลาง กรุงเทพมหานคร ประเทศไทย";
      $latitude = 13.780401863217657;
      $longitude = 100.61141967773438;
      $replyData = new LocationMessageBuilder($placeName, $placeAddress, $latitude ,$longitude);              
      break;
  case "s":
      $stickerID = 22;
      $packageID = 2;
      $replyData = new StickerMessageBuilder($packageID,$stickerID);
      break;      
  case "im":
      $imageMapUrl = 'https://www.mywebsite.com/imgsrc/photos/w/sampleimagemap';
      $replyData = new ImagemapMessageBuilder(
          $imageMapUrl,
          'This is Title',
          new BaseSizeBuilder(699,1040),
          array(
              new ImagemapMessageActionBuilder(
                  'test image map',
                  new AreaBuilder(0,0,520,699)
                  ),
              new ImagemapUriActionBuilder(
                  'http://www.ninenik.com',
                  new AreaBuilder(520,0,520,699)
                  )
          )); 
      break;          
  case "tm":
      $replyData = new TemplateMessageBuilder('Confirm Template',
          new ConfirmTemplateBuilder(
                  'Confirm template builder',
                  array(
                      new MessageTemplateActionBuilder(
                          'Yes',
                          'Text Yes'
                      ),
                      new MessageTemplateActionBuilder(
                          'No',
                          'Text NO'
                      )
                  )
          )
      );
      break;                                                                                                                          
  default:
      $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
      $replyData = new TextMessageBuilder($textReplyMessage);         
      break;                                      
}*/
