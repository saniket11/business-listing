<?php

$bodyData = file_get_contents('php://input');
$data = json_decode($bodyData);
date_default_timezone_set('Asia/Kolkata');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include '../config/db.php';
include '../config/db_function.php';

$response = [];
$type = base64_decode(strip_tags(filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
if ($type == "") {
    $type = base64_decode(strip_tags(filter_var($data->type, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
}
$imageError = 0;

if ($type == 'add-business') {
    $name = base64_decode(strip_tags(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $email = base64_decode(strip_tags(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $phone = base64_decode(strip_tags(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $address = base64_decode(strip_tags(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));


    if ($name === "" || $email === "" || $phone === "" || $address === "" ) {
        $response = generate_error_response('0001', 'required');
    } else {
       
        $chechQuery = "SELECT * FROM businesses WHERE (email = ? || phone = ? ) AND delete_flag = 0";
        $fetchResult = execute_select_query($conn, $chechQuery, 'ss', [$email, $phone]);
        if(mysqli_num_rows($fetchResult) > 0){
            $data = mysqli_fetch_array($fetchResult);
            $id = $data['id'];
            $updateQuery = "UPDATE businesses  SET name = ?, address = ?, phone = ?, email = ? WHERE id = ?";
            $updateResult = execute_insert_query($conn, $updateQuery, 'ssssi', [$name, $address, $phone, $email, $id]);
        }
        else{
            $updateQuery = "INSERT INTO businesses (name, address, phone, email, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
            $updateResult = execute_insert_query($conn, $updateQuery, 'ssss', [$name, $address, $phone, $email]);
        }
       
        if ($updateResult > 0) {
             $response = generate_error_response('0000', 'add');
        }
        else if ($updateResult == 0) {
             $response = generate_error_response('0000', 'add');
        }
        else{
            $response = generate_error_response('0003', 'sql');
        }
            
    }
}

if ($type == 'update-business') {
    $id = base64_decode(strip_tags(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $name = base64_decode(strip_tags(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $email = base64_decode(strip_tags(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $phone = base64_decode(strip_tags(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $address = base64_decode(strip_tags(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));


    if ($name === "" || $email === "" || $phone === "" || $address === "" ) {
        $response = generate_error_response('0001', 'required');
    } else {
       
        $chechQuery = "SELECT * FROM businesses WHERE (email = ? || phone = ?) AND delete_flag = 0";
        $fetchResult = execute_select_query($conn, $chechQuery, 'ss', [$email, $phone]);
        if(mysqli_num_rows($fetchResult) > 0){
            $data = mysqli_fetch_array($fetchResult);
            $id = $data['id'];
            $updateQuery = "UPDATE businesses  SET name = ?, address = ?, phone = ?, email = ? WHERE id = ?";
            $updateResult = execute_insert_query($conn, $updateQuery, 'ssssi', [$name, $address, $phone, $email, $id]);
        }
        else{
            $updateQuery = "INSERT INTO businesses (name, address, phone, email, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
            $updateResult = execute_insert_query($conn, $updateQuery, 'ssss', [$name, $address, $phone, $email]);
        }
       
        if ($updateResult > 0) {
             $response = generate_error_response('0000', 'add');
        }
        else if ($updateResult == 0) {
             $response = generate_error_response('0000', 'add');
        }
        else{
            $response = generate_error_response('0003', 'sql');
        }
            
    }
}

if ($type == 'delete-business') {

        $id = base64_decode(strip_tags(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));


    $query = "UPDATE businesses SET delete_flag = 1 WHERE id = ?";
    $result = execute_insert_query($conn, $query, 'i', [$id]);

    if ($result >= 0) {
        $response = generate_error_response('0000', 'data');
    } else {
        $response = generate_error_response('0003', 'sql');
    }
}

if ($type == 'add-rating') {

    $business_id = base64_decode(strip_tags(filter_input(INPUT_POST, 'business_id', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $name        = base64_decode(strip_tags(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $email       = base64_decode(strip_tags(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $phone       = base64_decode(strip_tags(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));
    $rating      = base64_decode(strip_tags(filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)));

    if ($business_id === "" || $name === "" || $email === "" || $phone === "" || $rating === "") {
        $response = generate_error_response('0001', 'required');
    } else {

        $checkQuery = "SELECT * FROM ratings WHERE business_id = ? AND email = ? AND delete_flag = 0";
        $fetchResult = execute_select_query($conn, $checkQuery, 'is', [$business_id, $email]);

        if (mysqli_num_rows($fetchResult) > 0) {

            $data = mysqli_fetch_array($fetchResult);
            $id = $data['id'];

            $updateQuery = "UPDATE ratings 
                            SET name = ?, phone = ?, rating = ?, updated_at = NOW()
                            WHERE id = ?";

            $updateResult = execute_insert_query(
                $conn,
                $updateQuery,
                'ssdi',
                [$name, $phone, $rating, $id]
            );

        } else {

            $insertQuery = "INSERT INTO ratings 
                            (business_id, name, email, phone, rating, created_at, updated_at, delete_flag)
                            VALUES (?, ?, ?, ?, ?, NOW(), NOW(), 0)";

            $updateResult = execute_insert_query(
                $conn,
                $insertQuery,
                'isssd',
                [$business_id, $name, $email, $phone, $rating]
            );
        }

        if ($updateResult > 0) {
            $response = generate_error_response('0000', 'add');
        } elseif ($updateResult == 0) {
            $response = generate_error_response('0000', 'add');
        } else {
            $response = generate_error_response('0003', 'sql');
        }
    }
}

echo json_encode($response);

?>