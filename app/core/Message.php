<?php

class Message
{
    public static function set_message($pesan)
    {
        $_SESSION['message'] = [
            'pesan' => $pesan,
        ];
    }

    public static function show_message()
    {
        if (isset($_SESSION['message'])) {
            echo "
                <script>
                    alert('". $_SESSION['message']['pesan'] . "');
                </script>
                ";
            unset($_SESSION['message']);
        }
    }
}