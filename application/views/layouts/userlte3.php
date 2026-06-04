<!DOCTYPE html>
<html lang="id">

<head>
    <?php $this->load->view('layouts/parts_user/head'); ?>
</head>

<body class="d-flex flex-column min-vh-screen">
    <!-- NAVBAR -->
    <?php $this->load->view('layouts/parts_user/navbar'); ?>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="flex-grow-1">
        <!-- Dynamic Page Content -->
        <?php
        if (isset($content) && !empty($content)) {
            // Cek apakah file view-nya benar-benar ada
            $view_path = APPPATH . 'views/' . $content . '.php';

            if (file_exists($view_path)) {
                $this->load->view($content, isset($data) ? $data : array());
            } else {
                echo "Error: File view tidak ditemukan di: " . $view_path;
            }
        } else {
            echo "Error: Variabel \$content belum didefinisikan di Controller.";
        }
        ?>
    </main>

    <!-- FOOTER -->
    <?php $this->load->view('layouts/parts_user/footer'); ?>
</body>

</html>