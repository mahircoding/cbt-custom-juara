<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - {{ t.invoice.pageTitle }}</title>
    </Head>

    <div class="page-wrapper invoice-page">
        <div class="page-content">
            <div class="invoice-hero mb-4 d-flex flex-column flex-lg-row justify-content-between gap-2">
                <div>
                    <h2 class="hero-title mb-1">{{ t.invoice.heroTitle }}</h2>
                    <p class="hero-subtitle mb-0">{{ t.invoice.heroSubtitle }}</p>
                </div>
                <div class="d-flex gap-2">
                    <Link href="/user/transactions" class="btn btn-outline-primary btn-sm rounded-pill px-3">{{ t.common.back }}</Link>
                    <button class="btn btn-primary btn-sm rounded-pill px-3" @click="printInvoice">
                        <i class='bx bx-printer me-1'></i> {{ t.invoice.print }}
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-start align-items-lg-center">
                    <div>
                        <span class="badge status-pill" :class="statusClass(transaction.transaction_status)">
                            {{ statusText(transaction.transaction_status) }}
                        </span>
                        <div class="small text-muted mt-2">{{ t.invoice.invoiceNumber }}</div>
                        <div class="fw-bold fs-5">{{ transaction.code }}</div>
                    </div>
                    <div class="text-lg-end">
                        <div class="fw-bold">{{ $page.props.setting.app_name ?? '-' }}</div>
                        <div class="small text-muted">{{ $page.props.setting.address ?? '-' }}</div>
                        <div class="small text-muted">{{ $page.props.setting.whatsapp_number ?? '-' }}</div>
                        <div class="small text-muted">{{ $page.props.setting.email ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4 invoice-card" id="invoice-print-area">
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-lg-7">
                            <div class="small text-muted mb-1">{{ t.invoice.invoiceFor }}</div>
                            <div class="fw-bold fs-6">{{ transaction.user.name }}</div>
                            <div class="small text-muted">
                                {{ transaction.user.student && transaction.user.student.address ? transaction.user.student.address : '-' }}
                                {{ transaction.user.student && transaction.user.student.village ? ', ' + transaction.user.student.village.name : '' }}
                                {{ transaction.user.student && transaction.user.student.district ? ', Kec. ' + transaction.user.student.district.name : '' }}
                                {{ transaction.user.student && transaction.user.student.city ? ', ' + transaction.user.student.city.name : '' }}
                                {{ transaction.user.student && transaction.user.student.province ? ', ' + transaction.user.student.province.name : '' }}
                            </div>
                            <div class="small text-muted">{{ transaction.user.student && transaction.user.student.phone_number ? transaction.user.student.phone_number : '-' }}</div>
                            <div class="small text-muted">{{ transaction.user.email ?? '-' }}</div>
                        </div>
                        <div class="col-12 col-lg-5 text-lg-end">
                            <div class="small text-muted mb-1">{{ t.common.date }}</div>
                            <div class="fw-semibold">{{ formatDateWithTimeHourMinute(transaction.created_at) }}</div>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="70">No</th>
                                    <th>Deskripsi</th>
                                    <th width="180" class="text-end">Harga</th>
                                    <th width="180" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>{{ transaction.description }}</td>
                                    <td class="text-end">{{ formatPrice(transaction.total_payment) }}</td>
                                    <td class="text-end fw-bold">{{ formatPrice(transaction.total_payment) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-end">{{ t.invoice.subtotal }}</td>
                                    <td class="text-end">{{ formatPrice(transaction.total_payment) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-end">{{ t.invoice.taxZero }}</td>
                                    <td class="text-end">Rp.0</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-end fw-bold">{{ t.invoice.grandTotal }}</td>
                                    <td class="text-end fw-bold text-primary">{{ formatPrice(transaction.total_payment) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-muted small">{{ t.invoice.footerNote }}</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" v-if="transaction.transaction_status == 'pending'">
                <div class="card-header border-0 bg-transparent p-4 pb-2">
                    <h6 class="mb-0 fw-bold">{{ t.invoice.bankInfoTitle }}</h6>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="row g-3">
                        <div class="col-12 col-md-6 col-xl-4" v-for="bank in banks" :key="bank.id">
                            <div class="bank-card h-100">
                                <div class="bank-logo-wrap">
                                    <img :src="'/storage/upload_files/banks/' + bank.image" class="bank-logo" :alt="bank.bank_name">
                                </div>
                                <h6 class="text-center mb-2">{{ bank.bank_name }}</h6>
                                <p class="text-center mb-1">{{ bank.rekening_number }}</p>
                                <p class="text-center small text-muted mb-0">{{ bank.rekening_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Head } from '@inertiajs/inertia-vue3';
import { transactionText } from '../../../../lang/id/transaction';

export default {
    layout: LayoutUser,

    components: {
        Link,
        Head,
    },

    props: {
        transaction: Object,
        banks: Object,
    },

    methods: {
        formatPrice(value) {
            const val = (value / 1).toFixed().replace('.', ',');
            return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },
        statusText(status) {
            if (status === 'pending') return this.t.status.pending;
            if (status === 'paid') return this.t.status.paid;
            if (status === 'failed') return this.t.status.failed;
            if (status === 'done') return this.t.status.done;
            if (status === 'expired') return this.t.status.expired;
            return status ?? '-';
        },
        statusClass(status) {
            if (status === 'pending') return 'bg-warning text-dark';
            if (status === 'paid') return 'bg-success';
            if (status === 'failed') return 'bg-danger';
            if (status === 'done') return 'bg-primary';
            if (status === 'expired') return 'bg-danger';
            return 'bg-secondary';
        },
        printInvoice() {
            window.print();
        },
    },
    data() {
        return {
            t: transactionText,
        };
    },
};
</script>

<style scoped>
.invoice-page {
    background: radial-gradient(1200px 300px at 20% -15%, rgba(0, 140, 255, 0.08) 0%, rgba(0, 140, 255, 0) 60%), #f8fbff;
}

.hero-title {
    color: #1f2430;
    font-weight: 700;
}

.hero-subtitle {
    color: #667085;
}

.invoice-card,
.bank-card {
    border-radius: 16px;
}

.status-pill {
    font-size: 0.78rem;
    padding: 0.5rem 0.65rem;
}

.table-modern thead th {
    border-bottom: 1px solid #e8e8ef;
    color: #5c6272;
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.table-modern tbody td,
.table-modern tfoot td {
    border-color: #f0f1f5;
}

.bank-card {
    border: 1px solid #e8ebf2;
    padding: 0.85rem;
    background: #fff;
}

.bank-logo-wrap {
    min-height: 72px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bank-logo {
    max-width: 130px;
    max-height: 42px;
    object-fit: contain;
}

@media print {
    .invoice-hero,
    .btn,
    .page-breadcrumb,
    .card:last-child {
        display: none !important;
    }

    .invoice-page {
        background: #fff !important;
    }

    .page-wrapper,
    .page-content {
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>
