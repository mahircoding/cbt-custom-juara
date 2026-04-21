<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Dashboard</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!-- Welcome Back Section -->
            <div class="card radius-10 border-0 shadow-sm mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                <div class="card-body p-4 position-relative" style="z-index: 1;">
                    <div class="d-flex align-items-center">
                        <div class="text-white">
                            <h4 class="mb-2 font-bold text-white">Selamat Datang Kembali, {{ $page.props.auth.user.name }}! 👋</h4>
                            <p class="mb-0 text-white-50 fs-6">Terus kembangkan potensi Anda dan raih target belajar hari ini.</p>
                        </div>
                        <div class="ms-auto d-none d-lg-block">
                            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2);">
                                <i class='bx bxs-rocket text-white' style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Abstract decorative circle -->
                <div class="position-absolute rounded-circle" style="width: 150px; height: 150px; background: rgba(255,255,255,0.1); top: -50px; right: -20px; z-index: 0;"></div>
            </div>



            <div class="row g-3 mb-4">
                <div class="col-12 col-xl-6">
                    <div class="card radius-10 border-0 shadow-sm bg-gradient-indigo-modern overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start mb-4">
                                <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 46px; height: 46px; background: rgba(255,255,255,0.2);">
                                    <i class='bx bx-wallet text-white text-xl'></i>
                                </div>
                                <div class="text-white">
                                    <div class="font-semibold text-uppercase text-white-50 mb-1">Saldo Belajar</div>
                                    <h3 class="mb-1 font-bold text-white">{{ formatCurrencyNoDecimal(walletSummary.learning_balance) }}</h3>
                                    <div class="small text-white-50">Gunakan untuk mengakses Premium</div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <Link href="/user/account-balances" class="btn btn-white flex-fill radius-30 fw-bold">
                                    <i class='bx bx-down-arrow-circle me-1'></i> Top Up
                                </Link>
                                <Link href="/user/account-balances" class="btn btn-outline-white-glass flex-fill radius-30 fw-bold">
                                    <i class='bx bx-history me-1'></i> Riwayat
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-6" v-if="$page.props.setting.enable_affiliate_feature == 1">
                    <div class="card radius-10 border-0 shadow-sm bg-gradient-indigo-modern overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start mb-4">
                                <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 46px; height: 46px; background: rgba(255,255,255,0.2);">
                                    <i class='bx bx-user-plus text-white text-xl'></i>
                                </div>
                                <div class="text-white">
                                    <div class="font-semibold text-uppercase text-white-50 mb-1">Saldo Referral</div>
                                    <h3 class="mb-1 font-bold text-white">{{ formatCurrencyNoDecimal(walletSummary.referral_balance) }}</h3>
                                    <div class="small text-white-50">Kode Referral: {{ walletSummary.referral_code ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-white flex-fill radius-30 fw-bold" @click="shareReferralLink">
                                    <i class='bx bx-share-alt me-1'></i> Bagikan
                                </button>
                                <Link href="/user/affiliates/withdrawals/create" class="btn btn-outline-white-glass flex-fill radius-30 fw-bold">
                                    <i class='bx bx-up-arrow-circle me-1'></i> Tarik
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="schedule-frame mb-4">
                <div class="schedule-frame__head">
                    <h4 class="mb-0 fw-bold text-white">Jadwal Live Class</h4>
                    <Link href="/user/classrooms" class="btn btn-sm btn-light text-primary radius-30 px-3">Lihat Semua</Link>
                </div>
                <div class="schedule-frame__body">
                    <div v-if="todayLiveClasses && todayLiveClasses.length" class="table-responsive schedule-table-wrap">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="py-3 px-4 border-0 rounded-tl-lg">Judul</th>
                                    <th class="py-3 px-4 border-0">Mentor</th>
                                    <th class="py-3 px-4 border-0 text-center">Jadwal</th>
                                    <th class="py-3 px-4 border-0 text-center">Status</th>
                                    <th class="py-3 px-4 border-0 text-center rounded-tr-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr v-for="(liveClass, index) in todayLiveClasses" :key="index" class="border-b border-slate-100 transition-colors hover:bg-slate-50">
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-slate-800">{{ liveClass.title }}</div>
                                        <div class="text-xs text-slate-500">{{ liveClass.category ? liveClass.category.name : 'Live Class' }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700">{{ liveClass.user ? liveClass.user.name : 'Instruktur' }}</td>
                                    <td class="py-3 px-4 text-center text-slate-500">{{ formatDateWithTimeHourMinute(liveClass.start_time) }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span v-if="liveClass.status == 'active'" class="badge bg-success text-white">Aktif</span>
                                        <span v-else-if="liveClass.status == 'inactive'" class="badge bg-danger text-white">Selesai</span>
                                        <span v-else class="badge bg-warning text-dark">Coming Soon</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a v-if="liveClass.status == 'active'" :href="liveClass.link" target="_blank" class="btn btn-primary btn-sm px-3">Gabung</a>
                                            <Link :href="`/user/classrooms/${liveClass.id}`" class="btn btn-outline-primary btn-sm px-3">Detail</Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="schedule-empty">
                        Belum ada rekomendasi live class saat ini.
                    </div>
                </div>
            </div>

            <div class="news-frame mb-4">
                <div class="news-frame__head">
                    <h4 class="mb-0 fw-bold text-dark">Informasi CPNS Terbaru</h4>
                    <div class="d-flex align-items-center gap-2">
                        <button class="news-nav-btn" type="button" @click="scrollNews('left')" :disabled="!canNewsScrollLeft || !newsSummaries || !newsSummaries.length">
                            <i class="bx bx-chevron-left"></i>
                        </button>
                        <button class="news-nav-btn" type="button" @click="scrollNews('right')" :disabled="!canNewsScrollRight || !newsSummaries || !newsSummaries.length">
                            <i class="bx bx-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="news-frame__body">
                    <div v-if="newsSummaries && newsSummaries.length" ref="newsScroller" class="news-scroller" @scroll="onNewsScroll">
                        <Link
                            v-for="(news, index) in newsSummaries"
                            :key="index"
                            :href="`/user/news/${news.id}`"
                            class="news-card"
                            :style="{
                                backgroundImage: news.thumbnail
                                    ? `linear-gradient(180deg, rgba(9, 31, 69, 0.16) 0%, rgba(6, 19, 43, 0.88) 74%), url('/storage/upload_files/news/${news.thumbnail}')`
                                    : 'linear-gradient(135deg, #0f4fc3 0%, #071f4d 100%)'
                            }"
                        >
                            <div class="news-card__content">
                                <h5 class="news-card__title">{{ news.title }}</h5>
                                <div class="news-card__meta">{{ formatDateWithTimeHourMinute(news.created_at) }}</div>
                            </div>
                        </Link>
                    </div>
                    <div v-else class="news-empty">
                        Belum ada informasi terbaru saat ini.
                    </div>
                </div>
            </div>

            <!-- <div v-if="canDisplayTransactions" class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-3">
                <div class="col">
                    <div class="card radius-10 border-0 bg-gradient-amber-modern shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-white font-semibold text-opacity-90 text-sm">Transaksi Pending</p>
                                    <h4 class="mb-0 text-white font-bold text-3xl">{{ totalTransactionPending }}</h4>
                                </div>
                                <div class="ms-auto">
                                    <i class="bx bxs-time stat-icon-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 bg-gradient-emerald-modern shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-white font-semibold text-opacity-90 text-sm">Transaksi Dibayar</p>
                                    <h4 class="mb-0 text-white font-bold text-3xl">{{ totalTransactionPaid }}</h4>
                                </div>
                                <div class="ms-auto">
                                    <i class="bx bx-check-shield stat-icon-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 bg-gradient-rose-modern shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-white font-semibold text-opacity-90 text-sm">Transaksi Gagal</p>
                                    <h4 class="mb-0 text-white font-bold text-3xl">{{ totalTransactionFailed }}</h4>
                                </div>
                                <div class="ms-auto">
                                    <i class="bx bx-x-circle stat-icon-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-0 bg-gradient-blue-modern shadow-sm card-hover-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-white font-semibold text-opacity-90 text-sm">Transaksi Selesai</p>
                                    <h4 class="mb-0 text-white font-bold text-3xl">{{ totalTransactionDone }}</h4>
                                </div>
                                <div class="ms-auto">
                                    <i class="bx bxs-badge-check stat-icon-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="card" v-if="banners.length > 0">
                <Splide :options="{
                        type: 'loop',
                        perPage: 1,
                        perMove: 1,
                        autoplay: true,
                        direction: 'ltr',
                        pauseOnHover: true,
                        pauseOnFocus: true,
                        interval: 5000
                    }"
                    aria-label="My Favorite Images">
                    <SplideSlide v-for="(banner, index) in banners" :key="index">
                        <img v-bind:src="'/storage/upload_files/banners/' + banner.image" :alt="banner.name" style="width:100%; cursor: pointer;" @click="showPopup(banner)">
                    </SplideSlide>
                </Splide>
            </div>

            <!-- <div v-if="canDisplayTransactions" class="card radius-10 border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-circle icon-box-soft-blue me-3">
                            <i class='bx bx-receipt text-xl'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800">Riwayat Transaksi Terbaru</h6>
                            <p class="mb-0 text-sm text-slate-500">Log transaksi pembelian paket</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="py-3 px-4 border-0 rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 border-0">Kode</th>
                                    <th class="py-3 px-4 border-0">Keterangan</th>
                                    <th class="py-3 px-4 border-0">Tanggal</th>
                                    <th class="py-3 px-4 border-0">Total</th>
                                    <th class="py-3 px-4 border-0">Metode Pembayaran</th>
                                    <th class="py-3 px-4 border-0">Status</th>
                                    <th class="py-3 px-4 border-0 rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr v-if="transactions.length" v-for="(transaction, index) in transactions" :key="index" class="border-b border-slate-100 transition-colors hover:bg-slate-50">
                                    <td class="py-3 px-4"><span class="badge bg-slate-100 text-slate-700">{{ ++index }}</span></td>
                                    <td class="py-3 px-4 font-semibold text-slate-800">{{ transaction.code }}</td>
                                    <td class="py-3 px-4">{{ transaction.description }}</td>
                                    <td class="py-3 px-4 text-slate-500">{{ formatDateWithTimeHourMinute(transaction.created_at) }}</td>
                                    <td class="py-3 px-4 font-semibold text-slate-800">{{ formatPrice(transaction.total_payment) }}</td>
                                    <td class="py-3 px-4">
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'account_balance'">Saldo Akun</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'automatic_transfer_midtrans'">Transfer Otomatis</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'manual_transfer'">Transfer Manual</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'not_payment_method'">Tanpa Metode Pembayaran</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="badge bg-warning text-dark" v-if="transaction.transaction_status == 'pending'">Pending</span>
                                        <span class="badge bg-primary" v-if="transaction.transaction_status == 'paid'">Paid</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'expired'">Expired</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'failed'">Failed</span>
                                        <span class="badge bg-success" v-if="transaction.transaction_status == 'done'">Done</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="d-flex order-actions">
                                            <Link :href="`/user/transactions/${transaction.id}`" class="d-flex align-items-center justify-content-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Lihat Detail Transaksi">
                                                <i class='bx bx-search-alt-2'></i>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else class="border-b border-slate-100">
                                    <td colspan="8" align="center" class="py-4 text-slate-500">Belum ada transaksi</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->

            <!-- Recent Exams Section -->
            <!-- <div class="card radius-10 border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-circle icon-box-soft-indigo me-3">
                            <i class='bx bx-book-bookmark text-xl'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800">Riwayat Ujian (Tryout) Terbaru</h6>
                            <p class="mb-0 text-sm text-slate-500">Hasil pengerjaan tryout grup Anda</p>
                        </div>
                        <div class="ms-auto">
                            <Link href="/user/exam-group-histories" class="btn btn-sm btn-outline-indigo radius-30 px-3">Lihat Semua</Link>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="py-3 px-4 border-0 rounded-tl-lg">Judul Tryout</th>
                                    <th class="py-3 px-4 border-0 text-center">Skor</th>
                                    <th class="py-3 px-4 border-0 text-center">Tanggal</th>
                                    <th class="py-3 px-4 border-0 text-center rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr v-if="recentExamGroupUsers.length" v-for="(history, index) in recentExamGroupUsers" :key="index" class="border-b border-slate-100 transition-colors hover:bg-slate-50">
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-slate-800">{{ history.exam_group.title }}</div>
                                        <div class="text-xs text-slate-500">{{ history.exam_group.category.name }} • {{ history.exam_group.lesson_category.name }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="badge bg-indigo-50 text-indigo-700 font-bold px-3 py-2 rounded-lg">{{ history.grade }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center text-slate-500">{{ formatDateWithTimeHourMinute(history.created_at) }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="d-flex justify-content-center order-actions">
                                            <Link :href="`/user/exam-group-histories/${history.id}`" class="d-flex align-items-center justify-content-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors">
                                                <i class='bx bx-search-alt-2'></i>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else class="border-b border-slate-100">
                                    <td colspan="4" align="center" class="py-4 text-slate-500">Belum ada riwayat ujian</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->

            <!-- Recent Practice Section -->
            <!-- <div class="card radius-10 border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-circle icon-box-soft-blue me-3">
                            <i class='bx bx-edit text-xl'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800">Riwayat Latihan Soal Terbaru</h6>
                            <p class="mb-0 text-sm text-slate-500">Hasil pengerjaan mandiri Anda</p>
                        </div>
                        <div class="ms-auto">
                            <Link href="/user/grades" class="btn btn-sm btn-outline-primary radius-30 px-3">Lihat Semua</Link>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="py-3 px-4 border-0 rounded-tl-lg">Materi Latihan</th>
                                    <th class="py-3 px-4 border-0 text-center">Skor</th>
                                    <th class="py-3 px-4 border-0 text-center">Tanggal</th>
                                    <th class="py-3 px-4 border-0 text-center rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr v-if="recentGrades.length" v-for="(grade, index) in recentGrades" :key="index" class="border-b border-slate-100 transition-colors hover:bg-slate-50">
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-slate-800">{{ grade.exam.title }}</div>
                                        <div class="text-xs text-slate-500">{{ grade.category.name }} • {{ grade.lesson_category.name }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="badge bg-primary font-bold px-3 py-2 rounded-lg">{{ grade.grade }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center text-slate-500">{{ formatDateWithTimeHourMinute(grade.created_at) }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="d-flex justify-content-center order-actions">
                                            <Link :href="`/user/grades/${grade.id}`" class="d-flex align-items-center justify-content-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                                <i class='bx bx-search-alt-2'></i>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else class="border-b border-slate-100">
                                    <td colspan="4" align="center" class="py-4 text-slate-500">Belum ada riwayat latihan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->

            <div class="card radius-10 border-0 shadow-sm mb-4" v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code === 'announcement')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code === 'announcement'))">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-circle icon-box-soft-green me-3">
                            <i class='bx bxs-megaphone text-xl'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800">Pengumuman</h6>
                            <p class="mb-0 text-sm text-slate-500">Informasi dan kabar terupdate untuk Anda</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="py-3 px-4 border-0 rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 border-0">Perihal</th>
                                    <th class="py-3 px-4 border-0 rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!announcementSummaries.length" class="border-b border-slate-100">
                                    <td colspan="3" align="center" class="py-4 text-slate-500">Data pengumuman kosong</td>
                                </tr>
                                <tr v-for="(announcementSummary, index) in announcementSummaries" :key="index" class="border-b border-slate-100 transition-colors hover:bg-slate-50">
                                    <td class="py-3 px-4">
                                        <span class="badge bg-slate-100 text-slate-700 fw-bold">{{ ++index }}</span>
                                    </td>
                                    <td class="py-3 px-4 font-medium text-slate-800">{{ announcementSummary.title }}</td>
                                    <td class="py-3 px-4">
                                        <div class="d-flex order-actions">
                                            <Link :href="`/user/announcements/${announcementSummary.id}`" class="d-flex align-items-center justify-content-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors">
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

            <div class="card radius-10 border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center w-10 h-10 rounded-circle icon-box-soft-amber me-3">
                            <i class='bx bx-folder text-xl'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800">Informasi Kategori Peminatan</h6>
                            <p class="mb-0 text-sm text-slate-500">Ringkasan modul, tryout, dan bahan belajar saya</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4" v-for="(totalDataInCategory, index) in totalDataInCategories" :key="index">
                            <div class="card radius-10 overflow-hidden mb-0 border border-slate-100 shadow-none transition-all duration-300 hover:border-slate-200 hover:shadow-sm">
                                <div class="card-header bg-transparent border-b border-slate-100 py-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-1 font-semibold text-slate-800">{{ totalDataInCategory.name }}</h6>
                                            <div class="text-xs text-slate-500">Informasi Kategori {{ totalDataInCategory.name }}</div>
                                        </div>
                                        <div :class="['widgets-icons', 'w-10 h-10 rounded-full d-flex align-items-center justify-content-center', bgColors[index % bgColors.length], textColors[index % textColors.length], 'ms-auto']">
                                            <i class="bx bx-info-circle"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0 align-middle">
                                        <tbody class="text-sm">
                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code === 'tryout')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code === 'tryout'))" class="border-b border-slate-50">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-edit text-info font-20 align-middle me-1'></i> Tryout</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.exam_group_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" :href="`/user/exam-groups/${totalDataInCategory.id}/lesson-categories`">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code === 'exam')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code === 'exam'))" class="border-b border-slate-50">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-book text-success font-20 align-middle me-1'></i> Latihan Soal</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.exam_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" :href="`/user/categories/${totalDataInCategory.id}/lesson-categories`">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code === 'module')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code === 'module'))" class="border-b border-slate-50">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-file text-warning font-20 align-middle me-1'></i> Modul / Materi</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.module_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" href="/user/modules">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code === 'video_module')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code === 'video_module'))" class="border-b border-slate-50">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-video-recording text-danger font-20 align-middle me-1'></i> Video Belajar</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.video_module_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" href="/user/video-modules">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code === 'course')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code === 'course'))" class="border-b border-slate-50">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-video-plus text-success font-20 align-middle me-1'></i> Course</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.course_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" href="/user/courses">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code === 'classroom')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code === 'classroom'))">
                                                <td class="py-2 px-3 border-0"><i class='bx bx-chalkboard text-default font-20 align-middle me-1'></i> Live Class</td>
                                                <td class="py-2 px-3 border-0 text-center font-semibold">{{ totalDataInCategory.classroom_count }}</td>
                                                <td class="py-2 px-3 border-0 text-end"><Link class="badge bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" href="/user/classrooms">Lihat</Link></td>
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
    import LayoutUser from '../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import sweet alert2
    import Swal from 'sweetalert2';
    import { push } from 'notivue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Splide, SplideSlide } from '@splidejs/vue-splide';
    import '@splidejs/vue-splide/css';
    import '@splidejs/vue-splide/css/skyblue';
    import '@splidejs/vue-splide/css/sea-green';

    export default {
        // layout
        layout: LayoutUser,
        // register components
        components: {
            Head,
            Link,
            Splide,
            SplideSlide,
        },
        // props
        props: {
            banners: Object,
            totalTransactionPending: Object,
            totalTransactionPaid: Object,
            totalTransactionDone: Object,
            totalTransactionFailed: Object,
            transactions: Object,
            gradeSummaries: Object,
            recentExamGroupUsers: Object,
            recentGrades: Object,
            todayLiveClasses: Object,
            announcementSummaries: Object,
            newsSummaries: Object,
            totalDataInCategories: Object,
            walletSummary: Object,
        },
        data() {
            return {
                canNewsScrollLeft: false,
                canNewsScrollRight: false,
            };
        },
        setup() {
            const bgColors = ['bg-light-primary', 'bg-light-danger', 'bg-light-info', 'bg-light-warning'];
            const textColors = ['text-primary', 'text-danger', 'text-info', 'text-warning'];

            const showPopup = (banner) => {
                Swal.fire({
                    imageUrl: '/storage/upload_files/banners/' + banner.image,
                    imageAlt: banner.name,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: '100%',
                    customClass: {
                        popup: 'custom-popup', // Optional for additional customization
                    },
                });
            }

            return {
                bgColors,
                textColors,
                showPopup
            };
        },
        mounted() {
            this.$nextTick(() => this.updateNewsNavState());
            window.addEventListener('resize', this.updateNewsNavState);
        },
        beforeUnmount() {
            window.removeEventListener('resize', this.updateNewsNavState);
        },
        watch: {
            newsSummaries: {
                handler() {
                    this.$nextTick(() => this.updateNewsNavState());
                },
                deep: true,
            }
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            formatCurrencyNoDecimal(value) {
                const number = Number(value || 0);
                return 'Rp' + number.toLocaleString('id-ID');
            },
            shareReferralLink() {
                const referralCode = this.walletSummary?.referral_code;
                if (!referralCode) {
                    push.warning('Kode referral belum tersedia.');
                    return;
                }

                const referralUrl = `${this.$page.props.setting.app_url}/register?ref=${referralCode}`;

                const copyFallback = () => {
                    const textArea = document.createElement('textarea');
                    textArea.value = referralUrl;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                };

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(referralUrl).then(() => {
                        push.success('Berhasil menyalin kode referral');
                    }).catch(() => {
                        copyFallback();
                        push.success('Berhasil menyalin kode referral');
                    });
                    return;
                }

                copyFallback();
                push.success('Berhasil menyalin kode referral');
            },
            updateNewsNavState() {
                const node = this.$refs.newsScroller;
                if (!node || !this.newsSummaries || !this.newsSummaries.length) {
                    this.canNewsScrollLeft = false;
                    this.canNewsScrollRight = false;
                    return;
                }

                const maxLeft = node.scrollWidth - node.clientWidth;
                this.canNewsScrollLeft = node.scrollLeft > 2;
                this.canNewsScrollRight = node.scrollLeft < (maxLeft - 2);
            },
            onNewsScroll() {
                this.updateNewsNavState();
            },
            scrollNews(direction) {
                const node = this.$refs.newsScroller;
                if (!node) return;

                const amount = Math.max(node.clientWidth * 0.85, 340);
                const offset = direction === 'left' ? -amount : amount;

                node.scrollBy({
                    left: offset,
                    behavior: 'smooth'
                });

                window.setTimeout(() => {
                    this.updateNewsNavState();
                }, 220);
            }
        },
        computed: {
            canDisplayTransactions() {
                const { transactions } = this;
                const user = this.$page.props.auth.user;
                const settings = this.$page.props.setting;

                return (
                    transactions &&
                    user.member_type == 2 &&
                    (
                        settings.enable_practice_question_sales == 1 ||
                        settings.enable_tryout_sales == 1 ||
                        settings.enable_module_material_sales == 1 ||
                        settings.enable_video_module_sales == 1 ||
                        settings.enable_course_sales == 1
                    )
                );
            }
        }
    }
</script>

<style>
    .splide {
        padding: 10px !important;
        margin: 0 !important;
    }

    .custom-popup {
        border-radius: 10px;
    }

    .swal2-close {
        font-size: 32px;
        width: 32px;
        height: 32px;
        line-height: 32px;
        padding: 4px;
        outline: none;
        background: none;
        box-shadow: none;
        transition: none;
    }

    .swal2-close:active,
    .swal2-close:focus {
        outline: none;
        background: none;
        box-shadow: none;
        color: inherit;
    }

    /* Modern Dashboard Styles */
    .bg-gradient-blue-modern {
        background: linear-gradient(135deg, #0061ff 0%, #60efff 100%);
    }

    .bg-gradient-emerald-modern {
        background: linear-gradient(135deg, #1d976c 0%, #93f9b9 100%);
    }

    .bg-gradient-rose-modern {
        background: linear-gradient(135deg, #e52d27 0%, #b31217 100%);
    }

    .bg-gradient-amber-modern {
        background: linear-gradient(135deg, #f09819 0%, #edde5d 100%);
    }

    .card-hover-modern {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-hover-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
    }

    .icon-box-soft-blue { background: rgba(0, 97, 255, 0.1); color: #0061ff; }
    .icon-box-soft-green { background: rgba(29, 151, 108, 0.1); color: #1d976c; }
    .icon-box-soft-rose { background: rgba(229, 45, 39, 0.1); color: #e52d27; }
    .icon-box-soft-amber { background: rgba(240, 152, 25, 0.1); color: #f09819; }
    .icon-box-soft-indigo { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .icon-box-soft-emerald { background: rgba(16, 185, 129, 0.1); color: #10b981; }

    .bg-gradient-indigo-modern {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    }

    .btn-white {
        background: white;
        color: #6366f1;
    }
    .btn-white:hover {
        background: #f8fafc;
        transform: translateY(-1px);
    }

    .btn-outline-white-glass {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }
    .btn-outline-white-glass:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .btn-outline-indigo {
        color: #6366f1;
        border-color: #6366f1;
    }
    .btn-outline-indigo:hover {
        background-color: #6366f1;
        color: white;
    }
    .btn-outline-emerald {
        color: #10b981;
        border-color: #10b981;
    }
    .btn-outline-emerald:hover {
        background-color: #10b981;
        color: white;
    }

    .stat-icon-white {
        font-size: 2.5rem;
        color: rgba(255, 255, 255, 0.5);
    }

    .schedule-frame {
        border: 1px solid #c9cdd4;
        border-radius: 16px;
        overflow: hidden;
        background-color: #fff;
    }

    .schedule-frame__head {
        background-color: var(--bs-primary);
        border-bottom: 1px solid var(--bs-primary);
        padding: 0.85rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .schedule-frame__body {
        min-height: 240px;
        background-color: #fff;
        padding: 1rem;
    }

    .schedule-table-wrap {
        border: 1px solid #eef1f5;
        border-radius: 10px;
    }

    .schedule-empty {
        min-height: 208px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #616875;
        font-size: 1.08rem;
    }

    .news-frame {
        border: 1px solid #c8ccd2;
        border-radius: 16px;
        background-color: #f2f3f5;
        overflow: hidden;
    }

    .news-frame__head {
        background-color: #f2f3f5;
        border-bottom: 1px solid #d7dbe0;
        padding: 0.85rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .news-frame__body {
        min-height: 260px;
        padding: 1rem 1.25rem 1.05rem;
    }

    .news-scroller {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        padding-bottom: 0.25rem;
    }

    .news-scroller::-webkit-scrollbar {
        height: 6px;
    }

    .news-scroller::-webkit-scrollbar-thumb {
        background: #c8cfd8;
        border-radius: 999px;
    }

    .news-card {
        width: 380px;
        min-width: 380px;
        height: 280px;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.35);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.18);
        text-decoration: none;
        scroll-snap-align: start;
        display: flex;
        align-items: flex-end;
        transition: transform 0.2s ease;
    }

    .news-card:hover {
        transform: translateY(-2px);
    }

    .news-card__content {
        width: 100%;
        padding: 1rem;
    }

    .news-card__title {
        color: #ffffff;
        font-size: 1.7rem;
        line-height: 1.15;
        font-weight: 800;
        margin-bottom: 0.6rem;
        text-transform: uppercase;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-card__meta {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.02rem;
    }

    .news-nav-btn {
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 1px solid #d5d9de;
        background-color: #eef0f3;
        color: #9aa1ab;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .news-nav-btn i {
        font-size: 1.15rem;
    }

    .news-nav-btn:hover:not(:disabled) {
        color: #6a7380;
        border-color: #c7cdd6;
        background: #e7eaef;
    }

    .news-nav-btn:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .news-empty {
        min-height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #5e6672;
        font-size: 1.04rem;
    }

    @media (max-width: 575.98px) {
        .schedule-frame__head {
            padding: 0.75rem 0.9rem;
        }

        .schedule-frame__body {
            padding: 0.9rem;
            min-height: 220px;
        }

        .news-frame__head {
            padding: 0.75rem 0.9rem;
        }

        .news-frame__body {
            padding: 0.9rem;
            min-height: 240px;
        }

        .news-card {
            width: 290px;
            min-width: 290px;
            height: 230px;
        }

        .news-card__title {
            font-size: 1.3rem;
            -webkit-line-clamp: 2;
        }
    }

</style>
