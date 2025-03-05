function HomeViewModel() {
    var self = this;

    // 🔹 認証チェック (未ログインなら `signin.html` へリダイレクト)
    self.checkAuth = function() {
        var token = localStorage.getItem("authToken");
        if (!token) {
            alert("ログインしてください");
            window.location.href = "signin.html"; // 未ログインならサインインページへ
        }
    };

    // 🔹 サインアウト処理
    self.signout = function() {
        fetch("/api/signout", {
            method: "POST"
        })
        .then(response => response.json())
        .then(data => {
            alert("ログアウトしました");
            localStorage.removeItem("authToken"); // トークン削除
            window.location.href = "signin.html"; // サインインページへ
        })
        .catch(error => console.error("Error:", error));
    };

    self.checkAuth(); // ページ読み込み時に認証チェック
}

// Knockout.js のバインディング適用
ko.applyBindings(new HomeViewModel());
