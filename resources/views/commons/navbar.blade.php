<header class="mb-4">
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        {{-- トップページへのリンク --}}
        <a class="navbar-brand" href="/">TaskBoard</a>

        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
                @if (Auth::check())
                    {{-- タスク作成ページへのリンク --}}
                    <li class="nav-item">{!! link_to_route('tasks.create', '新規タスクの投稿', [], ['class' => 'nav-link']) !!}</li>
                    {{-- タスク一覧ページへのリンク --}}
                    <li class="nav-item">{!! link_to_route('tasks.index', 'Tasks', [], ['class' => 'nav-link']) !!}</li>
                    <li class="nav-item dropdown">
                            <ul class="dropdown-menu dropdown-menu-right">
                                {{-- タスク詳細ページへのリンク --}}
                                <li class="dropdown-item">{!! link_to_route('tasks.show', 'My profile', ['task' => Auth::id()]) !!}</li>
                                <li class="dropdown-divider"></li>
                                {{-- ログアウトへのリンク --}}
                                <li class="dropdown-item">{!! link_to_route('logout.get', 'Logout') !!}</li>
                            </ul>
 
            </ul>
            
                
            <ul class="navbar-nav">
                @else
                    {{-- ユーザ登録ページへのリンク --}}
                    <li>{!! link_to_route('signup.get', 'Signup', [], ['class' => 'nav-link']) !!}</li>
                    {{-- ログインページへのリンク --}}
                    <li class="nav-item"><a href="#" class="nav-link">Login</a></li>
                @endif
            </ul>

        </div>
    </nav>
</header>