@extends('layouts.admin')
@section('content')
    <div class="container">
        <div>
            <h3>Todos os laudos</h3>
        </div>
        <div class="row">
            <div class="mb-3 col-10">
                <label for="exampleFormControlInput1" class="form-label">Buscar por animal</label>
                <input type="text" id="busca" class="form-control">
            </div>
            <div class="mb-3 col-8">
                <label for="exampleFormControlInput1" class="form-label">Buscar por codlab</label>
                <input type="text" id="codlab" class="form-control">
            </div>
            <div class="mb-3 mt-4 col-2">
                <button class="btn btn-primary" id="busca-codlab">BUSCAR CODLAB</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Numero/Pedido</th>
                    <th scope="col">Produto</th>
                    <th>Data de criação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody class="table-busca">
                @foreach ($laudos as $item)
                    <tr>
                        <th scope="row">{{ $item->id }} / {{ $item->order_id }}</th>

                        <td>{{ $item->animal->animal_name }}</td>
                        <th>{{ date('d/m/Y', strtotime($item->created_at)) }}</th>
                        <td>
                            <div class="row">
                                <div class="col-4"><a href="{{ route('laudo.download', $item->id) }}"><button
                                            class="btn btn-primary"><i class="fa-solid fa-download"></i>
                                        </button></a>
                                </div>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $laudos->links() }}
    </div>
@endsection

@section('js')
    <script>
        // Armazena os itens iniciais
        var initialItems = $('.table-busca').html();

        $('#busca').keyup(function() {
            var busca = $(this).val();

            if (busca === '') {
                // Redefine para os itens iniciais
                $('.table-busca').html(initialItems);
                return;
            }

            $.ajax({
                url: "{{ route('search.laudo') }}",
                type: "POST",
                data: {
                    busca: busca,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('.table-busca').html(response.viewRender);
                }
            });
        });

        $('#busca-codlab').click(function() {
            var codlab = $('#codlab').val();

            if (codlab === '') {
                // Redefine para os itens iniciais
                $('.table-busca').html(initialItems);
                return;
            }

            $.ajax({
                url: "{{ route('search.laudo.codlab') }}",
                type: "POST",
                data: {
                    codlab: codlab,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('.table-busca').html(response.viewRender);
                }
            });
        });
    </script>
@endsection
