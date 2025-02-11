<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data per Tahun - Monitoring Polusi Udara UNM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Lihat Data per Tahun - Monitoring Polusi Udara</h1>
    <a href="index.php" class="back-link">Kembali ke Dashboard</a>
</header>

<div class="data-box">
    <h2>Pilih Tahun</h2>
    <form id="yearForm">
        <select id="yearSelect">
            <?php
            $currentYear = date('Y');
            for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                echo "<option value='$year'>$year</option>";
            }
            ?>
        </select>
        <button type="button" onclick="fetchDataByYear()">Lihat Data</button>
    </form>

    <div id="dataContainer"></div>
</div>

<script>
function fetchDataByYear() {
    const year = document.getElementById('yearSelect').value;
    fetch(`data_by_year.php?year=${year}`)
        .then(response => response.json())
        .then(data => {
            const dataContainer = document.getElementById('dataContainer');
            dataContainer.innerHTML = '';

            if (data.length > 0) {
                let table = '<table class="container"><thead><tr><th>MQ-2 (Gas Asap)</th><th>MQ-7 (Karbon Monoksida)</th><th>Waktu</th></tr></thead><tbody>';
                data.forEach(row => {
                    table += `<tr><td>${row.mq2}</td><td>${row.mq7}</td><td>${new Date(row.waktu).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}</td></tr>`;
                });
                table += '</tbody></table>';
                dataContainer.innerHTML = table;
            } else {
                dataContainer.innerHTML = 'Tidak ada data untuk tahun ini.';
            }
        })
        .catch(error => console.error('Error Fetch Data:', error));
}
</script>
</body>
</html>