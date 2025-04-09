// signup.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-auth.js";

const firebaseConfig = {
  apiKey: "AIzaSyAWHhYKS8OXj_-x5MGoSae04qs9RNaEoKY",
  authDomain: "testing-ceaad.firebaseapp.com",
  projectId: "testing-ceaad",
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

document.addEventListener("DOMContentLoaded", () => {
  const button = document.querySelector("button");

  document.getElementById('signup-btn').addEventListener('click', async (e) => {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const id = document.getElementById("id").value.trim();
    const fname = document.getElementById("fname").value.trim(); // Use fname input value
    const lname = document.getElementById("lname").value.trim();   // Use lname input value
    const number = document.getElementById("number").value.trim();
    const password = document.getElementById("password").value;
    const passwordConfirm = document.getElementById("password-confirm").value;
    const yearLevel = document.getElementById("year-level").value;
    const block = document.getElementById("block").value;

    if (password !== passwordConfirm) {
      alert("Passwords do not match!");
      return;
    }

    try {
      console.log("Creating user...");

      const userCredential = await createUserWithEmailAndPassword(auth, email, password);
      const idToken = await userCredential.user.getIdToken();

      // Use values from the form fields directly
      const firstName = document.getElementById("fname").value.trim();  // Get first name
      const lastName = document.getElementById("lname").value.trim();   // Get last name

      const res = await fetch("save-user.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          idToken,
          firstName,
          lastName,
          email,
          id,
          number,
          yearLevel,
          block,
        }),
      });

      const data = await res.json();

      if (data.success) {
        alert("Signup successful!");
        window.location.href = "login.html";
      } else {
        alert("Error: " + data.error);
      }
    } catch (err) {
      let message = "An error occurred during signup.";
    
      if (err.code) {
        switch (err.code) {
          case "auth/email-already-in-use":
            message = "This email is already registered.";
            break;
          case "auth/invalid-email":
            message = "Invalid email format.";
            break;
          case "auth/weak-password":
            message = "Password is too weak (minimum 6 characters).";
            break;
          default:
            message = err.message;
            break;
        }
    }
      alert("Signup failed: " + message);
    }
  })
})
