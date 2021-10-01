<?php

function lang($phrase)
{
    static $langs = array(

        // Navbar links

        'DEFAULT_TITLE'    =>       'Admin',
        'HOME'             =>       'Home',
        'CATEGORIES'       =>       'Categories',
        'ITEMS'            =>       'Items',
        'MEMBERS'          =>       'Members',
        'COMMENTS'         =>       'Comments',
        'STATISTICS'       =>       'Statistics',
        'LOGS'             =>       'Logs',
        'WELCOME'          =>       'Welcome',
        'VIEW_STORE'       =>       'view store',
        'EDIT_PROFILE'     =>       'Edit profile',
        'SETTINGS'         =>       'Settings',
        'LOGOUT'           =>       'Logout',
    );

    return $langs[$phrase];
}
