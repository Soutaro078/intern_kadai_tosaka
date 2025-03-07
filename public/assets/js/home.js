function HomeViewModel() {
    var self = this;

    self.lists = ko.observableArray([]);
    self.tasks = ko.observableArray([]);
    self.errorMessage = ko.observable("");

    // 🔹 認証チェック
    self.checkAuth = function() {
        var token = localStorage.getItem("authToken");
        if (!token) {
            alert("ログインしてください");
            window.location.href = "/signin";
        }
    };

    // 🔹 リスト一覧の取得
    self.fetchLists = function() {
        fetch("/api/lists", {
            method: "GET",
            headers: { "Authorization": "Bearer " + localStorage.getItem("authToken") }
        })
        .then(response => response.json())
        .then(data => {
            self.lists(data.lists);
        })
        .catch(error => {
            self.errorMessage("リストの取得に失敗しました: " + error);
        });
    };

    // 🔹 リスト選択時の処理
    self.selectList = function(list) {
        fetch("/api/lists/" + list.id + "/tasks", {
            method: "GET",
            headers: { "Authorization": "Bearer " + localStorage.getItem("authToken") }
        })
        .then(response => response.json())
        .then(data => {
            self.tasks(data.tasks);
        })
        .catch(error => {
            self.errorMessage("タスクの取得に失敗しました: " + error);
        });
    };

    // 🔹 タスク削除
    self.deleteTask = function(task) {
        fetch("/api/tasks/" + task.id, {
            method: "DELETE",
            headers: { "Authorization": "Bearer " + localStorage.getItem("authToken") }
        })
        .then(response => response.json())
        .then(() => {
            self.tasks.remove(task);
        })
        .catch(error => {
            self.errorMessage("タスクの削除に失敗しました: " + error);
        });
    };

    // 🔹 サインアウト処理
    self.signout = function() {
        fetch("/api/signout", { method: "POST" })
        .then(response => response.json())
        .then(() => {
            localStorage.removeItem("authToken");
            alert("ログアウトしました");
            window.location.href = "/signin";
        })
        .catch(error => {
            self.errorMessage("サインアウトに失敗しました: " + error);
        });
    };

    // 初期化
    self.checkAuth();
    self.fetchLists();
}

// Knockout.js の適用
ko.applyBindings(new HomeViewModel());
