<?php

// src/Controller/AdminController.php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminController extends EasyAdminController
{
    // ...

    public function persistEntity($entity)
    {
        $this->updateSlug($entity);
        parent::persistEntity($entity);
    }

    public function updateEntity($entity)
    {
        $this->updateSlug($entity);
        parent::updateEntity($entity);
    }

    private function updateSlug($entity)
    {
        if (method_exists($entity, 'setSlug') and method_exists($entity, 'getTitle')) {
            $entity->setSlug($this->slugger->slugify($entity->getTitle()));
        }
    }
}