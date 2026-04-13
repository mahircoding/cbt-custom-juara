<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data activity</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">activity</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data activity</li>
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
                            <div class="col-lg-3">
                                <label for="lesson_id">Log Name</label>
                                <select v-model="form.log_name" class="form-select form-select-sm">
                                    <option value="">[ Choose ]</option>
                                    <!-- Affiliate -->
                                    <option value="Commission">Commission</option>
                                    <option value="ReferralLink">ReferralLink</option>
                                    <option value="UserBank">UserBank</option>
                                    <option value="UserCommission">UserCommission</option>
                                    <option value="Withdrawal">Withdrawal</option>
                                    <!-- Course -->
                                    <option value="Course">Course</option>
                                    <option value="CourseDetail">CourseDetail</option>
                                    <option value="CourseSection">CourseSection</option>
                                    <!-- Exam -->
                                    <option value="Exam">Exam</option>
                                    <option value="ExamGroup">ExamGroup</option>
                                    <option value="ExamGroupUser">ExamGroupUser</option>
                                    <option value="Grade">Grade</option>
                                    <!-- Lesson -->
                                    <option value="DetailValueCategory">DetailValueCategory</option>
                                    <option value="Lesson">Lesson</option>
                                    <option value="LessonCategory">LessonCategory</option>
                                    <option value="QuestionTemplate">QuestionTemplate</option>
                                    <option value="QuestionTitle">QuestionTitle</option>
                                    <option value="ValueCategory">ValueCategory</option>
                                    <option value="ValueCategoryGroup">ValueCategoryGroup</option>
                                    <!-- Master data -->
                                    <option value="Announcement">Announcement</option>
                                    <option value="Category">Category</option>
                                    <option value="Faq">Faq</option>
                                    <option value="News">News</option>
                                    <option value="Student">Student</option>
                                    <option value="SubCategory">SubCategory</option>
                                    <!-- Material -->
                                    <option value="DetailModule">DetailModule</option>
                                    <option value="DetailVideoModule">DetailVideoModule</option>
                                    <option value="Module">Module</option>
                                    <option value="VideoModule">VideoModule</option>
                                    <!-- Setting -->
                                    <option value="Banner">Banner</option>
                                    <option value="InternetProtocolWhitelist">InternetProtocolWhitelist</option>
                                    <option value="Setting">Setting</option>
                                    <option value="SocialGroup">SocialGroup</option>
                                    <!-- Transaction -->
                                    <option value="Bank">Bank</option>
                                    <option value="PaymentConfirmation">PaymentConfirmation</option>
                                    <option value="PaymentMethod">PaymentMethod</option>
                                    <option value="Transaction">Transaction</option>
                                    <option value="Voucher">Voucher</option>
                                    <option value="VoucherCode">VoucherCode</option>
                                    <!-- Etc -->
                                    <option value="MemberCategory">MemberCategory</option>
                                    <option value="MemberCategoryUser">MemberCategoryUser</option>
                                    <option value="MenuUser">MenuUser</option>
                                    <option value="User">User</option>

                                </select>
                            </div>

                            <div class="col-lg-3">
                                <label for="category">Event</label>
                                <select v-model="form.event" class="form-select form-select-sm">
                                    <option value="">[ Choose]</option>
                                    <option value="created">Created</option>
                                    <option value="updated">Updated</option>
                                    <option value="deleted">Deleted</option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label>Start Date</label>
                                <input type="date" v-model="form.start_date" class="form-control form-control-sm" id="start_date">
                            </div>

                            <div class="col-lg-2">
                                <label>End Date</label>
                                <input type="date" v-model="form.end_date" class="form-control form-control-sm" id="end_date">
                            </div>

                            <div class="col-lg-2">
                                <label for="end_date">Action</label><br>
                                <button @click.prevent="handleSearch" class="btn btn-primary btn-sm me-2">
                                    <i class="bx bx-filter"></i>Filter
                                </button>

                                <Link href="/admin/activity-logs" class="btn btn-danger btn-sm me-2">
                                    <i class="bx bx-refresh"></i>reset
                                </Link>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="accordion" id="accordionExample">
                        <div v-if="activities.data.length" class="accordion-item" v-for="(activity, index) in activities.data" :key="index">
                            <h2 class="accordion-header" :id="`heading_${activity.id}`">
                                <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    :data-bs-target="`#collapse_${activity.id}`"
                                    aria-expanded="false"
                                    :aria-controls="`collapse_${activity.id}`"
                                >
                                    <div class="d-flex justify-content-between w-100 m-2">
                                        <span>{{ activity.description }}</span>
                                        <small>{{ dateFormat(activity.created_at) }}</small>
                                    </div>
                                </button>
                            </h2>
                            <div :id="`collapse_${activity.id}`" class="accordion-collapse collapse" :aria-labelledby="`heading_${activity.id}`" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <table class="table table-bordered">
                                        <thead class="table-success">
                                            <tr>
                                                <th colspan="3">Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Log Name</th>
                                                <td>{{ activity.log_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Description</th>
                                                <td>{{ activity.description }}</td>
                                            </tr>
                                            <tr>
                                                <th>Subject Type</th>
                                                <td>{{ activity.subject_type }}</td>
                                            </tr>
                                            <tr>
                                                <th>Event</th>
                                                <td>{{ activity.event }}</td>
                                            </tr>
                                            <tr>
                                                <th>Causer Type</th>
                                                <td>{{ activity.causer_type }}</td>
                                            </tr>
                                            <tr>
                                                <th>Causer</th>
                                                <td>{{ activity.causer ? activity.causer.name : 'System : Unknown' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Created at</th>
                                                <td>{{ dateFormat(activity.created_at) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="table table-bordered">
                                        <thead class="table-danger">
                                            <tr>
                                                <th>Propeties</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <pre class="bg-light p-3 rounded" style="font-family: monospace; font-size: 13px; white-space: pre-wrap; margin-bottom: 0;">
{{ JSON.stringify(activity.properties, null, 4) }}
                                                    </pre>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center">
                            Data tidak tersedia.
                        </div>
                    </div>

                    <Pagination :links="activities.links" align="end" />

                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import sweet alert2
    import Swal from 'sweetalert2';

    import { ref } from 'vue';

    // import Head from Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    import { reactive } from 'vue';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Pagination
        },

        // props
        props: {
            activities: Object
        },
        setup() {
            // define state search
            const form = reactive({
                log_name: ref((new URL(document.location)).searchParams.get('log_name') || ''),
                event: ref((new URL(document.location)).searchParams.get('event') || ''),
                start_date: ref((new URL(document.location)).searchParams.get('start_date') || ''),
                end_date: ref((new URL(document.location)).searchParams.get('end_date') || ''),
            });

            // define method search
            const handleSearch = () => {
                Inertia.get('/admin/activity-logs', {
                    //send params "search" with value from state "search"
                    log_name: form.log_name,
                    event: form.event,
                    start_date: form.start_date,
                    end_date: form.end_date,
                })
            }

            const showDetails = ref({});

            const toggleDetails = (index) => {
                showDetails.value[index] = !showDetails.value[index];
            };

            return {
                showDetails,
                toggleDetails,
                form,
                handleSearch
            };
        },
    }
</script>
