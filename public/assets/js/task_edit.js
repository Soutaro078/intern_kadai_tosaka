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

    // // 🔹 認証チェック
    // self.checkAuth = function() {
    //     var token = localStorage.getItem("authToken");
    //     if (!token) {
    //         alert("ログインしてください");
    //         window.location.href = "signin.html";
    //     }
    // };

    // 🔹 タスク情報の取得
    self.fetchTask = function() {
        const taskId = self.getTaskIdFromURL();
        if (!taskId) {
            alert("タスクIDが指定されていません");
            window.location.href = "home.php";
            return;
        }
        self.taskId(taskId);
    
        fetch("/api/tasks/" + taskId, {
            method: "GET",
            credentials: "include"
        })        
        .then(response => response.json())
        .then(data => {
            console.log("🟢 取得したタスクデータ:", data);
            console.log("🔹 APIからの deadline:", data.deadline); 
    
            self.taskTitle(data.title);
    
            // 🔹 `deadline` を UTC で解釈し JST に変換
            if (data.deadline) {
                let utcDate = new Date(data.deadline + " UTC"); // 🔥 UTC でパース
                console.log("🌍 UTC の Date オブジェクト:", utcDate.toString());
    
                utcDate.setHours(utcDate.getHours() + 9); // 🔥 JST に変換
                console.log("✅ JST に変換後:", utcDate.toString());
    
                let formattedDeadline = utcDate.toISOString().slice(0, 16); // YYYY-MM-DDTHH:mm に変換
                self.taskLimit(formattedDeadline);
            } else {
                self.taskLimit(""); // 空の値
            }
    
            self.taskStatus(data.status);
        })
        .catch(error => console.error("❌ タスク取得失敗:", error));
    };
    
    
    
    

    // 🔹 タスクの更新
    self.updateTask = function() {
        let formattedDeadline = self.taskLimit() 
            ? new Date(self.taskLimit()).toISOString().slice(0, 19).replace("T", " ") 
            : null;
    
        fetch("/api/tasks/update/" + self.taskId(), { // 🔥 エンドポイント修正
            method: "PUT",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            credentials: "include", // 🔥 セッション情報を含める
            body: new URLSearchParams({
                title: self.taskTitle(),
                deadline: formattedDeadline,
                status: self.taskStatus()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert("タスク更新失敗: " + data.error);
            } else {
                alert("タスクを更新しました！");
                window.location.href = "home.php";
            }
        })
        .catch(error => console.error("❌ タスク更新失敗:", error));
    };
    
    

    // 初期化
    self.fetchTask();
}

// Knockout.js の適用
ko.applyBindings(new TaskEditViewModel());
