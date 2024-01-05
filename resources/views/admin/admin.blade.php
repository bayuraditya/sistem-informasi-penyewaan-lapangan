dashboard admin
<form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
                    </form>