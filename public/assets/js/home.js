// console.log("✅ home.js が実行されました！");

function HomeViewModel() {
    var self = this;

    self.lists = ko.observableArray([]);
    self.tasks = ko.observableArray([]);
    self.selectedListId = ko.observable(null); // 🔹 選択中のリストIDを管理
    self.selectedListName = ko.observable(""); // 🔹 選択中のリスト名を管理
    self.errorMessage = ko.observable("");

    // 🔹 セッションチェック
    self.checkAuth = function() {
        console.log("🔍 checkAuth() が実行されました"); // 🔹 追加

        fetch("/api/check_session", { method: "GET", credentials: "include" })
        .then(response => {
            console.log("🟢 check_session API レスポンス:", response); // 🔹 追加
            return response.json();
        })
        .then(data => {
            console.log("✅ 認証チェック結果:", data); // 🔹 追加
            if (!data.isAuthenticated) {
                alert("ログインしてくださいって言ってるけどバグだよね");
                window.location.href = "/signin";
            }
        })
        .catch(error => {
            console.error("❌ セッションチェックエラー:", error);
            alert("セッションの確認に失敗しました。もう一度ログインしてください。");
            window.location.href = "/signin";
        });
    };

    // 🔹 リスト一覧の取得
    self.fetchLists = function() {
        fetch("/api/lists", {
            method: "GET",
            credentials: "include"
        })
        .then(response => response.json())
        .then(data => {
            console.log("🟢 取得したリスト:", data);

            if (!data || data.length === 0) {
                console.warn("⚠️ リストが空です");
                self.lists([]);  // 空の配列をセット（エラー回避）
            } else {
                self.lists(data);  // Knockout.js の配列にセット
                self.fetchTasks(); // 🔥 タスクも同時に取得
            }
        })
        .catch(error => {
            console.error("❌ リスト取得エラー:", error);
        });
    };

    self.deleteList = function(list) {
        if (!confirm("本当にこのリストを削除しますか？削除すると関連するタスクも消えます。")) {
            return;
        }
    
        fetch(`/api/lists/delete/${list.id}`, {
            method: "DELETE",
            credentials: "include",
            headers: { "Content-Type": "application/json" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert("リストの削除に失敗しました: " + data.error);
            } else {
                alert("リストが削除されました");
                self.lists.remove(list);  // UI からも削除
            }
        })
        .catch(error => {
            console.error("❌ リスト削除エラー:", error);
            alert("リストの削除に失敗しました");
        });
    };
    

    // 🔹 タスク一覧の取得（🔥 追加）
    self.fetchTasks = function() {
        fetch("/api/tasks", {
            method: "GET",
            credentials: "include"
        })
        .then(response => response.json())
        .then(data => {
            console.log("🟢 取得したタスク:", data);

            if (!data || data.length === 0) {
                console.warn("⚠️ タスクが空です");
                self.tasks([]);  // 空の配列をセット（エラー回避）
            } else {
                self.tasks(data);  // Knockout.js の配列にセット
            }
        })
        .catch(error => {
            console.error("❌ タスク取得エラー:", error);
        });
    };

    // 🔹 選択されたリストのタスク取得
    self.fetchTasksByList = function(list) {
        console.log("📋 選択されたリスト:", list);
        console.log(`🟢 送信するリクエスト: /api/tasks?list_id=${list.id}`);
    
        self.selectedListId(list.id);
        self.selectedListName(list.name);
    
        fetch(`/api/tasks?list_id=${list.id}`, { 
            method: "GET",
            credentials: "include"
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("🟢 取得したタスク:", data);
    
            if (data.error) {
                console.error("❌ APIエラー:", data.error);
                return;
            }
    
            if (!Array.isArray(data)) {
                console.error("❌ 不正なデータフォーマット:", data);
                return;
            }
    
            self.tasks(data);
        })
        .catch(error => {
            console.error("❌ タスク取得エラー:", error);
        });
    };
    
    
    

    // 🔹 タスク削除
    self.deleteTask = function(task) {
        if (confirm("本当にこのタスクを削除しますか？")) {
            fetch("/api/tasks/delete/" + task.id, {
                method: "DELETE",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json"
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(error => { throw new Error(error.error || 'Unknown error'); });
                }
                return response.json();
            })
            .then(data => {
                if (data.message === "Task deleted successfully") {
                    alert("タスクが削除されました");
                    self.tasks.remove(task);
                } else {
                    alert("タスクの削除に失敗しました: " + data.error);
                }
            })
            .catch(error => {
                console.error("❌ タスク削除失敗:", error);
                alert("タスクの削除に失敗しました");
            });
        }
    };


    self.signout = function() {
        fetch("/api/signout", { method: "POST" })
        .then(response => {
            if (response.ok) {
                alert("サインアウトしました");
                localStorage.removeItem("authToken"); // トークン削除
                window.location.href = "/signin"; // サインイン画面へ遷移
            } else {
                alert("サインアウトに失敗しました");
            }
        })
        .catch(error => console.error("❌ サインアウトエラー:", error));
    };

    // 🔹 初期化処理
    console.log("🔄 ViewModel が作成されました");
    self.checkAuth();
    self.fetchLists();
}

// Knockout.js の適用
console.log("🔹 Knockout.js のバインディングを適用");
ko.applyBindings(new HomeViewModel());


// console.log("✅ home.js が実行されました！");

// function HomeViewModel() {
//     var self = this;

//     self.lists = ko.observableArray([]);
//     self.tasks = ko.observableArray([]);
//     self.selectedListId = ko.observable(null); // 🔹 選択中のリストIDを管理
//     self.selectedListName = ko.observable(""); // 🔹 選択中のリスト名を管理
//     self.errorMessage = ko.observable("");

//     // 🔹 セッションチェック
//     self.checkAuth = function() {
//         console.log("🔍 checkAuth() が実行されました");

//         fetch("/api/check_session", { method: "GET", credentials: "include" })
//         .then(response => response.json())
//         .then(data => {
//             if (!data.isAuthenticated) {
//                 alert("ログインしてください");
//                 window.location.href = "/signin";
//             }
//         })
//         .catch(error => {
//             console.error("❌ セッションチェックエラー:", error);
//             alert("セッションの確認に失敗しました。もう一度ログインしてください。");
//             window.location.href = "/signin";
//         });
//     };

//     // 🔹 リスト一覧の取得
//     self.fetchLists = function() {
//         fetch("/api/lists", {
//             method: "GET",
//             credentials: "include"
//         })
//         .then(response => response.json())
//         .then(data => {
//             console.log("🟢 取得したリスト:", data);

//             if (!data || data.length === 0) {
//                 console.warn("⚠️ リストが空です");
//                 self.lists([]);  // 空の配列をセット
//             } else {
//                 self.lists(data); // Knockout.js の配列にセット
                
//                 // クエリパラメータ `list_id` を取得
//                 const urlParams = new URLSearchParams(window.location.search);
//                 const paramListId = urlParams.get("list_id");

//                 // 🔹 クエリパラメータがある場合はそのリストを選択
//                 let selectedList = data.find(list => list.id == paramListId);

//                 // 🔹 クエリパラメータがない場合は最初のリストを選択
//                 if (!selectedList) {
//                     selectedList = data[0];
//                 }

//                 // 🔥 選択リストをセットしてタスクを取得
//                 self.selectedListId(selectedList.id);
//                 self.selectedListName(selectedList.name);
//                 self.fetchTasksByList(selectedList);
//             }
//         })
//         .catch(error => {
//             console.error("❌ リスト取得エラー:", error);
//         });
//     };

//     // 🔹 タスク一覧の取得
//     self.fetchTasksByList = function(list) {
//         if (!list || !list.id) {
//             console.error("❌ 無効なリスト選択:", list);
//             return;
//         }

//         console.log("📋 選択されたリスト:", list);
//         console.log(`🟢 送信するリクエスト: /api/tasks?list_id=${list.id}`);

//         self.selectedListId(list.id);
//         self.selectedListName(list.name);

//         fetch(`/api/tasks?list_id=${list.id}`, { 
//             method: "GET",
//             credentials: "include"
//         })
//         .then(response => response.json())
//         .then(data => {
//             console.log("🟢 取得したタスク:", data);

//             if (data.error) {
//                 console.error("❌ APIエラー:", data.error);
//                 return;
//             }

//             if (!Array.isArray(data)) {
//                 console.error("❌ 不正なデータフォーマット:", data);
//                 return;
//             }

//             self.tasks(data);
//         })
//         .catch(error => {
//             console.error("❌ タスク取得エラー:", error);
//         });
//     };

//     // 🔹 タスク削除
//     self.deleteTask = function(task) {
//         if (confirm("本当にこのタスクを削除しますか？")) {
//             fetch("/api/tasks/delete/" + task.id, {
//                 method: "DELETE",
//                 credentials: "include",
//                 headers: {
//                     "Content-Type": "application/json"
//                 }
//             })
//             .then(response => {
//                 if (!response.ok) {
//                     return response.json().then(error => { throw new Error(error.error || 'Unknown error'); });
//                 }
//                 return response.json();
//             })
//             .then(data => {
//                 if (data.message === "Task deleted successfully") {
//                     alert("タスクが削除されました");
//                     self.tasks.remove(task);
//                 } else {
//                     alert("タスクの削除に失敗しました: " + data.error);
//                 }
//             })
//             .catch(error => {
//                 console.error("❌ タスク削除失敗:", error);
//                 alert("タスクの削除に失敗しました");
//             });
//         }
//     };


//     self.signout = function() {
//         fetch("/api/signout", { method: "POST" })
//         .then(response => {
//             if (response.ok) {
//                 alert("サインアウトしました");
//                 localStorage.removeItem("authToken"); // トークン削除
//                 window.location.href = "/signin"; // サインイン画面へ遷移
//             } else {
//                 alert("サインアウトに失敗しました");
//             }
//         })
//         .catch(error => console.error("❌ サインアウトエラー:", error));
//     };

//     // 🔹 初期化処理
//     console.log("🔄 ViewModel が作成されました");
//     self.checkAuth();
//     self.fetchLists();
// }

// // Knockout.js の適用
// console.log("🔹 Knockout.js のバインディングを適用");
// ko.applyBindings(new HomeViewModel());



