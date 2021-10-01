<?php

    function lang( $phrase )
    {
        static $langs = array(
            'MESSAGE' => 'السلام عليكم',
            'ADMIN' => 'مدير'
        );

        return $langs[$phrase];
    }