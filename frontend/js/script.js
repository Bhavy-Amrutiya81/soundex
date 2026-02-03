document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username == 'admin' && password == 'admin') {
        document.getElementById('login-container').style.display = 'none';
        document.getElementById('main-content').style.display = 'flex';
    } else {
        alert('Incorrect username or password');
    }
});

document.querySelector('.buy-btn').addEventListener('click', function() {
    alert('Thank you for purchasing a new speaker!');
});

document.querySelector('.repair-btn').addEventListener('click', function() {
    alert('You have selected live repair service!');
});

document.querySelector('.sell-btn').addEventListener('click', function() {
    alert('You can now sell your old device to us!');
});

document.querySelector('.internship-btn').addEventListener('click', function() {
    alert('You have applied for a free internship!');
});