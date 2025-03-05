function AuthViewModel() {
    var self = this;

    self.username = ko.observable("");
    self.password = ko.observable("");
    self.email = ko.observable("");  // signup用

    // 🔹 サインイン処理 (usernameを使用)
    self.login = function() {
        fetch("/api/signin", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                username: self.username(),
                password: self.password()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === "Login successful") {
                alert("ログイン成功！");
                window.location.href = "home.html"; // ホームへリダイレクト
            } else {
                alert("ログイン失敗: " + data.error);
            }
        })
        .catch(error => console.error("Error:", error));
    };

    // 🔹 新規登録処理 (username, email, password)
    self.signup = function() {
        fetch("/api/signup", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                username: self.username(),
                email: self.email(),
                password: self.password()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === "User created") {
                alert("アカウント作成成功！サインインしてください");
                window.location.href = "signin.html";
            } else {
                alert("アカウント作成失敗: " + data.error);
            }
        })
        .catch(error => console.error("Error:", error));
    };
}

// Knockout.js のバインディング適用
ko.applyBindings(new AuthViewModel());
