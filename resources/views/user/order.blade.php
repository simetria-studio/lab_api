@extends('layouts.loja')

@section('content')
    <div class="container">
        <h1 class="text-primary">Minha Conta</h1>
        <div class="row gx-3">
            @component('layouts.partials.user-menu')
            @endcomponent

            <div class="col-md-8 col-12">
                @foreach ($order->orderRequestPayment as $item)
                    @php
                        $animal = App\Models\Animal::where('id', $item->animal_id)
                            ->orWhere('register_number_brand', $item->animal_id)
                            ->first();
                        if ($animal->especies == 'EQUINA') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 1)
                                ->where('status', 1)
                                ->get();
                        } elseif ($animal->especies == 'ASININA') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 2)
                                ->where('status', 1)
                                ->get();
                        } elseif ($animal->especies == 'ASININO') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 2)
                                ->where('status', 1)
                                ->get();
                        } elseif ($animal->especies == 'MUARES') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 2)
                                ->where('status', 1)
                                ->get();
                        } elseif ($animal->especies == 'MUAR') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 2)
                                ->where('status', 1)
                                ->get();
                        } elseif ($animal->especies == 'EQUINO_PEGA') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 2)
                                ->where('status', 1)
                                ->get();
                        } elseif ($animal->especies == 'PEGA_EQUINO') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 2)
                                ->where('status', 1)
                                ->get();
                        } elseif ($animal->especies == null) {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 1)
                                ->where('status', 1)
                                ->get();
                        } else {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 1)
                                ->where('status', 1)
                                ->get();
                        }
                    @endphp
                    @php
                        $status = 'Aguardando Pagamento';
                        if ($item->payment_status == 1) {
                            $status = 'Pagamento Confirmado';
                        } elseif ($item->payment_status == 2) {
                            $status = 'Pagamento Recusado';
                        }
                    @endphp
                    @if ($item->payment_status != 2)
                        <form action="" method="post">
                            @csrf
                            <input type="hidden" name="orderId" id="orderId" value="{{ $order->id }}">
                            <input type="hidden" name="itemId[]" value="{{ $item->id }}">
                            <div class="row order-itens">
                                <div class="col-md-6">
                                    <p>Produto: <span>{{ $item->animal }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p>Tipo de Exame: <span>{{ $item->category }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p>Status de Pagamento: <span
                                            @if ($item->payment_status == 1) class="bg-success text-white p-1" @endif>{{ $status }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>Valor: <span
                                            class="valor-{{ $item->id }}">{{ 'R$ ' . number_format($item->value, 2, ',', '.') }}</span>
                                    </p>
                                </div>
                                @if ($order->origin == 'sistema' || $order->origin == 'API' || $order->origin == 'email')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Tempo de
                                                entrega</label>

                                            <select class="form-select sel-price" name="days[]"
                                                @if ($item->payment_status == 1) disabled @endif
                                                aria-label="Default select example">
                                                @foreach ($exames as $key => $exame)
                                                    @if ($exame->status == 1)
                                                        <option data-exame="{{ $exame->id }}"
                                                            value="{{ $key }}-{{ $item->id }}-{{ $exame->id }}"
                                                            data-value="{{ $exame->value }}"
                                                            data-order="{{ $item->id }}" data-id="{{ $order->id }}"
                                                            @if ($item->exam_id == $exame->id) selected @else @endif>
                                                            {{ $exame->title }}
                                                        </option>
                                                    @endif
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($payment)
                                    @if ($payment->payment_type == 'boleto')
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <a href="{{ route('user.success', $order->id) }}"><button type="button"
                                                        class="btn btn-primary">link do boleto</button></a>
                                            </div>
                                        </div>
                                    @elseif ($payment->payment_type == 'pix')
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <a href="{{ route('user.success', $order->id) }}"><button type="button"
                                                        class="btn btn-primary">link da para pix</button></a>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                <div class="col-md-6 mt-3 d-none">
                                    <select class="form-select" name="selectedItems[]"
                                        @if ($item->payment_status == 1) disabled @endif
                                        aria-label="Default select example">
                                        <option value="{{ $item->id }}-1">
                                            Pagar Agora
                                        </option>
                                        <option value="{{ $item->id }}-0">
                                            Pagar Depois
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-none">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $item->id }}"
                                            name="paynow[]" id="flexCheckChecked"
                                            @if ($item->payment_status == 0) checked @else disabled="disabled" @endif>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Pagar Agora
                                        </label>
                                    </div>
                                </div>
                            </div>
                    @endif
                @endforeach
                @php

                    $total = $order->orderRequestPayment
                        ->where('payment_status', 0)
                        ->map(function ($query) {
                            return $query->value;
                        })
                        ->sum();

                @endphp
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 text-uppercase">
                            <h4>Total do seu pedido: <span
                                    class="total-price text-gray">{{ 'R$ ' . number_format($total, 2, ',', '.') }}</span>
                            </h4>
                            <input type="hidden" class="price-total" name="totalprice" value="{{ $total }}">
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="submitPay" class="btn btn-primary">Formas
                                de Pagamento</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('select[name^="selectedItems"]').change(function() {
                updateTotal();
            });

            // Função para atualizar o total
            function updateTotal() {
                var totalPrice = 0;

                $('select[name^="selectedItems"]').each(function() {
                    var optionValue = $(this).val(); // Valor selecionado no select (por exemplo, "9175-1")
                    var optionParts = optionValue.split('-'); // Divide o valor em partes

                    var itemId = optionParts[0]; // ID do item
                    var selectedOption = optionParts[
                    1]; // Opção selecionada (1 para Pagar Agora, 0 para Pagar Depois)

                    // Se a opção for "Pagar Agora", adicione o valor ao total
                    if (selectedOption === '1') {
                        var itemValue = parseFloat($(`.valor-${itemId}`).text().replace('R$ ', '').replace(
                            ',', '.'));
                        totalPrice += itemValue;
                    }
                });

                $(`.total-price`).text(`R$ ${totalPrice.toFixed(2).replace('.', ',')}`);
                $(`.price-total`).val(`${totalPrice.toFixed(2).replace('.', ',')}`);
            }

        });
        $(document).on('change', '.sel-price', function() {
            var orderId = $('#orderId').val();
            var totalPrice = 0;
            $('.sel-price').each(function() {
                if (!$(this).attr('disabled')) {
                    totalPrice += parseFloat($(this).find(':selected').data('value'));
                    console.log($(this));
                }

            });
            var valor = parseFloat($(this).find(':selected').data('value'));
            var order = $(this).find(':selected').data('order');
            var exame = $(this).find(':selected').data('exame');

            $(`.valor-${order}`).text(`R$ ${valor.toFixed(2).replace('.', ',')}`);


            $(`.total-price`).text(`R$ ${totalPrice.toFixed(2).replace('.', ',')}`);
            $(`.price-total`).val(`${totalPrice.toFixed(2).replace('.', ',')}`);
            $.ajax({
                type: 'post',
                url: `/value-update/${orderId}`,
                data: {
                    productValue: valor,
                    product: order,
                    value: totalPrice,
                    exame: exame,
                },
                success: function(data) {
                    console.log(data)
                }
            });
        });

        $(document).on('click', '.paynow', function() {
            var totalPrice = 0;
            $('.sel-price').each(function() {
                if (!$(this).attr('disabled')) {
                    if ($(this).closest('.order-itens').find('.paynow').prop('checked'))
                        totalPrice += parseFloat($(this).find(':selected').data('value'));
                    console.log($(this));
                }
            });
            $(`.total-price`).text(`R$ ${totalPrice.toFixed(2).replace('.', ',')}`);
            $(`.price-total`).val(`${totalPrice.toFixed(2).replace('.', ',')}`);
        });
        $(document).on('click', '#submitPay', function() {
            var total = parseFloat($('.price-total').val());
            var orderId = $('#orderId').val();
            var values = $("select[name^='days']").map(function(idx, ele) {
                return $(ele).val();
            }).get();
            var paynow = $("select[name^='selectedItems']").map(function(idx, ele) {
                return $(ele).val();
            }).get();

            $.ajax({
                type: 'post',
                url: `{{ route('user.payment.process') }}`,
                data: {
                    total: total,
                    orderId: orderId,
                    days: values,
                    paynow: paynow,
                },
                success: function(data) {
                    console.log(data)
                    window.location.href = `/user-payment/${orderId}`
                }
            });
        });
    </script>
@endsection
