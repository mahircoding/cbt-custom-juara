<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - {{ t.show.pageTitle }}</title>
    </Head>

    <div class="page-wrapper transaction-detail-page">
        <div class="page-content">
            <div class="detail-hero mb-4">
                <h2 class="hero-title mb-1">{{ t.show.heroTitle }}</h2>
                <p class="hero-subtitle mb-0">{{ t.show.heroSubtitle }}</p>
            </div>

            <div v-if="$page.props.session.error" class="alert alert-danger border-0 shadow-sm">
                <div v-html="$page.props.session.error"></div>
            </div>
            <div v-if="$page.props.session.success" class="alert alert-success border-0 shadow-sm">
                <div v-html="$page.props.session.success"></div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-start align-items-lg-center">
                    <div>
                        <div class="small text-muted mb-1">{{ t.common.code }}</div>
                        <div class="fw-bold fs-5">{{ transaction.code }}</div>
                        <div class="small text-muted mt-1">{{ formatDateWithTimeHourMinute(transaction.created_at) }}</div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge status-pill" :class="statusClass(transaction.transaction_status)">
                            {{ statusText(transaction.transaction_status) }}
                        </span>
                        <span class="badge method-pill">{{ paymentMethodText(transaction.payment_method) }}</span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <Link href="/user/transactions" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            {{ t.show.historyButton }}
                        </Link>

                        <Link
                            v-if="transaction.transaction_status == 'pending' && transaction.payment_method == 'manual_transfer'"
                            :href="`/user/transactions/${transaction.id}/payment-confirmations`"
                            class="btn btn-danger btn-sm rounded-pill px-3"
                        >
                            {{ t.show.uploadProofShort }}
                        </Link>

                        <button
                            v-if="transaction.payment_method == 'automatic_transfer_midtrans' && transaction.transaction_status == 'pending'"
                            @click="pay"
                            class="btn btn-success btn-sm rounded-pill px-3"
                        >
                            {{ t.show.payNow }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm h-100 info-card">
                        <div class="card-header border-0 bg-transparent p-4 pb-2">
                            <h6 class="mb-0 fw-bold">{{ t.show.buyerData }}</h6>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="info-row">
                                <span>Nama Lengkap</span>
                                <strong>{{ transaction.user.name }}</strong>
                            </div>
                            <div class="info-row">
                                <span>Email</span>
                                <strong>{{ transaction.user.email }}</strong>
                            </div>
                            <div class="info-row">
                                <span>{{ t.show.phoneNumber }}</span>
                                <strong>{{ transaction.user.student && transaction.user.student.phone_number ? transaction.user.student.phone_number : '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm h-100 info-card">
                        <div class="card-header border-0 bg-transparent p-4 pb-2">
                            <h6 class="mb-0 fw-bold">{{ t.show.transactionDetail }}</h6>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="info-row" v-if="transaction.category">
                                <span>{{ t.common.category }}</span>
                                <strong>{{ transaction.category.name }}</strong>
                            </div>
                            <div class="info-row">
                                <span>{{ t.common.description }}</span>
                                <strong>{{ transaction.description }}</strong>
                            </div>
                            <div class="info-row">
                                <span>{{ t.common.total }}</span>
                                <strong class="text-primary">{{ formatPrice(transaction.total_payment) }}</strong>
                            </div>
                            <div class="info-row" v-if="transaction.payment_method == 'automatic_transfer_midtrans' && transaction.transaction_status == 'pending'">
                                <span>{{ t.show.payment }}</span>
                                <button @click="pay" class="btn btn-success btn-sm rounded-pill">{{ t.show.payNow }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="transaction.payment_method == 'manual_transfer' && transaction.payment_confirmation" class="card border-0 shadow-sm mt-4 info-card">
                <div class="card-header border-0 bg-transparent p-4 pb-2">
                    <h6 class="mb-0 fw-bold">{{ t.show.paymentConfirmation }}</h6>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <div class="info-row">
                                <span>{{ t.show.senderName }}</span>
                                <strong>{{ transaction.payment_confirmation.rekening_name }}</strong>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="info-row">
                                <span>{{ t.show.transferDate }}</span>
                                <strong>{{ formatDate(transaction.payment_confirmation.transfer_date) }}</strong>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="info-row">
                                <span>{{ t.show.transferTo }}</span>
                                <strong>{{ transaction.payment_confirmation.bank.bank_name }} - {{ transaction.payment_confirmation.bank.rekening_number }}</strong>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="info-row">
                                <span>{{ t.show.transferAmount }}</span>
                                <strong class="text-primary">{{ formatPrice(transaction.payment_confirmation.total_payment) }}</strong>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="small text-muted mb-2">{{ t.show.transferProof }}</div>
                            <img
                                :src="'/storage/upload_files/payment_confirmation/' + transaction.payment_confirmation.file"
                                class="proof-image"
                                alt="Bukti Transfer"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="transaction.payment_method == 'manual_transfer' && transaction.transaction_status == 'pending'" class="card border-0 shadow-sm mt-4 info-card">
                <div class="card-header border-0 bg-transparent p-4 pb-2 d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">{{ t.show.paymentDestinationTitle }}</h6>
                    <a :href="`https://wa.me/${$page.props.setting.whatsapp_number}`" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                        <i class='lni lni-whatsapp me-1'></i> {{ t.show.contactAdmin }}
                    </a>
                </div>
                <div class="card-body p-4 pt-2">
                    <p class="text-muted small mb-3">{{ t.show.paymentDestinationSubtitle }}</p>

                    <div class="row g-3">
                        <div class="col-12 col-md-6 col-xl-4" v-for="bank in banks" :key="bank.id">
                            <div class="bank-card h-100">
                                <div class="bank-logo-wrap">
                                    <img :src="'/storage/upload_files/banks/' + bank.image" class="bank-logo" :alt="bank.bank_name">
                                </div>
                                <div class="bank-info text-center">
                                    <h6 class="mb-2">{{ bank.bank_name }}</h6>
                                    <div class="input-group input-group-sm mb-2">
                                        <input type="text" class="form-control text-center" :value="bank.rekening_number" readonly>
                                        <button class="btn btn-outline-primary" type="button" @click="copyText(bank.rekening_number)">{{ t.common.copy }}</button>
                                    </div>
                                    <div class="small text-muted">{{ bank.rekening_name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <Link :href="`/user/transactions/${transaction.id}/payment-confirmations`" class="btn btn-danger btn-sm rounded-pill px-4">
                            {{ t.show.uploadProof }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../../Layouts/Layout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Head } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import { transactionText } from '../../../../lang/id/transaction';

export default {
    layout: LayoutAdmin,

    components: {
        Link,
        Head,
    },

    props: {
        errors: Object,
        transaction: Object,
        banks: Object,
    },

    methods: {
        pay() {
            if (typeof window.snap !== 'undefined') {
                window.snap.pay(this.transaction.snap_token, {
                    onSuccess: () => {
                        Inertia.visit('/user/transactions/success');
                    },
                    onPending: () => {
                        Inertia.visit('/user/transactions');
                    },
                    onError: () => {
                        Inertia.visit('/user/transactions');
                    },
                });
                return;
            }

            Swal.fire({
                title: this.t.show.snapNotReadyTitle,
                text: this.t.show.snapNotReadyText,
                icon: 'error',
                showConfirmButton: true,
            });
        },
        formatPrice(value) {
            const val = (value / 1).toFixed(2).replace('.', ',');
            return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },
        copyText(text) {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);

            Swal.fire({
                title: this.t.show.copiedTitle,
                text: this.t.show.copiedText,
                icon: 'success',
                timer: 1200,
                showConfirmButton: false,
            });
        },
        paymentMethodText(method) {
            if (method === 'account_balance') return this.t.paymentMethod.account_balance;
            if (method === 'manual_transfer') return this.t.paymentMethod.manual_transfer;
            if (method === 'automatic_transfer_midtrans') return this.t.paymentMethod.automatic_transfer_midtrans;
            if (method === 'not_payment_method') return this.t.paymentMethod.not_payment_method;
            return method ?? '-';
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
            if (status === 'paid') return 'bg-primary';
            if (status === 'failed') return 'bg-danger';
            if (status === 'done') return 'bg-success';
            if (status === 'expired') return 'bg-danger';
            return 'bg-secondary';
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
.transaction-detail-page {
    background: radial-gradient(1200px 300px at 20% -15%, rgba(0, 140, 255, 0.08) 0%, rgba(0, 140, 255, 0) 60%), #f8fbff;
}

.hero-title {
    color: #1f2430;
    font-weight: 700;
}

.hero-subtitle {
    color: #667085;
}

.info-card {
    border-radius: 16px;
}

.status-pill,
.method-pill {
    font-size: 0.78rem;
    padding: 0.5rem 0.65rem;
}

.method-pill {
    background: rgba(0, 140, 255, 0.12);
    color: #008cff;
    font-weight: 600;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #edf0f5;
}

.info-row:last-child {
    border-bottom: 0;
}

.info-row span {
    color: #667085;
    font-size: 0.9rem;
    min-width: 140px;
}

.info-row strong {
    text-align: right;
    color: #1f2430;
    word-break: break-word;
}

.proof-image {
    width: 100%;
    max-width: 360px;
    border-radius: 12px;
    border: 1px solid #e8ebf2;
}

.bank-card {
    border: 1px solid #e8ebf2;
    border-radius: 14px;
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

@media (max-width: 767px) {
    .info-row {
        flex-direction: column;
        gap: 0.35rem;
    }

    .info-row strong {
        text-align: left;
    }
}
</style>
