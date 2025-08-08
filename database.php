<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class DB{
    public $db;

    function __construct(){
        $this->db = new mysqli("localhost", "root", "root", "PIAproject");
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

        if($row['role'] == 'artist'){
            $this->deleteArtworkByArtistID($user_id);
        }

        $this->deleteUserLikesByUserID($user_id);

        $query = "DELETE FROM users WHERE user_id = '$user_id'";
        $this->db->query($query);
    }

    function deleteUserLikesByUserID($user_id) {
        $query = "SELECT * FROM user_likes WHERE user_id = '$user_id'";
        $result = $this->db->query($query);
    
        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $artwork_id = $row['artwork_id'];
    
                $query2 = "DELETE FROM user_likes WHERE user_id = $user_id AND artwork_id = $artwork_id";
                $this->db->query($query2);
    
                if ($row['liked'] === '1') {
                    $queryUpdateLikes = "UPDATE artworks SET likes = likes - 1 WHERE artwork_id = $artwork_id";
                    $queryUpdateLikesRes = $this->db->query($queryUpdateLikes);
                    if (!$queryUpdateLikesRes) {
                        echo "Error updating likes: " . $this->db->error;
                    }
                }
    
                if ($row['favorite'] === '1') {
                    $queryUpdateFavorites = "UPDATE artworks SET favorites = favorites - 1 WHERE artwork_id = $artwork_id";
                    $queryUpdateFavoritesRes = $this->db->query($queryUpdateFavorites);
                    if (!$queryUpdateFavoritesRes) {
                        echo "Error updating favorites: " . $this->db->error;
                    }
                }
            }
        }
    }
    

    function deleteArtworkByArtistID($artist_id){
        $query = "SELECT * FROM artworks WHERE artist_id = '$artist_id'";
        $result = $this->db->query($query);

        while($row = $result->fetch_assoc()){
            $artwork_id = $row['artwork_id'];

            $this->deleteCommentByArtworkID($artwork_id);
            $query2 = "DELETE FROM artworks WHERE artwork_id = '$artwork_id'";
            $this->db->query($query2);
            
            $query3 = "DELETE FROM user_likes WHERE artwork_id = '$artwork_id'";
            $this->db->query($query3);
        }
    }

    function deleteCommentByUserID($user_id){
        $query = "DELETE FROM comments WHERE user_id = '$user_id'";
        $this->db->query($query);
    }

    function deleteCommentByArtworkID($artwork_id){
        $query = "DELETE FROM comments WHERE artwork_id = '$artwork_id'";
        $this->db->query($query);
    }

    function deleteCommentByCommentID($comment_id){
        $query = "DELETE FROM comments WHERE comment_id = '$comment_id'";
        $this->db->query($query);
    }
    
    function deleteArtworkByArtworkID($artwork_id){
        $this->deleteCommentByArtworkID($artwork_id);

        $query = "DELETE FROM artworks WHERE artwork_id = '$artwork_id'";
        $this->db->query($query);
    }
}
?>