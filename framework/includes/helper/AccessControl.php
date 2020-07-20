<?php

function isClientAdmin()
{
    if ($_SESSION['user']['system_role'] == 'CLIENT ADMIN')
        return true;

    return false;
}