// login.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-app.js";
import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-auth.js";

const firebaseConfig = {
    apiKey: "AIzaSyAWHhYKS8OXj_-x5MGoSae04qs9RNaEoKY",
    authDomain: "testing-ceaad.firebaseapp.com",
    projectId: "testing-ceaad",
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

// Handle login button click
document.getElementById('login-btn').addEventListener('click', async (e) => {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
        // 1. Sign in to Firebase
        const userCredential = await signInWithEmailAndPassword(auth, email, password);
        const idToken = await userCredential.user.getIdToken();

        // 2. Send token to PHP for verification
        const res = await fetch('verify-login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ idToken })
        });

        const text = await res.text();
        console.log("Raw response:", text);

        const data = JSON.parse(text);

        if (data.success) {
            alert('Login verified!');
            window.location.href = 'dashboard.php';  // Redirect on success
        } else {
            alert('Login failed: ' + data.error);
        }

    } catch (err) {
        alert('Error: ' + err.message);
    }
});
