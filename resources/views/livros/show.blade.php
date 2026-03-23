@extends('layouts.app')

@section('content')
<div class="livro-container">
    <div class="col-esquerda">
        <div class="capa-box">
            <img src="{{ Storage::url($livro->capa) }}" alt="{{ $livro->titulo }}">
        </div>

        <div class="acao-box">
            @if($livro->estoque > 0)
                <form action="{{ route('livros.alugar', $livro->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-alugar">ALUGAR</button>
                </form>
            @else
                <button class="btn-esgotado" disabled>SEM ESTOQUE</button>
            @endif
        </div>

        {{-- MÉDIA DE AVALIAÇÕES --}}
        <div class="avaliacao-media-box">
            @php $media = $livro->mediaNotas(); @endphp
            <div class="estrelas-media">
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= round($media) ? 'estrela-cheia' : 'estrela-vazia' }}">★</span>
                @endfor
            </div>
            <span class="media-numero">{{ $media > 0 ? $media . ' / 5' : 'Sem avaliações' }}</span>
            <span class="total-avaliacoes">{{ $livro->avaliacoes->count() }} avaliação(ões)</span>
        </div>
    </div>

    <div class="col-direita" style="position: relative;">
        @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="admin-actions-top">
                <a href="{{ route('livros.edit', $livro->id) }}" class="btn-admin btn-edit">EDITAR</a>
                <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" onsubmit="return confirm('Deletar este livro?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-admin btn-delete">EXCLUIR</button>
                </form>
            </div>
        @endif

        <h1 class="titulo">{{ $livro->titulo }}</h1>
        <p class="autor">por <span>{{ $livro->autor }}</span></p>
        <span class="badge">Estoque atual: {{ $livro->estoque }}</span>

        <div class="meta-info">
            <span class="badge">{{ $livro->genero->nome ?? 'Geral' }}</span>
            <span class="data">Publicado em: {{ \Carbon\Carbon::parse($livro->data_publicacao)->format('Y') }}</span>
        </div>

        <hr class="divisor">

        <div class="descricao-box">
            <h3>Descrição</h3>
            <p>{{ $livro->descricao }}</p>
        </div>

        <hr class="divisor">

        {{-- FORMULÁRIO DE AVALIAÇÃO --}}
        @auth
            @if(!$minhaAvaliacao)
                <div class="avaliacao-form-box">
                    <h3>Deixe sua Avaliação</h3>
                    <form action="{{ route('livros.avaliar', $livro->id) }}" method="POST">
                        @csrf

                        <div class="estrelas-input" id="estrelas-input">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="estrela-btn" data-valor="{{ $i }}" onclick="selecionarNota({{ $i }})">★</span>
                            @endfor
                        </div>
                        <input type="hidden" name="nota" id="nota-input" value="">
                        <span id="nota-texto" style="font-size:0.8rem; color:var(--accent); display:block; margin-bottom:12px;">
                            Clique numa estrela para avaliar
                        </span>

                        <div class="input-group">
                            <label>Comentário: <span style="font-size:0.75rem; opacity:0.5;">(opcional)</span></label>
                            <textarea name="comentario" rows="3" placeholder="O que achou do livro?" style="width:100%; resize:vertical;"></textarea>
                        </div>

                        <button type="submit" class="btn-primary" style="width:100%;">Enviar Avaliação</button>
                    </form>
                </div>
            @else
                <div class="avaliacao-form-box">
                    <h3>Sua Avaliação</h3>
                    <div class="estrelas-media" style="margin-bottom: 8px;">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $minhaAvaliacao->nota ? 'estrela-cheia' : 'estrela-vazia' }}">★</span>
                        @endfor
                    </div>
                    @if($minhaAvaliacao->comentario)
                        <p style="font-size:0.9rem; color:#ccc; font-style:italic; margin-bottom:12px;">
                            "{{ $minhaAvaliacao->comentario }}"
                        </p>
                    @endif
                    <form action="{{ route('livros.avaliar.destroy', $livro->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-admin btn-delete" style="font-size:0.7rem;">Remover avaliação</button>
                    </form>
                </div>
            @endif
        @endauth

        {{-- LISTA DE AVALIAÇÕES --}}
        @if($livro->avaliacoes->count() > 0)
            <hr class="divisor">
            <h3 style="margin-bottom: 16px;">O que os leitores acharam</h3>
            <div class="avaliacoes-lista">
                @foreach($livro->avaliacoes->sortByDesc('created_at') as $avaliacao)
                    <div class="avaliacao-item">
                        <div class="avaliacao-header">
                            <div class="avaliacao-avatar">
                                {{ strtoupper(substr($avaliacao->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="avaliacao-nome">{{ $avaliacao->user->name }}</span>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $avaliacao->nota ? 'estrela-cheia' : 'estrela-vazia' }}" style="font-size:0.9rem;">★</span>
                                    @endfor
                                </div>
                            </div>
                            <span class="avaliacao-data">{{ $avaliacao->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($avaliacao->comentario)
                            <p class="avaliacao-comentario">"{{ $avaliacao->comentario }}"</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.avaliacao-media-box {
    margin-top: 16px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}
.estrela-cheia { color: #e1b12c; }
.estrela-vazia  { color: #444; }
.estrelas-media span { font-size: 1.4rem; }
.media-numero { font-size: 0.85rem; color: #e1b12c; font-weight: 700; }
.total-avaliacoes { font-size: 0.75rem; color: #888; }

.avaliacao-form-box { margin-top: 20px; }
.avaliacao-form-box h3 { margin-bottom: 14px; }

.estrelas-input { display: flex; gap: 6px; margin-bottom: 6px; cursor: pointer; }
.estrela-btn {
    font-size: 2rem;
    color: #444;
    transition: color 0.15s;
    cursor: pointer;
    user-select: none;
}
.estrela-btn.ativa { color: #e1b12c; }

.avaliacoes-lista { display: flex; flex-direction: column; gap: 14px; }
.avaliacao-item {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 8px;
    padding: 14px;
}
.avaliacao-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}
.avaliacao-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e1b12c;
    color: #111;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 0.8rem;
    flex-shrink: 0;
}
.avaliacao-nome { font-size: 0.85rem; font-weight: 700; color: #fff; display: block; }
.avaliacao-data { font-size: 0.75rem; color: #666; margin-left: auto; }
.avaliacao-comentario { font-size: 0.85rem; color: #bbb; font-style: italic; margin: 0; padding-left: 42px; }

body.light-mode .estrela-vazia { color: #c8c0ad; }
body.light-mode .avaliacao-item { background: rgba(36,52,71,0.04); border-color: rgba(36,52,71,0.1); }
body.light-mode .avaliacao-nome { color: #1a2a3a; }
body.light-mode .avaliacao-comentario { color: #3a5068; }
</style>

<script>
function selecionarNota(valor) {
    document.getElementById('nota-input').value = valor;
    var textos = ['Muito ruim', 'Ruim', 'Regular', 'Bom', 'Excelente'];
    document.getElementById('nota-texto').textContent = textos[valor - 1] + ' (' + valor + '/5)';
    var estrelas = document.querySelectorAll('.estrela-btn');
    estrelas.forEach(function(e, i) {
        e.classList.toggle('ativa', i < valor);
    });
}
</script>
@endsection