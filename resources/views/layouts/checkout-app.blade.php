

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Lapangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script type="text/javascript"
      src="{{config('midtrans.snap_url')}}"
      data-client-key="{{config('midtrans.client_key')}}"></script>
  </head>
  <body>
    <div class="m-5">

      <a href="/" class="btn btn-primary">HOME</a> <br><br>
      
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          Profile
        </button>
        <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="/profile">{{$user->name}}</a></li>
        <li><a class="dropdown-item" href="/profile/reservation-history">Reservation History</a></li>
        <li><a class="dropdown-item" href="/profile/transaction-history">Transaction History</a></li>
      </ul>
    </div>
  </div>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>