
<?php
require_once __DIR__ . '/vendor/autoload.php';


define('APPLICATION_NAME', 'Gmail API PHP Quickstart');
define('CREDENTIALS_PATH', '~/.credentials/gmail-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/gmail-php-quickstart.json
define('SCOPES', implode(' ', array(
  Google_Service_Gmail::GMAIL_READONLY)
));

if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}

$credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
echo $credentialsPath."\n";
if (file_exists($credentialsPath)) {
  $accessToken = json_decode(file_get_contents($credentialsPath), true);
  echo $accessToken."\n";
} else {
  if(!file_exists(dirname($credentialsPath))) {
    //mkdir(dirname($credentialsPath), 0700, true);
echo "no existe el archivo";
  }
  //file_put_contents($credentialsPath, json_encode($accessToken));
  //printf("Credentials saved to %s\n", $credentialsPath);
}


function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
  }
  return str_replace('~', realpath($homeDirectory), $path);
}




