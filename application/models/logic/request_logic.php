<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_Logic extends MY_Model
{
    private $response;
    private $requestUrl;

    public function __construct()
    {
        parent::__construct();
    }

    public function sendRequest($post)
    {
        $this->requestUrl = $post['requestUrl'];

        $keyVals = array_keys($post);
        $requestValues = array('key' => array(), 'val' => array());
        $customHeaders = array('key' => array(), 'val' => array());
        foreach($keyVals as $keyval){
            if(strpos($keyval, 'requestKey') === 0) $requestValues['key'][] = $post["$keyval"];
            if(strpos($keyval, 'requestVal') === 0) $requestValues['val'][] = $post["$keyval"];
            if(strpos($keyval, 'headerKey') === 0) $customHeaders['key'][] = $post["$keyval"];
            if(strpos($keyval, 'headerVal') === 0) $customHeaders['val'][] = $post["$keyval"];
            if(strpos($keyval, 'upfileKey') === 0) $uploadFiles['key'][] = $post["$keyval"];
        }

        $postFields = array();
        for($i=0; count($requestValues['key'])>$i; $i++){
            if(!empty($requestValues['key'][$i]) && !empty($requestValues['val'][$i])){
                $key = $requestValues['key'][$i];
                $postFields["$key"] = $requestValues['val'][$i];
            }
        }

        $fileData = array();
        for($i=0; count($uploadFiles['key'])>$i; $i++){
            if(!empty($uploadFiles['key'][$i]) && !empty($_FILES["upfile$i"]['name'])){

                $config['upload_path'] = './tmp/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '0';
                $config['file_name'] = mt_rand().time().$_FILES["upfile$i"]['name'];
                $this->load->library('upload', $config);

                if ($this->upload->do_upload("upfile$i")) {
                    $uploadData = $this->upload->data();

                    $key = $uploadFiles['key'][$i];
                    $fileData["$key"] = $uploadData;
                } else {
                    var_dump('failed to get file');
                }
            }
        }

        $contentData = "";
        if (empty($fileData)) {
            $contentData = http_build_query($postFields,"","&");
            $header = array(
                "Content-Type: application/x-www-form-urlencoded",
                "Content-Length: ".strlen($contentData),
                );
        } else {
            $boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10);

            foreach($postFields as $key => $val) {
                $contentData .= "--$boundary" . "\n";
                $contentData .= 'Content-Disposition: form-data; name="' . $key . '"'  . "\n" . "\n" . $val . "\n";
            }

            foreach($fileData as $key => $file) {
                $contentData .= "--$boundary" . "\n";
                $fileContents = file_get_contents($file['full_path']);
                $contentData .= 'Content-Disposition: form-data; name="' . $key . '"; filename="' . $file['file_name'] . '"' . "\n";
                $contentData .= "Content-Type: ".$file['file_type'] . "\n";
                $contentData .= "Content-Transfer-Encoding: binary" . "\n" . "\n";
                $contentData .= $fileContents . "\n";
            }
            $contentData .= "--$boundary--" . "\n";

            $header = array(
                "Content-Type: multipart/form-data, boundary=".$boundary,
                "Content-Length: ".strlen($contentData),
                );
        }

        if (!empty($post['authUser']) && !empty($post['authPass'])) {
            $header[] = "Authorization: Basic ".base64_encode($post['authUser'].':'.$post['authPass']);
        }

        for($i=0; count($customHeaders['key'])>$i; $i++){
            if(!empty($customHeaders['key'][$i]) && !empty($customHeaders['val'][$i])){
                $header[] = $customHeaders['key'][$i].': '.$customHeaders['val'][$i];
            }
        }

        $context = array(
            "http" => array(
                "method" => $post['method'],
                "header" => implode("\r\n", $header),
                "content" => $contentData,
            )
        );;

        $this->response['requestHeader'] = $header;
        $this->response['contents'] = mb_convert_encoding(file_get_contents($post['requestUrl'], false, stream_context_create($context)), 'UTF-8', 'auto');
        $this->response['responseHeader'] = $http_response_header;

        shell_exec('rm -rf ./tmp/*');
    }

    public function getResponse()
    {
        $this->response['status'] = (empty($this->response['contents']))? false:true;
        foreach ($this->response['responseHeader'] as $header) {
            if (strpos($header, 'X-Debug-Token: ') === 0) {
                $token = substr($header, strlen('X-Debug-Token: '), strlen($header));
                $symfonyUrl = substr($this->requestUrl, 0, strpos($this->requestUrl, 'app_dev.php') + strlen('app_dev.php'));
                $this->response['symfonyProfiler'] = $symfonyUrl . '/_profiler/' . $token;
            }
        }
        return $this->response;
    }
}
