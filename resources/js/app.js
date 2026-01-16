import Chart from 'chart.js/auto';
import annotationPlugin from 'chartjs-plugin-annotation';

import colors from 'tailwindcss/colors';

Chart.register(annotationPlugin);
window.Chart = Chart;
window.twColors = colors;

function hexToRgba(hex, alpha) {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);

    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

window.hexToRgba = hexToRgba;
