<?php

/**
 * A simple rocket chat message schedulator.
 * @author loviuz
 */

include __DIR__.'/vendor/autoload.php';
include __DIR__.'/config.inc.php';

// Loop through users
foreach( $users as $user => $params ){
    echo '[*] Checking messages for '.$user.'... ';


    // Checking if a message should be send
    $when = 'Y-m-d H:i';

    // When we want to send?
    $when = str_replace( 'Y', $params['year'] == '*' ? date('Y') : $params['year'], $when );
    $when = str_replace( 'm', $params['month'] == '*' ? date('m') : $params['month'], $when );
    $when = str_replace( 'd', $params['day'] == '*' ? date('d') : $params['day'], $when );
    $when = str_replace( 'H', $params['hour'] == '*' ? date('H') : $params['hour'], $when );
    $when = str_replace( 'i', $params['minute'] == '*' ? date('i') : $params['minute'], $when );
    
    // Send at exact moment by day or by day of week
    $send = false;

    if( date('Y-m-d H:i', strtotime($when)) == date('Y-m-d H:i') && $params['daysOfWeek'] == '*' ){
        $send = true;
    }
    
    foreach( explode(',', $params['daysOfWeek']) as $dayOfWeek ){
        if( date('H:i', strtotime($when)) == date('H:i') && (int)$dayOfWeek == (int)date('N') ){
            $send = true;
        }
    }

    if( $send ){
        echo 'sending message... ';

        $response = \Httpful\Request::post($params['webhook'], json_encode($params['message']))
            ->expectsJson()
            ->send();

        if( $response->code == 200 ){
            if( $response->body->success ){
                echo "sent!\n";
            } else {
                echo $response->error."\n";
            }
        } else {
            echo 'error '.$response->code.'! ';

            // Additional details
            if( !empty($response->body->error) ){
                echo $response->body->error;
            }

            echo "\n";
        }
    } else {
        echo "no message to send.\n";
    }
}