<?php

namespace BlizzardBundle\Interfaces;


interface MongoRepository
{
    public function add(EntityInterface $entity);
    public function update(string $id, EntityInterface $entity);
    public function remove(EntityInterface $entity);
    public function findById(string $id): EntityInterface;
}