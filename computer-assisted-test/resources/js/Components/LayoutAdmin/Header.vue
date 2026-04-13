<template>
    <!--start header -->
    <header>
        <div class="topbar d-flex align-items-center">
            <nav class="navbar navbar-expand gap-3 w-100 px-4">
                <div class="mobile-toggle-menu d-lg-none cursor-pointer">
                    <i class='bx bx-menu fs-2'></i>
                </div>
                
                <div class="search-bar flex-grow-1 d-none d-md-block">
                    <!-- Placeholder for search or page title if needed -->
                </div>

                <div class="top-menu ms-auto d-flex align-items-center gap-2">
                    <Link v-if="$page.props.auth.user.level == 2 && $page.props.setting.category_access == 2 && $page.props.setting.allow_category_access_changes == 1" 
                          :href="`/user/users/${$page.props.auth.user.id}/edit`" 
                          class="btn btn-primary btn-sm rounded-pill px-3 hide-on-mobile">
                        Ubah Kategori
                    </Link>
                    <a v-if="$page.props.auth.user.level == 2" target="_blank" 
                       :href="`https://wa.me/${$page.props.setting.whatsapp_number}`" 
                       class="btn btn-light-success btn-sm rounded-pill px-3 d-flex align-items-center gap-1">
                        <i class='bx bxl-whatsapp fs-5'></i>
                        <span class="hide-on-mobile">WA Support</span>
                    </a>
                </div>

                <div class="user-box dropdown px-3">
                    <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret gap-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-info d-none d-md-block text-end">
                            <p class="user-name mb-0">{{ $page.props.auth.user.name }}</p>
                            <p class="designattion mb-0 text-muted">{{ $page.props.auth.user.email }}</p>
                        </div>
                        <img v-if="$page.props.auth.user.photo"
                             :src="'/storage/upload_files/photos/' + $page.props.auth.user.photo"
                             class="user-img-modern" alt="User" />
                        <img v-else src="/assets/images/avatars/user.png" class="user-img-modern" alt="User" />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-premium border-0 rounded-4 mt-2">
                        <li class="p-2">
                            <div class="dropdown-item-text border-bottom pb-2 mb-2 d-md-none">
                                <p class="fw-bold mb-0">{{ $page.props.auth.user.name }}</p>
                                <p class="small text-muted mb-0">{{ $page.props.auth.user.email }}</p>
                            </div>
                        </li>
                        <li v-if="$page.props.auth.user.level == 2 ">
                            <Link class="dropdown-item d-flex align-items-center gap-2 py-2" :href="`/user/users/${$page.props.auth.user.id}/edit`">
                                <i class='bx bx-user fs-5'></i><span>Edit Profil</span>
                            </Link>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li v-if="$page.props.auth.user.level == 1">
                            <Link class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger" href="/admin/logout" method="POST" as="button">
                                <i class='bx bx-log-out-circle fs-5'></i><span>Logout</span>
                            </Link>
                        </li>
                        <li v-else>
                            <Link class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger" href="/logout" method="POST" as="button">
                                <i class='bx bx-log-out-circle fs-5'></i><span>Logout</span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
</template>

<script>
    // import link
    import { Link } from '@inertiajs/inertia-vue3';

    export default {
        // register component
        components: {
            Link
        }
    }
</script>

<style>
    @media (max-width: 768px) {
        .hide-on-mobile {
            display: none !important;
        }
    }
</style>
