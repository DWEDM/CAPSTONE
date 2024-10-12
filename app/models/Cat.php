<?php

class Cat extends Model
{
    protected $table = 'cats';  

    public function findAllCats()
    {
        return $this->findAll(); 
    }

    public function findCat($cat_id)
    {
        return $this->first(['cat_id' => $cat_id]); 
    }

    public function addCat($data)
    {
        return $this->insert($data); 
    }

    public function updateCat($cat_id, $data)
    {
        return $this->update($cat_id, $data, 'cat_id');
    }

    public function deleteCat($cat_id)
    {
        return $this->delete($cat_id, 'cat_id');
    }
}
