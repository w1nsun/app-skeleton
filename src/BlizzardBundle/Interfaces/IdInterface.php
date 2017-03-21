<?php

namespace BlizzardBundle\Interfaces;

interface IdInterface
{
    public function convertToPhpValue();
    public function convertToRepositoryValue();
}