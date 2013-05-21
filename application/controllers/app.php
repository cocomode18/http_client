<?php

class App extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if($this->input->post()){
            $request = $this->logic('Request_Logic');
            $request->sendRequest($this->input->post());
            $response = $request->getResponse();
            $data = array(
                'error' => !$response['status'],
                'post' => $this->input->post(),
                'response' => $response
            );
        }else{
            $data = array(
                'error' => false,
            );
        }

        $this->load->view('app',$data);
    }
}
