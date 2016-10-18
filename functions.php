<?php

//Funcion que devuelve la lista de mensajes
function listMessages($service, $userId) {
  $pageToken = NULL;
  $q='is:unread';
  $messages = array();
  $opt_param = array();
  do {
    try {
      if ($pageToken) {
        $opt_param['pageToken'] = $pageToken;
      }
      $messagesResponse = $service->users_messages->listUsersMessages($userId, $opt_param);
      if ($messagesResponse->getMessages()) {
        $messages = array_merge($messages, $messagesResponse->getMessages());
        $pageToken = $messagesResponse->getNextPageToken();
      }
    } catch (Exception $e) {
      print 'An error occurred: ' . $e->getMessage();
    }
  } while ($pageToken);

  foreach ($messages as $message) {
    print 'Message with ID: ' . $message->getId() . '<br/>';
  }

  return $messages;
}

//Funcion que borra una label determinada
function deleteLabel($service, $user, $labelId) {
  $label = new Google_Service_Gmail_Label();
  $label->setName($labelId);
  try {
    $service->users_labels->delete($user, $labelId);
    $salida= "Label con id: " . $labelId . " eliminado correctamente.\n";
  } catch (Exception $e) {
    $salida= "Error: " . $e->getMessage()."\n";
  }
  return $salida ."//".$labelId;
}


//Funcion que crea una label determinada
function createLabel($service, $user,$new_label_name) {
  $label = new Google_Service_Gmail_Label();
  $label->setName($new_label_name);
  try {
    $label = $service->users_labels->create($user, $label);
    $salida= "Label con ID: " . $label->getId() . " creada correctamente.\n";
  } catch (Exception $e) {
    $salida= "Error: " . $e->getMessage()."\n";
  }
  return $salida ."//".$label->getId();
}


function enviarProcesados($service,$message_id){
    $labelProcesados=recogeLabelProcesados();
    $labelsToAdd=array($labelProcesados);
    $labelsToRemove=array("INBOX");
    $mods = new Google_Service_Gmail_ModifyMessageRequest();
    $mods->setAddLabelIds($labelsToAdd);
    $mods->setRemoveLabelIds($labelsToRemove);
    try {
        $message = $service->users_messages->modify('me', $message_id, $mods);
        $salida= "Mensaje con ID: " . $message_id . " enviado a procesados.";
    } catch (Exception $e) {
        $salida= 'Error: ' . $e->getMessage();
    }
    return $salida;
}

function recogeLabelProcesados(){
   $dsn='mysql:dbname=sistemaPIT;host=127.0.0.1';
   $DBuser='root';
   $DBpassword='root';
   try {
       $dbh=new PDO($dsn,$DBuser,$DBpassword);
   } 
   catch (PDOException $e) {
   }  
   $sth = $dbh->prepare('SELECT label_id FROM labels WHERE label_name = ?;');
   $sth->execute(array("procesados"));
   $user = $sth->fetch();
   return $user['label_id'];
}

function getLabels($servicem,$user){
  $results = $service->users_labels->listUsersLabels($user);
  if (count($results->getLabels()) == 0) {
     print "No labels found.\n";
  } 
  else {
  print "Labels:\n";
  foreach ($results->getLabels() as $label) {
    printf("- %s\n", $label->getId());
    //print_r($label);
  }
}

}
