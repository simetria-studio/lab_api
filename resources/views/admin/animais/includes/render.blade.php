@foreach ($animais as $animal)
@if ($animal->status == 1)
    @php
        $status = 'Aguardando amostra';
    @endphp
@elseif($animal->status == 2)
    @php
        $status = 'Amostra recebida';
    @endphp
@elseif($animal->status == 3)
    @php
        $status = 'Em análise';
    @endphp
@elseif($animal->status == 4)
    @php
        $status = 'Análise concluída';
    @endphp
@elseif($animal->status == 5)
    @php
        $status = 'Resultado disponível';
    @endphp
@elseif($animal->status == 6)
    @php
        $status = 'Análise reprovada';
    @endphp
@elseif($animal->status == 7)
    @php
        $status = 'Análise Aprovada';
    @endphp
@elseif($animal->status == 8)
    @php
        $status = 'Recoleta solicitada';
    @endphp
@elseif($animal->status == 9)
    @php
        $status = 'Amostra paga';
    @endphp
@elseif($animal->status == 10)
    @php
        $status = 'Pedido Concluído';
    @endphp
@endif

<tr>
    <td>{{ $animal->animal_name }}</td>
    <td> {{ $animal->breed }} </td>
    <td>
        {{ $status }}
    </td>
    <td>
        <div class="dropdown">
            <a class="btn btn-alt-loci text-white dropdown-toggle" href="#"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ações
            </a>

            <ul class="dropdown-menu">
                <a href="{{ route('animais.show', $animal->id) }}" class="dropdown-item">Editar</a>
                <a href="{{ route('animais.status', $animal->id) }}" class="dropdown-item">Editar Status</a>
            </ul>
        </div>

    </td>
</tr>
@endforeach