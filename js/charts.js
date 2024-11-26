const primary = "#004c99";
const secondary = "#3dc3ff";
const tertiary = "#1c98ed";

fetch('api/data.php')
.then(response => response.json())
.then(data => {
    console.log(data);
    
    const orderData = [
        { order_id: "ORD001", total_amount: 500, status: "Completed" },
        { order_id: "ORD002", total_amount: 750, status: "Completed" },
        { order_id: "ORD003", total_amount: 200, status: "Pending" },
        { order_id: "ORD004", total_amount: 1200, status: "Completed" },
        { order_id: "ORD005", total_amount: 450, status: "Pending" },
        { order_id: "ORD006", total_amount: 800, status: "Completed" },
        { order_id: "ORD007", total_amount: 950, status: "Completed" },
        { order_id: "ORD008", total_amount: 600, status: "Pending" },
        { order_id: "ORD009", total_amount: 1100, status: "Completed" },
        { order_id: "ORD010", total_amount: 500, status: "Pending" }
    ];

    const barChartData = {
        labels: data.user_order_count.map(order => order.username),
        datasets: [{
            data: data.user_order_count.map(order => order.order_count),
            backgroundColor: [secondary, primary],
            borderColor: primary,
            borderWidth: 1
        }]
    };

    const lineChartData = {
        labels: data.orders_created_at.map(order => order.created_at),
        datasets: [{
            label: 'Order Amount',
            data: data.orders_created_at.map(order => order.created_at_count),
            backgroundColor: primary,
            borderColor: primary,
            fill: false,
            tension: 0.1
        }]
    };

    const pieChartData = {
        labels: ['Ordered', 'In Stock'],
        datasets: [{
            data: [
                data.order_count[0].order_count + data.order_count[1].order_count,
                data.order_count[0].quantity + data.order_count[1].quantity
            ],
            backgroundColor: [secondary, primary],
            borderColor: primary,
            borderWidth: 1
        }]
    };

    const barChart = new Chart(document.getElementById('ordersBarChart'), {
        type: 'bar',
        data: barChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: false
            },
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    const lineChart = new Chart(document.getElementById('ordersLineChart'), {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    const pieChart = new Chart(document.getElementById('ordersPieChart'), {
        type: 'pie',
        data: pieChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
});