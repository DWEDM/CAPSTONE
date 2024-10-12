<?php
class Category extends Model
{
    protected $table = 'categories';  // Set the table name

    public function findAllCategories()
    {
        return $this->findAll(); // Inherited method
    }

    public function findCategory($category_id)
    {
        return $this->first(['category_id' => $category_id]); // Use inherited method to find by id
    }

    public function addCategory($data)
    {
        return $this->insert($data); // Inherited method
    }

    public function updateCategory($category_id, $data)
    {
        return $this->update($category_id, $data, 'category_id'); // Inherited method
    }

    public function deleteCategory($category_id)
    {
        return $this->delete($category_id, 'category_id'); // Inherited method
    }
}
