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
    
    <h1>Data User</h1>

     <form class="search-form">
        <input type="text" name="search" placeholder="Cari..." value="" autocomplete="off">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
    </form>

    <table border="1" class="data-table data-user">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>NO HP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
            <tr>
                <td>{{ $data->firstItem() + $loop->index  }}</td>
                <td>{{ $d->name }}</td>
                <td>{{ $d->alamat }}</td>
                <td>{{ $d->noHp }}</td>
                <td style="display: flex; gap: 8px;">
                    <a href="{{ route('data.create', $d->username) }}" class="btn-edit">
                        Input Meteran
                    </a> 
                    | 
                    <form action="{{ route('user.destroy', $d->username) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
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