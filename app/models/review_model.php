<?php

class review_model
{
    private $data;
    private $db;

    public function __construct()
    {
        $this->db = new Database;
        //$this->data = $_GET['id'];
    }

    public function GetReview($iduser, $idmovie)
    {
        $this->db->query("SELECT * FROM reviews WHERE reviews.id_user='$iduser'");
        return $this->db->single();
        $ch = curl_init();
        $init_url = "http://localhost:3005/review";

        $user_id = "user_id=" . $iduser;
        $film_id = "film_id=" . $idmovie;

        $query_string = "?" . $user_id . "&" . $film_id;
        $url = $init_url . $query_string;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $review = curl_exec($ch);
        $review = json_decode($review, true)['values'][0];

        $ch = curl_init();

        $init_url = "https://api.themoviedb.org/3/movie";

        $api_key = "api_key=24e24f2fe04971613e54501530daca7e";
        $language = "language=en-US";
        $id = $idmovie;

        $query_string = "?" . $api_key . "&" . $language;

        $url = $init_url . "/" . $id . $query_string;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        $output = json_decode($output, true);

        $name = $output['original_title'];

        $review['name'] = $name;

        return $review;
    }
}
