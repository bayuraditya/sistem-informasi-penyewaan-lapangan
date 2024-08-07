@extends('layouts.app')
@section('style')
    <style>
        #facility,
        #contact-us {
            padding-top: 70px;
            /* Sesuaikan tinggi ini dengan tinggi navbar Anda */
            margin-top: -70px;
            /* Sesuaikan tinggi ini dengan tinggi navbar Anda */
            margin-bottom: 80px;
        }
    </style>
@endsection

@section('content')
    <div class="hero-section" style="">
        <div class="container">
            <form method="GET" action="available-courts">
                <h2 class="text-center">BOOKING LAPANGAN</h2><br>
                <div class="py-3  mx-auto form-group text-dark bg-white d-flex justify-content-center rounded-pill ">
                    <label for="date" class="my-auto py-auto m-2">Pilih Tanggal:</label>
                    <div class="d-inline-flex m-2">
                        <input type="date" class="form-control d-inline-flex" id="date" name="date">
                    </div>
                    <button type="submit" class="m-2 px-4 rounded-pill btn text-light"
                        style="background-color: #4CAF50;">Cari</button>
                </div>
            </form>
        </div>
    </div>
    <div class="container my-5">
        <div class="row mt-5" id="facility">
            <div class="col-12 col-lg-6 order-2 order-md-2">


            <div id="carouselExample" class="carousel slide">
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img src="{{ asset('assets-user/img/facility.jpg') }}" alt="" class="img-fluid rounded-4 d-block w-100">

                </div>
                <div class="carousel-item">
                <img src="{{ asset('assets-user/img/facility.jpg') }}" alt="" class="img-fluid rounded-4 d-block w-100">

                </div>
                <div class="carousel-item">
                <img src="{{ asset('assets-user/img/facility.jpg') }}" alt="" class="img-fluid rounded-4 d-block w-100">

                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            </div>



                <!-- <img src="{{ asset('assets-user/img/facility.jpg') }}" alt="" class="img-fluid rounded-4"> -->
            </div>
            <div class="col-12 col-lg-6 order-1 order-md-1">
                <h3>Fasilitas</h3>
                <p>Di SBC menyediakan beberapa fasilitas untuk para pemain agar pemain merasa aman dan nyaman</p>
                <div class="row">
                    <div class="col-md-6 order-2 order-md-1">
                        <ul class="list-unstyled">
                            <li class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa fa-circle-check text-success me-2"></i>
                                    <p class="mb-0 fs-5">Lapangan Berkualitas</p>
                                </div>
                                <p class="ms-4">Karpet berkualitas tinggi untuk kenyamanan dan keamanan bermain.</p>
                            </li>
                            <li class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa fa-circle-check text-success me-2"></i>
                                    <p class="mb-0 fs-5">Restroom</p>
                                </div>
                                <p class="ms-4">Kamar mandi bersih dan modern, selalu terjaga kebersihannya.</p>
                            </li>
                            <!-- Tambahkan item lainnya di sini jika diperlukan -->
                        </ul>
                    </div>
                    <div class="col-md-6 order-1 order-md-2">
                        <ul class="list-unstyled">
                            <li class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa fa-circle-check text-success me-2"></i>
                                    <p class="mb-0 fs-5">Cafe</p>
                                </div>
                                <p class="ms-4">Hidangan lezat yang beragam dan minuman segar, tempat santai ideal.</p>
                            </li>
                            <li class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa fa-circle-check text-success me-2"></i>
                                    <p class="mb-0 fs-5">Parkir Luas</p>
                                </div>
                                <p class="ms-4">Area parkir luas dan aman, akses mudah bagi pengunjung.</p>
                            </li>
                            <!-- Tambahkan item lainnya di sini jika diperlukan -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row mt-5" id="contact-us">
            <div class="col-12">
                <h3>Hubungi Kami</h3>
                <p>Hubungi kami jika Anda memiliki pertanyaan atau masalah</p>
                <form action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">No HP</label>
                        <input type="tel" id="phone" class="form-control" name="phone" pattern="[0-9]{10,15}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            </div>
            <div class="col-12 col-lg-6">
                <img src="{{ asset('assets/img/contact-us.jpg') }}" alt="" class="img-fluid rounded-4">
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#date').val('{{ $todayDate }}');
            $("#date").change(function() {
                var dateValue = $(this).val();
                var todayDate = '{{ $todayDate }}';
                if (dateValue < todayDate) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Tanggal tidak boleh kurang dari hari ini',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                }
            })
        });
    </script>
@endsection
