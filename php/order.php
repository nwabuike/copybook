<?php
require_once("db.php");
date_default_timezone_set("Africa/Lagos");

if ((isset($_POST['name']) && $_POST['pack'] != '')) {

    // $i = implode(" ", $_POST['bundle_jamb']);
    $user_name = $conn->real_escape_string($_POST['name']);

    $user_email = $conn->real_escape_string($_POST['email']);
    $user_phone = $conn->real_escape_string($_POST['phone']);
    $user_altphone = $conn->real_escape_string($_POST['altphone']);
    $user_address = $conn->real_escape_string($_POST['address']);
    $user_state = $conn->real_escape_string($_POST['state']);
    $user_pack = $conn->real_escape_string($_POST['pack']);
    $user_date = date("M d, Y h:i a");
    $sql = "INSERT INTO orders (name, email, phone, altphone, address, state, pack, created_at) 
VALUES('" . $user_name . "', '" . $user_email . "', '" . $user_phone . "','" . $user_altphone . "', '" . $user_address . "', '" . $user_state . "','" . $user_pack . "','" . $user_date . "')";
    // echo $sql;
    if (!$result = $conn->query($sql)) {
        $output = json_encode(array('type' => 'error', 'text' => 'There was an error running the query [' . $conn->error . ']'));
        die($output);
        // die('There was an error running the query [' . $conn->error . ']');
    } else {
        require_once("mailer.php");
        // require_once("./Emailing.php");
        // header("Location: ../../thankYou.php");
        $output = json_encode(array('type' => 'message', 'text' => 'Hi ' . $user_name . ', thank you for the message. We will get back to you shortly.'));
        die($output);
    }
    return !$result;
} else {
    $output = json_encode(array('type' => 'error_emptyfield', 'text' => 'Oops!! There was a problem with your submission. Please complete the form and try again. [' . $conn->error . ']'));
    die($output);
    // echo 'Oops!! There was a problem with your submission. Please complete the form and try again.';
    // die('<h4 class="alert alert-danger">Oops! There was a problem with your submission. Please complete the form and try again.</h4>');
}
