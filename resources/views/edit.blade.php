<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <h1>Edit Data</h1>

      <form class="tambah" action="{{ route('data.update', $data->slug) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="nama">Nama</label>
        <input type="text" id="nama" placeholder="Nama" value="{{ $data->user->name}}" readonly/>

        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" placeholder="Alamat" value="{{ $data->user->alamat }}" readonly/>

        <label for="telepon">No Telepon</label>
        <input type="text" id="telepon" placeholder="Nomor telpon" value="{{ $data->user->noHp }}" readonly/>

        <input type="hidden" name="user_id" id="id" placeholder="Status" value="{{ $data->user_id }}" readonly/>

        <label for="meteran">Input meteran </label>
        <input type="number" name="meteran" id="meteran" placeholder="Masukkan meter" value="{{ $data->meteran }}" readonly/>

        
        <label for="harga">Harga</label>
        <input type="text" id="hargaDisplay" value="Rp. {{ number_format($data->harga, 0, ',', '.') }}" readonly/>

        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="Belum Lunas" {{ $data->status == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
            <option value="Lunas" {{ $data->status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
        </select>

        <button type="submit">Submit</button>
      </form>
</x-layout>