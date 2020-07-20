<div class="main-content-inner">
        <div class="row mt-5">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Progress Table start -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">List User</h4>
                                <div class="single-table">
                                    <div class="table-responsive">
                                        <?php $number = 0; ?>
                                            <?php foreach( $users as $user ) :?>
                                                <table class="table table-hover progress-table text-center">
                                                    <thead class="text-uppercase">
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">username</th>
                                                            <th scope="col">email</th>
                                                            <th scope="col">phone</th>
                                                            <th scope="col">role</th>
                                                            <th scope="col">action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row"><?= $number += 1; ?></th>
                                                            <td><?= $user->username; ?></td>
                                                            <td><?= ($user->email) ? $user->email : '-' ; ?></td>
                                                            <td><?= ($user->phone) ? $user->phone : '-' ; ?></td>
                                                            <td><?= $user->role; ?></td>
                                                            <td>
                                                                <ul class="d-flex justify-content-center">
                                                                    <li class="mr-3"><a href="#" class="text-secondary"><i class="fa fa-edit"></i></a></li>
                                                                    <li><a href="#" class="text-danger"><i class="ti-trash"></i></a></li>
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Progress Table end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- main content area end -->
        