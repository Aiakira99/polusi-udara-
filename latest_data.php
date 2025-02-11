<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Terbaru - Monitoring Polusi Udara UNM</title>
    <link rel="stylesheet" href="latest_data.css">
</head>

<body>
<header>
    <h1>Data Terbaru - Monitoring Polusi Udara</h1>
    <a href="index.php" class="back-link">Kembali ke Dashboard</a>
</header>

<div class="data-box">
    <h2>Polusi yang Terdeteksi</h2>
    <table class="container">
        <thead>
            <tr>
                <th>MQ-2 (Gas Asap)</th>
                <th>MQ-7 (Karbon Monoksida)</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody id="dataTable"></tbody>
    </table>
</div>

<script>
let allData = [];

function fetchData() {
    fetch("data.php")
        .then(response => response.json())
        .then(data => {
            allData = data;
            showLatestData();
        })
        .catch(error => console.error("Error Fetch Data:", error));
}

function showLatestData() {
    const tableBody = document.getElementById("dataTable");
    tableBody.innerHTML = ""; // Kosongkan tabel sebelum update data baru

    const today = new Date().toLocaleDateString('id-ID');

    allData.forEach(row => {
        let rowDate = new Date(row.waktu).toLocaleDateString('id-ID');
        if (rowDate === today) {
            let tr = `<tr>
                        <td>${row.mq2}</td>
                        <td>${row.mq7}</td>
                        <td>${new Date(row.waktu).toLocaleTimeString('id-ID')}</td>
                    </tr>`;
            tableBody.innerHTML += tr;
        }
    });
}

document.addEventListener("DOMContentLoaded", fetchData);
</script>
</body>
</html>