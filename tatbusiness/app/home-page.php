<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_POST['action'])) {
    header('Location: forbidden.php');
    exit();
}

// Nếu chưa đăng nhập thì về trang login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Nếu chưa đổi mật khẩu khi đăng nhập lần đầu thì không cho vào trang này
if ($_SESSION['activated'] == 0) {
    header('Location: set-new-password.php');
    exit();
}

// Nếu là nhân viên thì trang hiện đầu tiên là trang TASK
if ($_POST['action'] == 'after-login' && $_SESSION['role'] == 'Nhân viên') {
    return false;
}

require_once '../backend/DAO/MixableDAO.php';

if ($_SESSION['role'] == 'Giám đốc') {
    $result = statisticsStatus();
} else {
    $result = statisticsStatusByEmployee($_SESSION['userID']);
}

if (!$result['status']) {
    die(json_encode($result));
}

$result = $result['result'];

?>
<div class="container">
    <div class="card badge-primary">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h3>Số liệu tổng quát</h3>
                <h3><i class="fas fa-chart-bar mr-2"></i></h3>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if ($_SESSION['role'] == 'Giám đốc') {
        ?>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card badge-info">
                    <div class="card-body text-center">
                        <a href='#' id="all-user">
                            <h4 class="">Nhân viên</h4>
                            <span class="h5"><i class="fas fa-users mr-2"></i><?= $result['users'] ?></span>       
                        </a>                 
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card badge-danger">
                    <div class="card-body text-center">
                    <a href='#' id="list-absence">
                    <h4 class="">Đang nghỉ phép</h4>
                        <span class="h5"><i class="fas fa-bed mr-2"></i><?= $result['users-absence'] ?></span>
                                    </a>

                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card badge-warning">
                    <div class="card-body text-center">
                    <a href='#' id="list-department">
                    <h4 class="">Danh sách phòng ban</h4>

                                        <span class="h5"><i class="fas fa-cubes mr-2"></i><?= $result['departments'] ?></span>
                                    </a>

                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card badge-default">
                    <div class="card-body text-center">
                        <h4 class="">Công việc</h4>
                        <span class="h5"><i class="fas fa-clipboard-list mr-2"></i><?= $result['tasks'] ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-xl-8">
                <div class="card badge-success">
                    <div class="card-body text-center">
                        <h4 class="">Công việc hoàn thành</h4>
                        <span class="h5"><i class="fas fa-clipboard-check mr-2"></i><?= $result['tasks-completed'] ?></span>
                    </div>
                </div>
            </div>
        <?php
        } else {
        ?>
            <!-- Nhân viên / trưởng phòng -->
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card badge-info">
                    <div class="card-body text-center">
                        <h4 class="">Đồng nghiệp</h4>
                        <span class="h5"><i class="fas fa-users mr-2"></i><?= $result['teamates'] ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card badge-danger">
                    <div class="card-body text-center">
                        <h4 class="">Lương</h4>
                        <span class="h5"><i class="fas fa-coins mr-2"></i><?= number_format($result['salary'], 0, ',', '.')  ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card badge-warning">
                    <div class="card-body text-center">
                        <h4 class="">Thưởng tháng</h4>
                        <span class="h5"><i class="fas fa-hand-holding-usd mr-2"></i><?= number_format($result['bonus-salary'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card badge-default">
                    <div class="card-body text-center">
                        <h4 class="">Công việc</h4>
                        <span class="h5"><i class="fas fa-clipboard-list mr-2"></i><?= $result['tasks'] . ' (Tổng)'?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-xl-8">
                <div class="card badge-success">
                    <div class="card-body text-center">
                        <h4 class="">Công việc hoàn thành</h4>
                        <span class="h5"><i class="fas fa-clipboard-check mr-2"></i><?= $result['tasks-completed-month']." (tháng " . $month = date('n') .")/". $result['tasks-completed-total'] . " (tổng)"?></span>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>

    </div>
</div>