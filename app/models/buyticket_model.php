<?php

class buyticket_model{
    private $data;
    private $db;

    public function __construct()
    { 
        $this->db = new Database;
        //$this->data = $_GET['id'];
    }

    public function GetMovieTicket($idmovie,$idschedule)
    {
        $this->db->query("SELECT * FROM movies JOIN schedules WHERE movies.id='$idmovie' AND schedules.id='$idschedule'");
        return $this->db->single();
    }

    public function GetSeat($idschedule)
    {
        $this->db->query("SELECT * FROM seats WHERE seats.id_schedules='$idschedule'");
        $seat_list = $this->db->resultSet();
        $ch = curl_init();
        $init_url = "http://localhost:3005/seatsbychair";

        $film = "film=" . $idmovie;
        $schedule = "schedule=" . $idschedule;

        $total_result = array();

        foreach ($seat_list as $sl) {
            $chair = "chair_no=" . $sl['name'];
            $query_string = "?" . $film . "&" . $schedule . "&" . $chair;
            $url = $init_url . $query_string;

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $output = curl_exec($ch);
            $output = json_decode($output, true)['values'];
            if (!empty($output)) {
                $sl['vacant'] = 0;
                array_push($total_result, $sl);
            } else {
                array_push($total_result, $sl);
            }
        }
        return ($total_result);
    }

    public function GetUserId($username)
    {
        $this->db->query("SELECT * FROM users WHERE users.username='$username'");
        return $this->db->resultSet();
    }

    public function InsertWatches($id_user, $id_schedule, $chair)
    {
        $sql_insert = "INSERT INTO watches (`id_user`, `id_schedule`, `chair_number`) VALUES (:id_user, :id_schedule, :chair)";
        $this->db->query($sql_insert);
        $this->db->bind('id_user', $id_user);
        $this->db->bind('id_schedule', $id_schedule);
        $this->db->bind('chair', $chair);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function UpdateSeat($id_schedule,$idseat)
    {
        $this->db->query("UPDATE `seats` SET `vacant` = '0' WHERE seats.name = '$idseat' AND seats.id_schedules = '$id_schedule'");
        $this->db->execute();
        return $this->db->rowCount();
    }

    // public function InsertWatches($iduser,$idschedule,$idseat)
    // {
    //     $this->db->query("INSERT INTO `watches` (`id`, `id_user`, `id_schedule`, `chair_number`) VALUES ('', '$iduser', '$idschedule', '$idseat')");
    //     $this->db->execute();
    //     return $this->db->rowCount();
    // }


}