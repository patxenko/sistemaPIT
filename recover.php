<?php
require_once __DIR__ . '/quikstart.php';
include_once('functions.php');
// Recoge el API client y construye el objeto del servicio.
$client = getClient();
$service = new Google_Service_Gmail($client);

$user = 'me';
echo "\n";
//$results = $service->users_messages->listUsersMessages($user);
$search='category:primary';
$list = $service->users_messages->listUsersMessages('me',['maxResults' => 100, 'q' => $search]);
$messageList = $list->getMessages();
$inboxMessage = [];
foreach($messageList as $mlist){
    $optParamsGet2['format'] = 'full';
    $single_message = $service->users_messages->get('me',$mlist->id, $optParamsGet2);
    $message_id = $mlist->id;
    $headers = $single_message->getPayload()->getHeaders();
    $snippet = $single_message->getSnippet();
    foreach($headers as $single) {

       if ($single->getName() == 'Subject') {
           $message_subject = $single->getValue();
       }
       else if ($single->getName() == 'Date') {
           $message_date = $single->getValue();
           $message_date = date('M jS Y h:i A', strtotime($message_date));
       }
       else if ($single->getName() == 'From') {
           $message_sender = $single->getValue();
           $message_sender = str_replace('"', '', $message_sender);
       }
       else if ($single->getName() == 'To') {
           $message_destin = $single->getValue();
           $message_destin = str_replace('"', '', $message_destin);
       }
    }
    $inboxMessage[] = [
         'messageId' => $message_id,
         'messageSnippet' => $snippet,
         'messageSubject' => $message_subject,
         'messageDate' => $message_date,
         'messageSender' => $message_sender,
         'messageDestins' => $message_destin,
    ];
    echo "mensaje procesado con id: ".$message_id."\n";
    var_dump($inboxMessage);

    //----Enviamos al label procesados y quitamos del INBOX
    //$proces=enviarProcesados($service,$message_id);
    //echo $proces."\n";

}
?>
