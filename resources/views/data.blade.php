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
    <h1>Input Meteran</h1>
      <form class="tambah" action="{{ route('data.store2') }}" method="POST">
        @csrf

        <label for="nama">Nama</label>
        <input type="text" id="nama" placeholder="Nama" value="{{ $user->name}}" readonly/>

        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" placeholder="Alamat" value="{{ $user->alamat }}" readonly/>

        <label for="telepon">No Telepon</label>
        <input type="text" id="telepon" placeholder="Nomor telpon" value="{{ $user->noHp }}" readonly/>

        <input type="hidden" name="user_id" id="id" placeholder="Status" value="{{ $user->id }}" readonly/>

        <label for="tanggal">Tanggal Tagihan</label>
        <input type="date" name="tanggal" id="tanggal" placeholder="Tanggal" required/>

        <label for="meteran">Input meteran</label>
        <input type="number" name="meteran" id="meteran" step="any" min="0" placeholder="Masukkan meter" required/>
        
        <label for="harga">Harga</label>
        <input type="text" id="hargaDisplay" readonly/>
        <input type="hidden" name="harga" id="harga" required>

        <button type="submit">Submit</button>
      </form>
</x-layout>