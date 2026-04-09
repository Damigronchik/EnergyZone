<?php
class MailSender {
    
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
    }
    
    /**
     * Отправка письма с формы обратной связи
     * 
     * @param string $user_email Email пользователя
     * @param string $message Текст сообщения
     * @return array ['status' => 'success/error', 'message' => '...']
     */
    public function sendFeedback($user_email, $message) {
        try {
            // Настройки SMTP
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.yandex.ru';
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = 'your-email@yandex.ru';
            $this->mail->Password   = 'your-password';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port       = 465;
            
            // Отправитель и получатель
            $this->mail->setFrom('your-email@yandex.ru', 'Фитнес-центр');
            $this->mail->addAddress('admin@fitnes-centr.ru', 'Администратор');
            $this->mail->addReplyTo($user_email, 'Пользователь');
            
            // Содержимое письма
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Subject = 'Новое сообщение с сайта';
            $this->mail->Body    = "
                <h2>Новое сообщение с сайта</h2>
                <p><strong>Email:</strong> {$user_email}</p>
                <p><strong>Сообщение:</strong></p>
                <p>{$message}</p>
            ";
            $this->mail->AltBody = "Email: {$user_email}\nСообщение: {$message}";
            
            $this->mail->send();
            return ['status' => 'success', 'message' => 'Сообщение отправлено'];
            
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $this->mail->ErrorInfo];
        }
    }
}