<?php

/*
Funkcija za preverjanje uporabniškega imena
    - ime ne sme biti numerično
    - ime mora biti dolžine od 6 do 20 znakov
*/
function usernameValid ($username)
{
    if (is_numeric($username))
        return "Username can't be numeric!";

    elseif (strlen($username) < 6 || strlen($username) > 20)
        return "Username must be between 6 and 20 characters long!";
    
    else
        return "";
}

/*
Funkcija za preverjanje gesla
    - geslo in potrditveno geslo morata biti enaki
    - geslo ne sme biti numerično
    - geslo mora biti dolžine od 8 do 25 znakov
*/
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