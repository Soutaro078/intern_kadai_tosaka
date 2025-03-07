function TaskCreateViewModel() {
    var self = this;

    self.lists = ko.observableArray([]);
    self.selectedList = ko.observable();
    self.taskTitle = ko.observable("");
    self.taskLimit = ko.observable("");

    // 🔹 認証チェック
    self.checkAuth = function() {
        var token = localStorage.getItem("authToken");
        if (!token) {
            alert("ログインしてください");
            window.location.href = "signin.html";
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
            if (data.lists.length > 0) {
                self.selectedList(data.lists[0].id);
            }
        })
        .catch(error => console.error("リストの取得に失敗:", error));
    };

    // 🔹 タスクの作成
    self.createTask = function() {
        fetch("/api/tasks", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + localStorage.getItem("authToken")
            },
            body: new URLSearchParams({
                list_id: self.selectedList(),
                title: self.taskTitle(),
                limit: self.taskLimit()
            })
        })
        .then(response => response.json())
        .then(() => {
            alert("タスクを作成しました！");
            window.location.href = "home.html";
        })
        .catch(error => console.error("タスク作成失敗:", error));
    };

    // 初期化
    self.checkAuth();
    self.fetchLists();
}

// Knockout.js の適用
ko.applyBindings(new TaskCreateViewModel());
