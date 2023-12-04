document.getElementById('user-input-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var userInput = document.getElementById('user-input').value;
    fetch('/api/process-input', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ input: userInput })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('result').innerText = data.result;
    })
    .catch(error => console.error('Error:', error));
});