@extends('layouts.checkout-app')

@section('content')
    <div class="container" style="margin-top: 100px; margin-bottom: 100px;">
        <h1 class="mb-4">Pembayaran</h1>
        <strong>Detail Pemesanan: </strong>

        <div class="mb-3 mt-3">
            <strong>Nama:</strong> {{ $user->name }} <br>
            <strong>Tanggal:</strong> {{ $order['date'] }}
        </div>
        <hr>
        @php
            $i = 1;
            $totalPrice = 0;
        @endphp

        <strong>Detail Reservasi: </strong>

        <div class="d-flex gap-5">
            @foreach ($reservationDetail as $index => $reservation)
                @foreach ($reservation as $courtId => $courts)
                    @foreach ($courts['sesi'] as $rentalSessionId => $rentalSessionDetail)
                        <div class="mb-3">
                            <table class="table table-borderless" style="width: fit-content">
                                <tr>
                                    <td>Lapangan</td>
                                    <td>: {{ $courts['court_name'] }}</td>
                                </tr>
                                <tr>
                                    <td>Sesi</td>
                                    <td>: {{ $rentalSessionId }}</td>
                                </tr>
                                <tr>
                                    <td>Jam</td>
                                    <td>: {{ $rentalSessionDetail['time'] }}</td>
                                </tr>
                                <tr>
                                    <td>Harga</td>
                                    <td>:
                                        {{ 'Rp ' . number_format($rentalSessionDetail['price'], 0, ',', '.') }}</td>
                                </tr>
                            </table>
                            @php
                                $totalPrice += $rentalSessionDetail['price'];
                            @endphp
                            <input type="checkbox" class="form-check-input d-none" name="reservation[{{ $courtId }}][]"
                                value="{{ $rentalSessionId }}" checked>
                        </div>
                    @endforeach
                @endforeach
            @endforeach
        </div>
        <div class="mb-3">
            <strong>Catatan:</strong> {{ $order->note == null ? 'Tidak ada catatan' : $order->note }}
        </div>
        <hr>

        <div class="mb-4 fs-5">
            <strong>Total Bayar:</strong> <span class="text-primary text-bold">
                {{ 'Rp ' . number_format($totalPrice, 0, ',', '.') }}</span>
        </div>

        <hr>

        <div class="d-none" id="created_at_{{ $transaction->id }}">{{ $transaction->created_at }}</div>
        <h3 id="countdown_{{ $transaction->id }}" class="mb-4"></h3>



        <button type="submit" class="btn btn-success" id="pay-button">Lakukan Pembayaran</button>

    </div>
@endsection

@section('script')
    <script>
        var createdAt{{ $transaction->id }} = document.getElementById("created_at_{{ $transaction->id }}").innerHTML;
        var endTime{{ $transaction->id }} = new Date(new Date(createdAt{{ $transaction->id }}).getTime() + 15 * 60 *
            1000);
        var countdownDate{{ $transaction->id }} = endTime{{ $transaction->id }};

        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance{{ $transaction->id }} = countdownDate{{ $transaction->id }} - now;
            var hours = Math.floor((distance{{ $transaction->id }} % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance{{ $transaction->id }} % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance{{ $transaction->id }} % (1000 * 60)) / 1000);

            document.getElementById('countdown_{{ $transaction->id }}').innerHTML = 'Sisa Waktu ' + ("0" + hours)
                .slice(-2) + ":" + ("0" + minutes).slice(-2) + ":" + ("0" + seconds).slice(-2);

            if (distance{{ $transaction->id }} < 0) {
                document.getElementById('pay-button').classList.add('d-none');
                document.getElementById('pay-button').classList.add('disabled');
                document.getElementById('countdown_{{ $transaction->id }}').innerHTML = 'Pembayaran Gagal';
                document.getElementById('countdown_{{ $transaction->id }}').classList.add('text-danger');
                clearInterval(x);
            }
        }, 1000);
    </script>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '/invoice/{{ $transaction->id }}';
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran!");
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        });
    </script>
@endsection
