<?php

namespace App\Modules\Pages\Controllers;

use App\Components\Admin\Controller;
use App\Modules\Pages\Models\Page;
use T4\Core\Exception;

class Admin
    extends Controller
{
 /*    protected function access($action)
     {
         return !empty($this->app->user);
     }
*/
    public function actionDefault()
    {
        $this->app->extensions->jstree->init();
        $this->data->items = Page::findAllTree();
    }


    public function actionEdit($id=null, $parent=null)
    {
        $this->app->extensions->ckeditor->init();
        $this->app->extensions->ckfinder->init();

        if (null === $id || 'new' == $id) {
            $this->data->item = new Page();
            if (null !== $parent) {
                $this->data->item->parent = $parent;
            }
        } else {
            $this->data->item = Page::findByPK($id);
        }
    }

    public function actionSave($redirect = 0)
    {
        if (!empty($this->app->request->post->id)) {
            $item = Page::findByPK($this->app->request->post->id);
        } else {
            $item = new Page();
        }
        $item->fill($this->app->request->post);
        $item->save();

        if ($item->wasNew()) {
            $item->moveToFirstPosition();
        }

        if ($redirect) {
            $this->redirect('/pages/' . $item->url . '.html');
        } else {
            $this->redirect('/pages/admin/');        }
    }

    public function actionDelete($id)
    {
        $item = Page::findByPK($id);
        if ($item)
            $item->delete();
        $this->redirect('/pages/admin');
    }

    public function actionUp($id)
    {
        $item = Page::findByPK($id);
        if (empty($item))
            $this->redirect('/pages/admin/');
        $sibling = $item->getPrevSibling();
        if (!empty($sibling)) {
            $item->insertBefore($sibling);
        }
        $this->redirect('/pages/admin/');
    }

    public function actionDown($id)
    {
        $item = Page::findByPK($id);
        if (empty($item))
            $this->redirect('/pages/admin/');
        $sibling = $item->getNextSibling();
        if (!empty($sibling)) {
            $item->insertAfter($sibling);
        }
        $this->redirect('/pages/admin/');
    }

    public function actionMoveBefore($id, $to)
    {
        try {
            $item = Page::findByPK($id);
            if (empty($item)) {
                throw new Exception('Source element does not exist');
            }
            $destination = Page::findByPK($to);
            if (empty($destination)) {
                throw new Exception('Destination element does not exist');
            }
            $item->insertBefore($destination);
            $this->data->result = true;
        } catch (Exception $e) {
            $this->data->result = false;
            $this->data->error = $e->getMessage();
        }
    }

    public function actionAfter($id, $to)
    {
        try {
            $item = Page::findByPK($id);
            if (empty($item)) {
                throw new Exception('Source element does not exist');
            }
            $destination = Page::findByPK($to);
            if (empty($destination)) {
                throw new Exception('Destination element does not exist');
            }
            $item->insertAfter($destination);
            $this->data->result = true;
        } catch (Exception $e) {
            $this->data->result = false;
            $this->data->error = $e->getMessage();
        }
    }
}