@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 100px; margin-bottom: 100px">
        <h1 class="mb-4">Detail Pesanan</h1>

        <form action="/checkout" method="post">
            @csrf
            <div class="mb-3">
                <strong>Tanggal:</strong> {{ $order['date'] }}
            </div>

            @php
                $i = 1;
                $totalPrice = 0;
            @endphp

            <div class="d-flex gap-5 flex-wrap">
                @foreach ($reservation as $index => $reservation)
                    @foreach ($reservation as $courtId => $courts)
                        @foreach ($courts['sesi'] as $rentalSessionId => $rentalSessionDetail)
                            <div class="mb-3 table-responsive">
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
                                        <td>: {{ 'Rp ' . number_format($rentalSessionDetail['price'], 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                                @php
                                    $totalPrice += $rentalSessionDetail['price'];
                                @endphp
                                <input type="checkbox" class="form-check-input d-none"
                                    name="reservation[{{ $courtId }}][]" value="{{ $rentalSessionId }}" checked>
                            </div>
                        @endforeach
                    @endforeach
                @endforeach
            </div>


            <div class="mb-4 fs-5">
                <strong>Total Bayar:</strong> <span
                    class="text-primary text-bold">{{ 'Rp ' . number_format($totalPrice, 0, ',', '.') }}</span>
            </div>

            <div class="mb-4">
                <label for="note" class="form-label">Tambahkan Catatan (Optional)</label>
                <textarea rows="3" class="form-control" name="note"></textarea>
            </div>

            <input type="date" name="date" value="{{ $order['date'] }}" class="d-none">

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">syarat dan
                        ketentuan yang berlaku</a>
                </label>
            </div>

            <!-- Modal S&K -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Syarat dan Ketentuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ol class="list-group list-group-numbered">
                                <li class="list-group-item">Reservasi tidak dapat dibatalkan atau direfund setelah
                                    pembayaran lunas.</li>
                                <li class="list-group-item">Batas pelunasan adalah 15 menit setelah pesanan dibuat.</li>
                                <li class="list-group-item">Jika setelah 15 menit pelunasan tidak dilakukan, maka pesanan
                                <li class="list-group-item">Pengunjung dapat melakukan reschedule di tanggal berbeda namun di lapangan dan jam yang sama maksimal 60 menit setelah setelah pelunasan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="submitButton" disabled>Lanjutkan Pembayaran</button>
        </form>
    </div>

    <script>
        const checkbox = document.getElementById('flexCheckDefault');
        const submitButton = document.getElementById('submitButton');
        checkbox.addEventListener('change', function() {
            submitButton.disabled = !this.checked;
        });
    </script>
@endsection
