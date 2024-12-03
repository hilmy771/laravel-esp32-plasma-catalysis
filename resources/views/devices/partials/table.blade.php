<table class="table-auto w-full border">
    <thead>
        <tr>
            <th class="border py-2">No</th>
            <th class="border py-2">Name</th>
            <th class="border py-2">APPID</th>
            <th class="border py-2">APPSECRET</th>
            <th class="border py-2">Action</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @forelse ($records as $record)    
            <tr>
                <td class="border py-2 px-2 text-center w-1/12">{{ $i }}</td>
                <td class="border py-2 px-2">{{ $record->name }}</td>
                <td class="border py-2 px-2">{{ $record->appid }}</td>
                <td class="border py-2 px-2">{{ $record->appsecret }}</td>
                <td class="border py-2 px-2 text-center w-2/12">
                    <button type="button" class="rounded bg-slate-500 hover:bg-slate-600 text-white px-2" title="Show" onclick="window.open(`{{ route('devices.show', $record->id) }}`, `_self`)">
                        <i class="fa fa-eye"></i>
                    </button>
                    <button type="button" class="rounded bg-blue-500 hover:bg-blue-600 text-white px-2" title="Edit" onclick="window.open(`{{ route('devices.edit', $record->id) }}`, `_self`)">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button type="button" class="rounded bg-red-500 hover:bg-red-600 text-white px-2" title="Delete" onclick="handleDelete({{ $record->id }})">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            @php $i++; @endphp
        @empty
            <tr>
                <td colspan="99" class="text-center italic py-4">No Data</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    const handleDelete = (id) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            focusCancel: true,
            reverseButtons: true,
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/devices/${id}`)
                .then((res) => {
                    if (res.data.status) {
                        Swal.fire({
                            title: 'Success',
                            text: res.data.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                })
                .catch((err) => {
                    Swal.fire({
                        title: `${err.response.status} | ${err.response.statusText}`,
                        text: err.message,
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 3000
                    })
                });
            }
        });
    }
</script>