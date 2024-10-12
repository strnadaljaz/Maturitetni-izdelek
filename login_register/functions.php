<?php

function usernameValid ($username) 
{
    if (is_numeric(value: $username))
        return "Username can't be numeric!";

    elseif (strlen($username) < 6 || strlen($username) > 20)
        return "Username must be between 6 and 20 characters long!";
    
    else
        return "";
}

function passwordValid ($password, $confirm_password)
{
    if ($password != $confirm_password)
        return "Passwords don't match!";

    elseif (is_numeric($password))
        return "Password can't be numeric!";

    elseif (strlen($password) < 8 || strlen($password) > 25)
        return "Password must be between 8 and 25 characters long!";

    else
        return "";
}