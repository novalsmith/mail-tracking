<?php

use App\Models\ModelOtentikasi;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getJWT($otentikasiHeader)
{
    if (is_null($otentikasiHeader)) {
        throw new Exception("Otentikasi JWT Gagal");
    }
    return explode(" ", $otentikasiHeader)[1];
}
function validateJWT($encodedToken)
{
    $key = getenv('JWT_SECRET_KEY');
    // $decodedToken = JWT::decode($encodedToken, $key, 'HS256');
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));

    $modelOtentikasi = new ModelOtentikasi();
    //xxxx.xxxx.xxxx
    $modelOtentikasi->getEmployee($decodedToken->nip,$decodedToken->password);
}
function createJWT($nip,$password)
{
    $waktuRequest = time();
    $waktuToken = getenv('JWT_TIME_TO_LIVE');
    $waktuExpired = $waktuRequest + $waktuToken;
    $payload = [
        'nip' => $nip,
        'password' => $password,
        'iat' => $waktuRequest,
        'exp' => $waktuExpired
    ];
    $jwt = JWT::encode($payload, getenv('JWT_SECRET_KEY'),'HS256');
    return $jwt;
}


// Sample Code, it's just temporary will  be remove it
// <?php

// use App\Models\ModelOtentikasi;
// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;

// function getJWT($otentikasiHeader)
// {
//     if (is_null($otentikasiHeader)) {
//         throw new Exception("Otentikasi JWT Gagal");
//     }
//     return explode(" ", $otentikasiHeader)[1];
// }
// function validateJWT($encodedToken)
// {
//     // $key = getenv('JWT_SECRET_KEY');
//     // // $decodedToken = JWT::decode($encodedToken, $key, 'HS256');
//     // $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));

//     // $modelOtentikasi = new ModelOtentikasi();
//     // //xxxx.xxxx.xxxx
//     // $modelOtentikasi->getEmail($decodedToken->email);
//     $key = getenv('JWT_SECRET_KEY');
  
//     try {
   
//     // $decodedToken = JWT::decode($encodedToken, $key, 'HS256');
//     $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
  
//     $modelOtentikasi = new ModelOtentikasi();
  
//     //xxxx.xxxx.xxxx
//     $modelOtentikasi->getEmail($decodedToken->email);
        
//         } catch (Exception $e){
//             // Tell the user that their JWT has expired
//             // $this->createJWT($decodedToken->email);
//             // $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
       
//             if($e->getMessage() == "Expired token"){
             
//                 list($header, $payload, $signature) = explode(".", $encodedToken);
//                  $payload = json_decode(base64_decode($payload));
//                 // // $refresh_token = $payload->data->refresh_token;
//                 // $ok = $payload->data;

//                 if (\is_array($payload)) {
//                     // prevent PHP Fatal Error in edge-cases when payload is empty array
//                     $payload = (object) $payload;
//                 }
               
//                 // throw new Exception($payload->email);
//                 $jwtData = createJWT($payload->email); 
                
//             //   return  $jwt;
//                 throw new Exception($jwtData);
//                 // die();
//             }else {

//                 // set response code
//                 http_response_code(401);
            
//                 // show error message
//                 echo json_encode(array(
//                     "message" => "Access denied.",
//                     "error" => $e->getMessage()
//                 ));
//                 die();
//                 }
            
//             // echo 'Caught exception: ',  $e->getMessage(), "\n";

//             // echo $exception->getMessage();
//             // echo $e->getMessage();
            
//         }
        
// }
// function createJWT($email)
// {
//     $waktuRequest = time();
//     $waktuToken = getenv('JWT_TIME_TO_LIVE');
//     $waktuExpired = $waktuRequest + $waktuToken;
//     $payload = [
//         'email' => $email,
//         'iat' => $waktuRequest,
//         'exp' => $waktuExpired
//     ];
//     $jwt = JWT::encode($payload, getenv('JWT_SECRET_KEY'),'HS256');
//     return $jwt;
// }
