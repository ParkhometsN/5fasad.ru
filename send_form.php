<?php
header('Content-Type: application/json');

require_once('phpmailer/src/PHPMailer.php');
require_once('phpmailer/src/SMTP.php');
require_once('phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Функция отправки письма
function sendEmail($subject, $body) {
    $mail = new PHPMailer(true);
    $mail->CharSet = 'utf-8';
    
    try {
        // Настройки SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mofmails@gmail.com';
        $mail->Password = 'tsdzbhmemjyfjlkh';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        
        // Отправитель и получатель
        $mail->setFrom('mofmails@gmail.com', '5fasad.ru');
        $mail->addAddress('parkhometsnikita@gmail.com');
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        
        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}

// Обработка формы
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Получаем данные из формы
    $name = htmlspecialchars($_POST['name'] ?? '');
    $position = htmlspecialchars($_POST['position'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    
    // Проверяем обязательные поля
    if (empty($name) || empty($position) || empty($phone) || empty($email)) {
        $response['message'] = 'Заполните все обязательные поля';
        echo json_encode($response);
        exit;
    }
    
    // Формируем письмо
    $subject = 'Новое сообщение с сайта - Flavus';
    
    $body = "
        <h2>Новое сообщение с контактной формы</h2>
        <table style='border-collapse: collapse; width: 100%;'>
            <tr style='background-color: #f2f2f2;'>
                <th style='padding: 10px; border: 1px solid #ddd; text-align: left;'>Поле</th>
                <th style='padding: 10px; border: 1px solid #ddd; text-align: left;'>Значение</th>
            </tr>
            <tr>
                <td style='padding: 10px; border: 1px solid #ddd;'><strong>Имя</strong></td>
                <td style='padding: 10px; border: 1px solid #ddd;'>{$name}</td>
            </tr>
            <tr style='background-color: #f9f9f9;'>
                <td style='padding: 10px; border: 1px solid #ddd;'><strong>Должность</strong></td>
                <td style='padding: 10px; border: 1px solid #ddd;'>{$position}</td>
            </tr>
            <tr>
                <td style='padding: 10px; border: 1px solid #ddd;'><strong>Телефон</strong></td>
                <td style='padding: 10px; border: 1px solid #ddd;'>{$phone}</td>
            </tr>
            <tr style='background-color: #f9f9f9;'>
                <td style='padding: 10px; border: 1px solid #ddd;'><strong>Email</strong></td>
                <td style='padding: 10px; border: 1px solid #ddd;'>{$email}</td>
            </tr>
            <tr>
                <td style='padding: 10px; border: 1px solid #ddd;'><strong>Сообщение</strong></td>
                <td style='padding: 10px; border: 1px solid #ddd;'>{$message}</td>
            </tr>
        </table>
    ";
    
    // Отправляем
    if (sendEmail($subject, $body)) {
        $response['success'] = true;
        $response['message'] = 'Сообщение успешно отправлено!';
    } else {
        $response['message'] = 'Ошибка при отправке. Попробуйте позже.';
    }
    
} else {
    $response['message'] = 'Неверный метод запроса';
}

echo json_encode($response);
?>