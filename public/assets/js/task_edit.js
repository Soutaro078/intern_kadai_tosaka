function TaskEditViewModel() {
    var self = this;

    self.taskId = ko.observable();
    self.taskTitle = ko.observable("");
    self.taskLimit = ko.observable("");
    self.taskStatus = ko.observable("未完了");

    // 🔹 URL パラメータから `task_id` を取得
    self.getTaskIdFromURL = function() {
        const params = new URLSearchParams(window.location.search);
        return params.get("id");
    };

    // 🔹 認証チェック
    self.checkAuth = function() {
        var token = localStorage.getItem("authToken");
        if (!token) {
            alert("ログインしてください");
            window.location.href = "signin.html";
        }
    };

    // 🔹 タスク情報の取得
    self.fetchTask = function() {
        const taskId = self.getTaskIdFromURL();
        if (!taskId) {
            alert("タスクIDが指定されていません");
            window.location.href = "home.html";
            return;
        }
        self.taskId(taskId);

        fetch("/api/tasks/" + taskId, {
            method: "GET",
            headers: { "Authorization": "Bearer " + localStorage.getItem("authToken") }
        })
        .then(response => response.json())
        .then(data => {
            self.taskTitle(data.title);
            self.taskLimit(data.limit);
            self.taskStatus(data.status);
        })
        .catch(error => console.error("タスク取得失敗:", error));
    };

    // 🔹 タスクの更新
    self.updateTask = function() {
        fetch("/api/tasks/" + self.taskId(), {
            method: "PUT",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + localStorage.getItem("authToken")
            },
            body: new URLSearchParams({
                title: self.taskTitle(),
                limit: self.taskLimit(),
                status: self.taskStatus()
            })
        })
        .then(response => response.json())
        .then(() => {
            alert("タスクを更新しました！");
            window.location.href = "home.html";
        })
        .catch(error => console.error("タスク更新失敗:", error));
    };

    // 初期化
    self.checkAuth();
    self.fetchTask();
}

// Knockout.js の適用
ko.applyBindings(new TaskEditViewModel());
