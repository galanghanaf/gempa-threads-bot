<?php

class Database
{
    private static function conn()
    {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        return $conn;
    }

    public static function login($table, $where, $username, $password)
    {
        // cek apakah username/email yang diinput ada atau tidak
        $result = mysqli_query(self::conn(), "SELECT * FROM $table WHERE $where = '$username'");

        // cek username/email menggunakan mysqli_num_rows secara satu persatu
        // apabila ada, maka akan bernilai 1
        if (mysqli_num_rows($result) === 1) {

            // mengecek password dari yang inputkan di $password,
            // disamakan dengan password di database menggunakan $user
            $user = mysqli_fetch_assoc($result);
            if ($password == $user["password"] || $password == $user["pass"]) {
                // set session
                $_SESSION["login"] = [
                    $where => $user[$where],
                ];
                return true;
            }
            return false;
        } else {
            return false;
        }
    }


    public static function query($query)
    {
        $result = mysqli_query(self::conn(), $query);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function query_check($table, $column, $where)
    {
        $result = mysqli_query(self::conn(), "SELECT * FROM $table WHERE $column = '$where'");

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function delete($table, $column, $where)
    {
        mysqli_query(self::conn(), "DELETE FROM $table WHERE $column = $where");
        return mysqli_affected_rows(self::conn());
    }

    public static function insert($table, $data, $redirect)
    {


        $key = array_keys($data);
        $val = array_values($data);
        $query = "INSERT INTO $table (" . implode(', ', $key) . ") " . "VALUES ('" . implode("', '", $val) . "')";

        try {
            mysqli_query(self::conn(), $query);
        } catch (mysqli_sql_exception $error) {
            if ($error->getCode() == 1062) {
                Message::set_message('Data Sudah Digunakan!');
                header("Location: " . BASE_URL . $redirect);
                exit();
                // Menampilkan Pesan Error
                // throw $error; 
            } else {
                Message::set_message('Data Gagal Di Input!');
                header("Location: " . BASE_URL . $redirect);
                exit();
                // Menampilkan Pesan Error
                // throw $error; 
            }
        }
        return mysqli_affected_rows(self::conn());
    }

    public static function update($table, $data, $where, $redirect)
    {

        $where_key = array_keys($where);
        $where_val = array_values($where);

        $cols = array();

        foreach ($data as $key => $val) {
            if ($val != NULL) // check if value is not null then only add that column to array
            {
                $cols[] = "$key = '$val'";
            }
        }
        $query = "UPDATE $table SET " . implode(', ', $cols) .
            " WHERE " . implode(', ', $where_key) . "=" . implode("', '", $where_val);

        try {
            mysqli_query(self::conn(), $query);
        } catch (mysqli_sql_exception $error) {
            if ($error->getCode() == 1062) {
                Message::set_message('Data Sudah Digunakan!');
                header("Location: " . BASE_URL . $redirect);
                exit();
                // Menampilkan Pesan Error
                // throw $error; 
            } else {
                Message::set_message('Data Gagal Di Input!');
                header("Location: " . BASE_URL . $redirect);
                exit();
                // Menampilkan Pesan Error
                // throw $error;
            }
        }

        return mysqli_affected_rows(self::conn());
    }

    public static function upload($file, $location, $size, $name = NULL)
    {
        $file_name = $_FILES[$file]['name'];
        $file_size = $_FILES[$file]['size'];
        $file_tmp = $_FILES[$file]['tmp_name'];

        // cek apakah yang diuploud sesuai dengan valid_file_extension
        $valid_file_extension = ['jpg', 'jpeg', 'png'];
        $file_extension = explode('.', $file_name); //memecah nama file dan tipe filenya
        $file_extension = strtolower(end($file_extension)); // memaksa mengubah nama tipe file huruf kecil

        // mengecek apakah file yang diuploud sesuai dengan format valid_file_extension
        if (!in_array($file_extension, $valid_file_extension)) {
            return false;
        }

        // cek jika ukuran file terlalu besar
        if ($file_size > $size) {
            return false;
        }

        // lolos pengecekan, file siap diuploud
        // generate nama file baru
        $new_file_name = $name != NULL ? $name : pathinfo($file_name, PATHINFO_FILENAME);
        $new_file_name .= '.';
        $new_file_name .= $file_extension;
        move_uploaded_file($file_tmp, $location . $new_file_name);

        return $new_file_name;
    }
}
