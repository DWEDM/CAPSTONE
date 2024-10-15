<?php

class Species extends Model
{
    protected $table = 'species';  

    public function findAllSpecies()
    {
        return $this->findAll(); 
    }

    public function findSpecies($species_id)
    {
        return $this->first(['species_id' => $species_id]); 
    }

    public function addSpecies($data)
    {
        return $this->insert($data); 
    }

    public function updateSpecies($species_id, $data)
    {
        return $this->update($species_id, $data, 'species_id');
    }

    public function deleteSpecies($species_id)
    {
        return $this->delete($species_id, 'species_id');
    }
}
