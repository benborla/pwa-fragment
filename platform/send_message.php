<?php

function sendGCM($message, $id) {
    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            'registration_ids' => array (
                    $id
            ),
            'data' => array (
                    "message" => $message
            )
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . "AIzaSyAuJCmxX_cBCu5kgXFxy0l8qwe49CBdXSU",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    echo $result;
    curl_close ( $ch );
}

echo 'Sending message';
sendGCM('Hello there', 'dfAQhDMJ7h5KtyciYAkqbq:APA91bFtAvJrge_UFlQLfJc6khAi_qmZ2jsSkCWQ4dEKP65uTt_65Qa6hw2Dfll4iWt7IjTX_-co-dk6dHpMyzd_GihVj98hThFdO3Czg1VysuAgRoFHnhWN8yTkPkqSIFq1FhJkTOCH');

?>
