import Chart from "chart.js/auto";

export function initDashboardCharts() {
    const issuanceEl = document.getElementById("issuanceChart");
    const categoryEl = document.getElementById("categoryChart");

    // Exit early if elements are missing
    if (!issuanceEl && !categoryEl) return;

    // --- Visual performance hint for the browser ---
    [issuanceEl, categoryEl].forEach((el) => {
        if (el) {
            el.style.willChange = "transform";
            el.style.transform = "translateZ(0)";
        }
    });

    // --- Chart.js global defaults for performance ---
    Chart.defaults.animation.duration = 0;
    Chart.defaults.responsiveAnimationDuration = 0;
    Chart.defaults.transitions.active.animation.duration = 0;
    Chart.defaults.hover.animationDuration = 0;
    Chart.defaults.plugins.tooltip.animation = false;
    Chart.defaults.devicePixelRatio = 1;

    // --- Helper: destroy chart if it exists ---
    const destroyIfExists = (el) => {
        if (el?.chartInstance) {
            el.chartInstance.destroy();
            el.chartInstance = null;
        }
    };

    // --- Helper: create chart safely ---
    const createChart = (el, config) => {
        destroyIfExists(el);
        const chart = new Chart(el, config);
        el.chartInstance = chart;
        return chart;
    };

    // --- Fetch data from backend ---
    fetch("/admin/dashboard/data")
        .then((response) => {
            if (!response.ok)
                throw new Error(`Failed to fetch chart data (HTTP ${response.status})`);
            return response.json();
        })
        .then((data) => {
            const issuanceTrends = data.issuanceTrends || { labels: [], data: [] };
            const categoryDistribution = data.categoryDistribution || {
                labels: [],
                data: [],
            };

            // ---------------------------
            // 📈 Issuance Line Chart
            // ---------------------------
            if (issuanceEl) {
                createChart(issuanceEl, {
                    type: "line",
                    data: {
                        labels: issuanceTrends.labels,
                        datasets: [
                            {
                                label: "Issued Firearms",
                                data: issuanceTrends.data,
                                borderColor: "#2563eb",
                                backgroundColor: "rgba(37,99,235,0.15)",
                                fill: true,
                                tension: 0.35,
                                borderWidth: 2,
                                pointRadius: 3,
                                pointHoverRadius: 4,
                                pointBackgroundColor: "#2563eb",
                                pointHoverBackgroundColor: "#1d4ed8",
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        resizeDelay: 200,
                        animation: false,
                        interaction: {
                            mode: "nearest",
                            axis: "x",
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 6,
                                },
                            },
                            tooltip: {
                                backgroundColor: "#1e293b",
                                titleColor: "#fff",
                                bodyColor: "#e2e8f0",
                                borderColor: "#3b82f6",
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false,
                                animation: false,
                            },
                        },
                        hover: {
                            mode: "nearest",
                            intersect: false,
                            animationDuration: 0,
                        },
                        elements: {
                            point: { hoverRadius: 5 },
                        },
                        layout: { padding: 10 },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: "#475569" },
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: "rgba(0,0,0,0.05)" },
                                ticks: { color: "#475569" },
                            },
                        },
                    },
                });
            }

            // ---------------------------
            // 📊 Category Bar Chart
            // ---------------------------
            if (categoryEl) {
                createChart(categoryEl, {
                    type: "bar",
                    data: {
                        labels: categoryDistribution.labels,
                        datasets: [
                            {
                                label: "Category Count",
                                data: categoryDistribution.data,
                                backgroundColor: [
                                    "#3b82f6",
                                    "#f59e0b",
                                    "#10b981",
                                    "#ef4444",
                                    "#8b5cf6",
                                ],
                                borderRadius: 6,
                                borderWidth: 1,
                                borderColor: "rgba(0,0,0,0.1)",
                                hoverBackgroundColor: "rgba(37,99,235,0.8)",
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        hover: { animationDuration: 0 },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: "#1e293b",
                                titleColor: "#fff",
                                bodyColor: "#e2e8f0",
                                borderColor: "#3b82f6",
                                borderWidth: 1,
                                padding: 10,
                                displayColors: true,
                                animation: false,
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: "#475569" },
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: "rgba(0,0,0,0.05)" },
                                ticks: { color: "#475569", stepSize: 1 },
                            },
                        },
                    },
                });
            }
        })
        .catch((err) => {
            console.error("Chart load error:", err);
            // Optional: show user-friendly message
            const container = document.querySelector("#dashboard-charts");
            if (container) {
                container.innerHTML =
                    '<div class="text-red-500 text-center mt-4">⚠️ Unable to load charts.</div>';
            }
        });
}
