<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class DB{
    public $db;

    function __construct(){
        $this->db = new mysqli("localhost", "root", "root", "projekatrtsi");

        if ($this->db->connect_errno) {
            die("Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error);
        }   
    }

    function __desctruct(){
        $this->db->close();
    }

    function logIN($username, $password){
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $this->db->query($query);

        return $result->fetch_assoc();
    }

    function getUserByID($user_id){
        $query = "SELECT * FROM users WHERE user_id = '$user_id'";
        $result = $this->db->query($query);

        return $result->fetch_assoc();
    }

    function deleteUserByID($user_id){
        $query = "SELECT * FROM users WHERE user_id = '$user_id'";
        $result = $this->db->query($query);

        $row = $result->fetch_assoc();

        $this->deleteCommentByUserID($user_id);
        $this->deleteOglasByUserID($user_id);
        $this->deleteUserLikesByUserID($user_id);

        $query = "DELETE FROM users WHERE user_id = '$user_id'";
        $this->db->query($query);
    }

    function deleteUserLikesByUserID($user_id) {
        $query = "SELECT * FROM user_likes WHERE user_id = '$user_id'";
        $result = $this->db->query($query);
    
        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $oglas_id = $row['oglas_id'];
    
                $query2 = "DELETE FROM user_likes WHERE user_id = $user_id AND oglas_id = $oglas_id";
                $this->db->query($query2);
    
                if ($row['liked'] === '1') {
                    $queryUpdateLikes = "UPDATE oglasi SET likes = likes - 1 WHERE oglas_id = $oglas_id";
                    $queryUpdateLikesRes = $this->db->query($queryUpdateLikes);
                    if (!$queryUpdateLikesRes) {
                        echo "Error updating likes: " . $this->db->error;
                    }
                }
            }
        }
    }
    

    function deleteOglasByUserID($user_id){
        $query = "SELECT * FROM oglasi WHERE user_id = '$user_id'";
        $result = $this->db->query($query);

        while($row = $result->fetch_assoc()){
            $oglas_id = $row['oglas_id'];

            $this->deleteCommentByOglasID($oglas_id);
            $query2 = "DELETE FROM oglasi WHERE oglas_id = '$oglas_id'";
            $this->db->query($query2);
            
            $query3 = "DELETE FROM user_likes WHERE oglas_id = '$oglas_id'";
            $this->db->query($query3);
        }
    }

    function deleteCommentByUserID($user_id){
        $query = "DELETE FROM comments WHERE user_id = '$user_id'";
        $this->db->query($query);
    }

    function deleteCommentByOglasID($oglas_id){
        $query = "DELETE FROM comments WHERE oglas_id = '$oglas_id'";
        $this->db->query($query);
    }

    function deleteCommentByCommentID($comment_id){
        $query = "DELETE FROM comments WHERE comment_id = '$comment_id'";
        $this->db->query($query);
    }
    
    function deleteOglasByOglasID($oglas_id){
        $this->deleteCommentByOglasID($oglas_id);

        $query1 = "DELETE FROM user_likes WHERE oglas_id = '$oglas_id'";
        $this->db->query($query1);
        

        $query2 = "DELETE FROM oglasi WHERE oglas_id = '$oglas_id'";
        $this->db->query($query2);
    }
}
?>