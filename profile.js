document.addEventListener("DOMContentLoaded", function () {
    let userId = document.getElementById("profile").dataset.userId;
    updateFollowersCount(userId);
    checkFollowStatus(userId);
});

function updateFollowersCount(userId) {
    fetch("count_followers.php?user_id=" + userId)
        .then(response => response.json())
        .then(data => {
            document.getElementById("followers-count").innerText = data.followers + " seguidores";
        })
        .catch(error => {
            console.error("Erro ao contar seguidores:", error);
            document.getElementById("followers-count").innerText = "Erro ao carregar";
        });
}

function checkFollowStatus(userId) {
    fetch("check_follow.php?user_id=" + userId)
        .then(response => response.json())
        .then(data => {
            let followBtn = document.getElementById("follow-btn");
            followBtn.innerText = data.status === "following" ? "Deixar de Seguir" : "Seguir";
        })
        .catch(error => {
            console.error("Erro ao verificar status:", error);
            document.getElementById("follow-btn").innerText = "Erro ao carregar";
        });
}

function toggleFollow(userId) {
    fetch("follow.php", {
        method: "POST",
        body: new URLSearchParams({ user_id: userId }),
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
    })
        .then(response => response.json())
        .then(data => {
            let followBtn = document.getElementById("follow-btn");
            followBtn.innerText = data.status === "following" ? "Deixar de Seguir" : "Seguir";
            updateFollowersCount(userId);
        })
        .catch(error => {
            console.error("Erro ao seguir:", error);
            document.getElementById("follow-btn").innerText = "Erro ao carregar";
        });
}
