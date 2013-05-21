<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	protected $_time;

	public function __construct()
	{
		parent::__construct();
		$this->_time = time();
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

	public function fetchLastInsertSeq()
	{
		return $this->db->insert_id();
	}
}
