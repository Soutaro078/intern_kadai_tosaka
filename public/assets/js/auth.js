function AuthViewModel() {
    var self = this;

    self.username = ko.observable("");
    self.password = ko.observable("");
    self.email = ko.observable("");  // signup用

    // 🔹 サインイン処理 (usernameを使用)
    self.login = function() {

        console.log("ログインボタンが押されました"); // ← デバッグログ追加

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
                alert("ログイン成功したよ！");
                window.location.href = "home.php"; // ホームへリダイレクト
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
        .then(response => {
            console.log("API レスポンス受信", response);
            return response.json();
        })    
        .then(data => {
            if (data.message === "User created") {
                alert("アカウント作成成功！サインインしてください");
                window.location.href = "signin.php"; // サインインページへリダイレクト
            } else {
                alert("アカウント作成失敗だよーん: " + data.error);
                // alert("アカウント作成失敗");
            }
        })
        .catch(error => console.error("Error:", error));
    };
}

// Knockout.js のバインディング適用
ko.applyBindings(new AuthViewModel());
