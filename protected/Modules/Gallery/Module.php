<?php

namespace App\Modules\Gallery;

class Module
    extends \App\Components\Module
{

    public function getAdminMenu()
    {
        return [
            ['title' => 'Галерея', 'icon' => '<i class="glyphicon glyphicon-picture"></i>','url' => '/admin/gallery/' ],
        ];
    }

}