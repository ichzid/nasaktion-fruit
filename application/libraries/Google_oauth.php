<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Google_oauth {

    protected $CI;
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('google');

        $this->client_id     = $this->CI->config->item('google_client_id');
        $this->client_secret = $this->CI->config->item('google_client_secret');
        $this->redirect_uri  = $this->CI->config->item('google_redirect_uri');
    }

    /**
     * Get Google Login URL
     */
    public function get_login_url() {
        $params = array(
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->redirect_uri,
            'response_type' => 'code',
            'scope'         => 'email profile',
            'access_type'   => 'online',
            'prompt'        => 'select_account',
        );
        return 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
    }

    /**
     * Exchange authorization code for access token
     */
    public function authenticate($code) {
        $post_data = array(
            'code'          => $code,
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri'  => $this->redirect_uri,
            'grant_type'    => 'authorization_code',
        );

        $response = $this->_curl_post('https://oauth2.googleapis.com/token', $post_data);

        if ($response && isset($response['access_token'])) {
            return $response;
        }

        return false;
    }

    /**
     * Get user info from Google using access token
     */
    public function get_user_info($access_token) {
        if (is_array($access_token) && isset($access_token['access_token'])) {
            $token = $access_token['access_token'];
        } else {
            $token = $access_token;
        }

        $response = $this->_curl_get('https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $token);

        if ($response && isset($response['id'])) {
            return array(
                'google_id'   => $response['id'],
                'email'       => $response['email'],
                'nama'        => isset($response['name']) ? $response['name'] : $response['email'],
                'foto'        => isset($response['picture']) ? $response['picture'] : '',
                'given_name'  => isset($response['given_name']) ? $response['given_name'] : '',
                'family_name' => isset($response['family_name']) ? $response['family_name'] : '',
            );
        }

        return false;
    }

    /**
     * Check if token is valid
     */
    public function is_valid_token($access_token) {
        if (is_array($access_token) && isset($access_token['access_token'])) {
            $token = $access_token['access_token'];
        } else {
            $token = $access_token;
        }

        $response = $this->_curl_get('https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $token);

        return ($response && isset($response['expires_in']) && $response['expires_in'] > 0);
    }

    /**
     * cURL POST helper
     */
    private function _curl_post($url, $post_data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200 && $response) {
            return json_decode($response, true);
        }

        return false;
    }

    /**
     * cURL GET helper
     */
    private function _curl_get($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200 && $response) {
            return json_decode($response, true);
        }

        return false;
    }
}