<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Kategori</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <div v-if="$page.props.session.error" class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-white">Gagal</h6>
                        <div class="text-white">{{ $page.props.session.error }}</div>
                    </div>
                </div>  
            </div>
            
            <div 
                class="row row-cols-1 row-cols-md-2"
                :class="{'row-cols-xl-4': canDisplayAdminTransactions, 'row-cols-xl-3': !canDisplayAdminTransactions}"
                v-if="$page.props.auth.user.level == 1"
                >
                <div class="col">
                    <div class="card radius-10 bg-gradient-blue-modern border-0 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-white font-semibold text-sm">Peserta</p>
                                    <h4 class="mb-0 text-white font-bold text-3xl">{{ totalStudent }}</h4>
                                </div>
                                <div class="ms-auto"><i class='bx bxs-user-detail stat-icon-white'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-rose-modern border-0 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-white font-semibold text-sm">Akun Aktif</p>
                                    <h4 class="mb-0 text-white font-bold text-3xl">{{ totalStudentActive }}</h4>
                                </div>
                                <div class="ms-auto"><i class='bx bx-user-check stat-icon-white'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-amber-modern border-0 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-slate-800 font-semibold text-sm">Akun Non-Aktif</p>
                                    <h4 class="mb-0 text-slate-800 font-bold text-3xl">{{ totalStudentNonActive }}</h4>
                                </div>
                                <div class="ms-auto"><i class='bx bx-user-minus stat-icon-dark'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" v-if="canDisplayAdminTransactions">
                    <div class="card radius-10 bg-gradient-emerald-modern border-0 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-white font-semibold text-sm">Peserta Menjadi Member</p>
                                    <h4 class="mb-0 text-white font-bold text-3xl">{{ totalStudentMember }}</h4>
                                </div>
                                <div class="ms-auto"><i class='bx bx-message-rounded-dots stat-icon-white'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4" v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-sky-400 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Transaksi Hari Ini</p>
                                    <h4 class="mb-0 text-sky-400 font-bold text-2xl">{{ totalTransactionToday }}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-12 h-12 rounded-full icon-box-soft-blue ms-auto">
                                    <i class='bx bxs-cart text-2xl'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-emerald-500 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Transaksi Bulan Ini</p>
                                    <h4 class="mb-0 text-emerald-500 font-bold text-2xl">{{ totalTransactionMonthly }}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-12 h-12 rounded-full icon-box-soft-green ms-auto">
                                    <i class='bx bx-bar-chart-alt-2 text-2xl' ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-rose-500 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Transaksi Tahun Ini</p>
                                    <h4 class="mb-0 text-rose-500 font-bold text-2xl">{{ totalTransactionYearly }}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-12 h-12 rounded-full icon-box-soft-rose ms-auto">
                                    <i class='bx bxs-wallet text-2xl'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-amber-400 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Pemasukan Bulan Ini</p>
                                    <h4 class="mb-0 text-amber-500 font-bold text-2xl">0</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-12 h-12 rounded-full icon-box-soft-amber ms-auto">
                                    <i class='bx bxs-group text-2xl'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4" v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-amber-400 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Transaksi Pending</p>
                                    <h4 class="mb-0 text-slate-800 font-bold text-2xl">{{ totalTransactionPending }}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-lg icon-box-soft-amber ms-auto">
                                    <i class="bx bxs-wallet text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-emerald-500 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Transaksi Dibayar</p>
                                    <h4 class="mb-0 text-slate-800 font-bold text-2xl">{{ totalTransactionPaid }}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-lg icon-box-soft-green ms-auto">
                                    <i class="bx bxs-wallet text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-rose-500 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Transaksi Gagal</p>
                                    <h4 class="mb-0 text-slate-800 font-bold text-2xl">{{ totalTransactionFailed }}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-lg icon-box-soft-rose ms-auto">
                                    <i class="bx bxs-wallet text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-blue-500 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Transaksi Selesai</p>
                                    <h4 class="mb-0 text-slate-800 font-bold text-2xl">{{ totalTransactionDone }}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-lg icon-box-soft-blue ms-auto">
                                    <i class="bx bxs-wallet text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3" v-if="$page.props.auth.user.level == 3">
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-sky-400 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Total Paket Soal Dibuat</p>
                                    <h4 class="mb-0 text-sky-400 font-bold text-2xl">{{ totalQuestionTitleByUser }}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-12 h-12 rounded-full icon-box-soft-blue ms-auto">
                                    <i class='bx bxs-cart text-2xl'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-emerald-500 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Total Latihan Soal Dibuat</p>
                                    <h4 class="mb-0 text-emerald-500 font-bold text-2xl">{{ totalExamByUser }}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-12 h-12 rounded-full icon-box-soft-green ms-auto">
                                    <i class='bx bx-bar-chart-alt-2 text-2xl' ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 border-l-4 border-rose-500 shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-slate-500 font-semibold text-sm">Total Tryout Dibuat</p>
                                    <h4 class="mb-0 text-rose-500 font-bold text-2xl">{{ totalQuestionTitleByUser }}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center w-12 h-12 rounded-full icon-box-soft-rose ms-auto">
                                    <i class='bx bxs-wallet text-2xl'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="card radius-10 border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-circle icon-box-soft-green me-3">
                            <i class='bx bxs-megaphone text-xl'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800">Pengumuman</h6>
                            <p class="mb-0 text-sm text-slate-500">Informasi dan update terbaru</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="py-3 px-4 border-0 rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 border-0">Perihal</th>
                                    <th class="py-3 px-4 border-0">Dibuat</th>
                                    <th class="py-3 px-4 border-0 rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!announcementSummaries.length" class="border-b border-slate-100">
                                    <td colspan="4" align="center" class="py-4 text-slate-500">Data Pengumuman kosong</td>
                                </tr>
                                <tr v-for="(announcementSummary, index) in announcementSummaries" :key="index" class="border-b border-slate-100 transition-colors hover:bg-slate-50">
                                    <td class="py-3 px-4">
                                        <span class="badge bg-slate-100 text-slate-700 fw-bold">{{ ++index }}</span>
                                    </td>
                                    <td class="py-3 px-4 font-medium text-slate-800">{{ announcementSummary.title }}</td>
                                    <td class="py-3 px-4 text-sm text-slate-500">{{ announcementSummary.created_at }}</td>
                                    <td class="py-3 px-4">
                                        <div class="d-flex order-actions">
                                            <Link :href="`/admin/announcements/${announcementSummary.id}`" class="d-flex align-items-center justify-content-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors">
                                                <i class='bx bx-search-alt-2'></i>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div>
                        <apexchart :width="chart.width" :height="chart.height" :type="chart.type" :options="chart.options" :series="chart.series"></apexchart>
                    </div>
                </div>
            </div> -->

            <div class="card radius-10 border-0 shadow-sm mb-4" v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-circle icon-box-soft-blue me-3">
                            <i class='bx bx-receipt text-xl'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800">Transaksi Bulan Ini</h6>
                            <p class="mb-0 text-sm text-slate-500">Log transaksi terbaru pengguna</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="py-3 px-4 border-0 rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 border-0">Kode Transaksi</th>
                                    <th class="py-3 px-4 border-0">User</th>
                                    <th class="py-3 px-4 border-0">Keterangan</th>
                                    <th class="py-3 px-4 border-0">Tanggal Transaksi</th>
                                    <th class="py-3 px-4 border-0">Total Pembayaran</th>
                                    <!-- <th class="border-0">Metode Pembayaran</th> -->
                                    <th class="py-3 px-4 border-0">Status</th>
                                    <th class="py-3 px-4 border-0 rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr v-for="(transaction, index) in transactions.data" :key="index" class="border-b border-slate-100 transition-colors hover:bg-slate-50">
                                    <td class="py-3 px-4"><span class="badge bg-slate-100 text-slate-700">{{ ++index + (transactions.current_page - 1) * transactions.per_page }}</span></td>
                                    <td class="py-3 px-4 font-semibold text-slate-800">{{ transaction.code }}</td>
                                    <td class="py-3 px-4 text-indigo-600 font-medium">{{ transaction.user.name }}</td>
                                    <td class="py-3 px-4">{{ transaction.description }}</td>
                                    <td class="py-3 px-4 text-slate-500">{{ formatDateWithTimeHourMinute(transaction.created_at) }}</td>
                                    <td class="py-3 px-4 font-semibold">{{ formatPrice(transaction.total_payment) }}</td>
                                    <!-- <td class="py-3 px-4">{{ transaction.payment_method == 'account_balance' ? 'Saldo Akun' : ''}}</td> -->
                                    <td class="py-3 px-4">
                                        <span class="badge bg-warning" v-if="transaction.transaction_status == 'pending'">Pending</span>
                                        <span class="badge bg-primary" v-if="transaction.transaction_status == 'paid'">Paid</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'expired'">Expired</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'failed'">Failed</span>
                                        <span class="badge bg-success" v-if="transaction.transaction_status == 'done'">Done</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="d-flex order-actions gap-2">
                                            <Link :href="`/admin/transactions/${transaction.id}`" class="d-flex align-items-center justify-content-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Detail">
                                                <i class='bx bx-search-alt-2'></i>
                                            </Link>
                                            <Link :href="`/admin/transactions/${transaction.id}/invoice`" class="d-flex align-items-center justify-content-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors" title="Invoice">
                                                <i class='bx bx-receipt'></i>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!transactions.data.length" class="border-b border-slate-100">
                                    <td align="center" colspan="9" class="py-4 text-slate-500">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="transactions.links" align="end" class="mt-4" />

                </div>
            </div>

            <div class="card radius-10 border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-circle icon-box-soft-amber me-3">
                            <i class='bx bx-folder text-xl'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800">Informasi Kategori Peminatan {{ $page.props.auth.user.level == 3 ? 'Yang Sudah Dibuat' : ''}}</h6>
                            <p class="mb-0 text-sm text-slate-500">Ringkasan modul, tryout, dan latihan soal per kategori</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4" v-for="(totalDataInCategory, index) in totalDataInCategories" :key="index">
                            <div class="card radius-10 overflow-hidden mb-0 border border-slate-100 shadow-none transition-all duration-300 hover:border-slate-200 hover:shadow-sm">
                                <div class="card-header bg-transparent border-b border-slate-100 py-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-1 font-semibold text-slate-800">{{ totalDataInCategory.name }}</h6>
                                            <div class="text-xs text-slate-500">Statistik Data</div>
                                        </div>
                                        <div :class="['widgets-icons', 'w-10 h-10 rounded-full d-flex align-items-center justify-content-center', bgColors[index % bgColors.length], textColors[index % textColors.length], 'ms-auto']">
                                            <i class="bx bx-info-circle"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0 align-middle">
                                        <tbody class="text-sm">
                                            <tr v-if="$page.props.menu_users.some(item => item.code == 'tryout' && item.is_active == '1')" class="border-b border-slate-50">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-edit text-info font-20 align-middle me-1'></i> Tryout</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.exam_group_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" :href="`/admin/exam-groups?category_id=${totalDataInCategory.id}`">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="$page.props.menu_users.some(item => item.code == 'exam' && item.is_active == '1')" class="border-b border-slate-50">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-book text-success font-20 align-middle me-1'></i> Latihan Soal</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.exam_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" :href="`/admin/exams?category_id=${totalDataInCategory.id}`">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="$page.props.menu_users.some(item => item.code == 'module' && item.is_active == '1')" class="border-b border-slate-50">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-file text-warning font-20 align-middle me-1'></i> Modul / Materi</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.module_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" href="/admin/modules">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="$page.props.menu_users.some(item => item.code == 'video_module' && item.is_active == '1')" class="border-b border-slate-50">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-video-recording text-danger font-20 align-middle me-1'></i> Video Belajar</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.video_module_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" href="/admin/video-modules">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="$page.props.menu_users.some(item => item.code == 'course' && item.is_active == '1')" class="border-b border-slate-50">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-video-plus text-success font-20 align-middle me-1'></i> Course</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.course_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" href="/admin/courses">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="$page.props.menu_users.some(item => item.code == 'classroom' && item.is_active == '1')">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-chalkboard text-default font-20 align-middle me-1'></i> Live Class</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.classroom_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" href="/admin/classrooms">Lihat</Link></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../Layouts/Layout.vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    //import component pagination
    import Pagination from '../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    export default {
        // layout
        layout: LayoutAdmin,
        // register components
        components: {
            Head,
            Pagination,
            Link
        },
        props: {
            totalStudent: String,
            totalStudentActive: String,
            totalStudentNonActive: String,
            totalStudentMember: String,

            totalTransactionToday: String,
            totalTransactionMonthly: String,
            totalTransactionYearly: String,

            totalTransactionPending: String,
            totalTransactionPaid: String,
            totalTransactionDone: String,
            totalTransactionFailed: String,

            totalQuestionTitleByUser: String,
            totalExamByUser: String,
            totalExamGroupByUser: String,
            totalDataInCategories: Object,
            transactions: Object,
            announcementSummaries: Object,

            // chart: Object
        },
        setup() {
            const bgColors = ['bg-light-primary', 'bg-light-danger', 'bg-light-info', 'bg-light-warning'];
            const textColors = ['text-primary', 'text-danger', 'text-info', 'text-warning'];

            return {
                bgColors,
                textColors,
            };
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        },
        computed: {
            canDisplayAdminTransactions() {
                const setting = this.$page.props.setting;

                return (
                        setting.enable_practice_question_sales == 1 ||
                        setting.enable_tryout_sales == 1 ||
                        setting.enable_module_material_sales == 1 ||
                        setting.enable_video_module_sales == 1 ||
                        setting.enable_course_sales == 1
                );
            }
        }
    }
</script>

<style scoped>
/* Custom Premium Dashboard Classes */
.bg-gradient-blue-modern {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%) !important;
}

.bg-gradient-rose-modern {
    background: linear-gradient(135deg, #f43f5e 0%, #fb7185 100%) !important;
}

.bg-gradient-amber-modern {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%) !important;
}

.bg-gradient-emerald-modern {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%) !important;
}

.icon-box-soft-blue {
    background: rgba(56, 189, 248, 0.1) !important;
    color: #0ea5e9 !important;
}

.icon-box-soft-green {
    background: rgba(16, 185, 129, 0.1) !important;
    color: #10b981 !important;
}

.icon-box-soft-rose {
    background: rgba(244, 63, 94, 0.1) !important;
    color: #f43f5e !important;
}

.icon-box-soft-amber {
    background: rgba(245, 158, 11, 0.1) !important;
    color: #f59e0b !important;
}

.card-hover-modern {
    transition: all 0.3s ease;
}

.card-hover-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
}

.stat-icon-white {
    font-size: 35px;
    opacity: 0.8;
    color: #fff !important;
}

.stat-icon-dark {
    font-size: 35px;
    opacity: 0.8;
    color: #1e293b !important;
}
</style>
