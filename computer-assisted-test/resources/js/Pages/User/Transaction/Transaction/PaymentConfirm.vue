<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - {{ t.paymentConfirm.pageTitle }} {{ type }}</title>
    </Head>

    <div class="page-wrapper payment-confirm-page">
        <div class="page-content">
            <div class="confirm-hero mb-4">
                <h2 class="hero-title mb-1">{{ t.paymentConfirm.heroTitle }}</h2>
                <p class="hero-subtitle mb-0">{{ t.paymentConfirm.heroSubtitle }}</p>
            </div>

            <div v-if="$page.props.session.error" class="alert alert-danger border-0 shadow-sm">
                <div v-html="$page.props.session.error"></div>
            </div>
            <div v-if="$page.props.session.success" class="alert alert-success border-0 shadow-sm">
                <div v-html="$page.props.session.success"></div>
            </div>
            <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 shadow-sm">
                <strong>{{ t.paymentConfirm.warning }}</strong>
                <ul class="mt-2 mb-0">
                    <li v-for="(error, idx) in errors" :key="idx">{{ error }}</li>
                </ul>
            </div>

            <div class="card border-0 shadow-sm mb-4 summary-card">
                <div class="card-body p-4 d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-start align-items-lg-center">
                    <div>
                        <div class="small text-muted mb-1">{{ t.common.code }}</div>
                        <div class="fw-bold fs-5">{{ transaction.code }}</div>
                        <div class="small text-muted mt-1">{{ transaction.description }}</div>
                    </div>
                    <div>
                        <div class="small text-muted mb-1">{{ t.paymentConfirm.totalBill }}</div>
                        <div class="fw-bold fs-4 text-primary">{{ formatPrice(transaction.total_payment) }}</div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <Link :href="`/user/transactions/${transaction.id}`" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            {{ t.common.back }}
                        </Link>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header border-0 bg-transparent p-4 pb-2">
                        <h6 class="mb-0 fw-bold">{{ t.paymentConfirm.formTitle }} {{ type }}</h6>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label class="form-label form-label-modern">{{ t.paymentConfirm.senderName }}</label>
                                <input
                                    type="text"
                                    class="form-control form-control-modern"
                                    v-model="form.rekening_name"
                                    :class="{ 'is-invalid': errors.rekening_name }"
                                    :placeholder="t.paymentConfirm.senderNamePlaceholder"
                                >
                                <div v-if="errors.rekening_name" class="invalid-feedback">{{ errors.rekening_name }}</div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <label class="form-label form-label-modern">{{ t.paymentConfirm.transferTo }}</label>
                                <select v-model="form.bank_id" class="form-select form-select-modern" :class="{ 'is-invalid': errors.bank_id }">
                                    <option value="">{{ t.paymentConfirm.transferToPlaceholder }}</option>
                                    <option v-for="(bank, index) in banks" :key="index" :value="bank.id">
                                        {{ bank.bank_name }} - {{ bank.rekening_name }} - {{ bank.rekening_number }}
                                    </option>
                                </select>
                                <div v-if="errors.bank_id" class="invalid-feedback">{{ errors.bank_id }}</div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <label class="form-label form-label-modern">{{ t.paymentConfirm.transferDate }}</label>
                                <input
                                    type="date"
                                    class="form-control form-control-modern"
                                    v-model="form.transfer_date"
                                    :class="{ 'is-invalid': errors.transfer_date }"
                                >
                                <div v-if="errors.transfer_date" class="invalid-feedback">{{ errors.transfer_date }}</div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <label class="form-label form-label-modern">{{ t.paymentConfirm.transferAmount }}</label>
                                <input
                                    type="number"
                                    class="form-control form-control-modern"
                                    v-model="form.total_payment"
                                    :class="{ 'is-invalid': errors.total_payment }"
                                    :placeholder="t.paymentConfirm.transferAmountPlaceholder"
                                    min="0"
                                >
                                <div v-if="errors.total_payment" class="invalid-feedback">{{ errors.total_payment }}</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label form-label-modern">{{ t.paymentConfirm.transferProof }}</label>
                                <input
                                    type="file"
                                    class="form-control form-control-modern"
                                    @input="form.file = $event.target.files[0]"
                                    :class="{ 'is-invalid': errors.file }"
                                    accept="image/*,.pdf"
                                >
                                <div v-if="errors.file" class="invalid-feedback">{{ errors.file }}</div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button class="btn btn-primary rounded-pill px-4">{{ t.paymentConfirm.submit }}</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 bg-transparent p-4 pb-2">
                    <h6 class="mb-0 fw-bold">{{ t.paymentConfirm.bankListTitle }}</h6>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="row g-3">
                        <div class="col-12 col-md-6 col-xl-4" v-for="bank in banks" :key="bank.id">
                            <div class="bank-card h-100">
                                <div class="bank-logo-wrap">
                                    <img :src="'/storage/upload_files/banks/' + bank.image" class="bank-logo" :alt="bank.bank_name" />
                                </div>
                                <h6 class="text-center mb-2">{{ bank.bank_name }}</h6>
                                <div class="input-group input-group-sm mb-2">
                                    <input type="text" class="form-control text-center" :value="bank.rekening_number" readonly>
                                    <button class="btn btn-outline-primary" type="button" @click="copyText(bank.rekening_number)">{{ t.common.copy }}</button>
                                </div>
                                <div class="small text-muted text-center">{{ bank.rekening_name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../../Layouts/Layout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { reactive } from 'vue';
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
        type: Object,
    },

    setup(props) {
        const form = reactive({
            bank_id: '',
            rekening_name: '',
            transfer_date: '',
            total_payment: props.transaction.total_payment,
            file: '',
        });

        const submit = () => {
            Inertia.post(
                `/user/transactions/${props.transaction.id}/payment-confirmations`,
                {
                    bank_id: form.bank_id,
                    rekening_name: form.rekening_name,
                    transfer_date: form.transfer_date,
                    total_payment: form.total_payment,
                    file: form.file,
                },
                {
                    forceFormData: true,
                    onSuccess: () => {
                        Swal.fire({
                            title: transactionText.paymentConfirm.successTitle,
                            text: transactionText.paymentConfirm.successText,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1200,
                        });
                    },
                }
            );
        };

        return {
            form,
            submit,
            t: transactionText,
        };
    },

    methods: {
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
    },
};
</script>

<style scoped>
.payment-confirm-page {
    background: radial-gradient(1200px 300px at 20% -15%, rgba(0, 140, 255, 0.08) 0%, rgba(0, 140, 255, 0) 60%), #f8fbff;
}

.hero-title {
    color: #1f2430;
    font-weight: 700;
}

.hero-subtitle {
    color: #667085;
}

.summary-card,
.bank-card {
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
</style>
