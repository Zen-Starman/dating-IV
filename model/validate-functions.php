<?php
/*
 * Validate functions for Dating2b
 *
 * @author:      Zane Stearman
 * @file:        validate-functions.php
 * @date:        05/05/2019
 */
/* Validate the form
 * @return boolean
 */
function validForm()
{
    global $f3;
    $isValid = true;

    if (!validFirstName($f3->get('f_name'))) {
        $isValid = false;
        $f3->set("errors['f_name']", "Please enter your first name.");
    }

    if (!validLastName($f3->get('l_name'))) {
        $isValid = false;
        $f3->set("errors['l_name']", "Please enter your first name.");
    }

    if (!validAge($f3->get('age'))) {
        $isValid = false;
        $f3->set("errors['age']", "Please enter your age.");
    }

    if (!validPhone($f3->get('phone'))) {
        $isValid = false;
        $f3->set("errors['phone']", "Please enter valid phone number.");
    }

    return $isValid;
}

function validFirstName($f_name){
    return (!empty($f_name) && ctype_alpha($f_name));
}

function validLastName($l_name){
    return (!empty($l_name) && ctype_alpha($l_name));
}

function validAge($age){
    return (!empty($age) && ctype_digit($age) && ($age >= 18 && $age <= 118));
}

function validPhone($phone){
    //this one is a bit tricky, had to do some searching on the web.
    return (!empty($phone) && ctype_digit($phone) &&
        preg_match(
            "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i", $phone
        ));
}

function validEmail($email){
    return (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL));
}

function validOutdoor($outdoor){
    global $f3;

    if (empty($outdoor)) {
        return true;
    }

    foreach ($outdoor as $interest) {
        if (!in_array($interest, $f3->get('outdoor'))){
            return false;
        }
    }
    return true;
}


function validIndoor($indoor){
    global $f3;

    if (empty($indoor)) {
        return true;
    }

    foreach ($indoor as $interest) {
        if (!in_array($interest, $f3->get('indoor'))) {
            return false;
        }
    }
    return true;
}

function validInterests($f3)
{
    global $f3;

    $isValid = true;
    if (!validOutdoor($f3->get('outdoor')))
    {
        $isValid = false;
        $f3->set("errors['interests']", "Outdoor interest errors..");
    }
    if (!validIndoor($f3->get('indoor')))
    {
        $isValid = false;
        $f3->set("errors['interests']", "Indoor interest errors..");
    }

    return $isValid;
}

