@extends('layouts.checkout-app')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.0/css/dataTables.dataTables.css" />
@endsection

@section('script')
    <script src="https://cdn.datatables.net/2.1.0/js/dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-all').DataTable();
            $('#table-pending').DataTable();
            $('#table-success').DataTable();
            $('#table-expired').DataTable();
        })
    </script>
@endsection

@section('content')
    <div class="container" style="margin-top: 100px; margin-bottom: 100px;">
        <h2>Riwayat Transaksi</h2>
        <br><br><br>
        @if (session('success'))
            <div class="alert-danger alert  alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class=" active btn btn-outline-primary mx-1" id="pills-home-tab" data-bs-toggle="pill"
                    data-bs-target="#all" type="button" role="tab" aria-controls="pills-home"
                    aria-selected="true">Semua</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="btn btn-outline-primary mx-1" id="pills-profile-tab" data-bs-toggle="pill"
                    data-bs-target="#pending" type="button" role="tab" aria-controls="pills-profile"
                    aria-selected="false">Menunggu Pembayaran</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="btn btn-outline-primary mx-1" id="pills-contact-tab" data-bs-toggle="pill"
                    data-bs-target="#settlement" type="button" role="tab" aria-controls="pills-contact"
                    aria-selected="false">Berhasil</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="btn btn-outline-primary mx-1" id="pills-contact-tab" data-bs-toggle="pill"
                    data-bs-target="#expire" type="button" role="tab" aria-controls="pills-contact"
                    aria-selected="false">Dibatalkan</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <!-- tab all -->
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="pills-home-tab">
                <table class="table table-striped" id="table-all">
                    <thead>
                        <tr>
                            <td>Id Transaksi</td>
                            <td>Total Bayar</td>
                            <td>Metode Pembayaran</td>
                            <td>Status Pembayaran</td>
                            <td>waktu transaksi dibuat</td>
                            <td>Waktu transaksi</td>
                            <td>Waktu Transaksi Lunas</td>
                            <td>Detail Pesanan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $tr)
                            <tr>
                                <td>{{ $tr->id }}</td>
                                <td> {{ 'Rp ' . number_format($tr->total_amount, 0, ',', '.') }}
                                </td>
                                <td>{{ $tr->payment_method }}</td>
                                <td>
                                    @if ($tr->payment_status == 'settlement' || $tr->payment_status == 'capture')
                                        <p class="text-success fw-bold">
                                            Lunas
                                        </p>
                                    @elseif($tr->payment_status == 'expire')
                                        <p class="text-danger fw-bold">
                                            Transaksi Dibatalkan
                                        </p>
                                    @elseif($tr->payment_status == 'pending')
                                        <p id="paymentStatusPending_{{ $tr->id }}" class="fw-bold text-warning">
                                            Pending
                                        </p>

                                        <p id="countdown_{{ $tr->id }}"></p>

                                        <form id="formCancel_{{ $tr->id }}" method="post"
                                            action="/cancel/{{ $tr->id }}">
                                            @csrf
                                            @method('PUT')
                                            <button
                                                onclick="return confirm('Apakah anda yakin ingin membatalkan pesanan ini ?')"
                                                type="submit" class="btn btn-danger">Batalkan Pesanan</button>
                                        </form>
                                        <button type="submit" class="btn btn-primary"
                                            id="pay-button{{ $tr->id }}">Selesaikan Pembayaran</button>
                                        <!-- payment gateway snap -->
                                    @else
                                        <p class="badge text-bg-seccondary">
                                            {{ $tr->payment_status }}
                                        </p>
                                    @endif
                                    <!-- untuk countdown expire otomatis -->
                                    <form class="d-none" id="cancel_{{ $tr->id }}" method="post"
                                        action="/cancel/{{ $tr->id }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger">Batalkan Pesanan</button>
                                    </form>
                                    <p class="d-none" id="created_at_{{ $tr->id }}">{{ $tr->created_at }}</p>

                                </td>
                                <td>{{ $tr->created_at }}</td>
                                <td>{{ $tr->transaction_time }}</td>
                                <td>{{ $tr->settlement_time }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#orderDetailModal_{{ $tr->id }}">
                                        Detail Reservasi
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- tab pending -->
            <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pills-profile-tab">
                <table class="table table-striped" id="table-pending">
                    <thead>
                        <tr>
                            <td>Id Transaksi</td>
                            <td>Total Bayar</td>
                            <td>Metode Pembayaran</td>
                            <td>Status Pembayaran</td>
                            <td>waktu transaksi dibuat</td>
                            <td>Waktu transaksi</td>
                            <td>Waktu Transaksi Lunas</td>
                            <td>Detail Pesanan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $tr)
                            @if ($tr->payment_status == 'pending')
                                <tr>
                                    <td>{{ $tr->id }}</td>
                                    <td>
                                        {{ 'Rp ' . number_format($tr->total_amount, 0, ',', '.') }} </td>
                                    <td>{{ $tr->payment_method }}</td>
                                    <td>
                                        @if ($tr->payment_status == 'settlement' || $tr->payment_status == 'capture')
                                            <p class="text-success fw-bold">
                                                Lunas
                                            </p>
                                        @elseif($tr->payment_status == 'expire')
                                            <p class="text-danger fw-bold">
                                                Transaksi Dibatalkan
                                            </p>
                                        @elseif($tr->payment_status == 'pending')
                                            <p id="paymentStatusPending_{{ $tr->id }}" class="fw-bold text-warning">
                                                Pending
                                            </p>
                                            <p id="countdown_{{ $tr->id }}"></p>

                                            <form id="formCancel_{{ $tr->id }}" method="post"
                                                action="/cancel/{{ $tr->id }}">
                                                @csrf
                                                @method('PUT')
                                                <button
                                                    onclick="return confirm('Apakah anda yakin ingin membatalkan pesanan ini ?')"
                                                    type="submit" class="btn btn-danger">Batalkan Pesanan</button>
                                            </form>
                                            <button type="submit" class="btn btn-primary"
                                                id="pay-button{{ $tr->id }}">Selesaikan Pembayaran</button>
                                            <!-- payment gateway snap -->
                                            <script type="text/javascript">
                                                // For example trigger on button clicked, or any time you need
                                                var payButtons = document.querySelectorAll('#pay-button{{ $tr->id }}');
                                                payButtons.forEach(function(payButton) {
                                                    payButton.addEventListener('click', function() {
                                                        // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
                                                        window.snap.pay('{{ $tr->snapToken }}', {
                                                            onSuccess: function(result) {
                                                                /* You may add your own implementation here */
                                                                //alert("payment success!");
                                                                window.location.href = ''
                                                                console.log(result);
                                                            },
                                                            onPending: function(result) {
                                                                /* You may add your own implementation here */
                                                                alert("wating your payment!");
                                                                console.log(result);
                                                            },
                                                            onError: function(result) {
                                                                /* You may add your own implementation here */
                                                                alert("payment failed!");
                                                                console.log(result);
                                                            },
                                                            onClose: function() {
                                                                /* You may add your own implementation here */
                                                                alert('you closed the popup without finishing the payment');
                                                            }
                                                        })
                                                    });
                                                });
                                            </script>
                                        @else
                                            <p class="badge text-bg-seccondary">
                                                {{ $tr->payment_status }}
                                            </p>
                                        @endif
                                        <!-- untuk countdown expire otomatis -->
                                        <form class="d-none" id="cancel_{{ $tr->id }}" method="post"
                                            action="/cancel/{{ $tr->id }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger">Batalkan Pesanan</button>
                                        </form>

                                        <script>
                                            var createdAt{{ $tr->id }} = document.getElementById("created_at_{{ $tr->id }}").innerHTML;
                                            var endTimeString{{ $tr->id }} = createdAt{{ $tr->id }};
                                            var endTimeDate{{ $tr->id }} = new Date(endTimeString{{ $tr->id }});
                                            var gmt8 = {
                                                timeZone: 'Asia/Singapore',
                                                hour12: false
                                            };
                                            var endTimeStringFormatted{{ $tr->id }} = endTimeDate{{ $tr->id }}.toLocaleString('en-US', gmt8);
                                            var endTime{{ $tr->id }} = new Date(endTimeStringFormatted{{ $tr->id }});
                                            endTime{{ $tr->id }}.setTime(endTime{{ $tr->id }}.getTime() + 15 * 60 * 1000);

                                            var countdownDate{{ $tr->id }} = endTime{{ $tr->id }};
                                            var x = setInterval(function() {
                                                var now = new Date().getTime();
                                                var distance{{ $tr->id }} = countdownDate{{ $tr->id }} - now;
                                                var hours = Math.floor((distance{{ $tr->id }} % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                var minutes = Math.floor((distance{{ $tr->id }} % (1000 * 60 * 60)) / (1000 * 60));
                                                var seconds = Math.floor((distance{{ $tr->id }} % (1000 * 60)) / 1000);
                                                var countdowns = document.querySelectorAll("#countdown_{{ $tr->id }}");
                                                countdowns.forEach(function(countdown) {
                                                    countdown.innerHTML = 'sisa waktu ' + ("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(
                                                        -2) + ":" + ("0" + seconds).slice(-2);
                                                });
                                                if (distance{{ $tr->id }} < 0) {
                                                    var formsCancel_{{ $tr->id }} = document.querySelectorAll(
                                                        '#formCancel_{{ $tr->id }}');
                                                    formsCancel_{{ $tr->id }}.forEach(function(f) {
                                                        f.classList.add('d-none');
                                                    });
                                                    var paymentStatusPending_{{ $tr->id }} = document.querySelectorAll(
                                                        "#paymentStatusPending_{{ $tr->id }}");
                                                    paymentStatusPending_{{ $tr->id }}.forEach(function(p) {
                                                        p.innerHTML = 'Transaksi Dibatalkan';
                                                        p.classList.add('text-danger');
                                                        p.classList.remove('text-warning');
                                                        clearInterval(x);
                                                    });
                                                    var paybuttons{{ $tr->id }} = document.querySelectorAll('#pay-button{{ $tr->id }}');
                                                    paybuttons{{ $tr->id }}.forEach(function(pbtn) {
                                                        pbtn.classList.add('d-none');
                                                    });
                                                    countdowns.forEach(function(countdown) {
                                                        countdown.innerHTML = '';
                                                    });
                                                }
                                            }, 1000);
                                        </script>

                                    </td>
                                    <td>{{ $tr->created_at }}</td>
                                    <td>{{ $tr->transaction_time }}</td>
                                    <td>{{ $tr->settlement_time }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#orderDetailModal_{{ $tr->id }}">
                                            Detail Reservasi
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- tab lunas -->
            <div class="tab-pane fade" id="settlement" role="tabpanel" aria-labelledby="pills-contact-tab">
                <table class="table table-striped" id="table-success">
                    <thead>
                        <tr>
                            <td>Id Transaksi</td>
                            <td>Total Bayar</td>
                            <td>Metode Pembayaran</td>
                            <td>Status Pembayaran</td>
                            <td>Waktu transaksi dibuat</td>
                            <td>Waktu transaksi</td>
                            <td>Waktu Transaksi Lunas</td>
                            <td>Detail Pesanan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $tr)
                            @if ($tr->payment_status == 'settlement' || $tr->payment_status == 'capture')
                                <tr>
                                    <td>{{ $tr->id }}</td>
                                    <td>
                                        {{ 'Rp ' . number_format($tr->total_amount, 0, ',', '.') }} </td>
                                    <td>{{ $tr->payment_method }}</td>
                                    <td>
                                        @if ($tr->payment_status == 'settlement' || $tr->payment_status == 'capture')
                                            <p class="text-success fw-bold">
                                                Lunas
                                            </p>
                                        @elseif($tr->payment_status == 'expire')
                                            <p class="text-danger fw-bold">
                                                Transaksi Dibatalkan
                                            </p>
                                        @elseif($tr->payment_status == 'pending')
                                            <p class="fw-bold text-warning">
                                                Pending
                                            </p>
                                            <form method="post" action="/cancel/{{ $tr->id }}">
                                                @csrf
                                                @method('PUT')
                                                <button
                                                    onclick="return confirm('Apakah anda yakin ingin membatalkan pesanan ini ?')"
                                                    type="submit" class="btn btn-danger">Batalkan Pesanan</button>
                                            </form>
                                            <button type="submit" class="btn btn-primary"
                                                id="pay-button{{ $tr->id }}">Selesaikan Pembayaran</button>
                                            <!-- payment gateway snap -->
                                        @else
                                            <p class="badge text-bg-seccondary">
                                                {{ $tr->payment_status }}
                                            </p>
                                        @endif
                                    </td>
                                    <td>{{ $tr->created_at }}</td>
                                    <td>{{ $tr->transaction_time }}</td>
                                    <td>{{ $tr->settlement_time }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#orderDetailModal_{{ $tr->id }}">
                                            Detail Reservasi
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- tab expire -->
            <div class="tab-pane fade" id="expire" role="tabpanel" aria-labelledby="pills-contact-tab">
                <table class="table table-striped" id="table-expired">
                    <thead>
                        <tr>
                            <td>Id Transaksi</td>
                            <td>Total Bayar</td>
                            <td>Metode Pembayaran</td>
                            <td>Status Pembayaran</td>
                            <td>Waktu transaksi dibuat</td>
                            <td>Waktu transaksi</td>
                            <td>Waktu Transaksi Lunas</td>
                            <td>Detail Pesanan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $tr)
                            @if ($tr->payment_status == 'expire')
                                <tr>
                                    <td>{{ $tr->id }}</td>
                                    <td>
                                        {{ 'Rp ' . number_format($tr->total_amount, 0, ',', '.') }} </td>
                                    <td>{{ $tr->payment_method }}</td>
                                    <td>
                                        @if ($tr->payment_status == 'settlement' || $tr->payment_status == 'capture')
                                            <p class="text-success fw-bold">
                                                Lunas
                                            </p>
                                        @elseif($tr->payment_status == 'expire')
                                            <p class="text-danger fw-bold">
                                                Transaksi Dibatalkan
                                            </p>
                                        @elseif($tr->payment_status == 'pending')
                                            <p class="fw-bold text-warning">
                                                Pending
                                            </p>
                                            <form method="post" action="/cancel/{{ $tr->id }}">
                                                @csrf
                                                @method('PUT')
                                                <button
                                                    onclick="return confirm('Apakah anda yakin ingin membatalkan pesanan ini ?')"
                                                    type="submit" class="btn btn-danger">Batalkan Pesanan</button>
                                            </form>
                                            <button type="submit" class="btn btn-primary"
                                                id="pay-button{{ $tr->id }}">Selesaikan Pembayaran</button>
                                        @else
                                            <p class="badge text-bg-seccondary">
                                                {{ $tr->payment_status }}
                                            </p>
                                        @endif
                                    </td>
                                    <td>{{ $tr->created_at }}</td>
                                    <td>{{ $tr->transaction_time }}</td>
                                    <td>{{ $tr->settlement_time }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#orderDetailModal_{{ $tr->id }}">
                                            Detail Reservasi
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>



    </div>


    <!-- Modal -->

    @foreach ($transactions as $tr)
<h3 id="expireReschedule{{$tr->id}}"></h3>
   
        <div class="modal fade" id="orderDetailModal_{{ $tr->id }}" tabindex="-1"
            aria-labelledby="orderDetailModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Order no {{ $tr->id }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- order detail here
                                                                    lapangan jam
                                                                    -->

                        <table>
                            <tbody>
                                @foreach ($tr->reservations as $ts)
                                    <tr>
                                        <td>{{ $loop->iteration }}. </td>
                                        <td>Tanggal</td>
                                        <td> : {{ $ts->date }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td> Lapangan</td>
                                        <td> : {{ $ts->court_id }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td> Jam</td>
                                        <td> : {{ $ts->rentalSession->rental_session_time }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                    
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#orderDetailModalReschedule_{{ $tr->id }}" id="reschedule{{$tr->id}}">Reschedule</button>
                            <script>
                                let paymentStatus{{$tr->id}} = '{{$tr->payment_status}}'
                                // console.log(paymentStatus{{$tr->id}});  
                                if(paymentStatus{{$tr->id}} == 'settlement'){

                                // String tanggal dan waktu awal
                                let dateTimeStr{{$tr->id}} = '{{$tr->settlement_time}}';
                                
                                // Ubah string menjadi objek Date
                                let dateParts{{$tr->id}} = dateTimeStr{{$tr->id}}.split(' ');
                                let date{{$tr->id}} = dateParts{{$tr->id}}[0].split('-'); // Pisah tanggal
                                let time{{$tr->id}} = dateParts{{$tr->id}}[1].split(':'); // Pisah waktu
                                
                                // Buat objek Date dari komponen tanggal dan waktu
                                let currentDate{{$tr->id}} = new Date(
                                    parseInt(date{{$tr->id}}[0]),       // Tahun
                                    parseInt(date{{$tr->id}}[1]) - 1,   // Bulan (mulai dari 0, jadi dikurangi 1)
                                    parseInt(date{{$tr->id}}[2]),       // Hari
                                    parseInt(time{{$tr->id}}[0]),       // Jam
                                    parseInt(time{{$tr->id}}[1]),       // Menit
                                    parseInt(time{{$tr->id}}[2])        // Detik
                                );
                                
                                // Tambahkan 60 menit
                                currentDate{{$tr->id}}.setMinutes(currentDate{{$tr->id}}.getMinutes() + 60);
                                
                                // Fungsi untuk memformat angka agar selalu memiliki 2 digit
                                function padToTwoDigits(num) {
                                    return num.toString().padStart(2, '0');
                                }
                                
                                // Format waktu setelah penambahan menjadi 'YYYY-MM-DD HH:MM:SS'
                                let expireRescheduleTime{{$tr->id}} = currentDate{{$tr->id}}.getFullYear() + '-' +
                                padToTwoDigits(currentDate{{$tr->id}}.getMonth() + 1) + '-' +
                                padToTwoDigits(currentDate{{$tr->id}}.getDate()) + ' ' +
                                padToTwoDigits(currentDate{{$tr->id}}.getHours()) + ':' +
                                padToTwoDigits(currentDate{{$tr->id}}.getMinutes()) + ':' +
                                padToTwoDigits(currentDate{{$tr->id}}.getSeconds());
                                
                                // document.getElementById("expireReschedule{{$tr->id}}").innerHTML = formattedDate{{$tr->id}}
                                // document.getElementById("expireReschedule{{$tr->id}}").textContent = formattedDate{{$tr->id}}
                                
                                // console.log(expireRescheduleTime{{$tr->id}})
                                let now = '{{$now}}';
                                if(expireRescheduleTime{{$tr->id}} < now){
                                    document.getElementById("reschedule{{$tr->id}}").disabled = true;
                                    // document.getElementById('reschedule{{$tr->id}}').classList.add('visually-hidden');
                                    // document.getElementById('reschedule{{$tr->id}}').innerHTML = 'ssss';
                                    // console.log('s');
                                // console.log(expireRescheduleTime{{$tr->id}} + '   ' + now)

                                }else{
                                    // document.getElementById("reschedule{{$tr->id}}").disabled = true;
                                }
                            }else{
                                document.getElementById("reschedule{{$tr->id}}").disabled = true;

                            }
                                
                        </script>
                     
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="orderDetailModalReschedule_{{ $tr->id }}" tabindex="-1"
            aria-labelledby="orderDetailModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Reschedule Order {{ $tr->id }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/profile/reschedule" method="post">
                            @csrf
                            @method('PUT')
                            
                            @foreach ($tr->reservations as $ts)
                                <input type="text" name="reservations[]" value="{{$ts->id}}" class="visually-hidden">                
                            @endforeach
                            
                            <label for="rescheduleData">Pilih Tanggal Reschedule :</label><br><br>
                            <input type="date" name="rescheduleDate" id=""> <br><br>
                            <button type="submit" class="btn btn-warning" data-bs-dismiss="modal">Reschedule</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

reschedule: 
max 60menit + tanggal lunas, klo lunas baru bisa reschedule klo pending/expire enggak
klik tombol 
pilih Tanggal
submit 
edit tanggal reservasi foreach
bawa id reservasi foreach, ubah tangggal foreach - routes baru,controller baru 