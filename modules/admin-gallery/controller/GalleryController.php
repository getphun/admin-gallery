<?php
/**
 * Gallery management
 * @package admin-gallery
 * @version 0.0.1
 * @upgrade true
 */

namespace AdminGallery\Controller;

use AdminGallery\Model\Gallery;

class GalleryController extends \AdminController
{
    private function _defaultParams(){
        return [
            'title'             => 'Gallery',
            'nav_title'         => 'Component',
            'active_menu'       => 'component',
            'active_submenu'    => 'gallery',
            'total'             => 0
        ];
    }

    public function editAction(){
        if(!$this->user->login)
            return $this->show404();

        $id = $this->param->id;
        if(!$id && !$this->can_i->create_gallery)
            return $this->show404();
        elseif($id && !$this->can_i->update_gallery)
            return $this->show404();

        $old = null;
        $params = $this->_defaultParams();
        $params['title'] = 'Create New Gallery';
        $params['jses'] = ['js/gallery.js'];
        $params['ref'] = $this->req->getQuery('ref') ?? $this->router->to('adminGallery');

        if($id){
            $params['title'] = 'Edit Gallery';
            $object = Gallery::get($id, false);
            if(!$object)
                return $this->show404();
            $old = $object;
        }else{
            $object = new \stdClass();
            $object->user = $this->user->id;
        }

        if(false === ($form = $this->form->validate('admin-gallery', $object)))
            return $this->respond('component/gallery/edit', $params);

        $object = object_replace($object, $form);

        $event = 'updated';

        if(!$id){
            $event = 'created';
            if(false === ($id = Gallery::create($object)))
                throw new \Exception(Gallery::lastError());
        }else{
            $object->updated = null;
            if(false === Gallery::set($object, $id))
                throw new \Exception(Gallery::lastError());
        }

        $this->fire('gallery:'. $event, $object, $old);

        return $this->redirect($params['ref']);
    }

    public function filterAction(){
        if(!$this->user->login)
            return $this->show404();
        if(!$this->can_i->read_gallery)
            return $this->show404();

        $q = $this->req->getQuery('q');
        if(!$q)
            return $this->ajax(['error'=>true, 'data'=>[]]);

        $galleries = Gallery::get(['q'=>$q], 12, false, 'LENGTH(name) ASC');
        if(!$galleries)
            return $this->ajax(['error'=>false, 'data'=>[]]);
        
        $result = array_column($galleries, 'name', 'id');
        $this->ajax(['error'=>false, 'data'=>$result]);
    }

    public function indexAction(){
        if(!$this->user->login)
            return $this->loginFirst('adminLogin');
        if(!$this->can_i->read_gallery)
            return $this->show404();
        
        $params = $this->_defaultParams();
        $params['reff'] = $this->req->url;
        $params['galleries'] = [];
        $params['pagination'] = null;

        $rpp = 20;
        $page = $this->req->getQuery('page',1);
        if($page < 1)
            $page = 1;

        $cond = [];
        if($q = $this->req->getQuery('q'))
            $cond['q'] = $q;

        $galleries = Gallery::get($cond, $rpp, $page, 'name ASC');
        if($galleries)
            $params['galleries'] = \Formatter::formatMany('gallery', $galleries, false, ['user']);

        $params['total'] = $total = (int)Gallery::count($cond);
        if($total > $rpp)
            $params['pagination'] = \calculate_pagination($page, $rpp, $total, 10, $cond);

        $this->form->setForm('admin-gallery-index');

        return $this->respond('component/gallery/index', $params);
    }

    public function removeAction(){
        if(!$this->user->login)
            return $this->show404();
        if(!$this->can_i->remove_gallery)
            return $this->show404();
        
        $id = $this->param->id;
        $object = Gallery::get($id, false);
        if(!$object)
            return $this->show404();
        
        $this->fire('gallery:deleted', $object);
        Gallery::remove($id);
        
        $ref = $this->req->getQuery('ref');
        if($ref)
            return $this->redirect($ref);
        
        return $this->redirectUrl('adminGallery');
    }
}