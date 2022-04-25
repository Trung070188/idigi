<?php
require '../../vendor/autoload.php';
require '../../lib/MyDB.php';
$_SERVER['SERVER_NAME'] = MyDB::readEnv('SOAP_SERVER_NAME', 'cms-v3.dreamlab.edu.vn');
$_SERVER['SERVER_PORT'] = 80;

function kiem_tra_mat_khau($mat_khau_trong_db, $mat_khau_user_gui_len)
{
    return password_verify($mat_khau_user_gui_len, $mat_khau_trong_db);
}

function generatePassword(){
    $seed = str_split('abcdefghijklmnopqrstuvwxyz'
        .'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    shuffle($seed);
    $rand = '';
    foreach (array_rand($seed, 4) as $k) $rand .= $seed[$k];

    $seed = str_split('0123456789');
    shuffle($seed);

    foreach (array_rand($seed, 4) as $k) $rand .= $seed[$k];

    return $rand;
}


$server = new nusoap_server();
$server->configureWSDL("Partner", "urn:Partner" );

function Test1() {
    return 'OK';
}

function ReceiveResponse($MOId, $Telco, $ServiceNum, $Phone, $Syntax, $EncryptedMessage, $Signature)
{
    if($MOId <= 0 || empty($Telco) || empty($ServiceNum) || empty($Phone) || empty($Syntax) || empty($EncryptedMessage) || empty($Signature))
    {
        // check param input empty reuturn text
        return "01|Param Invalid";
    }
    else
    {
        $MessageRequest = base64_decode($EncryptedMessage);
        $PrivateKey = '';
        $Signature = base64_encode(sha1($MOId . $ServiceNum . $Phone . strtolower($MessageRequest) . $PrivateKey, true));
        /**
         * 1. Check Signature = base64_encode(sha1(MOId + ServiceNum + Phone + strtolower(MessageRequest) + PrivateKey, true))
         * 		MessageRequest = base64_decode($EncryptedMessage);
         * 2. Decode base 64 EncryptedMessage
         * 3. return string Messages Respone
         */
        if ($Signature === $Signature) {

            return '00|Success';
        }

        return '01|Param Invalid';

    }
}
$server->register('Test1', array (), array("return" => "xsd:string"));

$server->register(
    "ReceiveResponse",
    array(
        "MOId"          => "xsd:int",
        "Telco"         => "xsd:string",
        "ServiceNum"    => "xsd:string",
        "Phone"         => "xsd:string",
        "Syntax"        => "xsd:string",
        "EncryptedMessage" => "xsd:string",
        "Signature"     => "xsd:string"
    ),
    array("return" => "xsd:string")
);

$rawPostData = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawPostData = file_get_contents('php://input');
}

$server->service($rawPostData);
