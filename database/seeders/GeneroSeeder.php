<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneroSeeder extends Seeder
{
    public function run(): void
    {
        $generos = [
            ['nome' => 'Ficção Científica'],
            ['nome' => 'Fantasia'],
            ['nome' => 'Distopia'],
            ['nome' => 'Horror'],
            ['nome' => 'Romance'],
            ['nome' => 'Thriller'],
            ['nome' => 'Histórico'],
            ['nome' => 'Biografia'],
            ['nome' => 'Autoajuda'],
            ['nome' => 'Técnico/Académico'],
            ['nome' => 'Poesia'],
            ['nome' => 'Drama'],
            ['nome' => 'Aventura'],
            ['nome' => 'Comédia'],
            ['nome' => 'Infantil'],
            ['nome' => 'Mangá'],
        ];

        DB::table('generos')->insert($generos);
    }
}
