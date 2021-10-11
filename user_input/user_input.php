<?php

// print_r($_SERVER); echo PHP_EOL;

if ( array_key_exists('HTTP_X_ACCESS_TOKEN', $_SERVER) )
{
    if ( $_SERVER['HTTP_X_ACCESS_TOKEN'] == 'SECRET_TOKEN')
    {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
        {
            if ( array_key_exists('page', $_GET) )
            {
                if ( in_array($_GET['page'], ['page1', 'page2', 'page3']) )
                {
                    echo "<p> requested page: ".htmlentities($_GET['page'])."</p>";
                    if ( array_key_exists('CONTENT_TYPE', $_SERVER) and $_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded')
                    {
                        if ( count($_POST) > 0 )
                        {
                            echo "<p> with POST sent ".count($_POST)." variables </p>";
                            echo "<ul>";
                            foreach($_POST as $key => $value) {
                                echo "<li>content of ".htmlentities($key).": ".htmlentities($value)."</li>";
                            }
                            echo "</ul>";

                        } else { echo "<h1>7. error, data not set </h1>"; }

                    } else { echo "<h1>6. error, incorrect data type </h1>"; }

                } else { echo "<h1>5. error, incorrect page </h1>"; }

            } else { echo "<h1>4. error, no page type provided </h1>"; }

        } else { echo "<h1>3. forbidden, wrong method </h1>"; }

    } else { echo "<h1>2. forbidden, incorrect token </h1>"; }

} else { echo "<h1>1. forbidden, token not set </h1>"; }
