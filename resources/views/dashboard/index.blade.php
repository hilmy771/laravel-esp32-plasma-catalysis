<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <div>
                                <x-select name="device_id" id="device_id" class="mt-1 block w-full" onchange="changeDeviceId()">
                                    <option value="">Select Device</option>
                                    @foreach ($devices as $device)
                                        <option value="{{ $device->id }}">{{ $device->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error :messages="$errors->get('device_id')" class="mt-2" />
                            </div>
                        </div>
                        <div class="border rounded border-gray-300 dark:border-gray-700 bg-white w-100 p-4">
                            <canvas id="suhu" style="display: none;"></canvas>
                        </div>
                        <div class="border rounded border-gray-300 dark:border-gray-700 bg-white w-100 p-4">
                            <canvas id="kelembaban" style="display: none;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let deviceId = '';
    changeDeviceId();

    function changeDeviceId() {
        deviceId = document.getElementById('device_id').value;
        if (deviceId.length > 0) {
            document.getElementById('suhu').style.display = 'block';
            document.getElementById('kelembaban').style.display = 'block';
        } else {
            document.getElementById('suhu').style.display = 'none';
            document.getElementById('kelembaban').style.display = 'none';
        }
    }
</script>

<script type="module">
    const idSuhu = document.getElementById('suhu');

    const labels = [];
    const data = [];
    const chartSuhu = new Chart(idSuhu, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'xxx',
                data: data,
                fill: false,
                borderColor: 'rgb(0, 163, 0)',
                tension: 0.5
            }]
        },
    });

    setInterval(() => {
        if (deviceId.length > 0) {
            axios.get(`/dashboard/${deviceId}/suhu`)
            .then((res) => {
                if (res?.data?.status) {
                    const labels = res?.data?.labels;
                    const values = res?.data?.values;

                    chartSuhu.config.data.labels = labels;
                    chartSuhu.data.datasets[0].data = values;
                    chartSuhu.update();
                }
            })
            .catch((err) => {
                console.log(err);
            });
    
        }
    }, 3000);
</script>

<script type="module">
    const kelembaban = document.getElementById('kelembaban');

    const labels = [];
    const data = [];
    const chartKelembaban = new Chart(kelembaban, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'yyy',
                data: data,
                fill: false,
                borderColor: 'rgb(45, 137, 239)',
                tension: 0.5
            }]
        },
    });

    setInterval(() => {
        if (deviceId.length > 0) {
            axios.get(`/dashboard/${deviceId}/kelembaban`)
            .then((res) => {
                if (res?.data?.status) {
                    const labels = res?.data?.labels;
                    const values = res?.data?.values;

                    chartKelembaban.config.data.labels = labels;
                    chartKelembaban.data.datasets[0].data = values;
                    chartKelembaban.update();
                }
            })
            .catch((err) => {
                console.log(err);
            });
    
        }
    }, 3000);
</script>