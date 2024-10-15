<?php

class Pet extends Model
{
    protected $table = 'pets';  

    public function findAllPets()
    {
        return $this->findAll(); 
    }

    public function findPet($pet_id)
    {
        return $this->first(['pet_id' => $pet_id]); 
    }

    public function addPet($data)
    {
        return $this->insert($data); 
    }

    public function updatePet($pet_id, $data)
    {
        return $this->update($pet_id, $data, 'pet_id');
    }

    public function deletePet($pet_id)
    {
        return $this->delete($pet_id, 'pet_id');
    }
}
