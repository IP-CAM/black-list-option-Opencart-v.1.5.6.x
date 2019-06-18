<?php
class ControllerMocoMail extends Controller {  
    
    public function index() {
        
        
        
    }
    
    public function send($data) {
        
        $this->load->language('common/one_click_order');
        $t = ($this->language->data);
        
        $data["text"] = ""
                . "Запит клієнта на товар!!!"
                . "<br>"
                . "Ім'я: {$data["name"]}"
                . "<br>"
                . "lot: {$data["product_id"]}"
                . "<br>"
                . "телефон: {$data["phone"]}"
                . "<br>"
                . "email: {$data["email"]}"
                . "<br>"
                . "Страхова сума: {$data["sum"]}"
                . "<br>"
                . "Місто: {$data["city"]}"
                . "<br>"
                . "Перевізник: {$data["delivery"]}"
                . "<br>"
                . "№ складу: {$data["delivery_n"]}"
                . "<br>"
                . "П.І.Б. одержувача: {$data["full_name"]}"
                . "<br>"
                . "Тара: {$data["tara"]}"
                . "<br>"
                . "Опис:"
                . "<br>"
                . "{$data["discription"]}"
                . "<br>";
        
        $data["text"] .= $data["url"]. "<br>";
        
        foreach ($data["images"] as $img) {
            $data["text"] .= '<img width="400px" height="400px" src="'.$img["image"].'"/><br>';
        }
        
        
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        
        try {
            
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = $this->config->get('config_mail_smtp_hostname');
            $mail->SMTPAuth = true;
            $mail->Username = $this->config->get('config_mail_smtp_username');
            $mail->Password = $this->config->get('config_mail_smtp_password');
            $mail->SMTPSecure = 'tls';
            $mail->Port = $this->config->get('config_mail_smtp_port');
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom($this->config->get('config_mail_smtp_username'), 'Manager');
            $mail->addAddress($data["email"]);
            $mail->addReplyTo($this->config->get('config_mail_smtp_username'), 'Information');
            $mail->addCC($data["email"]);
            $mail->addBCC($this->config->get('config_mail_smtp_username'));
            
            $mail->isHTML(true);
            $mail->Subject = $t['text_title1'].$data["order_namber"];
            $mail->Body    = $data["text"];
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
            $mail->send();
            echo 'Message has been sent';            
        } catch (Exception $ex) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
        
    }
    
    public function sendTest() {
               
        
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        
        try {
            
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'plcarallegro@gmail.com';
            $mail->Password = 'WPt9Zridjxrf146VOka';
            //$mail->SMTPSecure = 'ssl';
            $mail->Port = '587';
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom('plcarallegro@gmail.com', 'Manager');
            $mail->addAddress('stas.stecenko@ukr.net');
            
            $mail->isHTML(true);
            $mail->Subject = 'product $54';
            $mail->Body    = 'product $541';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
            $mail->send();
            echo 'Message has been sent';            
        } catch (Exception $ex) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
	        
    }
    
    public function sendTest1() {
	    
	$mail = new Mail($this->config->get('config_mail_engine'));
	$mail->parameter = $this->config->get('config_mail_parameter');
	$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
	$mail->smtp_username = $this->config->get('config_mail_smtp_username');
	$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
	$mail->smtp_port = $this->config->get('config_mail_smtp_port');
	$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
	$mail->setTo($this->config->get('config_email')); //почта куда, как правило админ магазина
	$mail->setFrom($this->config->get('config_mail_smtp_username')); //почта от кого, сюда идет логин в smtp
	$mail->setSender(html_entity_decode('stas.stecenko@ukr.net', ENT_QUOTES, 'UTF-8')); //это почта от кого придет письмо
	$mail->setReplyTo('stas.stecenko@ukr.net'); //почта куда будет идти ответ в случае ответа на письмо

	$mail->setText('dfgfdg');
	$mail->send();
        
    }
    
    
}

