<?php

class Breed extends Model
{
    protected $table = 'breeds';  

    public function findAllBreeds()
    {
        return $this->findAll(); 
    }

    public function findBreed($breed_id)
    {
        return $this->first(['breed_id' => $breed_id]); 
    }

    public function addBreed($data)
    {
        return $this->insert($data); 
    }

    public function updateBreed($breed_id, $data)
    {
        return $this->update($breed_id, $data, 'breed_id');
    }

    public function deleteBreed($breed_id)
    {
        return $this->delete($breed_id, 'breed_id');
    }
}
