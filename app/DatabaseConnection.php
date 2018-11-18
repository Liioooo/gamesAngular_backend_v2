<?php

namespace app;
use PDO;
use PDOException;

class DatabaseConnection {

    private $conn;

    public function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "liogames";
        $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $this->conn->exec("set names utf8");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createUser($user, $passwd) {
        try {
            $passwd =  sha1($passwd);
            $stmt = $this->conn->prepare("INSERT INTO users (username, passwd, picturePath) VALUES (:username, :passwd, '/profilePictures/default-profile-img.svg')");
            $stmt->bindParam(':username', $user);
            $stmt->bindParam(':passwd', $passwd);
            $stmt->execute();
            return "success";
        } catch(PDOException $e) {
            return 'error';
        }
    }
    
    public function deleteUser($userID) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE pk_userID = :userID");
            $stmt->bindParam(":userID", $userID);
            $stmt->execute();
            $stmt = $this->conn->prepare("DELETE FROM highscores WHERE pk_fk_userID = :userID");
            $stmt->bindParam(":userID", $userID);
            $stmt->execute();
            return null;
        } catch(PDOException $e) {
            return $e;
        }
    }
    
    public function checkCredentials($user, $passwd) {
        try {
            $stmt = $this->conn->prepare("SELECT passwd FROM users WHERE username = :username");
            $stmt->bindParam(':username', $user);
            $stmt->execute();
            $result = $stmt->fetch();
            if($result == null) {
                return "noSuchUser";
            }
            if($result['passwd'] == sha1($passwd)) {
                return "success";
            }
            return "invalid";
        } catch(PDOException $e) {
            return -1;
        }
    }
    
    public function getIDbyUsername($name) {
        try {
            $stmt = $this->conn->prepare("SELECT pk_userID FROM users WHERE username = :user");
            $stmt->bindParam(":user", $name);
            $stmt->execute();
            return $stmt->fetch()['pk_userID'];
        } catch(PDOException $e) {
            return -1;
        }
    }
    
    public function getUsernameByID($id) {
        try {
            $stmt = $this->conn->prepare("SELECT username FROM users WHERE pk_userID = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch()['username'];
        } catch(PDOException $e) {
            return null;
        }
    }
    public function updateUsername($userID, $newUsername) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET username = :newUser WHERE pk_userID = :userID");
            $stmt->bindParam(':newUser', $newUsername);
            $stmt->bindParam(':userID', $userID);
            $stmt->execute();
            return "success";
        } catch(PDOException $e) {
            return $e->getCode();
        }
    }
    
    public function getProfilePicture($userID) {
        try {
            $stmt = $this->conn->prepare("SELECT picturePath FROM users WHERE pk_userID = :id");
            $stmt->bindParam(":id", $userID);
            $stmt->execute();
            return $stmt->fetch()['picturePath'];
        } catch(PDOException $e) {
            return '/profilePictures/default-profile-img.svg';
        }
    }
    public function getUserDescription($userID) {
        try {
            $stmt = $this->conn->prepare("SELECT description FROM users WHERE pk_userID = :id");
            $stmt->bindParam(":id", $userID);
            $stmt->execute();
            return $stmt->fetch()['description'];
        } catch(PDOException $e) {
            return '';
        }
    }
    
    public function updateUserDescription($userID, $newDescription) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET description = :newDescription WHERE pk_userID = :userID");
            $stmt->bindParam(':newDescription', $newDescription);
            $stmt->bindParam(':userID', $userID);
            $stmt->execute();
            return "success";
        } catch(PDOException $e) {
            return $e->getCode();
        }
    }
    public function updateProfilePicture($userID, $newPicture) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET picturePath = :newPicture WHERE pk_userID = :userID");
            $stmt->bindParam(':newPicture', $newPicture);
            $stmt->bindParam(':userID', $userID);
            $stmt->execute();
            return "success";
        } catch(PDOException $e) {
            return $e->getCode();
        }
    }
    
    public function updatePassword($userID, $newPassword, $oldPassword) {
        if($this->checkCredentials($this->getUsernameByID($userID), $oldPassword) != 'success') {
            return 'invalid';
        }
        $newPassword = sha1($newPassword);
        try {
            $stmt = $this->conn->prepare("UPDATE users SET passwd = :newPasswd WHERE pk_userID = :userID");
            $stmt->bindParam(":userID", $userID);
            $stmt->bindParam(":newPasswd", $newPassword);
            $stmt->execute();
            return 'success';
        } catch(PDOException $e) {
            return $e->getCode();
        }
    }
    public function saveHighscore($gameID, $userID, $score) {
        $currentScore = $this->getHighscoreForUser($gameID, $userID);
        if($currentScore > $score) {
            return $currentScore;
        }
        
        try {
            $stmt = $this->conn->prepare("REPLACE INTO highscores (pk_fk_userID, pk_gameID, score) VALUES (:userID, :gameID, :score)");
            $stmt->bindParam(":userID", $userID);
            $stmt->bindParam(":gameID", $gameID);
            $stmt->bindParam(":score", $score);
            $stmt->execute();
            return $score;
        } catch(PDOException $e) {
            return 'error';
        }
    }
    public function getHighscoreForUser($gameID, $userID) {
        try {
            $stmt = $this->conn->prepare("SELECT score from highscores WHERE pk_fk_userID = :userID AND pk_gameID = :gameID");
            $stmt->bindParam(":userID", $userID);
            $stmt->bindParam(":gameID", $gameID);
            $stmt->execute();
            return $stmt->fetch()['score'];
        } catch(PDOException $e) {
            return $e->getCode();
        }
    }
    public function getHighscore($gameID) {
        try {
            $stmt = $this->conn->prepare("SELECT max(score) as highscore from highscores WHERE pk_gameID = :gameID");
            $stmt->bindParam(":gameID", $gameID);
            $stmt->execute();
            return $stmt->fetch()['highscore'];
        } catch(PDOException $e) {
            return $e->getCode();
        }
    }
    public function getHighscoresGame($gameID) {
        try {
            $stmt = $this->conn->prepare("SELECT highscores.score as 'score', users.username FROM highscores inner join users on highscores.pk_fk_userID = users.pk_userID WHERE highscores.pk_gameID = :gameID ORDER BY `score` DESC");
            $stmt->bindParam(":gameID", $gameID);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'app\database_helper_classes\UserScore');
        } catch(PDOException $e) {
            return null;
        }
    }
    public function getHighscoresUser($username) {
        try {
            $stmt = $this->conn->prepare("select score, case pk_gameID WHEN 3 THEN '4 Gewinnt' WHEN 1 THEN 'Flappy Bird' WHEN 0 THEN 'FallingBlocks' END as 'gameName' from highscores inner join users on highscores.pk_fk_userID = users.pk_userID WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'app\database_helper_classes\GameScore');
        } catch(PDOException $e) {
            return null;
        }
    }
}