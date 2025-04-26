<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Sensor</title>
    <link rel="stylesheet" href="{{ asset('monitoring/monitoring.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="navbar">
        <a href="/">Monitoring</a>
        <a href="/">Settings</a>
        <a href="/">Help</a>
    </div>

    <h1>Monitoring Data Sensor</h1>
    <div class="chart-container">
        <canvas id="gasChart"></canvas>
    </div>
    <div class="table-container">
        <table id="sensor-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <!-- <th>Gas Value MQ4</th> -->
                    <th>Propane/Butane Gas</th>
                    <th>Hydrogen Gas</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="loading">Loading data...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="{{ asset('monitoring/monitoring.js') }}"></script>
</body>
</html>
