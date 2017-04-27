<?php

namespace BlizzardBundle\Interfaces;


interface MongoRepository
{
    public function add(EntityInterface $entity);
    public function update($id, EntityInterface $entity);
    public function remove(EntityInterface $entity);
    public function findById($id);
}