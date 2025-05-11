$(document).ready(function () {
    getParty();
    loadParties();
    loadAdminDashboard();
    loadDashboardCounts();
    
    // sigin up request 
    $('#sign_up_form').on('submit', function (e) {
        e.preventDefault();

        const data = {
            firstName: $('#firstName').val(),
            lastName: $('#lastName').val(),
            email: $('#email').val(),
            userType: $('#userType').val(),
            password: $('#password').val(),
            confirmPassword: $('#confirmPassword').val(),
        };

        $.ajax({
            url: './backend/sign_up.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                // console.log(response);
                if (response.status === 'success') {
                    Swal.fire({
                        title: response.message,
                        icon: "success",
                        draggable: true
                    });
                    $('#firstName').val('');
                    $('#lastName').val('');
                    $('#email').val('');
                    $('#userType').val('');
                    $('#password').val('');
                    $('#confirmPassword').val('');
                } else if (response.errors) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        html: response.errors.join("<br>")
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: response.message,
                    });
                }
            },
            error: function () {
                alert('An error occurred while creating the account.');
            }
        });
    });

    // login request
    $('#login_form').on('submit', function (e) {
        e.preventDefault();

        const data = {
            email: $('#email').val(),
            password: $('#password').val(),
        };


        $.ajax({
            url: './backend/login.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: response.message,
                        icon: "success",
                        draggable: true
                    });
                    if (response.role == 'canidate') {
                        window.location.href = './attendee.php';
                    }
                    else if (response.role == 'party') {
                        window.location.href = './party.php';
                    }
                    else if (response.role == 'admin') {
                        window.location.href = './admin.php';
                    }
                    $('#email').val('');
                    $('#password').val('');
                } else {
                    Swal.fire({
                        icon: "error",
                        title: response.message,
                    });
                }
            },
            error: function () {
                alert('Something went wrong. Try again.');
            }
        });
    });


    // add party request 
    $('#addPartyModal form').on('submit', function (e) {
        e.preventDefault();

        var formData = {
            partyName: $('#partyName').val(),
            leaderFounder: $('#leaderFounder').val(),
            contactPhone: $('#contactPhone').val(),
            email: $('#email').val(),
            partyQuote: $('#partyQuote').val(),
            headquarter: $('#headquarter').val(),
            partyDescription: $('#partyDescription').val()
        };

        $.ajax({
            url: './backend/add_party.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: response.message,
                        icon: "success",
                        draggable: true
                    });
                    $('#addPartyModal').modal('hide');
                    $('#addPartyModal form')[0].reset();
                    getParty();
                } else if (response.errors) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        html: response.errors.join("<br>")
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                alert('AJAX Error: ' + error);
            }
        });
    });


    $('#logoutBtn').on('click', function () {
        $.ajax({
            url: './backend/logout.php',
            type: 'POST',
            success: function (response) {
                if (response.status === 'success') {
                    window.location.href = './index.php';
                } else {
                    alert(response.message || 'Logout failed.');
                }
            },
            error: function (xhr, status, error) {
                console.log(error)
            }
        });
    });


    $('#updateProfileModal form').on('submit', function (e) {
        e.preventDefault();

        const data = {
            full_name: $('#fullName').val(),
            last_name: $('#lastName').val(),
            phone: $('#phone').val(),
            address: $('#address').val(),
            new_password: $('#newPassword').val(),
            confirm_password: $('#confirmPassword').val()
        };

        if (data.new_password !== data.confirm_password) {
            Swal.fire({
                icon: "error",
                title: 'Passwords do not match!',
            });
            return;
        }

        $.ajax({
            url: './backend/update_profile.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: response.message,
                        icon: "success",
                        draggable: true
                    });
                    $('#updateProfileModal').modal('hide');
                    window.location.reload();
                } else if (response.errors) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        html: response.errors.join("<br>")
                    });
                }  else {
                    alert(response.message || "Update failed.");
                }
            },
            error: function () {
                alert("AJAX request failed.");
            }
        });
    });

    // Vote button click
    $(document).on('click', '.vote-btn', function () {
        const partyId = $(this).data('id');
        $.post('./backend/cast_vote.php', { party_id: partyId }, function (res) {
            const response = JSON.parse(res);
            if (response.status === 'success') {
                loadParties(); // Reload list
            } else if (response.status === 'already_voted') {
                Swal.fire({
                    icon: "error",
                    title: "You have already voted for this party.",
                });
            }
        });
    });

    function getParty() {
        $.ajax({
            url: './backend/get_party.php',
            type: 'GET',
            success: function (response) {
                const container = $('#partyContainer');

                if (response.status === 'success') {
                    const party = response.data;
                    $('#total_votes').text(party.votes);

                    container.html(`
                        <div class="card profile-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h2>${party.party_name}</h2>
                                        <p class="lead">${party.party_quote}</p>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Leader:</strong> ${party.leader_founder}</p>
                                                <p><strong>Founded:</strong> ${party.founded}</p>
                                                <p><strong>Headquarters:</strong> ${party.headquarter}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Contact Email:</strong> ${party.email}</p>
                                                <p><strong>Website:</strong> ${party.website}</p>
                                                <p><strong>Phone:</strong> ${party.phone_contact}</p>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <h5>Party Description</h5>
                                            <p>${party.party_description}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                } else if (response.status === 'empty') {
                    container.html('<div class="alert alert-warning">No party registered.</div>');
                }
            },
            error: function (xhr, status, error) {
                console.log(error)
            }
        });
    }


    function loadParties() {
        $.getJSON('./backend/get_voting_parties.php', function (data) {
            let html = '';
            data.forEach(party => {
                html += `
                <div class="col-md-4 mb-4">
                    <div class="card party-card ${party.voted ? 'voted' : ''}">
                        <div class="card-body">
                            <h5 class="card-title">${party.party_name}</h5>
                            <p class="card-text">${party.party_quote || ''}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Leader: ${party.leader_founder}</small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <button class="btn ${party.voted ? 'btn-secondary' : 'btn-success'} w-100 vote-btn" 
                                data-id="${party.id}" ${party.voted ? 'disabled' : ''}>
                                ${party.voted ? 'Voted' : 'Vote Now'}
                            </button>
                        </div>
                    </div>
                </div>`;
            });
            $('#party-list').html(html);
        });
    }

    function loadAdminDashboard() {
        $.getJSON('./backend/get_parties.php', function (data) {
            let html = '';
            if (data.length === 0) {
                html = `<tr>
                            <td colspan="5" class="text-center">No data found</td>
                        </tr>`;
            } else {
                data.forEach(party => {
                    html += `
                    <tr>
                        <td>${party.id}</td>
                        <td>${party.party_name}</td>
                        <td>${party.leader_founder}</td>
                        <td>${party.votes}</td>
                        <td><span class="badge bg-success status-badge">Active</span></td>
                    </tr>`;
                });
            }    
            $('#parties_table').html(html);
        });
    }

    function loadDashboardCounts() {
        $.getJSON('./backend/dashboard_counts.php', function (data) {
            $('#totalParties').text(data.total_parties);
            $('#totalCandidates').text(data.total_candidates);
            $('#totalVotes').text(data.total_votes);
        });
    }

})
