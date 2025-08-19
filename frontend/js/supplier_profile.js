function openEditProfileModal() {
    $("#editProfileModal").show();
}

function closeEditProfileModal() {
    $("#editProfileModal").hide();
}

function updateProfile() {
    let formData = new FormData();
    formData.append("company_name", $("#company_name").val());
    formData.append("phone", $("#phone").val());
    formData.append("address", $("#address").val());
    
    let logo = $("#logo")[0].files[0];
    if (logo) {
        formData.append("logo", logo);
    }

    $.ajax({
        type: "POST",
        url: "../backend/update_supplier_profile.php",
        data: formData,
        processData: false,
        contentType: false,
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
            alert("Error updating profile.");
        }
    });
}
