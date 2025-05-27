document.addEventListener("DOMContentLoaded", function() {
    const apikey = sessionStorage.getItem('apikey');
    console.log("key: ",sessionStorage.getItem('apikey'));
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
            dateRegistered = data.data.user.date_registered;

            // Create chart here
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
            });
            // Update DOM with user info here
            const user_name = data.data.user.name;
            if ( user_name.includes(' '))
                document.getElementById("greeting_dash").innerHTML = `Welcome ${user_name} to CompareIt`;

            document.getElementById("register_date").innerHTML = `User since: ${dateRegistered}.`;
            document.getElementById("registered_time").innerHTML = `You have been a user for ${timeSince(dateRegistered)}.`;
        } else {
            console.warn('API error:', data.message);
        }
    });



})

function retrieveClicks(apikey){
    const body = {
        type: 'GetStats',
        apikey: apikey
    }
    return sendRequest(body);
}

function timeSince(timestampStr) {
    // Convert 'YYYY/MM/DD' string to a Date object
    const pastDate = new Date(timestampStr);
    const now = new Date();

    const seconds = Math.floor((now - pastDate) / 1000);

    const years = Math.floor(seconds / (365 * 24 * 60 * 60));
    const months = Math.floor((seconds % (365 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));
    const days = Math.floor((seconds % (30 * 24 * 60 * 60)) / (24 * 60 * 60));
    const hours = Math.floor((seconds % (24 * 60 * 60)) / (60 * 60));
    const minutes = Math.floor((seconds % (60 * 60)) / 60);
    const secs = seconds % 60;

    if (years > 0) return `${years} year${years !== 1 ? 's' : ''}, ${months} month${months !== 1 ? 's' : ''}`;
    if (months > 0) return `${months} month${months !== 1 ? 's' : ''}, ${days} day${days !== 1 ? 's' : ''}`;
    if (days > 0) return `${days} day${days !== 1 ? 's' : ''}, ${hours} hour${hours !== 1 ? 's' : ''}`;
    if (hours > 0) return `${hours} hour${hours !== 1 ? 's' : ''}, ${minutes} minute${minutes !== 1 ? 's' : ''}`;
}
