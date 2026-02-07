const API = "php/";

function register(){
    $.ajax({
        url: API + "register.php",
        type: "POST",
        dataType: "json",
        data: {
            name: $("#name").val(),
            email: $("#email").val(),
            password: $("#password").val()
        },
        success: function(res){
            if (res.status) {
                alert(res.status);
                window.location.href = "index.html";
            } else {
                alert(res.error || "Registration failed");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX error:", status, error);
            alert("Error: " + (xhr.responseText || error));
        }
    });
}

function login() {
    $.ajax({
        url: API + "login.php",
        type: "POST",
        dataType: "json",
        data: {
            email: $("#email").val(),
            password: $("#password").val()
        },
        success: function (res) {

            if (res.token) {
                localStorage.setItem("token", res.token);
                window.location.href = "profile.html";
            } else {
                alert(res.error || res.message || "Invalid login");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX error:", status, error);
        }
    });
}


function updateProfile(){
    $.ajax({
        url: API + "update.php",
        type: "POST",
        dataType: "json",
        data: {
            token: localStorage.getItem("token"),
            age: $("#age").val(),
            dob: $("#dob").val(),
            contact: $("#contact").val()
        },
        success: function(res){
            if (res.status) {
                alert(res.status);
            } else {
                alert(res.error || "Update failed");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX error:", status, error);
            console.log("Server response:", xhr.responseText);
            alert("Error: " + (xhr.responseText || error));
        }
    });
}

function logout(){
    localStorage.removeItem("token");
    window.location.href = "index.html";
}