@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Monitoring Gas</h1>
                </div>
            </div>
        </div>
    </div>

<section class="content">
<form method="GET" action="{{ route('dashboard') }}">
    <div class="row mb-3">
            <!-- Pilih Ruangan -->
            <div class="col-sm-6 col-md-4">
                <div class="form-group">
                <label for="room_name">Pilih Ruangan:</label>
                <select id="room-select" name="room_name" class="form-control" onchange="this.form.submit()">
                    <option value="">Pilih Ruangan</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room }}" {{ $selectedRoom == $room ? 'selected' : '' }}>
                            {{ $room }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>  
                <!-- <select id="room-select" class="form-control">
                    <option value="">Pilih Ruangan</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room }}">{{ $room }}</option>
                    @endforeach
                </select> -->

            <!-- Pilih Perangkat -->
            <div class="col-sm-6 col-md-4">
                <div class="form-group">
                    <label for="device_id">Pilih Perangkat:</label>
                    <select id="device-select" name="device_id" class="form-control">
                    <option value="">Pilih Perangkat</option>
                    @foreach ($devices as $device)
                        <option value="{{ $device->id }}" {{ $selectedDevice == $device->id ? 'selected' : '' }}>
                            {{ $device->name }}
                        </option>
                    @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>


            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Grafik Data Sensor</h3>
                        </div>
                        {{-- <div class="card-body">
                            <canvas id="sensorChart"></canvas>
                        </div> --}}
                        <div class="chart-container">
                            <canvas id="gasChart"></canvas>
                        </div>
                            <div class="sensor-card mq6-card">
                                <h3>Propane/Butane Gas</h3>
                                <p>Gas Value: <strong id="mq6-value">-</strong></p>
                            </div>
                            <div class="sensor-card mq8-card">
                                <h3>Hydrogen Gas</h3>
                                <p>Gas Value: <strong id="mq8-value">-</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="{{asset('css/monitoring.js')}}"></script>

{{-- <script src="{{asset(path: 'css/monitoring.js')}}"></script> --}}

{{-- <script>
document.addEventListener("DOMContentLoaded", function () {
    let ctx = document.getElementById('sensorChart').getContext('2d');

    let sensorChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                // { label: 'MQ-4', borderColor: 'red', backgroundColor: 'rgba(255,0,0,0.2)', data: [], fill: true },
                { label: 'MQ-6', borderColor: 'yellow', backgroundColor: 'rgba(255,255,0,0.2)', data: [], fill: true },
                { label: 'MQ-8', borderColor: 'blue', backgroundColor: 'rgba(0,0,255,0.2)', data: [], fill: true }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true, // Jaga agar chart tetap proporsional
            scales: {
                x: {
                    title: { font : 16, display: true, text: 'Tanggal & Waktu', color: '#FFFFFF' },
                    ticks: {
                        color: '#FFFFFF',
                        callback: function(value, index, values) {
                            let date = new Date(value);
                            return date.toLocaleString("id-ID", {
                                year: "numeric",
                                month: "2-digit",
                                day: "2-digit",
                                hour: "2-digit",
                                minute: "2-digit",
                                second: "2-digit"
                            });
                        }
                    }
                },
                y: {
                    ticks: {
                        font: {
                            size: 16 // Ubah ukuran font sesuai kebutuhan
                        },
                        color: "white" // Pastikan warna terlihat jelas di tema gelap
                    },
                    title: {
                        display: true,
                        text: "Nilai Sensor",
                        font: {
                            size: 16, // Ukuran font label sumbu Y
                        },
                        color: "white"
                    }
                }
            },
            plugins: {
                legend: {
                    labels: { color: '#FFFFFF', font: { size: 14 } }
                }
            }
        }
    });

    let currentDeviceId = null;
    let interval = null;

    function updateChart(deviceId) {
        if (!deviceId || deviceId !== currentDeviceId) return;

        fetch(`/api/sensor?device_id=${deviceId}`)
            .then(response => response.json())
            .then(data => {
                let labels = [];
                // let mq4_values = [];
                let mq6_values = [];
                let mq8_values = [];

                data.forEach(sensor => {
                    labels.push(new Date(sensor.created_at).toLocaleString("id-ID", {
                        year: "numeric",
                        month: "2-digit",
                        day: "2-digit",
                        hour: "2-digit",
                        minute: "2-digit",
                        second: "2-digit"
                    }));
                    // mq4_values.push(sensor.mq4_value);
                    mq6_values.push(sensor.mq6_value);
                    mq8_values.push(sensor.mq8_value);
                });

                sensorChart.data.labels = labels;
                // sensorChart.data.datasets[0].data = mq4_values;
                sensorChart.data.datasets[0].data = mq6_values;
                sensorChart.data.datasets[1].data = mq8_values;
                sensorChart.update();
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    let deviceSelect = document.getElementById('device-select');
    deviceSelect.addEventListener('change', function () {
        let deviceId = this.value;
        currentDeviceId = deviceId;

        sensorChart.data.labels = [];
        sensorChart.data.datasets.forEach(dataset => dataset.data = []);
        sensorChart.update();

        if (interval) clearInterval(interval);

        updateChart(deviceId);

        interval = setInterval(() => {
            updateChart(deviceId);
        }, 5000);
    });

    window.addEventListener("resize", function () {
        sensorChart.resize();
    });
});
</script> --}}
@endsection
