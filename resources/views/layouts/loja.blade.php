<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('loja/node_modules/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('loja/css/style.min.css') }}">

    <title>Pedidos</title>
</head>

<body>
    <div class="container-fluid">
        <header
            class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-around py-3 mb-4 border-bottom border-primary">

            <a href="/" class="d-flex align-items-center justify-content-sm-around col-md-3 mb-2 mb-md-0">
                <img src="{{ asset('loja/assets/img/logo.svg') }}" alt="">
            </a>
            <form class="d-flex justify-content-center col-12 col-lg-5 mb-3 mb-lg-0 me-lg-3 " role="search">
                <div class="input-group submit-line">
                    <input class="form-control " type="search" placeholder="Busque aqui" />

                    <button class="submit-lente  btn-lg " type="submit">
                        <i class="bi bi-search"></i>
                    </button>

                </div>
            </form>
            <div class="col-md-3 text-end">
                @if (Auth::check())
                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-person"></i> Minha Conta
                    </a>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();"
                        class="btn btn-primary btn-sm">
                        <i class="bi bi-door-open"></i> Sair
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <a href="/entrar.html" type="button" class="btn btn-primary">Entrar</a>
                    <a href="/cadastro.html" type="button" class="btn btn-secondary">Cadastar-se</a>
                @endif

            </div>
        </header>
    </div>
    <div class="d-flex flex-column wrapper mb-5">
        <main class="flex-fill">
            @yield('content')
        </main>

    </div>
    <footer class="border-top border-primary text-muted text-bg-primary">
        <div class="container">
            <div class="row py-3 ">
                <div class="  col-5 patrocinios mb-3">
                    <img class="img-fluid" src="{{ asset('loja/assets/img/isag.jpg') }}" alt="">
                </div>
                <div class=" col-5 patrocinios mb-3">
                    <img class="img-fluid" src="{{ asset('loja/assets/img/mpa.jpg') }}" alt="">
                </div>
                <div class=" col-2 patrocinios mb-3">
                    <img class="img-fluid" src="{{ asset('loja/assets/img/inmetro.jpg') }}" alt="">
                </div>
                <div class="col-12  text-center text-bg-primary mt-3">
                    Rua Coronel Durães, 170, slj 01,
                    Bela Vista - Lagoa Santa l MG Labloci © 2022 Todos os Direitos Reservados
                </div>

            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    @yield('scripts')
    @if (Session::has('success'))
        <script type="text/javascript">
            Swal.fire({
                title: 'Sucesso!',
                icon: 'success',
                text: "{{ Session::get('success') }}",
                timer: 5000,
                type: 'success'
            }).then((result) => {
                // Reload the Page
                location.reload();
            });
        </script>
    @endif
</body>

</html>
