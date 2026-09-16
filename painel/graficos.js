// CONFIGURAÇÃO DO CHART.JS DO DASHBOARD
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('graficoLeituraTurmas');
    if (!ctx) return; 

    // Define cor destacada para a maior pontuação
    const maxVal = Math.max(...dadosValores);
    const backgroundColors = dadosValores.map(v => (v === maxVal && v > 0) ? '#df8508' : '#1c942f');

    new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: dadosLabels, 
            datasets: [{
                label: 'Empréstimos no Mês',
                data: dadosValores, 
                backgroundColor: backgroundColors,
                borderRadius: 6, // Arredonda o topo das barras
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, 
            plugins: {
                legend: {
                    display: false 
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` ${context.raw} livro(s) emprestado(s)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true, 
                    ticks: {
                        stepSize: 1, 
                        color: '#3b3c3f'
                    },
                    grid: {
                        color: '#f1f3f5' 
                    }
                },
                x: {
                    ticks: {
                        color: '#393b3e',
                        maxRotation: 45,
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