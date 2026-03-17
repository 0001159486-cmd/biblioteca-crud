<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeminiController extends Controller
{
    public function perguntar(Request $request)
    {
        $pergunta = $request->input('pergunta');
        if (! $pergunta) {
            return response()->json(['resposta' => 'Diga algo.']);
        }

        try {
            $apiKey = env('GEMINI_API_KEY');

            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key='.$apiKey;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'você é um bibliotecario. Pergunta: '.$pergunta],
                        ],
                    ],
                ],
            ]);

            $data = $response->json();

            if (isset($data['error'])) {
                return response()->json(['resposta' => 'Erro na API: '.$data['error']['message']]);
            }

            $texto = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sem resposta do modelo.';

            return response()->json(['resposta' => $texto]);

        } catch (\Exception $e) {
            return response()->json(['resposta' => 'Erro interno: '.$e->getMessage()]);
        }
    }
}
