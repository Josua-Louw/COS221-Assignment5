document.addEventListener("DOMContentLoaded", function() {
    const apikey = sessionStorage.getItem('apikey');

    if (!apikey) {
        console.warn('No API key found in sessionStorage.');
        return;
    }

    let labels = [];
    let data_graph = [];
    let dateRegistered;

    retrieveClicks(apikey).then(data => {
        console.log(data);
        if (data.status === 'success') {
            const clicks = data.data.clicksData;
            clicks.forEach(entry => {
                labels.push(entry.category);
                data_graph.push(entry.total_clicks);
            });
            dateRegistered = data.data.date;
        } else {
            console.warn('API error:', data.message);
        }
    });

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Categories',
                data: data_graph,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderWidth: 0
            }]
        }
    })

    let theme = localStorage.getItem('theme');
    let min_price = localStorage.getItem('min_price');
    let max_price = localStorage.getItem('max_price');
    let user_name = localStorage.getItem('name');

    const greetingElement = document.getElementById("greeting_dash");
    greetingElement.innerHTML = `Welcome ${user_name} to CompareIt`;

    const registerDate = document.getElementById("register_date");
    registerDate.innerHTML = `User since: ${formatTimestamp(dateRegistered)}.`;

    const registeredTime = document.getElementById("registered_time");
    registeredTime.innerHTML = `You have been a user for ${timeSince(dateRegistered)}.`;


})

function retrieveClicks(apikey){
    const body = {
        type: 'GetStats',
        apikey: apikey
    }
    return sendRequest(body);
}

function timeSince(timestamp) {
    const seconds = Math.floor(Date.now() / 1000 - timestamp);

    const years = Math.floor(seconds / (365 * 24 * 60 * 60));
    const months = Math.floor((seconds % (365 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));
    const days = Math.floor((seconds % (30 * 24 * 60 * 60)) / (24 * 60 * 60));
    const hours = Math.floor((seconds % (24 * 60 * 60)) / (60 * 60));
    const minutes = Math.floor((seconds % (60 * 60)) / 60);
    const secs = seconds % 60;

    if (years > 0) return `${years} year${years > 1 ? 's' : ''}, ${months} month${months !== 1 ? 's' : ''}`;
    if (months > 0) return `${months} month${months !== 1 ? 's' : ''}, ${days} day${days !== 1 ? 's' : ''}`;
    if (days > 0) return `${days} day${days !== 1 ? 's' : ''}, ${hours} hour${hours !== 1 ? 's' : ''}`;
    if (hours > 0) return `${hours} hour${hours !== 1 ? 's' : ''}, ${minutes} minute${minutes !== 1 ? 's' : ''}`;
    if (minutes > 0) return `${minutes} minute${minutes !== 1 ? 's' : ''}`;
    return `${secs} second${secs !== 1 ? 's' : ''}`;
}

function formatTimestamp(timestamp) {
    const date = new Date(timestamp * 1000); // Convert seconds to milliseconds

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
    const day = String(date.getDate()).padStart(2, '0');

    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${year}/${month}/${day} ${hours}:${minutes}`;
}