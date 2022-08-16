<?php

return array(


    'pdf' => array(
        'enabled' => true,

        'binary'  => env('WKHTMLTOPDF_PATH', '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf"'),
        'timeout' => false,
        'options' => array(
            'page-size' => 'letter',
            'images'=>true,
            
            'orientation' => 'Portrait',
            'disable-smart-shrinking' => true,
            'footer-center' => 'Page [page] of [toPage]',
            'footer-font-size' => 8,
            'footer-left' => 'PYC Members Use Only',
            'footer-right' =>'[date]'
       
       ),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => env('WKHTMLTOIMAGE_PATH', '"C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage"'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);

