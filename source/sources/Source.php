<?php

interface Source
{
    public function render();
    public function getTableName();
    public function parameters();
}
