<?php
interface iUserService
{
    public function getUserById($id);
    public function getUserByUsername($name);
}