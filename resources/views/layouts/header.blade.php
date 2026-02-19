<header>
        <nav>
            <ul>
                <li><a href="/">Alles auto's</a></li>
                <li><a href="/owncars">Mijn aanbod</a></li>
                <li><a href="/offers.offerStep1">Aanbod plaatsen</a></li>

                <li>
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
                </li>
            </ul>
        </nav>
</header>