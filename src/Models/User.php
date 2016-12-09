<?php

namespace Ghysbrecht\Checkmein\Models;


class User
{
    public $id;
    public $name;
    public $username;
    public $email;
    public $created_at;
    public $updated_at;

    private $access_key;
    private $password;
    private $passwordconfirmation;
    private $db;


    public function __construct(\PDO $db = null)
    {
        $this->db = $db;
    }

    public function create(Array $values)
    {
        $this->name = $values['name'];
        $this->username = $values['username'];
        $this->email = $values['email'];
        $this->password = $values['password'];
        if(isset($values['passwordconfirmation'])){
            $this->passwordconfirmation = $values['passwordconfirmation'];
        }
        if(!isset($values['access_key'])){
            $random = new \Rych\Random\Random();
            $this->access_key = $random->getRandomString(16);
        }
        else{
            $this->access_key = $values['access_key'];
        }
        return $this;
    }

    public function save()
    {
        if($this->validate())
        {
            $query = "INSERT INTO users (name, username, password, email, access_key) VALUES (:name, :username, :password, :email, :access_key)";
            $statement = $this->db->prepare($query);
            $statement->execute([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => password_hash($this->password,PASSWORD_DEFAULT),
                'access_key' => $this->access_key
            ]);
            $this->id = $this->db->lastInsertId();
        }
        return false;
    }

    public function validate()
    {
        if(strlen($this->username) < 5 || strlen($this->username) > 60) throw new \Exception("Username length invalid");
        if(strlen($this->name) < 3 || strlen($this->name) > 60) throw new \Exception("Name length invalid");
        if($this->password != $this->passwordconfirmation) throw new \Exception("Passwords do not match");
        if(strlen($this->password) < 8 || strlen($this->password) > 255 ) throw new \Exception("Password length invalid");
        if(strlen($this->email) < 8 || strlen($this->email) > 128 ) throw new \Exception("Email invalid");

        return true;
    }

    public function find($username, $password)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->execute([
            'username' => $username,
        ]);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $statement->fetch();
        if(empty($result)) throw new \Exception("No user found!");
        if(!password_verify($password, $result['password'])) throw new \Exception("Incorrect password");
        return $this->create($result);
    }

    public function findUser($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->execute([
            'username' => $username,
        ]);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $statement->fetch();
        return $this->create($result);
    }

    public function getUserName()
    {
        return $this->username;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getAccessKey()
    {
        return $this->access_key;
    }
}
