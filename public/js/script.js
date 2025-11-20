// ========================================================
// SIDEBAR TOGGLE FOR MOBILE & UI
// ========================================================
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const sidebar = document.querySelector('.icon-sidebar');
    
    if (mobileSidebarToggle) {
        mobileSidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
        });
    }
    
    // Smooth scrolling for sidebar navigation
    document.querySelectorAll('.icon-sidebar .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId.startsWith('#')) {
                e.preventDefault();
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const offsetTop = targetElement.offsetTop - 100;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                    
                    // Update active state
                    document.querySelectorAll('.icon-sidebar .nav-link').forEach(item => {
                        item.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            }
        });
    });
    
    // Update active state on scroll
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('div[id]');
        const scrollPos = window.scrollY + 150;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                document.querySelectorAll('.icon-sidebar .nav-link').forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${sectionId}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    });
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize charts
    initCharts();
});

// ========================================================
// CHART INITIALIZATION
// ========================================================
function initCharts() {
    
    // 1. KPI Achievement (Doughnut)
    const kpiAchieveCtx = document.getElementById('kpiAchievementChart');
    if (kpiAchieveCtx && typeof kpiAchievement !== 'undefined') {
        new Chart(kpiAchieveCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [kpiAchievement, 100 - kpiAchievement],
                    backgroundColor: ['#198754', '#E9ECEF'],
                    borderWidth: 0,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    tooltip: { enabled: false }
                }
            }
        });
    }

    // 2. KPI Total SOW (Doughnut)
    const kpiTotalSowCtx = document.getElementById('kpiTotalSowChart');
    if (kpiTotalSowCtx && typeof kpiSowLabels !== 'undefined' && kpiSowLabels.length > 0) {
        new Chart(kpiTotalSowCtx, {
            type: 'doughnut',
            data: {
                labels: kpiSowLabels,
                datasets: [{
                    data: kpiSowData,
                    backgroundColor: ['#0D6EFD', '#6C757D', '#198754', '#FFC107', '#DC3545'],
                    borderWidth: 0,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    // 3. Partner Performance (Bar)
    const partnerCtx = document.getElementById('partnerPerformanceChart');
    if (partnerCtx && typeof partnerLabels !== 'undefined' && partnerLabels.length > 0) {
        new Chart(partnerCtx, {
            type: 'bar',
            data: {
                labels: partnerLabels,
                datasets: [
                    {
                        label: 'Done',
                        data: partnerDataDone,
                        backgroundColor: '#198754',
                    },
                    {
                        label: 'Not Done',
                        data: partnerDataNotDone,
                        backgroundColor: '#FFC107',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    // 4. City Performance (Bar)
    const cityCtx = document.getElementById('cityPerformanceChart');
    if (cityCtx && typeof cityLabels !== 'undefined' && cityLabels.length > 0) {
        new Chart(cityCtx, {
            type: 'bar',
            data: {
                labels: cityLabels,
                datasets: [
                    {
                        label: 'Done',
                        data: cityDataDone,
                        backgroundColor: '#198754',
                    },
                    {
                        label: 'Not Done',
                        data: cityDataNotDone,
                        backgroundColor: '#FFC107',
                    }
                ]
            },
            options: {
                indexAxis: 'y', // Membuatnya jadi horizontal bar chart
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        beginAtZero: true,
                        grid: { display: false }
                    },
                    y: {
                        stacked: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }
    
    // 5. Program Distribution (Pie)
    const programCtx = document.getElementById('programDistributionChart');
    if (programCtx && typeof kpiSowLabels !== 'undefined' && kpiSowLabels.length > 0) {
        new Chart(programCtx, {
            type: 'pie',
            data: {
                labels: kpiSowLabels,
                datasets: [{
                    label: 'Total SOW',
                    data: kpiSowData,
                    backgroundColor: [
                        '#E60000', // Telkomsel Red
                        '#212529', // Dark Grey
                        '#FFC107', // Yellow
                        '#0D6EFD', // Blue
                        '#198754'  // Green
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
    
    // 6. NDP Base Chart (Dummy Data)
    const ndpCtx = document.getElementById('ndpBaseChart');
    if (ndpCtx) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const ndpData = [120, 150, 180, 200, 220, 250, 280, 300, 320, 350, 380, 400];
        
        new Chart(ndpCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'NDP Base',
                    data: ndpData,
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderColor: '#0D6EFD',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: true }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // 7. NDP Base Detailed Chart (Dummy)
    const ndpDetailedCtx = document.getElementById('ndpBaseDetailedChart');
    if (ndpDetailedCtx) {
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var plannedData = [100, 120, 140, 160, 180, 200, 220, 240, 260, 280, 300, 320];
        var actualData = [120, 150, 180, 200, 220, 250, 280, 300, 320, 350, 380, 400];
        
        new Chart(ndpDetailedCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Planned',
                        data: plannedData,
                        backgroundColor: 'rgba(108, 117, 125, 0.7)',
                    },
                    {
                        label: 'Actual',
                        data: actualData,
                        backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Sites'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    // 8. NOP Base Performance (Chart BARU dari data PHP)
    const nopCtx = document.getElementById('nopBaseChart');
    if (nopCtx && typeof nopLabels !== 'undefined' && nopLabels.length > 0) {
        new Chart(nopCtx, {
            type: 'bar',
            data: {
                labels: nopLabels, // Data dari PHP
                datasets: [
                    {
                        label: 'Done',
                        data: nopDataDone, // Data dari PHP
                        backgroundColor: '#198754', // Hijau
                    },
                    {
                        label: 'Not Done',
                        data: nopDataNotDone, // Data dari PHP
                        backgroundColor: '#FFC107', // Kuning
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

} // Ini adalah penutup dari fungsi initCharts()

