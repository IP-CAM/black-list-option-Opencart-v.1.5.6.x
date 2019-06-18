<?php
class ControllerMocoMsViber extends Controller {    
    
    private $apiKey ='48778ec81f27d1c0-d12e345d3177352c-40705c26d27a97bc';
    private $webhookUrl = 'https://www.xn--d1abkindgji.xn--j1amh/index.php?route=moco/ms/viber'; // <- PLACE-YOU-HTTPS-URL
    
    public function setup() {
        
        $url = 'https://chatapi.viber.com/pa/set_webhook';
        $jsonData='{ "auth_token": "'.$this->apiKey.'", "url": "'.$this->webhookUrl.'" }';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        var_dump($result);
        
    }
    
    public function index(){
        echo '1';
        $request = file_get_contents("php://input");
        $input = json_decode($request, true);
        if($input['event'] == 'webhook') {
          $webhook_response['status']=0;
          $webhook_response['status_message']="ok";
          $webhook_response['event_types']='delivered';
          echo json_encode($webhook_response);
          die;
        }
        else if($input['event'] == "subscribed") {
          // when a user subscribes to the public account
        }
        else if($input['event'] == "conversation_started"){
          // when a conversation is started
        }
        elseif($input['event'] == "message") {
          /* when a user message is received */
          $type = $input['message']['type']; //type of message received (text/picture)
          $text = $input['message']['text']; //actual message the user has sent
          $sender_id = $input['sender']['id']; //unique viber id of user who sent the message
          $sender_name = $input['sender']['name']; //name of the user who sent the message
          // here goes the data to send message back to the user
          $data['auth_token'] = $this->apiKey;
          $data['receiver'] = $sender_id;
          $data['text'] = "The message to send to user";
          $data['type'] = 'text';
          //here goes the curl to send data to user
          $ch = curl_init("https://chatapi.viber.com/pa/send_message ");
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
          $result = curl_exec($ch);
          var_dump($result);
        }
    }
    
}
