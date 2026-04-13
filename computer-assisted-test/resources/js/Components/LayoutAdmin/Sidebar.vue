<template>
    <!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header d-flex align-items-center justify-content-between px-3">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <div class="logo-icon-wrapper p-1 bg-white rounded-3 shadow-sm" v-if="$page.props.setting && $page.props.setting.logo">
                        <img v-bind:src="'/storage/upload_files/settings/' + $page.props.setting.logo" style="height:35px; width: auto;"/>
                    </div>
                    <h4 class="logo-text fw-bold mb-0 text-primary" style="font-size:18px;">{{ $page.props.setting.app_name ?? 'JUARA' }}</h4>
                </div>
				<div class="toggle-icon ms-auto d-none d-lg-flex cursor-pointer fs-4 text-primary">
                    <i class='bx bx-chevron-left-circle'></i>
                </div>
			</div>
			<!--navigation-->
            <!-- admin -->
			<ul class="metismenu" id="menu" v-if="$page.props.auth.user.level == 1 || $page.props.auth.user.level == 3">
                <li>
					<Link href="/admin/dashboard" class="menu-clicked" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-home-smile text-indigo-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title">Dashboard</div>
					</Link>
				</li>
				<li class="menu-label">Manajemen</li>
				<li v-if="$page.props.auth.user.level == 1">
					<a href="javascript:;" class="has-arrow" title="Master Data" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-customize text-blue-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title">Master Data</div>
					</a>
					<ul>
						<li>
                            <Link href="/admin/categories" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Kategori Peminatan
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/sub-categories" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Sub Kategori Peminatan
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/users" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>User
					        </Link>
                        </li>
					</ul>
				</li>
				<li v-if="$page.props.auth.user.level == 1">
					<a class="has-arrow" href="javascript:;" title="Mata Pelajaran" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-book-bookmark text-sky-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title">Mata Pelajaran</div>
					</a>
					<ul>
                        <li>
                            <Link href="/admin/lesson-categories" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Kategori Mata Pelajaran
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/lessons" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Mata Pelajaran
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/value-category-groups" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Kategori Penilaian
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/question-titles" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Paket Soal
					        </Link>
                        </li>
					</ul>
				</li>
                <li v-if="$page.props.auth.user.level == 3">
                    <Link href="/admin/question-titles" class="menu-clicked" title="Paket Soal" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-folder'></i>
						</div>
						<div class="menu-title">Paket Soal</div>
					</Link>
                </li>
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => item.code == 'exam' && item.is_active == '1')">
                    <Link href="/admin/exams" class="menu-clicked" title="Latihan Soal" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-pencil text-rose-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title">Latihan Soal</div>
					</Link>
                </li>
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => item.code == 'tryout' && item.is_active == '1')">
                    <Link href="/admin/exam-groups" class="menu-clicked" title="Tryout" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-medal text-rose-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title">Tryout</div>
					</Link>
                </li>
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => item.code == 'announcement' && item.is_active == '1')">
                    <Link href="/admin/announcements" class="menu-clicked" title="Pengumuman" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-bell text-emerald-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title">Pengumuman</div>
					</Link>
                </li>
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => item.code == 'faq' && item.is_active == '1')">
                    <Link href="/admin/faqs" class="menu-clicked" title="Faq" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-help-circle text-emerald-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title">Faq</div>
					</Link>
                </li>
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => item.code == 'news' && item.is_active == '1')">
                    <Link href="/admin/news" class="menu-clicked" title="Berita" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-news text-emerald-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title">Berita</div>
					</Link>
                </li>
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => item.code == 'psychology_calculator' && item.is_active == '1')">
                    <Link href="/admin/psychology-calculators" class="menu-clicked" title="Kalkulator Psikologi" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-calculator text-emerald-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Kalkulator Psikologi</div>
                    </Link>
                </li> 
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => ['module', 'video_module', 'course', 'classroom'].includes(item.code) && item.is_active == '1')" class="menu-label">Materi</li>
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => item.code == 'module' && item.is_active == '1')">
                    <Link href="/admin/modules" class="menu-clicked" title="Modul / Materi" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-book-open text-amber-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title">Modul / Materi</div>
					</Link>
                </li> 
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => item.code == 'video_module' && item.is_active == '1')">
                    <Link href="/admin/video-modules" class="menu-clicked" title="Video Pembelajaran" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-video text-amber-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Video Pembelajaran</div>
                    </Link>
                </li>
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => item.code == 'course' && item.is_active == '1')">
                    <Link href="/admin/courses" class="menu-clicked" title="Course" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-play-circle text-amber-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Course</div>
                    </Link>
                </li>
                <li v-if="$page.props.menu_users && $page.props.menu_users.some(item => item.code == 'classroom' && item.is_active == '1')">
                    <Link href="/admin/classrooms" class="menu-clicked" title="Ruang Kelas" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-chalkboard text-amber-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Ruang Kelas</div>
                    </Link>
                </li>
                <li v-if="$page.props.auth.user.level == 1" class="menu-label">Transaksi</li>
                <li>
					<Link v-if="$page.props.auth.user.level == 1" href="/admin/member-categories" title="Kategori Member" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-crown text-green-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title menu-clicked">Kategori Member</div>
					</Link>
				</li>
                <li>
					<Link v-if="$page.props.auth.user.level == 1" href="/admin/voucher-codes" title="Kode Voucher Gratis" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-barcode-reader text-green-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title menu-clicked">Kode Voucher Gratis</div>
					</Link>
				</li>
                <li>
					<Link v-if="$page.props.auth.user.level == 1" href="/admin/vouchers" title="Paket Membership" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-purchase-tag text-green-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title menu-clicked">Paket Membership</div>
					</Link>
				</li>
                <li>
                    <Link v-if="$page.props.auth.user.level == 1" href="/admin/transactions" title="Transaksi" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-transfer text-green-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title menu-clicked">Transaksi</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1 && $page.props.setting.enable_affiliate_feature == 1">
					<a href="javascript:;" class="has-arrow" title="Afiliasi" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-share-alt text-orange-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title">Afiliasi</div>
					</a>
					<ul>
						<li>
                            <Link href="/admin/affiliates/commissions" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Komisi
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/affiliates/withdrawals" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Pengajuan Penarikan
					        </Link>
                        </li>
					</ul>
				</li>
                <li v-if="$page.props.auth.user.level == 1" class="menu-label">Setting</li>
                <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/payment-methods" title="Metode Pembayaran" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-check-double text-slate-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title menu-clicked">Metode Pembayaran</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/banks" title="Bank" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-credit-card text-slate-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title menu-clicked">Bank</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/banners" title="Banner" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-image text-slate-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title menu-clicked">Banner</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/social-groups" title="Grup Media Sosial" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-group text-slate-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title menu-clicked">Grup Media Sosial</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/internet-protocol-whitelists" title="IP Whitelist" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-shield-quarter text-slate-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title menu-clicked">IP Whitelist</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/settings/applications" title="Setting" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-cog text-slate-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i>
						</div>
						<div class="menu-title menu-clicked">Setting</div>
					</Link>
				</li>
                <!-- <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/activity-logs">
						<div class="parent-icon"><i class='bx bx-file'></i>
						</div>
						<div class="menu-title menu-clicked">Aktivity Log</div>
					</Link>
				</li> -->
                <li class="menu-label">&nbsp;</li>
                <li class="menu-label">&nbsp;</li>
			</ul>

            <!-- user -->
            <ul class="metismenu px-3" id="menu" v-if="$page.props.auth.user.level == 2">
                <li class="mb-4 mt-3">
                    <div class="card-modern-user p-3 rounded-4 shadow-sm border-0 position-relative overflow-hidden" 
                         :class="[$page.props.setting.sidebar_color && $page.props.setting.header_color ? 'bg-primary text-white' : 'bg-light']">
                        <div class="position-relative z-1 text-center">
                            <p class="mb-1 small opacity-75">Halo, Selamat Belajar! ddddd</p>
                            <h6 class="fw-bold mb-3">{{ $page.props.auth.user.name }}</h6>
                            
                            <div v-if="canDisplayTransactions && $page.props.setting.payment_methods && $page.props.setting.payment_methods.some(item => item.code == 'account_balance')" 
                                 class="balance-box mb-3 p-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-25">
                                <p class="mb-0 small opacity-75">Saldo Akun</p>
                                <h5 class="mb-0 fw-black">Rp {{ formatPrice($page.props.auth.user.account_balance) }}</h5>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <button v-if="canDisplayTransactions && $page.props.setting.payment_methods && $page.props.setting.payment_methods.some(item => item.code == 'account_balance')" 
                                        class="btn btn-primary btn-sm rounded-pill py-2 shadow-sm" 
                                        @click="topUp()">
                                    <i class='bx bx-plus-circle me-1'></i> Top Up Saldo
                                </button>
                                <button v-if="$page.props.setting.social_group_mode == 1" 
                                        class="btn btn-success btn-sm rounded-pill py-2 shadow-sm" 
                                        @click="socialGroup()">
                                    <i class='bx bxl-whatsapp me-1'></i> Grup Belajar
                                </button>
                                <button @click="changeCategorySelected($page.props.auth.user.id)" 
                                        v-if="$page.props.auth.user.level == 2 && $page.props.setting.category_access == 2 && $page.props.setting.allow_category_access_changes == 1" 
                                        class="btn btn-outline-primary btn-sm rounded-pill py-2 show-on-mobile">
                                    <i class='bx bx-category me-1'></i> Ganti Kategori
                                </button>
                            </div>
                        </div>
                    </div>
				</li>
                <li class="menu-label">Navigation & Tools</li>
				<li>
					<Link href="/user/dashboard" class="menu-clicked" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-home-smile text-violet-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
						<div class="menu-title">Dashboard</div>
					</Link>
				</li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code == 'announcement')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code == 'announcement'))">
                    <Link href="/user/announcements" class="menu-clicked" title="Pengumuman" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-bell text-violet-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
						<div class="menu-title">Pengumuman</div>
					</Link>
                </li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code == 'faq')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code == 'faq'))">
                    <Link href="/user/faqs" class="menu-clicked" title="Faq" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-help-circle text-violet-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
						<div class="menu-title">Faq</div>
					</Link>
                </li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code == 'news')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code == 'news'))">
                    <Link href="/user/news" class="menu-clicked" title="Berita" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-news text-violet-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
						<div class="menu-title">Berita</div>
					</Link>
                </li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code == 'psychology_calculator')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code == 'psychology_calculator'))">
					<Link href="/user/psychology-calculators" class="menu-clicked" title="Kalkulator Psikologi" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-calculator text-violet-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
						<div class="menu-title">Kalkulator Psikologi</div>
					</Link>
				</li> 
                <li class="menu-label" v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => ['exam', 'tryout'].includes(item.code))) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => ['exam', 'tryout'].includes(item.code)))">Ujian</li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code == 'tryout')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code == 'tryout'))">
					<a href="javascript:;" class="has-arrow" title="Tryout" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-medal text-rose-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
						<div class="menu-title">Tryout</div>
					</a>
					<ul>
                        <li v-if="$page.props.voucherCategories.length > 1">
                            <Link href="/user/exam-groups" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Tryout
					        </Link>
                        </li>
                        <li v-else v-for="(category, index) in $page.props.voucherCategories" :key="index">
                            <Link :href="`/user/exam-groups/${category.id}/lesson-categories`" class="menu-clicked">
                            <i class="bx bx-right-arrow-alt"></i>Tryout
                            </Link>
                        </li>
                        <li>
                            <Link href="/user/exam-groups/histories" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Riwayat Tryout
					        </Link>
                        </li>
					</ul>
				</li>  
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code == 'exam')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code == 'exam'))">
					<a href="javascript:;" class="has-arrow" title="Latihan Soal" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-pencil text-rose-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
						<div class="menu-title">Latihan Soal</div>
					</a>
					<ul>
						<li v-if="$page.props.voucherCategories.length > 1">
                            <Link href="/user/categories" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Latihan Soal
					        </Link>
                        </li>
                        <li v-else v-for="(category, index) in $page.props.voucherCategories" :key="index">
                            <Link :href="`/user/categories/${category.id}/lesson-categories`" class="menu-clicked">
                            <i class="bx bx-right-arrow-alt"></i>Latihan Soal
                            </Link>
                        </li>
                        <li>
                            <Link href="/user/grades" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Riwayat Latihan Soal
					        </Link>
                        </li>
					</ul>
				</li>
                <li class="menu-label" v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => ['module', 'video_module', 'course', 'classroom'].includes(item.code))) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => ['module', 'video_module', 'course', 'classroom'].includes(item.code)))">Materi</li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code == 'module')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code == 'module'))">
                    <Link href="/user/modules" class="menu-clicked" title="Modul / Materi" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-book-open text-amber-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Modul / Materi</div>
                    </Link>
                </li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code == 'video_module')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code == 'video_module'))">
                    <Link href="/user/video-modules" class="menu-clicked" title="Video Pembelajaran" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-video text-amber-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Video Pembelajaran</div>
                    </Link>
                </li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code == 'course')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code == 'course'))">
                    <Link href="/user/courses" class="menu-clicked" title="Course" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-play-circle text-amber-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Course</div>
                    </Link>
                </li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access && $page.props.setting.free_member_access.some(item => item.code == 'classroom')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access && $page.props.setting.paid_member_access.some(item => item.code == 'classroom'))">
                    <Link href="/user/classrooms" class="menu-clicked" title="Ruang Kelas" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-chalkboard text-amber-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Ruang Kelas</div>
                    </Link>
                </li>
                <li class="menu-label" v-if="canDisplayTransactions">{{ hasAnyMembershipCategory ? 'Beli Paket' : 'Transaksi' }}</li>
                <li v-if="canDisplayTransactions && hasAnyMembershipCategory">
                    <a href="javascript:;" class="has-arrow" title="Paket Membership" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-purchase-tag text-green-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Paket Membership</div>
                    </a>
                    <ul>
                        <li v-for="(category, index) in $page.props.voucherCategories" :key="index">
                            <Link :href="`/user/vouchers?category_id=${category.id}`" class="menu-clicked" v-if="canDisplayMembershipTransactions(category)">
                                <i class="bx bx-right-arrow-alt"></i>{{ category.name }}
                            </Link>
                        </li>
                    </ul>
                </li>
                <li v-if="canDisplayTransactions">
                    <Link href="/user/transactions" class="menu-clicked" title="Riwayat Transaksi" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-transfer text-green-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Riwayat Transaksi</div>
                    </Link>
                </li>
                <li v-if="$page.props.setting.enable_affiliate_feature == 1" class="menu-label">Member get Member</li>
                <li v-if="$page.props.setting.enable_affiliate_feature == 1">
                    <Link href="/user/affiliates/balances" class="menu-clicked" title="Afiliasi" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-share-alt text-orange-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Afiliasi</div>
                    </Link>
                </li>
                <li v-if="$page.props.setting.enable_affiliate_feature == 1">
                    <Link href="/user/affiliates/user-banks" class="menu-clicked" title="Akun Bank" data-bs-toggle="tooltip" data-bs-placement="right">
                        <div class="parent-icon"><i class='bx bx-credit-card text-orange-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
                        <div class="menu-title">Akun Bank</div>
                    </Link>
                </li>
                <li class="menu-label">Manajemen Akun</li>
                <li>
					<Link href="/user/users" class="menu-clicked" title="Profil" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-user-circle text-slate-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
						<div class="menu-title">Profil</div>
					</Link>
				</li>     
                <li>
					<Link href="/logout" method="POST" class="menu-clicked" title="Log Out" data-bs-toggle="tooltip" data-bs-placement="right">
						<div class="parent-icon"><i class='bx bx-log-out-circle text-red-500 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm'></i></div>
						<div class="menu-title">Log Out</div>
					</Link>
				</li>         
                <li class="menu-label">&nbsp;</li>
                <li class="menu-label">&nbsp;</li>
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
</template>

<script>
    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // register component
        components: {
            Link
        },
        props: {
            isCollapsed: Boolean
        },
        watch: {
            isCollapsed(val) {
                if (val) {
                    this.initTooltips();
                } else {
                    this.destroyTooltips();
                }
            }
        },
        mounted() {
            if (this.isCollapsed) {
                this.initTooltips();
            }
        },
        beforeUnmount() {
            this.destroyTooltips();
        },
        setup() {
            const topUp = () => {
                $(".wrapper").removeClass("toggled");
                Inertia.get('/user/account-balances');
            }

            const socialGroup = () => {
                $(".wrapper").removeClass("toggled");
                Inertia.get('/user/social-groups');
            }

            const changeCategorySelected = (userId) => {
                $(".wrapper").removeClass("toggled");
                Inertia.get(`/user/users/${userId}/edit`);
            }

            return {
                topUp,
                socialGroup,
                changeCategorySelected
            }
        },  
        methods: {
            initTooltips() {
                this.$nextTick(() => {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                });
            },
            destroyTooltips() {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(el => {
                    const tooltip = bootstrap.Tooltip.getInstance(el);
                    if (tooltip) {
                        tooltip.dispose();
                    }
                });
            },
            formatPrice(value) {
                let val = (value/1).toFixed().replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            canDisplayMembershipTransactions(category) {
                const user = this.$page.props.auth.user;
                const setting = this.$page.props.setting;
                const transactionSaleType = setting.transaction_sale_type;
                const source = transactionSaleType == 1 ? setting : category;

                return (
                    user.member_type == 2 &&
                    (
                        source.practice_question_sales_type == 2 || source.practice_question_sales_type == 3 ||
                        source.tryout_sales_type == 2 || source.tryout_sales_type == 3 ||
                        source.module_material_sales_type == 2 || source.module_material_sales_type == 3 ||
                        source.video_module_sales_type == 2 || source.video_module_sales_type == 3 ||
                        source.course_sales_type == 2 || source.course_sales_type == 3 ||
                        source.classroom_sales_type == 2 || source.classroom_sales_type == 3
                    )
                );
            }

        },
        computed: {
            canDisplayTransactions() {
                const user = this.$page.props.auth.user;
                return user.member_type == 2;
            },
            hasAnyMembershipCategory() {
                const user = this.$page.props.auth.user;
                const setting = this.$page.props.setting;
                const transactionSaleType = setting.transaction_sale_type;

                const isMembership = (source) => (
                    user.member_type == 2 &&
                    (
                        source.practice_question_sales_type == 2 || source.practice_question_sales_type == 3 ||
                        source.tryout_sales_type == 2 || source.tryout_sales_type == 3 ||
                        source.module_material_sales_type == 2 || source.module_material_sales_type == 3 ||
                        source.video_module_sales_type == 2 || source.video_module_sales_type == 3 ||
                        source.course_sales_type == 2 || source.course_sales_type == 3 ||
                        source.classroom_sales_type == 2 || source.classroom_sales_type == 3
                    )
                );

                if (transactionSaleType == 1) {
                    return isMembership(setting);
                }

                return this.$page.props.voucherCategories && this.$page.props.voucherCategories.some(category => isMembership(category));
            }
        }
    }
</script>

<style>
    @media (min-width: 768px) { /* Ukuran max 768px untuk mobile */
        .show-on-mobile {
            display: none !important;
        }
    }
</style>