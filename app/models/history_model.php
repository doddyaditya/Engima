<?php

class history_model{
    private $data;
    private $db;

    public function __construct()
    { 
        $this->db = new Database;
        //$this->data = $_GET['id'];
    }

    public function ShowMovieHistoryNew($id)
    {
        if ($id == "home") {
            return;
        }

        $ch = curl_init();
        $init_url = "http://localhost:3005/movie_details";

        $user_id = "user_id=" . $id;

        $query_string = "?" . $user_id;
        $url = $init_url . $query_string;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $transaction_list = curl_exec($ch);
        $transaction_list = json_decode($transaction_list, true)['values'];

        $return_list = array();

        foreach ($transaction_list as $movie) {
            $ch = curl_init();

            $init_url = "https://api.themoviedb.org/3/movie";

            $api_key = "api_key=24e24f2fe04971613e54501530daca7e";
            $language = "language=en-US";
            $id = $movie['id_movie'];

            $query_string = "?" . $api_key . "&" . $language;

            $url = $init_url . "/" . $id . $query_string;

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $output = curl_exec($ch);
            $output = json_decode($output, true);

            $photo_web_path = "https://image.tmdb.org/t/p/w185_and_h278_bestv2";

            $photo = $photo_web_path . $output['poster_path'];
            $name = $output['original_title'];

            $movie['photo'] = $photo;
            $movie['name'] = $name;

            array_push($return_list, $movie);
        }
        return $return_list;
    }

    public function ShowMovieHistory($id)
    {
        $this->db->query("SELECT * FROM (((watches JOIN shows ON watches.id_schedule = shows.id_schedule) JOIN schedules ON watches.id_schedule = schedules.id) JOIN movies ON shows.id_movie = movies.id) WHERE watches.id_user = $id ORDER BY date_of_play DESC");
        //$this->db->bind('id_user', $id);
        return $this->ShowMovieHistoryNew($id);
    }

    public function ShowReviewNew($id)
    {
        $ch = curl_init();
        $init_url = "http://localhost:3005/status";

        $user_id = "user_id=" . $id;

        $total_result = array();

        $query_string = "?" . $user_id;
        $url = $init_url . $query_string;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $review_list = curl_exec($ch);
        $review_list = json_decode($review_list, true)['values'];

        $review_movie_id = array();

        foreach ($review_list as $rv) {
            array_push($review_movie_id, $rv['film_id']);
        }

        return $review_movie_id;
    }

    public function ShowReview($id)
    {
        $this->db->query("SELECT id_movie FROM reviews WHERE reviews.id_user='$id'");
        return $this->db->resultSet();
    }

    public function DeleteReview($iduser,$idmovie)
    {
        // $this->db->query("DELETE FROM reviews WHERE reviews.id_user = '$iduser' AND reviews.id_movie = '$idmovie'");
        // $this->db->execute();
        // return $this->db->rowCount();
        $ch = curl_init();
        $init_url = "http://localhost:3005/set_rating_and_review";

        $rating = "rating=" . "NULL";
        $review = "review=" . "NULL";
        $film_id = "film=" . $idmovie;
        $user = "user=" . $iduser;
        $url = $init_url;
        $post_field = $rating . "&" . $review . "&" . $film_id . "&" . $user;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        $status = json_decode($output, true)['status'];

        if ($status == 200) {
            $output = "SUCCESS";
        } else {
            $output = "FAIL";
        }
        return $output;
    }

    public function InsertReview($iduser,$idmovie,$star,$content)
    {
        // $this->db->query("INSERT INTO reviews (`id`, `id_movie`, `id_user`, `content`, `rating`) VALUES ('', '$idmovie', '$iduser', '$content', '$star')");
        // $this->db->execute();
        // return $this->db->rowCount();
        $ch = curl_init();
        $init_url = "http://localhost:3005/set_rating_and_review";

        $rating = "rating=" . $star;
        $review = "review=" . $content;
        $film_id = "film=" . $idmovie;
        $user = "user=" . $iduser;
        $url = $init_url;
        $post_field = $rating . "&" . $review . "&" . $film_id . "&" . $user;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        $status = json_decode($output, true)['status'];

        if ($status == 200) {
            $output = "SUCCESS";
        } else {
            $output = "FAIL";
        }
        return $output;
    }

    public function EditReview($iduser,$idmovie,$star,$content)
    {
        // $this->db->query("UPDATE reviews SET `content` = '$content', `rating` = '$star' WHERE reviews.id_movie = $idmovie AND reviews.id_user = $iduser;");
        // $this->db->execute();
        // return $this->db->rowCount();
        $ch = curl_init();
        $init_url = "http://localhost:3005/set_rating_and_review";

        $rating = "rating=" . $star;
        $review = "review=" . $content;
        $film_id = "film=" . $idmovie;
        $user = "user=" . $iduser;
        $url = $init_url;
        $post_field = $rating . "&" . $review . "&" . $film_id . "&" . $user;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        $status = json_decode($output, true)['status'];

        if ($status == 200) {
            $output = "SUCCESS";
        } else {
            $output = "FAIL";
        }
        return $output;
    }


}