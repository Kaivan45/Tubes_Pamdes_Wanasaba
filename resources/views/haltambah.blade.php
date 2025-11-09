<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
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
        <input type="password" id="password" name="password" placeholder="Masukkan password" required />

        <label for="password_confirmation">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Masukkan ulang password" required />

        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamat" required />

        <label for="telepon">No Telepon</label>
        <input type="text" id="telepon" name="noHp" placeholder="Masukkan nomor telepon" required />

        <button type="submit">Submit</button>
    </form>
</x-layout>
