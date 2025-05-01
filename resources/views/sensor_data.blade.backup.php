@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Sensor</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Pilih Perangkat dan Filter Tanggal -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <form action="{{ route('sensor.data') }}" method="GET">
                        <label for="device-select">Pilih Perangkat:</label>
                        <select id="device-select" name="device_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Perangkat</option>
                            @foreach ($devices as $device)
                                <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-md-4">
                    <form action="{{ route('sensor.data') }}" method="GET">
                        <input type="hidden" name="device_id" value="{{ request('device_id') }}">
                        <label for="date-filter">Filter Tanggal:</label>
                        <input type="date" id="date-filter" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()">
                    </form>
                </div>
            </div>

            <!-- Tabel Data Sensor -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tabel Data Sensor</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-dark table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nama Perangkat</th>
                                            <!-- <th>MQ-4</th> -->
                                            <th>Propane/Butane Gas</th>
                                            <th>Hydrogen Gas</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sensorData as $data)
                                            <tr>
                                                <td>{{ $data->device->name ?? 'Unknown' }}</td>
                                                <!-- <td>{{ $data->mq4_value }}</td> -->
                                                <td>{{ $data->mq6_value }}</td>
                                                <td>{{ $data->mq8_value }}</td>
                                                <td>{{ $data->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ $sensorData->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
