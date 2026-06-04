<?php defined('BASEPATH') or exit('No direct script access allowed');

class Password_reset extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('mailjet');
    }

    public function request()
    {
        $email = $this->input->post('email');

        if ($this->ion_auth->email_check($email)) {
            // Generate a reset token and send the email
            $reset_code = $this->ion_auth->forgotten_password($email);

            if ($reset_code) {
                $reset_link = site_url("password_reset/reset/{$reset_code}");
                $subject = "Password Reset Request";
                $message = "To reset your password, click the following link: $reset_link";

                if ($this->mailjet->send($email, $subject, $message)) {
                    $this->session->set_flashdata('message', 'Password reset email sent.');
                } else {
                    $this->session->set_flashdata('message', 'Failed to send password reset email.');
                }
            } else {
                $this->session->set_flashdata('message', 'Failed to generate reset code.');
            }
        } else {
            $this->session->set_flashdata('message', 'Email not found.');
        }

        redirect('password_reset/request');
    }

    public function reset($code = NULL)
    {
        if ($code) {
            // Verify the reset code
            $user = $this->ion_auth->forgotten_password_check($code);

            if ($user) {
                $new_password = $this->input->post('new_password');
                $this->ion_auth->reset_password($user->identity, $new_password);
                $this->session->set_flashdata('message', 'Password has been reset.');
                redirect('login');
            } else {
                $this->session->set_flashdata('message', 'Invalid reset code.');
                redirect('password_reset/request');
            }
        } else {
            $this->session->set_flashdata('message', 'Reset code required.');
            redirect('password_reset/request');
        }
    }
}
