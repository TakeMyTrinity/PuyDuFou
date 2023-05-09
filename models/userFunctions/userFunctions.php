<?php

// A class gathering all the methods related to a user

class userFunctions
{
    // this method allows to make sure that the user logged in and administrator
    public function isAdmin($admin)
    {
        if ($admin == 1) {
            return true;
        }
    }
}
