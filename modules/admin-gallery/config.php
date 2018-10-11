<?php
/**
 * admin-gallery config file
 * @package admin-gallery
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'admin-gallery',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/admin-gallery',
    '__files' => [
        'modules/admin-gallery' => [ 'install', 'remove', 'update' ],
        'theme/admin/component/gallery' => [ 'install', 'remove', 'update' ],
        'theme/admin/static/js/gallery.js' => [ 'install', 'remove', 'update' ]
    ],
    '__dependencies' => [
        'db-mysql',
        'admin'
    ],
    '_services' => [],
    '_autoload' => [
        'classes' => [
            'AdminGallery\\Model\\Gallery' => 'modules/admin-gallery/model/Gallery.php',
            'AdminGallery\\Controller\\GalleryController'   => 'modules/admin-gallery/controller/GalleryController.php'
        ],
        'files' => []
    ],

    '_routes' => [
        'admin' => [
            'adminGallery'   => [
                'rule'          => '/gallery',
                'handler'       => 'AdminGallery\\Controller\\Gallery::index'
            ],
            'adminGalleryEdit' => [
                'rule'          => '/gallery/:id',
                'handler'       => 'AdminGallery\\Controller\\Gallery::edit'
            ],
            'adminGalleryFilter' => [
                'rule'          => '/gallery-filter',
                'handler'       => 'AdminGallery\\Controller\\Gallery::filter'
            ],
            'adminGalleryRemove' => [
                'rule'          => '/gallery/:id/delete',
                'handler'       => 'AdminGallery\\Controller\\Gallery::remove'
            ]
        ]
    ],

    'admin' => [
        'menu' => [
            'component' => [
                'label'     => 'Component',
                'order'     => 1500,
                'icon'      => 'puzzle-piece',
                'submenu'   => [
                    'gallery'    => [
                        'label'     => 'Gallery',
                        'perms'     => 'read_gallery',
                        'target'    => 'adminGallery',
                        'order'     => 1500
                    ]
                ]
            ]
        ]
    ],

    'form' => [
        'admin-gallery' => [
            'name' => [
                'type'  => 'text',
                'label' => 'Name',
                'rules' => [
                    'required' => true
                ]
            ],
            'images' => [
                'type' => 'hidden',
                'label' => 'Images',
                'rules' => []
            ]
        ],
        'admin-gallery-image' => [
            'image' => [
                'type' => 'file',
                'label' => 'Gallery Image',
                'rules' => [
                    'file' => 'image/*'
                ]
            ]
        ],
        'admin-gallery-index' => [
            'q' => [
                'type' => 'search',
                'label'=> 'Find Gallery',
                'nolabel'=> true,
                'rules'=> []
            ]
        ]
    ],
    'formatter' => [
        'gallery' => [
            'user' => [
                'type' => 'object',
                'model' => 'User\\Model\\User'
            ],
            'images' => 'json',
            'updated' => 'date',
            'created' => 'date'
        ]
    ]
];