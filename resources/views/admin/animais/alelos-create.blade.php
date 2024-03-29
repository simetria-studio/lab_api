@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="mb-3 col-4">
                <label for="exampleFormControlInput1" class="form-label">Buscar animal pelo nome</label>
                <select class="js-example-basic-animal" id="buscar-nome">

                </select>
            </div>
            <div class="col-2 mt-4">
                <button class="btn btn-primary" type="button" id="buscar">BUSCAR</button>
            </div>
            <div class="mb-3 col-3">
                <label for="exampleFormControlInput1" class="form-label">Buscar animal pelo codlab</label>
                <input type="text" class="form-control" id="codlab">
            </div>
            <div class="col-2 mt-4">
                <button class="btn btn-primary" type="button" id="buscarCodlab">BUSCAR</button>
            </div>

        </div>
        <div id="animal-info">

        </div>
        <div class="d-none" id="especienull">

        </div>
        <div id="animalForm" class="d-none">

            <div class="my-3">
                <button class="btn btn-danger delete-alelos">APAGAR ALELOS</button>
                <button class="btn btn-success replicate">REPLICAR PARA TODOS</button>
            </div>
            <form id="form">
                <input type="hidden" name="animal_id" id="id">
                <input type="hidden" name="animal_name">
                <input type="hidden" id="especie">
                <input type="hidden" id="codlab">
                @csrf
                <div class="card px-2">
                    <div class="row">
                        <div class="mb-3 col-4">
                            <label for="exampleFormControlInput1" class="form-label">Laboratório</label>
                            <input type="text" name="lab" class="form-control" id="lab">
                        </div>
                        <div class="mb-3 col-4">
                            <label for="exampleFormControlInput1" class="form-label">Número</label>
                            <input type="text" name="identificador" class="form-control" id="identificador">
                        </div>
                        <div class="mb-3 col-4">
                            <label for="exampleFormControlInput1" class="form-label">Data</label>
                            <input type="date" name="data" class="form-control" id="data" required>
                        </div>
                    </div>
                    <div id="marcadores">

                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary">SALVAR</button>
                        </div>
                    </div>
            </form>

        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            let id = 1;

            $(document).on('click', '.replicate', function() {
                var codlab = $('#codlab').val();
                var id = $('#id').val();
                Swal.fire({
                    title: 'Você tem certeza?',
                    text: "Esse processo pode ser irreversível!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',

                    confirmButtonText: 'Sim, replicar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('alelos.replicate') }}",
                            type: 'POST',
                            data: {
                                codlab: codlab,
                                id: id
                            },
                            success: function(data) {
                                console.log(data);
                                Swal.fire(
                                    'Replicado!',
                                    'Alelos replicado com sucesso.',
                                    'success'
                                )
                                location.reload();
                            }
                        });

                    }
                })

            });

            $('.js-example-basic-animal').select2({
                width: "100%",
                placeholder: "Buscar animal pelo nome",
                ajax: {
                    url: "{{ route('get.animals.all') }}",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {

                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(data) {

                        var mappedData = data.map(function(item) {
                            return {
                                id: item.animal_name, // ID da opção
                                text: item.animal_name // Valor a ser exibido no Select2
                            };
                        });

                        return {
                            results: mappedData
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
            });


            $(document).on('click', '#buscar', function() {
                var name = $('#buscar-nome').val();
                if (name != '') {
                    $.ajax({
                        url: "{{ route('animais.buscar') }}",
                        method: "POST",
                        data: {
                            name: name,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            console.log(data);
                            id = data.animal.id;
                            $('#animal-info').fadeIn();
                            $('#animal-info').html(`
                            <p>Nome do animal: ${data.animal.animal_name}</p>
                            `);
                            if (data.especie == null) {
                                $('#especienull').removeClass('d-none').append(
                                    `<div class="alert alert-danger" role="alert">
                Espécie não cadastrada, por favor cadastre a espécie antes de cadastrar o animal! <a href="{{ url('/animal-show') }}/${id}">Clique aqui para editar o animal</a>
            </div>`);
                                $('#animalForm').addClass('d-none');
                            } else {
                                $('#especienull').addClass('d-none');
                                $('#animalForm').removeClass('d-none');
                                $('#especie').val(data.especie.id);
                            }
                            $('#id').val(data.animal.id);
                            $('#codlab').val(data.animal.codlab);
                            $('input[name="animal_name"]').val(data.animal.animal_name);
                            $('#animalForm').removeClass('d-none');
                            $('#lab').val(data.animal.alelos && data.animal.alelos[0] && data
                                .animal.alelos[0].lab ? data.animal.alelos[0].lab : '');
                            if (data.animal.alelos && data.animal.alelos[0] && data.animal
                                .alelos[0].data) {
                                var rawData = data.animal.alelos[0].data;
                                var formattedDate = formatDate(rawData);
                                $('#data').val(formattedDate);
                            } else {
                                $('#data').val('');
                            }
                            $('#identificador').val(data.animal.identificador ? data.animal
                                .identificador : '');
                            $('#marcadores').html(data.view);

                        }
                    });
                }
            });

            $(document).on('click', '#buscarCodlab', function() {

                var codlab = $('#codlab').val();
                if (codlab != '') {
                    $.ajax({
                        url: "{{ route('animais.buscar.codlab') }}",
                        method: "POST",
                        data: {
                            codlab: codlab,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            console.log(data);
                            id = data.animal.id;
                            $('#animal-info').fadeIn();
                            $('#animal-info').html(`
                            <p>Nome do animal: ${data.animal.animal_name}</p>
                            `);
                            if (data.especie == null) {
                                $('#especienull').removeClass('d-none').append(
                                    `<div class="alert alert-danger" role="alert">
                Espécie não cadastrada, por favor cadastre a espécie antes de cadastrar o animal! <a href="{{ url('/animal-show') }}/${id}">Clique aqui para editar o animal</a>
            </div>`);
                                $('#animalForm').addClass('d-none');
                            } else {
                                $('#especienull').addClass('d-none');
                                $('#animalForm').removeClass('d-none');
                                $('#especie').val(data.especie.id);
                            }
                            $('#id').val(data.animal.id)
                            $('input[name="animal_name"]').val(data.animal.animal_name);
                            $('#animalForm').removeClass('d-none');
                            $('#lab').val(data.animal.alelos && data.animal.alelos[0] && data
                                .animal.alelos[0].lab ? data.animal.alelos[0].lab : '');
                            if (data.animal.alelos && data.animal.alelos[0] && data.animal
                                .alelos[0].data) {
                                var rawData = data.animal.alelos[0].data;
                                var formattedDate = formatDate(rawData);
                                $('#data').val(formattedDate);
                            } else {
                                $('#data').val('');
                            }
                            $('#identificador').val(data.animal.identificador ? data.animal
                                .identificador : '');
                            $('#marcadores').html(data.view);

                        }
                    });
                }
            });

            function formatDate(dateString) {
                var date = new Date(dateString);
                var year = date.getFullYear();
                var month = ('0' + (date.getMonth() + 1)).slice(-2);
                var day = ('0' + date.getDate()).slice(-2);
                return year + '-' + month + '-' + day;
            }

            $(document).on('click', '.delete-alelos', function() {
                var id = $('#id').val();
                Swal.fire({
                    title: 'Você tem certeza?',
                    text: "Esse processo pode ser irreversível!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, deletar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('alelos.delete') }}",
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(data) {
                                console.log(data);
                                Swal.fire(
                                    'Deletado!',
                                    'Alelos deletado com sucesso.',
                                    'success'
                                )
                                location.reload();
                            }
                        });

                    }
                })

            });

        });
        $(document).ready(function() {
            $('form').submit(function(e) {
                e.preventDefault(); // Evita que o formulário seja enviado normalmente

                // Obtém os dados do formulário
                var formData = $(this).serialize();

                // Envia a solicitação AJAX
                $.ajax({
                    url: '{{ route('alelos.store.custom') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Manipule a resposta do servidor aqui
                        console.log(response);
                        if (response.success == 'ok') {
                            Swal.fire({
                                title: 'Sucesso!',
                                text: 'Alelos criado com sucesso!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        "{{ route('admin') }}";
                                }
                            });
                        }
                        if (response.error == 'existe') {
                            Swal.fire({
                                title: 'Erro!',
                                text: 'Alelos já importados!',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        "{{ route('alelos.create') }}";
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Manipule os erros da solicitação aqui
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endsection
