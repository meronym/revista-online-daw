<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/phpmailer/Exception.php';
require __DIR__ . '/../vendor/phpmailer/PHPMailer.php';
require __DIR__ . '/../vendor/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

// Formularele publice pot fi trimise si de un script, nu doar din browser
// deci verificam raspunsul reCAPTCHA aici pe server
function recaptchaOk(): bool
{
    $secret = getenv('RECAPTCHA_SECRET_KEY') ?: '';
    $answer = $_POST['g-recaptcha-response'] ?? '';

    if ($secret === '' || $answer === '') {
        return false;
    }

    $result = file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify'
        . '?secret=' . urlencode($secret)
        . '&response=' . urlencode($answer)
    );

    return ($result !== false) && (json_decode($result, true)['success'] ?? false) === true;
}

function recaptchaSiteKey(): string
{
    return getenv('RECAPTCHA_SITE_KEY') ?: '';
}

function validateMessage(array $input): array
{
    $errors = [];

    if ($input['nume'] === '') {
        $errors['nume'] = 'Numele este obligatoriu.';
    }

    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Adresa de email nu este validă.';
    }

    if ($input['continut'] === '') {
        $errors['continut'] = 'Mesajul este obligatoriu.';
    }

    return $errors;
}


// Toate mesajele pleaca prin acelasi cont SMTP
function mailer(): PHPMailer
{
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = (string) getenv('SMTP_HOST');
    $mail->Port = (int) getenv('SMTP_PORT');
    $mail->SMTPAuth = true;
    $mail->Username = (string) getenv('SMTP_USER');
    $mail->Password = (string) getenv('SMTP_PASS');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom((string) getenv('SMTP_FROM'), 'Revistă Online');

    return $mail;
}

// Mesajul e deja in DB cand ajungem aici, deci o eroare de SMTP nu il pierde
// scriem eroarea in log si lasam formularul sa confirme trimiterea
function sendMail(PHPMailer $mail): void
{
    if (!$mail->send()) {
        error_log('Email netrimis: ' . $mail->ErrorInfo);
    }
}

// Doua mesaje: unul catre redactie, altul de confirmare catre expeditor
function sendContactEmails(array $input): void
{
    $notice = mailer();
    $notice->addAddress((string) getenv('CONTACT_EMAIL'));
    // Raspunsul pleaca direct catre cel care a completat formularul
    $notice->addReplyTo($input['email'], $input['nume']);
    $notice->Subject = 'Mesaj nou din formularul de contact';
    $notice->Body = "Nume: {$input['nume']}\n"
        . "Email: {$input['email']}\n"
        . 'Telefon: ' . ($input['telefon'] ?? '-') . "\n\n"
        . $input['continut'];

    sendMail($notice);

    // Confirmarea nu reia textul primit: formularul e public, iar mesajul ar
    // pleca astfel, nefiltrat, catre orice adresa completata acolo
    $confirmation = mailer();
    $confirmation->addAddress($input['email'], $input['nume']);
    $confirmation->Subject = 'Am primit mesajul tău';
    $confirmation->Body = "Bună, {$input['nume']},\n\n"
        . "Am primit mesajul trimis prin formularul de contact și îți răspundem cât putem de repede.\n\n"
        . 'Revistă Online';

    sendMail($confirmation);
}
