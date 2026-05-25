(() => {
    const initHighlight = () => {
        if (window.hljs) {
            window.hljs.highlightAll();
        }
    };

    const initMermaid = () => {
        if (window.mermaid) {
            window.mermaid.initialize({ startOnLoad: false, securityLevel: 'strict', theme: 'base' });
            window.mermaid.run({ querySelector: '.mermaid' }).catch(() => {});
        }
    };

    const initCharts = () => {
        const chartEl = document.getElementById('service-chart');
        if (!chartEl || !window.echarts) return;

        let rows = [];
        try {
            rows = JSON.parse(chartEl.dataset.serviceShare || '[]');
        } catch (error) {
            rows = [];
        }

        const chart = window.echarts.init(chartEl);
        chart.setOption({
            tooltip: { trigger: 'item' },
            legend: { bottom: 0, left: 'center' },
            series: [
                {
                    name: '相談比率',
                    type: 'pie',
                    radius: ['45%', '70%'],
                    center: ['50%', '44%'],
                    avoidLabelOverlap: true,
                    itemStyle: { borderRadius: 10, borderColor: '#fff', borderWidth: 3 },
                    label: { formatter: '{b}\n{d}%' },
                    data: rows.map((row) => ({ name: row.label, value: Number(row.percent) || 0 }))
                }
            ]
        });

        window.addEventListener('resize', () => chart.resize());
        document.body.addEventListener('htmx:afterSwap', () => setTimeout(() => chart.resize(), 120));
    };

    const closeMobileMenuOnLink = () => {
        document.querySelectorAll('.mk-offcanvas-link').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.UIkit) {
                    const menu = document.getElementById('mk-mobile-menu');
                    if (menu) window.UIkit.offcanvas(menu).hide();
                }
            });
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        initHighlight();
        initMermaid();
        initCharts();
        closeMobileMenuOnLink();
    });

    document.body.addEventListener('htmx:afterSwap', () => {
        initHighlight();
        initMermaid();
        initCharts();
    });
})();
