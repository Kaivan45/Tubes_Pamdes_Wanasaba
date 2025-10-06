<div>
   <header>
        <div class="logo">
            <h3><a class="logoL" href="/">PAMDES</a></h3>
        </div>
        <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        <nav>
            <ul id="menu">
                <li><a href="/tampil">Tampil Data</a></li>
                <li><a href="/haltambah">Tambah Pelanggan</a></li>
               <li>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>
</div>