<?php

error_reporting(E_ALL ^ E_WARNING);

require_once('OwgSocket.php');
require_once('OwgApi.php');
require_once('OwgHelper.php');

$username   = getenv('OWG_APP_USERNAME');
$password   = getenv('OWG_APP_PASSWORD');
$serial     = getenv('OWG_G32_SERIAL');
$pop        = getenv('OWG_G32_POP');
$dataDir    = getenv('OWG_DATA_DIR');

$helper    = new OwgHelper($dataDir);
$owgApi    = new OwgApi($username, $password, $helper);
$owgSocket = new OwgSocket();

$accessToken = $owgApi->getAccessToken();
$helper->dLog('SUCCESS: authentication-token received','SUCCESS');
$socket = $owgSocket->initSocket($serial,$pop,$accessToken);
$helper->dLog('SUCCESS: socket was initialized, listening for data...', 'SUCCESS');
while(1) {
    while ($data = socket_read($socket, 240)) {
        $temperatureHash = $helper->processResult($data);
        $helper->saveData($temperatureHash);
    }
}
