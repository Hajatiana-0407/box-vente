<?php
function format_date(String $date, $option = null)
{
    $the_date = date_create($date);
    if ($option != null) {
        return date_format($the_date, "d/m/Y H:i:s");
    } else {
        return date_format($the_date, "d/m/Y H:i:s");
    }
}

function form_date($date)
{
    return date('d-m-Y H:i', strtotime($date));
}
function only_date($date)
{
    return date('d-m-Y', strtotime($date));
}

function Myurl($uri = '')
{
    $url = base_url() ; 
    // Vérifie si l'URL contient 'index.php'
    if (strpos(base_url(), 'index.php/') !== false) {
        // Remplace 'index.php/' par une chaîne vide
        $url = str_replace('index.php/', '', base_url());
    }

    return $url . $uri ; 
}
