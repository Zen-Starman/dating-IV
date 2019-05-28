<!--CREATE TABLE member-->
<!--(-->
<!--    member_id INT AUTO_INCREMENT PRIMARY KEY,-->
<!--    fname VARCHAR(40) NOT NULL,-->
<!--    lname VARCHAR(40) NOT NULL,-->
<!--    age INT NOT NULL,-->
<!--    gender VARCHAR(10) NOT NULL,-->
<!--    phone VARCHAR(14) NOT NULL,-->
<!--    email VARCHAR(60) NOT NULL,-->
<!--    state VARCHAR(20) NOT NULL,-->
<!--    seeking VARCHAR(10) NOT NULL,-->
<!--    premium TINYINT(1) NOT NULL,-->
<!--    image VARCHAR(120)-->
<!--);-->
<!--CREATE TABLE interest-->
<!--(-->
<!--    interest_id INT AUTO_INCREMENT PRIMARY KEY,-->
<!--    interest VARCHAR(20),-->
<!--    type TINYINT(1) NOT NULL-->
<!--);-->
<!--CREATE TABLE member_interest-->
<!--(-->
<!--    member-id INT NOT NULL,-->
<!--    FOREIGN KEY (member_id) REFERENCES member(member_id),-->
<!--    interest_id INT NOT NULL,-->
<!--    FOREIGN KEY (interest_id) REFERENCES interest(interest_id)-->
<!--);-->

<?php
/**
 * Database class
 * User: Zane Stearman
 * @version 1.0.0

 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

require "/home/ztsgreen/config.php";

class Database
{
    private $_dbh;

    function __construct()
    {
        $this->connect();
    }

    function connect()
    {
        try{
            //Instantiate a db object
            $this->_dbh = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
            echo "Connected!!!!";
            return $this->_dbh;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function insertMember($member)
    {
        $sql = "INSERT INTO member (member_id, fname, lname, age, gender, phone, email, state, seeking, bio, premium)
                VALUES (null, :fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio, :premium)";

        $statement = $this->connect()->prepare($sql);
        $fname = $member->getFname();
        $lname = $member->getLname();
        $age = $member->getAge();
        $gender = $member->getGender();
        $phone = $member->getPhone();
        $email = $member->getEmail();
        $state = $member->getState();
        $seeking = $member->getSeeking();
        $bio = $member->getBio();
        $premium = $member->getPremium();

        $statement->bindParam(':fname', $fname, 2);
        $statement->bindParam(':lname', $lname, 2);
        $statement->bindParam(':age', $age, 1);
        $statement->bindParam(':gender', $gender, 2);
        $statement->bindParam(':phone', $phone, 2);
        $statement->bindParam(':email', $email, 2);
        $statement->bindParam(':state', $state, 2);
        $statement->bindParam(':seeking', $seeking, 2);
        $statement->bindParam(':bio', $bio, 2);
        $statement->bindParam(':premium', $premium, 2);

        $statement->execute();

        if ($premium == 1){
            //need to set interests***

        }


    }

    function getMembers()
    {
        //1. Define the query
        $sql = "SELECT * FROM members
                ORDER BY last, first";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters

        //4. Execute the statement
        $statement->execute();

        //5. Return the result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function getMember($id)
    {
        $sql = "SELECT * FROM memmbers
        WHERE id = :id";

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':id', $id, PDO::PARAM_STR);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row;

    }

    function getInterests($id)
    {
        $sql = "SELECT interests FROM memmbers
        WHERE id = :id";

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':id', $id, PDO::PARAM_STR);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row;


    }
}