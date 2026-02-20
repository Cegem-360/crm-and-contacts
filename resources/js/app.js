import "./bootstrap";
import "../css/app.css";
import Chart from "chart.js/auto";
// Import logo for Vite to process
import.meta.glob("../images/**/*.{png,jpg,jpeg,gif,svg,webp}", { eager: true });

window.Chart = Chart;
