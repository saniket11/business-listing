<?php 

function generate_error_response($errorCode, $type) {
        $response['errorCode'] = $errorCode;

        switch ($errorCode) {
            case '0001':
                $response['errorMessage'] = 'Please fill all required fields!';
                break;

            case '0000':
                switch ($type) {
                    case 'login':
                        $response['errorMessage'] = 'Login Successful';
                        break;

                    case 'add':
                        $response['errorMessage'] = 'Data Saved Successful';
                        break;

                    case 'update':
                        $response['errorMessage'] = 'Data Updated Successful';
                        break;

                    case 'fetch':
                        $response['errorMessage'] = 'Data Fetched Successful';
                        break;

                    case 'otpsent':
                        $response['errorMessage'] = 'Otp sent successful';
                        break;
                    case 'otpverify':
                        $response['errorMessage'] = 'Otp Verified successful';
                        break;
                    case 'passwordReset':
                        $response['errorMessage'] = 'Password Reset successful';
                        break;

                    case 'logout':
                        $response['errorMessage'] = 'Logout Successfully';
                        break;
                    case 'image';
                        $response['errorMessage'] = 'Image Deleted Successfully';
                        break;
                    case 'data';
                        $response['errorMessage'] = 'Data Deleted Successfully';
                        break;
                    case 'noChanges';
                        $response['errorMessage'] = 'No Changes have been made';
                        break;
                    default:
                        $response['errorMessage'] = 'Data Saved Successfully';
                        break;
                }
                break;

            case '0002':
                switch ($type) {
                    case 'not found':
                        $response['errorMessage'] = 'Data Not Found!';
                        break;

                    case 'exist':
                        $response['errorMessage'] = 'Already Exist';
                        break;
                    case 'emailExist':
                        $response['errorMessage'] = 'This Email, Already Exist';
                        break;
                    case 'mobileExist':
                        $response['errorMessage'] = 'This Number, Already Exist';
                        break;
                    case 'alreadywishlisted':
                        $response['errorMessage'] = 'This Product already added in your wishlist';
                        break;
                    default:
                        $response['errorMessage'] = 'Not Found!';
                        break;
                }
                break;
            case '0003':
                switch ($type) {
                    case 'queryPreparation':
                        $response['errorMessage'] = 'Something went wrong, please try again later!';
                        break;

                    case 'sql':
                        $response['errorMessage'] = 'Please try again!';
                        break;
                    
                    case 'payment-failed':
                        $response['errorMessage'] = 'Payment Failed!';
                        break;

                    default:
                        $response['errorMessage'] = 'Not Found!';
                        break;
                }
                break;

            case '0004':
                switch ($type) {
                    case 'correctImageFormat':
                        $response['errorMessage'] = 'Please select correct file format!';
                        break;

                    case 'imageRequired':
                        $response['errorMessage'] = 'Please select file!';
                        break;

                    default:
                        $response['errorMessage'] = 'Please select correct image!';
                        break;
                }
                break;

            case '0005':
                switch ($type) {
                    case 'notverifiedotp':
                        $response['errorMessage'] = 'Otp Not Matched!';
                        break;

                    default:
                        $response['errorMessage'] = 'Not Verified Otp!';
                        break;
                }
                break;
            case '401':
                http_response_code(401);
                switch ($type) {
                    case 'unauthorized':
                        $response['errorMessage'] = 'Unauthorized Access';
                        break;
                        case 'invalid':
                            $response['errorMessage'] = 'Token is not valid';
                            break;
                            
                        default:
                            $response['errorMessage'] = 'Unauthorized Access';
                            break;
                }
                break;
            case '403':
                http_response_code(403);
                switch ($type) {
                    case 'expire':
                        $response['errorMessage'] = 'Token has expired';
                        break;
                            
                        default:
                            $response['errorMessage'] = 'Unauthorized Access';
                            break;
                }
                break;

            default:
                $response['errorMessage'] = 'Something went wrong!';
                break;
        }
        return $response;
    }

    function execute_select_query($conn, $query, $bindTypes, $bindValues) {
        $stmt = $conn->prepare($query);
        if ($stmt) {
            if ($bindTypes && $bindValues) {
                $stmt->bind_param($bindTypes, ...$bindValues);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        } else {
            return false; // Query preparation failed
        }
    }

    function execute_insert_query($conn, $query, $bindTypes, $bindValues) {
        try {
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($bindTypes && $bindValues) {
                    $stmt->bind_param($bindTypes, ...$bindValues);
                }
                $stmt->execute();
                $affectedRows = $stmt->affected_rows;
                $stmt->close();
                return $affectedRows;
            } else {
                return -1; // Query preparation failed
            }
        } catch (mysqli_sql_exception $e) {
            // Log the error for debugging purposes
            error_log("MySQL Error: " . $e->getMessage());

            // Return a default error message
            return -2;
        }
    }

    function get_data_from_columns($table_name, $column_name, $column_value, $column_name1, $column_value1, $conn){
        if($column_name1 == ""){
            $fetchQuery = "SELECT * FROM  ".$table_name." WHERE ".$column_name." = '".$column_value."' AND delete_flag = 0 ";
        }
        else{
            $fetchQuery = "SELECT * FROM  ".$table_name." WHERE ".$column_name." = '".$column_value."' AND ".$column_name1." = '".$column_value1."' AND delete_flag = 0";
        }

        $fetchResult = execute_select_query($conn, $fetchQuery, "", "");
        $fetchArray = mysqli_fetch_array($fetchResult);

        return json_encode($fetchArray);
    }

?>