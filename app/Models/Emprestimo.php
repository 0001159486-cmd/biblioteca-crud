<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    protected $fillable = ['user_id', 'livro_id', 'data_saida', 'data_devolucao'];

    public $timestamps = false;

    protected $casts = [
        'data_saida' => 'datetime',
        'data_devolucao' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }
}
