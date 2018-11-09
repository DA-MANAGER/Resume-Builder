<?php

return array(


    'pdf' => array(
        'enabled' => true,
        // For linux uncomment line - 9.
        'binary'  => base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'),
        
        // For windows uncomment line - 11.
        // 'binary' => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf"',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    
    'image' => array(
        'enabled' => true,
        'binary'  => base_path('vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
