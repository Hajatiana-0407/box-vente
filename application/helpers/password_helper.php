<?php
function hash_it($pass)
{
    return password_hash($pass, PASSWORD_DEFAULT);
}

function de_hash_it($pass, $pass_hashed)
{
    return password_verify($pass, $pass_hashed);
}
