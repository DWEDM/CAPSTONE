<?php

class Article extends Model
{
    protected $table = 'articles';  

    public function findAllArticles()
    {
        return $this->findAll(); 
    }

    public function findArticle($article_id)
    {
        return $this->first(['article_id' => $article_id]); 
    }

    public function addArticle($data)
    {
        return $this->insert($data); 
    }

    public function updateArticle($article_id, $data)
    {
        return $this->update($article_id, $data, 'article_id');
    }

    public function deleteArticle($article_id)
    {
        return $this->delete($article_id, 'article_id');
    }
}
