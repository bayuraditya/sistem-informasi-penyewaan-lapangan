@extends('layouts.app')

@section('content')

<div class="m-5">
    <h1>Detail Pesanan</h1>
  
    <form action="/checkout" method="post">
        @csrf
        tanggal : {{$order['date']}} <br>

        @php
        $i=1;
        $session = 0;
        $totalPrice = 0;
        @endphp

        @foreach($reservation as $index => $reservation)   
            @foreach($reservation as $courtId => $courts)
                @foreach($courts['sesi'] as $rentalSessionId => $rentalSessionDetail)
                    <table>
                            <tr>
                                <td>
                                {{$i++}}. 

                                </td>
                                <td>
                                    lapangan 
                                </td>
                                <td>
                                    : {{$courts['court_name']}}
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>
                                sesi 
                                </td>
                                <td>
                                : {{$rentalSessionId}}    
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>
                                jam  
                                </td>
                                <td>
                                : {{$rentalSessionDetail['time']}} 
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                harga 
                                </td>
                                <td>
                                : {{$rentalSessionDetail['price']}} 
                                </td>
                            </tr>
                        </table>
                        @php
                        $totalPrice+=$rentalSessionDetail['price']
                        @endphp
                        <input type="checkbox" class="form-check-input d-none" name="reservation[{{$courtId}}][]" id="" value="{{$rentalSessionId}}" checked>
                @endforeach
            @endforeach
        @endforeach

        <br>

        total bayar : Rp{{$totalPrice}}  <br>
    <br><br>
        <label for="note" class="form-label">Tambahkan Catatan (Optional)</label><br>
        <!-- <input class="w-25" type="text" class="form-control" name="note"> -->
    <div class="row">
        <div class="col-12 col-md-3">
            <textarea rows="3" class="form-control" aria-label="With textarea" name="note"></textarea>
        </div>
    </div>
        <!-- <textarea rows="3" class="form-control w-25 " aria-label="With textarea" name="note"></textarea> -->
                     <br><br>  
        <input type="date" name="date" id=""value="{{$order['date']}}" class="d-none"  > <br>
       
        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault" >
            Saya menyetujui <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal"> syarat dan ketentuan yang berlaku </a> 
        </label>
        <!-- modal s&k -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Syarat dan Ketentuan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    <ol class="list-group list-group-numbered">
                    <li class="list-group-item">Reservasi tidak dapat dibatalkan atau direfund setelah pembayaran lunas.
                    </li>
                    <li class="list-group-item">Batas pelunasan adalah 5 menit setelah pesanan dibuat.
                    </li>
                    <li class="list-group-item">Jika setelah 5 menit pelunasan tidak dilakukan, maka pesanan akan dicancel otomatis.
                    </li>
                    </ol>
                </p>
            </div>
            </div>
        </div>
        </div> <br>

        <button type="submit" class="btn btn-primary" id="submitButton" disabled>Lanjutkan Pembayaran</button>
        <script>
            const checkbox = document.getElementById('flexCheckDefault');
            const submitButton = document.getElementById('submitButton');
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
            });
        </script>

   </form>


</div>
@endsection