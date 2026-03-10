<?php

namespace Database\Seeders;

use App\Models\Livro;
use Illuminate\Database\Seeder;

class LivroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Livro::create([
            'titulo' => 'O Senhor dos Anéis',
            'descricao' => 'A jornada épica de Frodo Bolseiro para destruir o Um Anel e salvar a Terra Média das forças do mal.',
            'autor' => 'J.R.R. Tolkien',
            'capa' => 'capas/lotr.jpg',
            'genero_id' => 2,
            'data_publicacao' => '1954-07-29',
            'estoque' => 5,
        ]);

        Livro::create([
            'titulo' => 'Harry Potter e a Pedra Filosofal',
            'descricao' => 'O jovem bruxo Harry Potter descobre que é famoso no mundo mágico e frequenta a Escola de Magia de Hogwarts, onde enfrenta desafios e faz amizades.',
            'autor' => 'J.K. Rowling',
            'capa' => 'capas/hp.jpg',
            'genero_id' => 2,
            'data_publicacao' => '1997-06-26',
            'estoque' => 10,
        ]);

        Livro::create([
            'titulo' => 'O Hobbit',
            'descricao' => 'Bilbo Bolseiro vive uma vida pacata até ser arrastado por Gandalf e um grupo de anões em uma jornada épica para recuperar o Reino de Erebor do dragão Smaug.',
            'autor' => 'J.R.R. Tolkien',
            'capa' => 'capas/hobbit.jpg',
            'genero_id' => 2,
            'data_publicacao' => '1937-09-21',
            'estoque' => 15,
        ]);

        Livro::create([
            'titulo' => 'Neon Genesis Evangelion',
            'descricao' => 'Em um mundo pós-apocalíptico, a organização NERV utiliza biomecas gigantes chamados Evangelions para combater seres monstruosos conhecidos como Anjos. Shinji Ikari, um adolescente traumatizado, é forçado a pilotar a Unidade-01 enquanto enfrenta dilemas existenciais e o colapso da humanidade.',
            'autor' => 'Yoshiyuki Sadamoto / Hideaki Anno',
            'capa' => 'capas/evangelion.jpg',
            'genero_id' => 16,
            'data_publicacao' => '1994-12-26',
            'estoque' => 5,
        ]);

    }
}
