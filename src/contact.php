<?php
declare(strict_types=1);

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
