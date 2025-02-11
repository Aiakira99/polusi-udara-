<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Polusi Udara UNM</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2"></script>
    <link rel="stylesheet" href="style.css">
    <include src="responsive.css">
    <include src="responsive.js">
</head>

<body>
<header>
    <h1>Monitoring Polusi Udara</h1>
    <p>Lokasi: Kota Makassar, Jl. Andi Pangeran Pettarani, Gedung Pinisi UNM</p>
    <a href="https://www.bing.com/maps?osid=56b7649c-4f24-4b84-b4b4-860209f2d855&cp=-5.168459~119.429626&lvl=16&pi=0&v=2&sV=2&form=S00027" target="_blank">Lihat Peta</a>
</header>

<div class="data-box">
    <table class="container">
        <thead>
            <tr>
                <th>PM2.5 (µg/m³)</th>
                <th>PM10 (µg/m³)</th>
                <th>Kelembaban (%)</th>
                <th>Suhu (°C)</th>
            </tr>
        </thead>
        <tbody id="dataTable"></tbody>
    </table>
</div>

<div id="warning" class="warning" style="display: none;">
    <p style="text-align: center; font-weight: bold; color: red;">Kadar polusi berbahaya!</p>
</div>

<div class="chart-container">
    <canvas id="pmChart"></canvas>
</div>

<script>
function fetchData() {
    const tableBody = document.getElementById("dataTable");
    const warningDiv = document.getElementById("warning");
    const currentDate = new Date().toLocaleDateString();
    const lastFetchDate = localStorage.getItem("lastFetchDate");

    // Jika tanggal berbeda, kosongkan tabel dan simpan tanggal baru
    if (currentDate !== lastFetchDate) {
        tableBody.innerHTML = "";
        localStorage.setItem("lastFetchDate", currentDate);
    }

    fetch("data.php")
        .then(response => response.json())
        .then(data => {
            const labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            const pm25Data = Array(7).fill(0);
            const pm10Data = Array(7).fill(0);
            const dayCount = Array(7).fill(0);
            let showWarning = false;

            data.forEach(row => {
                const date = new Date(row.waktu);
                const dayIndex = date.getDay() === 0 ? 6 : date.getDay() - 1; // Convert Sunday (0) to 6 and other days to 0-5
                pm25Data[dayIndex] += row.pm25;
                pm10Data[dayIndex] += row.pm10;
                dayCount[dayIndex] += 1;

                if (row.pm25 > 25 || row.pm10 > 50) {
                    showWarning = true;
                }

                let tr = `<tr>
                            <td>${row.pm25}</td>
                            <td>${row.pm10}</td>
                            <td>${row.kelembaban}</td>
                            <td>${row.suhu}</td>
                        </tr>`;
                tableBody.innerHTML += tr;
            });

            // Calculate average for each day
            for (let i = 0; i < 7; i++) {
                if (dayCount[i] > 0) {
                    pm25Data[i] /= dayCount[i];
                    pm10Data[i] /= dayCount[i];
                }
            }

            if (showWarning) {
                warningDiv.style.display = "block";
            } else {
                warningDiv.style.display = "none";
            }

            updateChart(labels, pm25Data, pm10Data);
        })
        .catch(error => console.error("Error Fetch Data:", error));
}

function updateChart(labels, pm25Data, pm10Data) {
    const ctx = document.getElementById('pmChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', // Set the chart type to bar
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'PM2.5 (µg/m³)',
                    data: pm25Data,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'PM10 (µg/m³)',
                    data: pm10Data,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Hari'
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Konsentrasi (µg/m³)'
                    },
                    grid: {
                        color: 'rgba(75, 192, 192, 0.2)'
                    }
                }
            },
            plugins: {
                annotation: {
                    annotations: {
                        line1: {
                            type: 'line',
                            yMin: 25,
                            yMax: 25,
                            borderColor: 'red',
                            borderWidth: 2,
                            label: {
                                content: 'Batas PM2.5',
                                enabled: true,
                                position: 'end'
                            }
                        },
                        line2: {
                            type: 'line',
                            yMin: 50,
                            yMax: 50,
                            borderColor: 'red',
                            borderWidth: 2,
                            label: {
                                content: 'Batas PM10',
                                enabled: true,
                                position: 'end'
                            }
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    mode: 'index',
                    intersect: false
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
}


// Panggil fetchData saat halaman dimuat
document.addEventListener("DOMContentLoaded", fetchData);
</script>

<div class="rekomendasi">
            <h2>Rekomendasi Kesehatan</h2>
            <ul>
                <li>Gunakan masker saat berada di luar ruangan.</li>
                <li>Gunakan alat pembersih udara di dalam rumah.</li>
                <li>Kurangi aktivitas luar rumah saat polusi tinggi.</li>
                <li>Gunakan ventilasi udara yang baik di rumah dan kantor.</li>
            </ul>
            <p>Ikuti rekomendasi ini untuk mengurangi risiko kesehatan akibat polusi udara.</p>
        </div>

<!-- Peta Lokasi -->
<div class="peta">
            <h2>Peta Lokasi</h2>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.387896924211!2d119.44001531477372!3d-5.139011253100313!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbf1d7999999999%3A0x9999999999999999!2sJl.%20Andi%20Pangeran%20Pettarani!5e0!3m2!1sen!2sid!4v1618300000000" 
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>
    <footer>
        <p>© Universitas Negeri Makassar 2025 | Fakultas Teknik</p>
    </footer>
</body>
</html>