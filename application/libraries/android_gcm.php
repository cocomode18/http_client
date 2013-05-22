<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Android_GCM {

	private $CI;
	private $settings = array();

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->config('android_gcm');

		$this->settings['app_key']	= $this->CI->config->item('app_key');
	}

	public function push($userToken,$data)
	{
		$url = 'https://android.googleapis.com/gcm/send';
		$header = array(
				'Content-Type: application/json',
				'Authorization: key=' . $this->settings['app_key'],
			       );
		$post_list = array();
		if(!is_array($userToken)) $userToken = array($userToken);
		$post_list['registration_ids'] = $userToken;
		$post_list['collapse_key'] = 'update';
		$post_list['data'] = $data;

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );

		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($post_list) );
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
}
