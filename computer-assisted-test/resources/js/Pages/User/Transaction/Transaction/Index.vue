<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Transaksi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Transaksi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Transaksi</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Filter Data</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body"> <!-- Change 'text-center' to 'text-end' -->
                    <form>
                        <div class="row">
                            <div class="col-lg-6 col-md-5 mt-1">
                                <label for="search">Kode Transaksi</label>
                                <input type="text" v-model="form.search" class="form-control form-control-sm" id="search" placeholder="Cari Berdasarkan Kode Transaksi....">
                            </div>

                            <div class="col-lg-5 col-md-5 mt-1">
                                <label for="item_type">Jenis Transaksi</label>
                                <select v-model="form.item_type" class="form-select form-select-sm" id="item_type" @change="handleSearch">
                                    <option value="">[ Pilih Jenis Transaksi ]</option>
                                    <option value="App\Models\Exam\Exam" v-if="$page.props.menu_users.some(item => item.code == 'exam' && item.is_active == '1')">Pembelian Latihan Soal</option>
                                    <option value="App\Models\Exam\ExamGroup" v-if="$page.props.menu_users.some(item => item.code == 'tryout' && item.is_active == '1')">Pembelian Tryout</option>
                                    <option value="App\Models\Material\Module" v-if="$page.props.menu_users.some(item => item.code == 'module' && item.is_active == '1')">Pembelian Modul/Materi</option>
                                    <option value="App\Models\Material\VideoModule" v-if="$page.props.menu_users.some(item => item.code == 'video_module' && item.is_active == '1')">Pembelian Video Pembajaran</option>
                                    <option value="App\Models\Material\Classroom" v-if="$page.props.menu_users.some(item => item.code == 'classroom' && item.is_active == '1')">Pembelian Kelas Online</option>
                                    <option value="App\Models\Transaction\Voucher">Pembelian Membership</option>
                                    <option value="App\Models\TopupBalance" v-if="$page.props.setting.payment_methods.some(item => item.code === 'account_balance')">Top Up Saldo</option>
                                    <option value="App\Models\Transaction\VoucherCode" v-if="$page.props.setting.payment_methods.some(item => item.code === 'account_balance')">Redeem Saldo</option>
                                </select>
                            </div>

                            <div class="col-lg-1 col-md-2 mt-1">
                                <label for="end_date">Action</label><br>

                                <Link href="/user/transactions" class="btn btn-danger btn-sm me-2 w-100">
                                    <i class="bx bx-refresh"></i>reset
                                </Link>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Riwayat Transaksi</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size: 10pt;">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!transactions.data.length">
                                    <td colspan="8" align="center">Belum ada transaksi</td>
                                </tr>
                                <tr v-for="(transaction, index) in transactions.data" :key="index">
                                    <td>{{ ++index + (transactions.current_page - 1) * transactions.per_page }}</td>
                                    <td>{{ transaction.code }}</td>
                                    <td>
                                        <span v-if="transaction.item_type == 'App\\Models\\Exam\\Exam'">
                                            <Link :href="`/user/categories/${transaction.item.category_id}/lesson-categories/${transaction.item.lesson_category_id}/lessons/${transaction.item.lesson_id}/exams/${transaction.item.id}`">{{ transaction.description }}</Link>  
                                        </span>
                                        <span v-else-if="transaction.item_type == 'App\\Models\\Exam\\ExamGroup'">
                                            <Link :href="`/user/exam-groups/${transaction.item.category_id}/lesson-categories/${transaction.item.lesson_category_id}/exams/${transaction.item.id}`">{{ transaction.description }}</Link>  
                                        </span>
                                        <span v-else-if="transaction.item_type == 'App\\Models\\Material\\Module'">
                                            <Link :href="`/user/modules/${transaction.item.id}`">{{ transaction.description }}</Link>  
                                        </span>
                                        <span v-else-if="transaction.item_type == 'App\\Models\\Material\\VideoModule'">
                                            <Link :href="`/user/video-modules/${transaction.item.id}`">{{ transaction.description }}</Link>  
                                        </span>
                                        <span v-else-if="transaction.item_type == 'App\\Models\\Material\\Classroom'">
                                            <Link :href="`/user/classrooms/${transaction.item.id}`">{{ transaction.description }}</Link>  
                                        </span>
                                        <span v-else>
                                            {{ transaction.description }}
                                        </span>
                                    </td>
                                    <td>{{ formatDateWithTimeHourMinute(transaction.created_at) }}</td>
                                    <td>{{ formatPrice(transaction.total_payment) }}</td>
                                    <td>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'account_balance'">Saldo Akun</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'automatic_transfer_midtrans'">Transfer Otomatis</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'manual_transfer'">Transfer Manual</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'not_payment_method'">Tanpa Metode Pembayaran</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning" v-if="transaction.transaction_status == 'pending'">Pending</span>
                                        <span class="badge bg-primary" v-if="transaction.transaction_status == 'paid'">Paid</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'expired'">Expired</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'failed'">Failed</span>
                                        <span class="badge bg-success" v-if="transaction.transaction_status == 'done'">Done</span>
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <Link :href="`/user/transactions/${transaction.id}`"><i class='bx bx-grid-alt'></i></Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="transactions.links" align="end" />

                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout user
    import LayoutUser from '../../../../Layouts/Layout.vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import sweet alert2
    import Swal from 'sweetalert2';

    //import ref from vue
    import {
        ref
    } from 'vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    import { reactive, watch } from 'vue';

    import debounce from 'lodash/debounce'

    export default {
        // layout
        layout: LayoutUser,

        // register components
        components: {
            Link,
            Head,
            Pagination
        },

        // props
        props: {
            transactions: Object
        },
        setup() {
            const form = reactive({
                search: ref("" || (new URL(document.location)).searchParams.get('search')),
                item_type: ref((new URL(document.location)).searchParams.get('item_type') || ''),
            });

            const handleSearch = () => {
                Inertia.get(
                    `/user/transactions`,
                    {
                        search: form.search,
                        item_type: form.item_type,
                    },
                    {
                        preserveState: true,     
                        preserveScroll: true,     
                        replace: true
                    }
                )
            }

            const debouncedSearch = debounce(handleSearch, 1000)

            watch(() => form.search, () => {
                debouncedSearch()
            })

            return {
                form,
                handleSearch
            }
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        }
    }
</script>
