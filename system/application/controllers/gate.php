<?php

class Gate extends Controller {

	function Gate()
	{
		parent::Controller();
	}
	
	function index()
	{
		$gate = '';
		if ($login = $this->session->flashdata('login'))
		{
			$gate['message'] = $login;
		}
		$main['body'] = $this->load->view('gate', $gate, TRUE);
		$this->load->view('main', $main);
	}

	function login()
	{
		$this->load->model('Voter');
		$username = $this->input->post('username');
		$password = sha1($this->input->post('password'));
		if ($voter = $this->Voter->authenticate($username, $password))
		{
			if ($voter['voted'] == TRUE)
			{
				$this->session->set_flashdata('login', e('already_voted'));
				redirect('gate');
			}
			else
			{
				// don't save password and pin to session
				unset($voter['password']);
				unset($voter['pin']);
				$this->session->set_userdata('voter', $voter);
				redirect('voter');
			}
		}
		else
		{
			$this->session->set_flashdata('login', e('login_failure'));
			redirect('gate');
		}
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect('gate');
	}

}
?>