<?php

function sanitizeString($raw) {
    $string = strip_tags($raw);
    $string = htmlentities($string);
    return $string;
}

function sanitizeInteger($raw) {
    return filter_var($raw, FILTER_SANITIZE_NUMBER_INT);
}

function validateInteger($sanitized) {
    return filter_var($sanitized, FILTER_VALIDATE_INT);
}

function validateUsername($username) {
    if (strlen($username) < 5 || strlen($username) > 20) {
        return false;
    }
    return true;
}

function validatePassword($password) {
    if (strlen($password) < 8) {
        return false;
    }
    return true;
}

function validateRole($role) {
    if ($role != 'Field Researcher' && $role != 'Administrator') {
        return false;
    }
    return true;
}

?>
