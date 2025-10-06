<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <h1>Tampil Data</h1>

     <form class="search-form">
        <input type="text" name="search" placeholder="Cari..." value="" autocomplete="off">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
    </form>

    <table border="1" class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>NO HP</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->user->name }}</td>
                <td>{{ $d->user->alamat }}</td>
                <td>{{ $d->user->noHp }}</td>
                <td>{{ $d->status }}</td>
                <td>
                    <a href="/data/{{ $d->slug }}" class="btn-edit">
                        <i class="fa-solid fa-pen-to-square"></i> 
                    </a> 
                    | 
                    <form action="{{ route('data.destroy', $d->slug) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-hapus"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            @endforeach
            
        </tbody>
    </table>
    <br>
    <br>
    <div class="pagination">
        {{ $data->links() }}
    </div>
    
</x-layout>