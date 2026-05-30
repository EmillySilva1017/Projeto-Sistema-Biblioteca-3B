// CONFIGURAÇÃO DO CHART.JS DO DASHBOARD
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('graficoLeituraTurmas');
    if (!ctx) return; // Proteção caso o canvas suma da tela

    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: dadosLabels, 
            datasets: [{
                label: 'Quantidade de Empréstimos',
                data: dadosValores, 
                borderColor: '#1c942f', 
                backgroundColor: 'rgba(28, 148, 47, 0.08)', 
                borderWidth: 3,
                tension: 0.25, 
                pointBackgroundColor: '#df8508', 
                pointBorderColor: '#ffffff',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, 
            plugins: {
                legend: {
                    display: false 
                }
            },
            scales: {
                y: {
                    beginAtZero: true, 
                    ticks: {
                        stepSize: 1, 
                        color: '#8492a6'
                    },
                    grid: {
                        color: '#f1f3f5' 
                    }
                },
                x: {
                    ticks: {
                        color: '#8492a6',
                        maxRotation: 45, // Evita textos atropelados no celular inclinando-os se preciso
                        minRotation: 0
                    },
                    grid: {
                        display: false 
                    }
                }
            }
        }
    });
});