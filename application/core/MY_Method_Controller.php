<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class MY_Method_Controller extends CI_Controller
{

	protected $_post;
	protected $_get;
	protected $_time;
	protected $_return;

	public function __construct()
	{
		parent::__construct();
		$this->_time = time();
	}

	abstract public function run($data=false);

	protected function model($name,$callName=false)
	{
		if(!$callName){
			$this->load->model($name);
			return $this->{$name};
		}
		$this->load->model($name,$callName);
		return $this->{$callName};
	}

	protected function sql($name,$callName=false)
	{
		if(!$callName){
			$this->load->model('sql/'.$name);
			return $this->{$name};
		}
		$this->load->model('sql/'.$name,$callName);
		return $this->{$callName};
	}

	protected function tb($name,$callName=false)
	{
		if(!$callName){
			$this->load->model('tables/'.$name);
			return $this->{$name};
		}
		$this->load->model('tables/'.$name,$callName);
		return $this->{$callName};
	}

	protected function logic($name,$callName=false)
	{
		if(!$callName){
			$this->load->model('logic/'.$name);
			return $this->{$name};
		}
		$this->load->model('logic/'.$name,$callName);
		return $this->{$callName};
	}

	protected function library($name)
	{
		$this->load->library($name);
		return $this->{$name};
	}

	protected function echoJsonResult()
	{
		$output = (is_array($this->_return))? json_encode($this->_return):$this->_return;
		echo $output;
		return true;
	}

	protected function getPost($data)
	{
		foreach($data as $post){
			$this->_post[$post] = $this->input->get_post($post);
		}
	}

	protected function get($data)
	{
		foreach($data as $get){
			$this->_get[$get] = $this->input->get($get);
		}
	}

	protected function isCorrectPostValue()
	{
		foreach($this->_post as $post){
			if($post === false || $post === '') return false;
		}
		return true;
	}

	protected function returnSuccess()
	{
		$this->_return['status'] = 'success';
		$this->echoJsonResult();
	}

	protected function returnError($errorMessage)
	{
		$this->_return = array(
			'status'=>'error',
			'error'=>$errorMessage,
		);
		$this->echoJsonResult();
	}
}
