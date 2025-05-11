<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendee Panel - Voting System</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .main-content {
            padding: 20px;
        }
        .party-card {
            transition: transform 0.3s;
            cursor: pointer;
        }
        .party-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .voted {
            border: 3px solid #28a745;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Attendee Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateProfileModal">Profile</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="logoutBtn">Log Out</button>
                        </div>
                    </div>
                </div>

                <!-- Parties Section -->
                <h3 class="mb-3">Registered Parties</h3>
                <div class="row" id="party-list">
                    
                </div>
            </div>
        </div>
    </div>
        <!-- Update Profile Modal -->
<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form>
          <div class="modal-header">
            <h5 class="modal-title" id="updateProfileModalLabel">Update Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          
          <div class="modal-body">
            <div class="mb-3">
              <label for="fullName" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="fullName" value="<?= $_SESSION['first_name'] ?? '' ?>">
            </div>
            <div class="mb-3">
              <label for="lastName" class="form-label">Last Name</label>
              <input type="text" class="form-control" id="lastName" value="<?= $_SESSION['last_name'] ?? '' ?>">
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="tel" class="form-control" id="phone" value="<?= $_SESSION['phone'] ?? '' ?>">
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <textarea class="form-control" id="address" rows="2"><?= $_SESSION['address'] ?? '' ?></textarea>
            </div>
            <div class="mb-3">
              <label for="newPassword" class="form-label">New Password</label>
              <input type="password" class="form-control" id="newPassword">
            </div>
            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm New Password</label>
              <input type="password" class="form-control" id="confirmPassword">
            </div>
          </div>
          
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update Profile</button>
          </div>
        </form>
      </div>
    </div>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./jquery.js"></script>
</body>
</html>