<?php

/*
 * The sample code from Onepage.
 */

/**
 * Description of OnepageAuth
 *
 * @author stuartlb3
 */
class OnepageAuth {

    protected $api_login = 'YOUR_ONEPAGECRM_LOGIN'; // need to pull this in
    protected $api_password = 'YOUR_ONEPAGECRM_PASSWORD'; // need to pull this in

// Make OnePage CRM API call
    function make_api_call($url, $http_method, $post_data = array(), $uid = null, $key = null) {
        $full_url = 'https://app.onepagecrm.com/api/v3/' . $url;
        $ch = curl_init($full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
        $timestamp = time();
        $auth_data = array($uid, $timestamp, $http_method, sha1($full_url));
        $request_headers = array();
// For POST and PUT requests we will send data as JSON
// as with regular "form data" request we won't be able
// to send more complex structures
        if ($http_method == 'POST' || $http_method == 'PUT') {
            $request_headers[] = 'Content-Type: application/json';
            $json_data = json_encode($post_data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            $auth_data[] = sha1($json_data);
        }
// Set auth headers if we are logged in
        if ($key != null) {
            $hash = hash_hmac('sha256', implode('.', $auth_data), $key);
            $request_headers[] = "X-OnePageCRM-UID: $uid";
            $request_headers[] = "X-OnePageCRM-TS: $timestamp";
            $request_headers[] = "X-OnePageCRM-Auth: $hash";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);
        if ($result->status > 99) {
            echo "API call error: {$result->message}\n";
            return null;
        }
        return $result;
    }

    function temp() {
        $data = make_api_call('login.json', 'POST', array('login' => $api_login, 'password' => $api_password));
        if ($data == null) {
            exit;
        }
// Get UID and API key from result
        $uid = $data->data->user_id;
        $key = base64_decode($data->data->auth_key);
        echo "Logged in, your UID is : {$uid}\n";
// Get contacts list
        echo "Getting contacts list...\n";
        $contacts = make_api_call('contacts.json', 'GET', array(), $uid, $key);
        if ($data == null) {
            exit;
        }
    }

}
