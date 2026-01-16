import Chart from 'chart.js/auto';
import annotationPlugin from 'chartjs-plugin-annotation';

import colors from 'tailwindcss/colors';

Chart.register(annotationPlugin);
window.Chart = Chart;
window.twColors = colors;
