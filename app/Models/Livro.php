<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    protected $fillable = ['genero_id', 'titulo', 'descricao', 'autor', 'capa', 'data_publicacao', 'estoque'];

    public function genero()
    {
        return $this->belongsTo(Genero::class);
    }

    public function emprestimos()
    {
        return $this->hasMany(Emprestimo::class);
    }
}
