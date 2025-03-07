function ListCreateViewModel() {
    var self = this;
    self.listName = ko.observable("");

    self.createList = function() {
        fetch("/api/lists", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + localStorage.getItem("authToken")
            },
            body: new URLSearchParams({ title: self.listName() })
        })
        .then(response => response.json())
        .then(data => {
            alert("リストを作成しました");
            window.location.href = "home.html";
        })
        .catch(error => console.error("リスト作成失敗:", error));
    };
}

// Knockout.js の適用
ko.applyBindings(new ListCreateViewModel());
