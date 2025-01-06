function updateStats() {
    fetch('/api/stats.php')
        .then(response => response.json())
        .then(stats => {
            document.getElementById('total-clients').textContent = stats.total_clients;
            document.getElementById('total-reservations').textContent = stats.total_reservations;
            // etc...
        });
}

// Mettre à jour toutes les 5 minutes
setInterval(updateStats, 300000);
