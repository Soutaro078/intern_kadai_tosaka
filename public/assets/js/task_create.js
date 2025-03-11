function TaskCreateViewModel() {
    var self = this;

    self.lists = ko.observableArray([]);
    self.selectedList = ko.observable();
    self.taskTitle = ko.observable("");
    self.taskLimit = ko.observable("");

    // // 🔹 認証チェック
    // self.checkAuth = function() {
    //     var token = localStorage.getItem("authToken");
    //     if (!token) {
    //         alert("ログインしてください");
    //         window.location.href = "signin";
    //     }
    // };


    self.fetchLists = function() {
        fetch("/api/lists", {
            method: "GET",
            credentials: "include" // クッキーを含める
        })
        .then(response => response.json())
        .then(data => {
            console.log("取得したリストデータ:", data);
    
            // APIレスポンスが配列かどうか確認
            if (Array.isArray(data)) {
                self.lists(data);
    
                if (data.length > 0) {
                    // IDを数値型に統一
                    self.selectedList(Number(data[0].id));
                } else {
                    self.selectedList(null);
                }
            } else {
                console.error("APIのレスポンス形式が想定外:", data);
            }
        })
        .catch(error => console.error("リストの取得に失敗:", error));
    };
    

    // 🔹 タスクの作成
    self.createTask = function() {
        fetch("api/tasks/create", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            credentials: "include", // クッキーを含める
            body: new URLSearchParams({
                list_id: self.selectedList(),
                title: self.taskTitle(),
                limit: self.taskLimit()
            })
        })
        .then(response => response.json())
        .then(() => {
            alert("タスクを作成しました！");
            window.location.href = "home.php";
        })
        .catch(error => console.error("タスク作成失敗:", error));
    };
    

    //初期化
    // self.checkAuth();
    self.fetchLists();
}

// Knockout.js の適用
ko.applyBindings(new TaskCreateViewModel());
