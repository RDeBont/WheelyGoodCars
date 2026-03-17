<header>
    <div class="navContainer">
        <div class="logo">
            <a href="/">Wheely Good Cars</a>
        </div>
        <nav class="nav-center">
            <ul>
                <li><a href="/">Alles auto's</a></li>
                <li><a href="/owncars">Mijn aanbod</a></li>
                <li><a href="/offers.offerStep1">Aanbod plaatsen</a></li>
                @auth
                    @if(auth()->user()->is_admin == 1)
                        <li><a href="{{ route('admin.index') }}">Admin</a>
</li>
                    @endif
                @endauth
            </ul>
        </nav>
        <div class="nav-right">
            @guest
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endguest
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">Log uit</a>
                </form>
            @endauth
        </div>
    </div>
</header>