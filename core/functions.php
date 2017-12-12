<?php if (!defined("INITIALIZED")) die();

function ErrorDie($code) 
{
    http_response_code($code);
    die();
}