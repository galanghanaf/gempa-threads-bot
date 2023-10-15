<?php

class Gempa extends Controller
{
    public function index()
    {
        $content = file_get_contents('https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json');
        $contents = utf8_encode($content);

        $data_earthquake = json_decode($contents, true);
        $key_earthquake = str_replace(':', '', $data_earthquake['Infogempa']['gempa']['Tanggal'] . $data_earthquake['Infogempa']['gempa']['Jam']);
        $id_earthquake = strtoupper(str_replace(' ', '', $key_earthquake));

        $earthquakeimg = $data_earthquake['Infogempa']['gempa']['Shakemap'];
        $earthquakethreads = "Gempa Bumi terjadi pada " . $data_earthquake['Infogempa']['gempa']['Tanggal'] . " " .
            $data_earthquake['Infogempa']['gempa']['Jam'] . ", di kordinat " .
            $data_earthquake['Infogempa']['gempa']['Lintang'] . " (Lintang Selatan), " .
            $data_earthquake['Infogempa']['gempa']['Bujur'] . " (Bujur Timur). Memiliki kekuatan magnitudo " .
            strtoupper($data_earthquake['Infogempa']['gempa']['Magnitude']) . " dengan kedalaman " .
            strtoupper($data_earthquake['Infogempa']['gempa']['Kedalaman']) . ". Untuk Lokasi " .
            $data_earthquake['Infogempa']['gempa']['Wilayah'] . ". Sumber https://data.bmkg.go.id/DataMKG/TEWS/" . $earthquakeimg;

        if (Database::query_check("tbl_earthquake", "id_earthquake", $id_earthquake) == false) {
            $data = [
                "id_earthquake" =>  $id_earthquake,
                "earthquakethreads" => $earthquakethreads,
                "earthquakeimg" => $earthquakeimg,
            ];

            Database::insert('tbl_earthquake', $data, '');

            $url = "http://localhost:8086";

            //open connection
            $curl = curl_init($url);

            //set the url, number of POST vars, POST data
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

            //So that curl_exec returns the contents of the cURL; rather than echoing it
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //execute post
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
            echo $response;
        } else {
        }
    }
}
