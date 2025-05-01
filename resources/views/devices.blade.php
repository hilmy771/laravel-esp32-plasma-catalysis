@extends('layout.master')

@section('content')
<div class="content-wrapper">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Device Monitoring Gas</h1>
        </div>
    </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <h3 class="card-title">Pendaftaran Perangkat</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('devices.store') }}" method="POST" onsubmit="return validateForm();">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_name" class="form-label">Nama Ruangan</label>
                            <input type="text" class="form-control" id="room_name" name="room_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Perangkat</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="name" class="form-label">Nama Perangkat</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div> -->
                    <div class="mb-3">
                        <label class="form-label">Pilih Sensor</label>
                        <div>
                            <!-- <input type="checkbox" name="sensors[]" value="mq4" class="sensor-checkbox"> MQ-4 -->
                            <input type="checkbox" name="sensors[]" value="mq6" class="sensor-checkbox"> Propane/Butane Gas
                            <input type="checkbox" name="sensors[]" value="mq8" class="sensor-checkbox"> Hydrogen Gas
                        </div>
                        <span id="sensor-error" class="text-danger" style="display:none;">Pilih setidaknya satu sensor!</span>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate Token</button>
                </form>
            </div>
        </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <h3 class="card-title">Daftar Perangkat Terdaftar</h3>
            </div>
            <div class="card-body">
            <table class="table table-dark table-bordered">
                <thead>
                <tr>
                    <th>Nama Ruangan</th>
                    <th>Nama Perangkat</th>
                    <th>Sensor</th>
                    <th>Token</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($devices as $device)
                <tr>
                    <td>{{ $device->room_name }}</td>
                    <td>{{ $device->name }}</td>
                    <td>{{ implode(' | ', (array) $device->sensors) }}</td>
                    <td>{{ $device->token }}</td>
                    <td>
                        <form action="{{ route('devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perangkat ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    </div>
</section>
</div>
@endsection

@section('js')
<script src="{{ asset('reglogin/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('monitoring/monitoring.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function validateForm() {
        let checkboxes = document.querySelectorAll('.sensor-checkbox');
        let checked = Array.from(checkboxes).some(checkbox => checkbox.checked);

        if (!checked) {
            document.getElementById('sensor-error').style.display = 'block';
            return false; // Mencegah form dikirim
        }
        return true; // Lanjutkan pengiriman form jika valid
    }
</script>
@endsection
