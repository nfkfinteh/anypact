<?
function khfskjfhsdhfd()
{
	var_dump(error_get_last());
}

register_shutdown_function( 'khfskjfhsdhfd' );

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/Email.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/ArrayHelper.class.php';

function IsTestEnvironment()
{
    return false;
}

$Email = 'strelok-2007@mail.ru';
$Subj = 'Subject';
$Msg = 'Message';

if ( ArrayHelper::Value( $_REQUEST, 'PM' ) !== 'N' )
{
    echo '<h1>use phpMailer</h1>' . PHP_EOL;
    \Strelok\Classes\Helpers\Bitrix\Email\Email::Mail( $Email, $Subj, $Msg );
}
else
{
    echo '<h1>use mail()</h1>' . PHP_EOL;

    $headers = "From: NFKSBER.RU <test@nfksber.ru>\n";
    $headers .= "X-Mailer: PHP\n";                                 // mailer
    $headers .= "X-Priority: 1\n";                                 // Urgent message!
    $headers .= "Content-Type: text/html; charset=utf-8\n"; // Mime type

    mail($Email, $Subj, $Msg, $headers);
}

