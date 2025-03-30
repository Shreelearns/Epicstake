document.addEventListener("DOMContentLoaded", function () {
    console.log("JavaScript Loaded!");

    // Signup Form Submission
    document.getElementById("signupForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append("action", "signup");
        formData.append("email", document.getElementById("signupEmail").value);
        formData.append("password", document.getElementById("signupPassword").value);

        fetch("login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Signup Response:", data);
            document.getElementById("signupMessage").innerText = data.message;
        })
        .catch(error => console.error("Signup Error:", error));
    });

    // Login Form Submission
    document.getElementById("loginForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append("action", "login");
        formData.append("email", document.getElementById("loginEmail").value);
        formData.append("password", document.getElementById("loginPassword").value);

        fetch("login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Login Response:", data);
            document.getElementById("loginMessage").innerText = data.message;
            if (data.status === "success") {
                setTimeout(() => window.location.href = "index.html", 2000);
            }
        })
        .catch(error => console.error("Login Error:", error));
    });
});
