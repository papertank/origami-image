<?php
return array(

    'url' => 'media',

    'sizes_route' => 'media/size',

    'paths' => array(
        public_path('media')
    ),

    'sizes' => array(
        'thumb' => [120, 90],
        'small' => [240, 180],
        'medium' => [480, 360],
        'large' => [960, 720],
    ),

    'templates' => [

        'thumb' => function($image) {
            return $image->fit(120, 90);
        },
        'small' => function($image) {
            return $image->fit(240, 180);
        },
        'medium' => function($image) {
            return $image->fit(480, 360);
        },
        'large' => function($image) {
            return $image->fit(960, 720);
        }

    ],

    'cache' => 43200,

);