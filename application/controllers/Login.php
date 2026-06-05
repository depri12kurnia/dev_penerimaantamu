<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_settings');
        $this->load->library('ion_auth');
        $this->load->config('google');
    }

    // 1. Menampilkan Halaman Login & Tombol Google
    public function index()
    {

        if ($this->ion_auth->logged_in()) {
            redirect('reservasi');
        }

        $client = new Google_Client();
        $client->setClientId($this->config->item('client_id', 'google'));
        $client->setClientSecret($this->config->item('client_secret', 'google'));
        $client->setRedirectUri($this->config->item('redirect_uri', 'google'));
        $client->addScope("email");
        $client->addScope("profile");

        $data['website'] = $this->M_settings->get_all_settings();
        $data['title'] = 'Login Tamu - Penerimaan Tamu Poltekkes Jakarta III';
        $data['content'] = 'login';
        $data['google_login_url'] = $client->createAuthUrl();

        $this->load->view('layouts/userlte3', $data);
    }

    // 2. Menerima Kembalian (Callback) dari Google
    public function callback()
    {
        $client = new Google_Client();
        $client->setClientId($this->config->item('client_id', 'google'));
        $client->setClientSecret($this->config->item('client_secret', 'google'));
        $client->setRedirectUri($this->config->item('redirect_uri', 'google'));

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token);

            $google_oauth = new Google_Service_Oauth2($client);
            $google_info = $google_oauth->userinfo->get();

            $email = $google_info->email;
            $nama  = $google_info->name;
            $picture = $google_info->picture; // Ambil foto profil dari Google

            // Cek apakah email google ini sudah terdaftar
            $user = $this->db->get_where('users', ['email' => $email])->row();

            if ($user) {
                // UPDATE FOTO PROFIL GOOGLE TERBARU (Opsional namun disarankan)
                // Agar menu navbar kamu otomatis menampilkan foto profil Google user
                $this->db->update('users', ['profile_pic' => $picture], ['id' => $user->id]);

                // ---- PROSES BUAT SESSION ION AUTH SECARA MANUAL ----
                $session_data = [
                    'identity'             => $user->email,
                    'email'                => $user->email,
                    'user_id'              => $user->id,
                    'id'                   => $user->id,    // KUNCI UTAMA 1: Wajib ada key 'id' ini!

                    // KUNCI UTAMA 2: Jika username di database kosong, ambil dari nama depan email
                    'username'             => (!empty($user->username)) ? $user->username : explode('@', $user->email)[0],

                    'old_last_login'       => $user->last_login,
                    'last_check'           => time(),
                    'profile_pic'          => $picture
                ];
                $this->session->set_userdata($session_data);

                // Pemicu log waktu login terakhir bawaan Ion Auth
                $this->ion_auth->update_last_login($user->id);

                redirect('reservasi');
            } else {
                // JIKA EMAIL BELUM TERDAFTAR:
                // Kita gunakan Pilihan B (Tolak dan lempar pesan error ke flashdata)
                $this->session->set_flashdata('message', 'Email Google Anda belum terdaftar di sistem.');
                redirect('login');
            }
        } else {
            redirect('login');
        }
    }

    // 3. Method Logout
    public function logout()
    {
        $this->ion_auth->logout();
        redirect('login');
    }
}
