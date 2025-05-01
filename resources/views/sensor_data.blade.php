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
      <!-- Filter: Ruangan, Perangkat, Tanggal -->
      <form action="{{ route('sensor.data') }}" method="GET" class="row mb-3 gx-3">
        <!-- Ruangan -->
        <div class="col-md-4">
          <label for="room-select" class="form-label">Pilih Ruangan:</label>
          <select id="room-select" name="room_name" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Ruangan</option>
            @foreach($rooms as $r)
              <option value="{{ $r }}"
                {{ request('room_name') == $r ? 'selected' : '' }}>
                {{ $r }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Perangkat -->
        <div class="col-md-4">
          <label for="device-select" class="form-label">Pilih Perangkat:</label>
          <select id="device-select" name="device_id" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Perangkat</option>
            @foreach ($devices as $device)
              <option value="{{ $device->id }}"
                {{ request('device_id') == $device->id ? 'selected' : '' }}>
                {{ $device->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Tanggal -->
        <div class="col-md-4">
          <label for="date-filter" class="form-label">Filter Tanggal:</label>
          <input type="date" id="date-filter" name="date"
                 class="form-control"
                 value="{{ request('date') }}"
                 onchange="this.form.submit()">
        </div>
      </form>

      <!-- Tabel Data Sensor -->
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Tabel Data Sensor</h3></div>
            <div class="card-body table-responsive">
              <table class="table table-dark table-bordered">
                <thead>
                  <tr>
                    <th>Nama Ruangan</th>       <!-- Tambahan -->
                    <th>Nama Perangkat</th>
                    <th>Propane/Butane Gas</th>
                    <th>Hydrogen Gas</th>
                    <th>Waktu</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($sensorData as $data)
                    <tr>
                      <td>{{ $data->device->room_name ?? '-' }}</td>        <!-- Tambahan -->
                      <td>{{ $data->device->name ?? 'Unknown' }}</td>
                      <td>{{ $data->mq6_value }}</td>
                      <td>{{ $data->mq8_value }}</td>
                      <td>{{ $data->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="card-footer">
              {{ $sensorData->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
