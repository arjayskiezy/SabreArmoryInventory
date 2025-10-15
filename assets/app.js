import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import Alpine from 'alpinejs'
import dashboard from './js/dashboard'


window.Alpine = Alpine
Alpine.data('dashboard', dashboard)
Alpine.start()

import Chart from "chart.js/auto";
window.dashboardData = function () {
    return {
        charts: {
            issuance: null,
            category: null,
        },
        async loadData() {
            try {
                const res = await fetch("/admin/dashboard/data");
                const data = await res.json();
                this.initCharts(data);
            } catch (e) {
                console.error("Failed to load dashboard data:", e);
            }
        },
        initCharts(data) {
            const issuanceCtx = document.getElementById("issuanceChart");
            const categoryCtx = document.getElementById("categoryChart");

            if (!issuanceCtx || !categoryCtx) return;

            // Destroy existing charts to prevent duplicates
            if (this.charts.issuance) this.charts.issuance.destroy();
            if (this.charts.category) this.charts.category.destroy();

            // 🎨 Issuance Line Chart
            this.charts.issuance = new Chart(issuanceCtx, {
                type: "line",
                data: {
                    labels: data.issuanceTrends.labels,
                    datasets: [{
                        label: "Issuances",
                        data: data.issuanceTrends.data,
                        borderColor: "#2563eb", // Tailwind blue-600
                        backgroundColor: "rgba(37, 99, 235, 0.2)",
                        borderWidth: 2,
                        tension: 0.4, // smooth curve
                        fill: true,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1500,
                        easing: "easeOutQuart", // smooth bounce-out feel
                    },
                    scales: {
                        y: { beginAtZero: true },
                    },
                },
            });

            // 🍩 Category Doughnut Chart
            this.charts.category = new Chart(categoryCtx, {
                type: "doughnut",
                data: {
                    labels: data.categoryDistribution.labels,
                    datasets: [{
                        data: data.categoryDistribution.data,
                        backgroundColor: [
                            "#2563eb", // blue
                            "#10b981", // green
                            "#f59e0b", // amber
                        ],
                        hoverOffset: 12,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 1800,
                        easing: "easeOutElastic",
                    },
                    plugins: {
                        legend: {
                            position: "bottom",
                            labels: { boxWidth: 12 },
                        },
                    },
                },
            });
        },
    };
};

