<?php

/**
 *  Dating Site Assignment part2
 *  Author: Zane Stearman
 *  Date:   04/18/2019
 */



//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require autoload file
require_once('vendor/autoload.php');
require_once('model/validate-functions.php');


//Create an instance of the Base class
$f3 = Base::instance();

session_start();

//Turn on Fat-Free error reporting
$f3->set('DEBUG', 3);

$f3->set('indoor',
    array('Food','Catnip','Naps','Nature shows/movies', 'Snacks','Boxes','Clothes','Self-Cleaning'));
$f3->set('outdoor',
    array('Looking Ready','Wild Catnip','Dirt Rolling','Boxes','Sun bathing','Bird watching',
        'Grass','Naps'));

$f3->set('states',
    array('Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware',
        'Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana',
        'Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska',
        'Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio',
        'Oklahoma','Oregon','Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas',
        'Utah','Vermont','Virginia','Washington','West Virginia','Wisconsin', 'Wyoming'));

//define a default route
$f3->route('GET /', function(){

    $view = new Template();

    echo $view->render('views/home.html');
});

//define profile paths
$f3->route('GET|POST /personal_info', function($f3){

    if (!empty($_POST)) {

        $f_name = $_POST['f_name'];
        $l_name = $_POST['l_name'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $premium = $_POST['premium'];

        $f3->set('f_name', $f_name);
        $f3->set('l_name', $l_name);
        $f3->set('age', $age);
        $f3->set('gender', $gender);
        $f3->set('phone', $phone);

        $f3->set('premium', $premium);

        if (validForm()) {

            $_SESSION['f_name'] = $f_name;
            $_SESSION['l_name'] = $l_name;
            $_SESSION['age'] = $age;
            $_SESSION['gender'] = $gender;
            $_SESSION['phone'] = $phone;

            if (isset($premium)){
                $member = new PremiumMember($f_name, $l_name, $age, $gender, $phone);
            } else {
                $member = new Member($f_name, $l_name, $age, $gender, $phone);
            }

            $_SESSION['member'] = $member;

            $f3->reroute('/profile');
        }
    }

    $view = new Template();
    echo $view->render('views/prsnInfo.html');
});

$f3->route('POST|GET /profile', function($f3){

    if (!empty($_POST)){

        $email = $_POST['email'];
        $states = $_POST['states'];
        $seeking = $_POST['seeking'];
        $bio = $_POST['bio'];


        $f3->set('email', $email);
        $f3->set('states', $states);
        $f3->set('seeking', $seeking);
        $f3->set('bio', $bio);

        if (validEmail($email)){

            $member = $_SESSION['member'];
            $member->setEmail($email);
            $member->setState($states);
            $member->setSeeking($seeking);
            $member->setBio($bio);

            $_SESSION['email'] = $email;
            $_SESSION['states'] = $states;
            $_SESSION['seeking'] = $seeking;
            $_SESSION['bio'] = $bio;


            if ($member instanceof PremiumMember) {
                $f3->reroute('/interests');
            } else {
                $f3->reroute('/summary');
            }
        }
    }

    $view = new Template();
    echo $view->render('views/profile.html');
});

$f3->route('POST|GET /interests', function($f3){

    if (!empty($_POST))
    {
        $indoor = $_POST['indoor'];
        $outdoor = $_POST['outdoor'];

        $f3->set('indoor', $indoor);
        $f3->set('outdoor', $outdoor);

        if (validIndoor($indoor) && validOutdoor($outdoor))
        {
            $member = $_SESSION['member'];
            $member->setInDoorInterests($indoor);
            $member->setOutDoorInterests($outdoor);

            $f3->reroute('/summary');
        }
    }

    $view = new Template();
    echo $view->render('views/interests.html');
});

$f3->route('POST|GET /summary', function($f3){

    $interests = "";
    $member = $_SESSION['member'];

    if (get_class($member) == 'Member') {
//        echo 'Worked?';
        $interests .= "for premium members only*";
        $_SESSION['interests'] = $interests;
    } else {
        $indoor = $member->getInDoorInterests();
        $outdoor = $member->getOutDoorInterests();
//        if ($indoor)
        if (sizeof($indoor) > 0){
            foreach ($indoor as $item) {
                $interests .= $item . ", ";
            }
        }
        if (sizeof($outdoor) > 0){
            foreach ($outdoor as $item) {
                $interests .= $item . ", ";
            }
        }
        $interests = rtrim($interests,', ');
        $_SESSION['interests'] = $interests;
    }
    $view = new Template();
    echo $view->render('views/summary.html');
});

//Run Fat-free
$f3->run(); // ->called the object operator