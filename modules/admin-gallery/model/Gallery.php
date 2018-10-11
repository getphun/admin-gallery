<?php
/**
 * gallery model
 * @package admin-gallery
 * @version 0.0.1
 * @upgrade true
 */

namespace AdminGallery\Model;

class Gallery extends \Model
{
    public $table = 'gallery';
    public $q_field = 'name';
}