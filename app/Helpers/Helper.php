<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('uploadImage')) {

    function uploadImage($imageFile, $folderName)
    {
        $name = time() . rand(0, 9999) . '.' . $imageFile->clientExtension();
        $destination_path = $folderName;
        $path = $imageFile->storeAs($destination_path, $name, 'public');
        $url = Storage::url($path);
        return $url;
    }
}

if (!function_exists('sendFCM_notification')) {

    function sendFCM_notification($type, $title, $message, $ids)
    {
        $res =  [
            "to" => $ids,
            //"to" => 'eCHmuhQanUiPkOwmO7YyLR:APA91bEkFrJ04PuztSNLsKbvThhYQrlqlpeRfIjPSQ6K8FLHe8dtotXQhQ522Sz-K7i6UfbO3ObslZ_ZeOOk50dJazrILl0GxbVTiNIcwQcGVLrAj73-LJrWeaCI3ZXqcnhdvJBJ2joM',
            "notification" => [
                "title" => $title,
                "body" => $message,
                "icon" => "myicon"
            ]
        ];

        $data = json_encode($res);
        //FCM API end-point
        $url = 'https://fcm.googleapis.com/fcm/send';
        if ($type === 'user') {
            //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
            $server_key     =   "AAAA_wusn-4:APA91bENpVv-rPqTEZ3Ylf4V5eSsMkzNCPutEE-37xd4o6a8tDq4CFQ4u92iJZsod4F-iwu56nvKDeaj1LQsfgNzgfA-j6Ufc51hCNLw9k1HnOlyqqpqkM2cSlVaC_pg68MdxcDzQ4ui";
        } else {
            $server_key     =   "AAAAJHbIpYA:APA91bGOhDHyBjKT8m9TrkBXQv-2WjtBJhMpXxkDN8ix7SnXewMVDVJk-PloSHeIkAkRCMfYKb_7nwnUzOTR85iSkU9iw_SYygVN1sd1fORogORILDsFuuXdgOeV65MkoVI-pj_ImC5v";
        }
        
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key
        );
        //CURL request to route notification to FCM connection server (provided by Google)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
      
        if ($result === FALSE) {
            return 'Oops! FCM Send Error: ' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
}
