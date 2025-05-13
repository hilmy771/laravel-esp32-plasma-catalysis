let alertHistory = [];

let chartData = {
    labels: [],
    datasets: [
        {
            label: 'Propane/Butane Value',
            data: [],
            borderColor: '#ab1111',
            backgroundColor: 'rgba(153, 0, 51, 0.2)',
            fill: true,
            tension: 0.5,
        },
        {
            label: 'Hydrogen Value',
            data: [],
            borderColor: '#32cd32',
            backgroundColor: 'rgba(0, 153, 76, 0.2)',
            fill: true,
            tension: 0.5,
        }
    ]
};

const ctx = document.getElementById('gasChart').getContext('2d');
const gasChart = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                labels: { color: '#ffffff' }
            }
        },
        scales: {
            x: {
                ticks: { color: '#ffffff' }
            },
            y: {
                ticks: { color: '#ffffff' },
                beginAtZero: true
            }
        }
    }
});

document.getElementById('gasChart').style.backgroundColor = '#343a40';

const roomSelect   = document.getElementById('room-select');
const deviceSelect = document.getElementById('device-select');
let currentDeviceId = null;
let interval = null;

function clearChart() {
    gasChart.data.labels = [];
    gasChart.data.datasets.forEach(ds => ds.data = []);
    gasChart.update();
}

async function updateChart() {
    const roomId   = roomSelect.value;
    const deviceId = deviceSelect.value;

    if (!roomId || !deviceId) return;

    try {
        const res = await fetch(`/api/sensor?room_name=${roomId}&device_id=${deviceId}&t=${Date.now()}`);
        const data = await res.json();

        data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        const labels = [], mq6_values = [], mq8_values = [];

        data.forEach(sensor => {
            const d = new Date(sensor.created_at);
            labels.push(d.toLocaleString("id-ID"));
            mq6_values.push(sensor.mq6_value ?? null);
            mq8_values.push(sensor.mq8_value ?? null);
        });

        gasChart.data.labels = labels.reverse();
        gasChart.data.datasets[0].data = mq6_values.reverse();
        gasChart.data.datasets[1].data = mq8_values.reverse();
        gasChart.update();

        // Update sensor card
        if (data.length > 0) {
            const latest = data[0];
            document.getElementById("mq6-value").textContent = latest.mq6_value;
            document.getElementById("mq8-value").textContent = latest.mq8_value;
        }
    } catch (err) {
        console.error("Gagal update chart:", err);
    }
}

roomSelect.addEventListener("change", () => {
    clearChart();
    if (interval) clearInterval(interval);

    deviceSelect.innerHTML = '<option value="">Pilih Perangkat</option>';

    const roomId = roomSelect.value;
    if (!roomId) return;

    fetch(`/devices/by-room?room_name=${roomId}`)
        .then(res => res.json())
        .then(data => {
            data.forEach(device => {
                const opt = document.createElement('option');
                opt.value = device.id;
                opt.textContent = device.name;
                deviceSelect.appendChild(opt);
                deviceSelect.dispatchEvent(new Event('change'));
            });
        })
        .catch(err => console.error(err));
});

deviceSelect.addEventListener("change", () => { 
    console.log("Device changed to:", deviceSelect.value);   
    clearChart();
    if (interval) clearInterval(interval);
    
    currentDeviceId = deviceSelect.value;
    
    updateChart();
    checkGasAlerts();
    // updateCards(currentDeviceId);

    interval = setInterval(() => {
        Promise.allSettled([updateChart(), checkGasAlerts()]);
        // checkGasAlerts();
        // updateCards(currentDeviceId);
    }, 5000);
});

window.addEventListener("resize", () => gasChart.resize());

let lastPopupMessage = null;

function showPopup(message) {
    const popup = document.getElementById("gasPopup");
    const popupMsg = document.getElementById("gasPopupMessage");
    popupMsg.textContent = message;

    popup.classList.add("show");
    popup.classList.remove("hidden");

    clearTimeout(popup._hideTimeout);
    popup._hideTimeout = setTimeout(() => {
        popup.classList.remove("show");
        setTimeout(() => popup.classList.add("hidden"), 300);
    }, 5000);
}

function getAlertId(message) {
    return message.trim(); // Remove timestamp — use the raw message only
}

async function checkGasAlerts() {
    try {
        const res = await fetch('/api/check-gas-alerts');
        const data = await res.json();

        const { alerts = [], alertTriggered = false, mq6_value = 0, mq8_value = 0 } = data;

        const dismissedAlerts = JSON.parse(localStorage.getItem("dismissedAlerts")) || [];
        let existingAlerts = JSON.parse(localStorage.getItem("activePushAlerts")) || [];

        if (!alertTriggered || alerts.length === 0) {
            updatePushNotificationPanel(
                existingAlerts.map(msg => ({
                    message: msg,
                    id: getAlertId(msg)
                }))
            );
            return;
        }

        const timestamp = new Date().toLocaleString('id-ID');
        const newAlertMessages = alerts.map(alert => `[${timestamp}] ${alert.message}`);

        const undismissedNewAlerts = newAlertMessages.filter(msg => {
            const alertId = getAlertId(msg);
            return !dismissedAlerts.includes(alertId);
        });

        const mergedAlerts = [...new Set([...existingAlerts, ...undismissedNewAlerts])];

        const cleanedAlerts = mergedAlerts.filter(msg => {
            const alertId = getAlertId(msg);
            return !dismissedAlerts.includes(alertId);
        });

        const activeAlerts = cleanedAlerts.map(msg => ({
            message: msg,
            id: getAlertId(msg)
        }));

        localStorage.setItem("activePushAlerts", JSON.stringify(cleanedAlerts));
        updatePushNotificationPanel(activeAlerts);

        const popupMessage = `Propane/Butane: ${mq6_value} ppm, Hydrogen: ${mq8_value} ppm`;
        if (currentDeviceId && alertTriggered && activeAlerts.length > 0 && popupMessage !== lastPopupMessage) {
            alertHistory.push(`[${timestamp}] ${popupMessage}`);
            localStorage.setItem("alertHistory", JSON.stringify(alertHistory));

            showPopup(popupMessage);
            lastPopupMessage = popupMessage;
        }
    } catch (error) {
        console.error('Error checking gas level:', error);
    }
}

function updatePushNotificationPanel(alerts) {
    const badge = document.getElementById('alert-badge-count');
    const list = document.getElementById('alert-list');
    list.innerHTML = '';

    const dismissedAlerts = JSON.parse(localStorage.getItem("dismissedAlerts")) || [];
    const activeAlerts = alerts.filter(alert => !dismissedAlerts.includes(alert.id));

    if (activeAlerts.length === 0) {
        badge.style.display = 'none';
        list.innerHTML = '<div class="text-sm text-muted">No active alerts</div>';
        return;
    }

    badge.textContent = activeAlerts.length;
    badge.style.display = 'inline';

    badge.style.transform = 'scale(1.4)';
    setTimeout(() => {
        badge.style.transform = 'scale(1)';
    }, 200);

    activeAlerts.forEach(alert => {
        const alertItem = document.createElement('div');
        alertItem.className = 'dropdown-item text-danger alert-item';
        alertItem.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${alert.message}`;

        alertItem.addEventListener("click", () => {
            const updatedDismissed = [...dismissedAlerts, alert.id];
            localStorage.setItem("dismissedAlerts", JSON.stringify([...new Set(updatedDismissed)]));
            updatePushNotificationPanel(alerts); // Re-render
        });

        list.appendChild(alertItem);
    });
}

function clearAllAlerts() {
    const allActive = JSON.parse(localStorage.getItem("activePushAlerts")) || [];
    const dismissed = JSON.parse(localStorage.getItem("dismissedAlerts")) || [];

    const updatedDismissed = [
        ...dismissed,
        ...allActive.map(msg => getAlertId(msg))
    ];

    localStorage.setItem("dismissedAlerts", JSON.stringify([...new Set(updatedDismissed)]));
    updatePushNotificationPanel([]); // Re-render with no alerts
}

// Optional: Resize chart on window resize
window.addEventListener("resize", () => gasChart.resize());
