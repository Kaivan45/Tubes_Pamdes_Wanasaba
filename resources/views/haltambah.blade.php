<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <h1>Tambah Pelanggan</h1>

    <form class="tambah" action="{{ route('users.store') }}" method="POST">
        @csrf

        <label for="nama">Nama</label>
        <input type="text" id="nama" name="name" placeholder="Masukkan nama lengkap" required />

        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Masukkan username" required />

        <label for="password">Password</label>
        <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Masukkan password" required
                style="padding-right: 2.5rem; height: 40px;" />
            <span class="togglePassword" data-target="password"
                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 18px;">
                👁️
            </span>
        </div>

        <label for="password_confirmation">Konfirmasi Password</label>
        <div style="position: relative;">
            <input type="password" id="password_confirmation" name="password_confirmation"
                placeholder="Masukkan password lagi" required style="padding-right: 2.5rem; height: 40px;" />
            <span class="togglePassword" data-target="password_confirmation"
                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 18px;">
                👁️
            </span>
        </div>

        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamat" required />

        <label for="telepon">No Telepon</label>
        <input type="text" id="telepon" name="noHp" placeholder="Masukkan nomor telepon" maxlength="15" required
            oninput="this.value = this.value.replace(/[^0-9]/g, '');" />

        <button type="submit">Submit</button>
    </form>
    <script>
        const toggles = document.querySelectorAll('.togglePassword');

        toggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const targetId = toggle.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
            });
        });
    </script>
</x-layout>
