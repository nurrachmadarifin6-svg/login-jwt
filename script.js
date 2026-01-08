const API = "";
let token = "";

// REGISTER
function register() {
  fetch(API + "register.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      username: ruser.value,
      password: rpass.value
    })
  })
  .then(r => r.json())
  .then(d => alert(d.status))
  .catch(err => alert("Error register: " + err));
}

// LOGIN
function login() {
  fetch(API + "login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      username: luser.value,
      password: lpass.value
    })
  })
  .then(r => r.json())
  .then(d => {
    if(d.token) {
      token = d.token;
      alert("Login sukses");
    } else {
      alert(d.status);
    }
  })
  .catch(err => alert("Error login: " + err));
}

// PROTECTED
function cek() {
  if(!token) { 
    alert("Silakan login dulu!"); 
    return; 
  }

  fetch(API + "protected.php", {
    headers: { Authorization: "Bearer " + token }
  })
  .then(r => r.json())
  .then(d => alert(JSON.stringify(d)))
  .catch(err => alert("Error cek token: " + err));
}
