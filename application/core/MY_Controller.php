<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class MY_Controller extends CI_Controller
{
	protected $_post;
	protected $_get;
	protected $_time;
	protected $_return;

	public function __construct()
	{
		parent::__construct();
		if (ENVIRONMENT === 'development') {
			$this->output->enable_profiler();
		}
		require_once(APPPATH.'core/MY_Table_Model.php');
	}

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
}
