function toggleStatus(userId, currentStatus) {
    let newStatus = (currentStatus === "Active") ? "Inactive" : "Active";

    $.ajax({
        type: "POST",
        url: "../backend/update_status.php",
        data: { id: userId, status: newStatus },
        success: function () {
            location.reload();
        }
    });
}

function deleteUser(userId) {
    if (confirm("Are you sure you want to delete this user?")) {
        $.ajax({
            type: "POST",
            url: "../backend/delete_user.php",
            data: { id: userId },
            success: function () {
                location.reload();
            }
        });
    }
}


function openAddUserModal() {
    $("#addUserModal").show();
}

function closeAddUserModal() {
    $("#addUserModal").hide();
}

function addUser() {
    let data = {
        firstName: $("#firstName").val().trim(),
        lastName: $("#lastName").val().trim(),
        email: $("#email").val().trim(),
        password: $("#password").val().trim(),
        phone: $("#phone").val().trim(),
        role: $("#role").val()
    };

    if (data.firstName === "" || data.lastName === "" || data.email === "" || data.password === "" || data.phone === "") {
        alert("Please fill in all fields.");
        return;
    }

    $.ajax({
        type: "POST",
        url: "../backend/add_user.php",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.success) {
                alert(response.success);
                location.reload();
            } else {
                alert(response.error);
            }
        },
        error: function () {
            alert("Error processing request.");
        }
    });
}


