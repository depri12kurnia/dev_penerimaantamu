<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qontak
{

    protected $CI;
    protected $token;
    protected $channel_id;

    public function __construct()
    {
        // Ambil instance super-object CodeIgniter
        $this->CI = &get_instance();

        // Load config qontak
        $this->CI->config->load('qontak');

        // Set kredensial dari config ke properti class
        $this->token      = $this->CI->config->item('qontak_access_token');
        $this->channel_id = $this->CI->config->item('qontak_channel_id');
    }

    /**
     * Fungsi untuk mengirim pesan template WhatsApp
     *
     * @param string $to_name Nama penerima
     * @param string $to_number Nomor WA penerima (awalan 62)
     * @param string $template_id ID Template pesan dari Qontak
     * @param array $parameters Array parameter/variabel template dinamis
     * @return array Response dari API
     */
    public function send_message($to_name, $to_number, $template_id, $parameters = array())
    {
        $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';

        // Setup Payload
        $payload = array(
            "to_name" => $to_name,
            "to_number" => $to_number,
            "message_template_id" => $template_id,
            "channel_integration_id" => $this->channel_id,
            "language" => array(
                "code" => "id"
            ),
            "parameters" => array(
                "body" => $parameters
            )
        );

        // Inisialisasi dan Eksekusi cURL
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $this->token,
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        // Kembalikan respons dalam bentuk array
        if ($err) {
            return array('status' => 'error', 'message' => "cURL Error #:" . $err);
        } else {
            return json_decode($response, true);
        }
    }
}
