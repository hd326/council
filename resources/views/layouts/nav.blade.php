<nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="/threads/create">New Thread</a></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Browse <span class="caret"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a href="/threads/">All Threads</a></li>

                            @if (auth()->check())
                            <li><a href="/threads?by={{ auth()->user()->name }}">My Threads</a></li>
                            {{-- the authenticated users name --}}
                            @endif

                            <li><a href="/threads?popular=1">Popular Threads</a></li>
                            <li><a href="/threads?unanswered=1">Unanswered Threads</a></li>
                        </ul>
                    </li>
                    

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Channel <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @foreach ($channels as $channel)
                            <li><a href="/threads/{{ $channel->slug }}">{{ $channel->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>

                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                    @else

                    <user-notifications></user-notifications>

                    {{-- @if (Auth::user()->isAdmin())
                    <li><a href="/admin"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a></li>
                    @endif --}}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-expanded="false" aria-haspopup="true" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="{{ route('profile', Auth::user()) }}">My Profile</a></li>
                            {{--<li><a href="/profiles/{{ auth()->user()->name }}">My Profile</a></li>--}}
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>