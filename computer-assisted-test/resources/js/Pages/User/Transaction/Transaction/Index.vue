<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - {{ t.index.pageTitle }}</title>
    </Head>

    <div class="page-wrapper transaction-page">
        <div class="page-content">
            <div class="transaction-hero mb-4">
                <h2 class="hero-title mb-1">{{ t.index.heroTitle }}</h2>
                <p class="hero-subtitle mb-0">{{ t.index.heroSubtitle }}</p>
            </div>

            <div class="card border-0 shadow-sm mb-4 filter-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0 fw-bold">{{ t.index.filterTitle }}</h6>
                        <Link href="/user/transactions" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="bx bx-refresh me-1"></i> {{ t.common.reset }}
                        </Link>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label for="search" class="form-label form-label-modern">{{ t.index.searchLabel }}</label>
                            <div class="search-input-wrap">
                                <i class="bx bx-search"></i>
                                <input
                                    type="text"
                                    v-model="form.search"
                                    class="form-control form-control-modern"
                                    id="search"
                                    :placeholder="t.index.searchPlaceholder"
                                >
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="item_type" class="form-label form-label-modern">{{ t.index.itemTypeLabel }}</label>
                            <select v-model="form.item_type" class="form-select form-select-modern" id="item_type">
                                <option value="">{{ t.index.itemTypePlaceholder }}</option>
                                <option value="App\\Models\\Exam\\Exam" v-if="$page.props.menu_users.some(item => item.code == 'exam' && item.is_active == '1')">Pembelian Latihan Soal</option>
                                <option value="App\\Models\\Exam\\ExamGroup" v-if="$page.props.menu_users.some(item => item.code == 'tryout' && item.is_active == '1')">Pembelian Tryout</option>
                                <option value="App\\Models\\Material\\Module" v-if="$page.props.menu_users.some(item => item.code == 'module' && item.is_active == '1')">Pembelian Modul / Materi</option>
                                <option value="App\\Models\\Material\\VideoModule" v-if="$page.props.menu_users.some(item => item.code == 'video_module' && item.is_active == '1')">Pembelian Video Pembelajaran</option>
                                <option value="App\\Models\\Material\\Classroom" v-if="$page.props.menu_users.some(item => item.code == 'classroom' && item.is_active == '1')">Pembelian Kelas Online</option>
                                <option value="App\\Models\\Transaction\\Voucher">Pembelian Membership</option>
                                <option value="App\\Models\\TopupBalance" v-if="$page.props.setting.payment_methods.some(item => item.code === 'account_balance')">Top Up Saldo</option>
                                <option value="App\\Models\\Transaction\\VoucherCode" v-if="$page.props.setting.payment_methods.some(item => item.code === 'account_balance')">Redeem Saldo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm transaction-list-card">
                <div class="card-header bg-transparent border-0 p-4 pb-2 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold">{{ t.index.listTitle }}</h6>
                    <span class="text-muted small">{{ transactions.total ?? 0 }} transaksi</span>
                </div>

                <div class="card-body p-4 pt-2">
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="70">No</th>
                                    <th width="170">Kode</th>
                                    <th>Keterangan</th>
                                    <th width="180">Tanggal</th>
                                    <th width="150">Total</th>
                                    <th width="170">{{ t.common.method }}</th>
                                    <th width="120">{{ t.common.status }}</th>
                                    <th width="80">{{ t.index.actionTitle }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!transactions.data.length">
                                    <td colspan="8" class="text-center py-4 text-muted">{{ t.index.empty }}</td>
                                </tr>
                                <tr v-for="(transaction, index) in transactions.data" :key="transaction.id">
                                    <td>
                                        <span class="number-badge">{{ rowNumber(index) }}</span>
                                    </td>
                                    <td class="fw-semibold">{{ transaction.code }}</td>
                                    <td>
                                        <Link v-if="itemHref(transaction)" :href="itemHref(transaction)">
                                            {{ transaction.description }}
                                        </Link>
                                        <span v-else>{{ transaction.description }}</span>
                                    </td>
                                    <td>{{ formatDateWithTimeHourMinute(transaction.created_at) }}</td>
                                    <td class="fw-bold">{{ formatPrice(transaction.total_payment) }}</td>
                                    <td>
                                        <span class="badge method-badge">{{ paymentMethodLabel(transaction.payment_method) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge" :class="statusClass(transaction.transaction_status)">
                                            {{ statusLabel(transaction.transaction_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <Link :href="`/user/transactions/${transaction.id}`" class="action-link" :title="t.common.detail">
                                            <i class='bx bx-grid-alt'></i>
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid gap-3 d-lg-none">
                        <div v-if="!transactions.data.length" class="mobile-item text-center text-muted py-4">
                            {{ t.index.empty }}
                        </div>

                        <div v-for="(transaction, index) in transactions.data" :key="`m-${transaction.id}`" class="mobile-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="number-badge">{{ rowNumber(index) }}</span>
                                        <span class="fw-semibold">{{ transaction.code }}</span>
                                    </div>
                                    <small class="text-muted">{{ formatDateWithTimeHourMinute(transaction.created_at) }}</small>
                                </div>
                                <span class="badge" :class="statusClass(transaction.transaction_status)">
                                    {{ statusLabel(transaction.transaction_status) }}
                                </span>
                            </div>

                            <div class="mb-2">
                                <Link v-if="itemHref(transaction)" :href="itemHref(transaction)">
                                    {{ transaction.description }}
                                </Link>
                                <span v-else>{{ transaction.description }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted">{{ t.common.method }}</div>
                                    <span class="badge method-badge">{{ paymentMethodLabel(transaction.payment_method) }}</span>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted">{{ t.common.total }}</div>
                                    <div class="fw-bold text-primary">{{ formatPrice(transaction.total_payment) }}</div>
                                </div>
                            </div>

                            <div class="mt-3 text-end">
                                <Link :href="`/user/transactions/${transaction.id}`" class="btn btn-primary btn-sm rounded-pill px-3">
                                    {{ t.common.detail }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <Pagination :links="transactions.links" align="end" class="mt-3" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue';
import Pagination from '../../../../Components/Pagination.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { reactive, watch } from 'vue';
import debounce from 'lodash/debounce';
import { transactionText } from '../../../../lang/id/transaction';

export default {
    layout: LayoutUser,

    components: {
        Link,
        Head,
        Pagination,
    },

    props: {
        transactions: Object,
    },

    setup() {
        const form = reactive({
            search: ref((new URL(document.location)).searchParams.get('search') || ''),
            item_type: ref((new URL(document.location)).searchParams.get('item_type') || ''),
        });

        const handleSearch = () => {
            Inertia.get(
                '/user/transactions',
                {
                    search: form.search,
                    item_type: form.item_type,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                }
            );
        };

        const debouncedSearch = debounce(handleSearch, 500);

        watch(
            () => [form.search, form.item_type],
            () => {
                debouncedSearch();
            }
        );

        return {
            form,
            t: transactionText,
        };
    },

    methods: {
        rowNumber(index) {
            return index + 1 + (this.transactions.current_page - 1) * this.transactions.per_page;
        },
        formatPrice(value) {
            const val = (value / 1).toFixed(2).replace('.', ',');
            return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },
        paymentMethodLabel(method) {
            if (method === 'account_balance') return this.t.paymentMethod.account_balance;
            if (method === 'automatic_transfer_midtrans') return this.t.paymentMethod.automatic_transfer_midtrans;
            if (method === 'manual_transfer') return this.t.paymentMethod.manual_transfer;
            if (method === 'not_payment_method') return this.t.paymentMethod.not_payment_method;
            return method ?? '-';
        },
        statusLabel(status) {
            if (status === 'pending') return this.t.status.pending;
            if (status === 'paid') return this.t.status.paid;
            if (status === 'expired') return this.t.status.expired;
            if (status === 'failed') return this.t.status.failed;
            if (status === 'done') return this.t.status.done;
            return status ?? '-';
        },
        statusClass(status) {
            if (status === 'pending') return 'bg-warning text-dark';
            if (status === 'paid') return 'bg-primary';
            if (status === 'expired') return 'bg-danger';
            if (status === 'failed') return 'bg-danger';
            if (status === 'done') return 'bg-success';
            return 'bg-secondary';
        },
        itemHref(transaction) {
            if (!transaction || !transaction.item) return null;

            if (transaction.item_type === 'App\\Models\\Exam\\Exam') {
                return `/user/categories/${transaction.item.category_id}/lesson-categories/${transaction.item.lesson_category_id}/lessons/${transaction.item.lesson_id}/exams/${transaction.item.id}`;
            }

            if (transaction.item_type === 'App\\Models\\Exam\\ExamGroup') {
                return `/user/exam-groups/${transaction.item.category_id}/lesson-categories/${transaction.item.lesson_category_id}/exams/${transaction.item.id}`;
            }

            if (transaction.item_type === 'App\\Models\\Material\\Module') {
                return `/user/modules/${transaction.item.id}`;
            }

            if (transaction.item_type === 'App\\Models\\Material\\VideoModule') {
                return `/user/video-modules/${transaction.item.id}`;
            }

            if (transaction.item_type === 'App\\Models\\Material\\Classroom') {
                return `/user/classrooms/${transaction.item.id}`;
            }

            return null;
        },
    },
};
</script>

<style scoped>
.transaction-page {
    background: radial-gradient(1200px 300px at 20% -15%, rgba(0, 140, 255, 0.08) 0%, rgba(0, 140, 255, 0) 60%), #f8fbff;
}

.hero-title {
    color: #1f2430;
    font-weight: 700;
}

.hero-subtitle {
    color: #667085;
}

.filter-card,
.transaction-list-card {
    border-radius: 16px;
}

.form-label-modern {
    font-size: 0.85rem;
    font-weight: 600;
    color: #454b5a;
    margin-bottom: 0.45rem;
}

.form-select-modern,
.form-control-modern {
    border: 1px solid #e3e6ef;
    border-radius: 12px;
    min-height: 44px;
    font-size: 0.92rem;
}

.form-select-modern:focus,
.form-control-modern:focus {
    border-color: rgba(0, 140, 255, 0.45);
    box-shadow: 0 0 0 0.2rem rgba(0, 140, 255, 0.15);
}

.search-input-wrap {
    position: relative;
}

.search-input-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #8a92a6;
    font-size: 1.15rem;
}

.search-input-wrap input {
    padding-left: 2.5rem;
}

.table-modern thead th {
    border-bottom: 1px solid #e8e8ef;
    color: #5c6272;
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.table-modern tbody td {
    border-color: #f0f1f5;
}

.number-badge {
    min-width: 32px;
    height: 32px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
    background: rgba(0, 140, 255, 0.12);
    color: #008cff;
}

.method-badge {
    background: rgba(0, 140, 255, 0.12);
    color: #008cff;
    font-weight: 600;
}

.action-link {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 140, 255, 0.1);
    color: #008cff;
    font-size: 1.1rem;
}

.action-link:hover {
    background: rgba(0, 140, 255, 0.18);
}

.mobile-item {
    border: 1px solid #eceff5;
    border-radius: 12px;
    background: #fff;
    padding: 12px;
}
</style>
