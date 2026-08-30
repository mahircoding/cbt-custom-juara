<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Riwayat Tryout</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="d-flex justify-content-between">
                <div class="ms-start">
                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Riwayat Tryout</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Detail Riwayat Latihan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="ms-auto mb-3">
                    <Link v-if="safeGrade.exam_group_id" :href="`/user/exam-groups/${safeGrade.category_id}/lesson-categories/${safeGrade.lesson_category_id}/exams/${safeGrade.exam_group_id}`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                    <Link v-else href="/user/grades" class="btn btn-primary btn-sm">Kembali</Link>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white" style="cursor: pointer;" @click="toggleCollapseTryOutInformation">
                        Informasi Tryout
                        <a class="float-end">
                            <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutInformation, 'bx bx-chevron-up': !collapseTryOutInformation }"></i>
                        </a>
                    </h5>
                </div>
                <div v-show="collapseTryOutInformation">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="220px">Peminatan</th>
                                        <td width="10px">:</td>
                                        <td><span class="badge bg-primary">{{ safeGrade.category.name }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Kategori Mata Pelajaran</th>
                                        <td>:</td>
                                        <td>{{ safeGrade.lesson_category.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Judul Ujian</th>
                                        <td>:</td>
                                        <td>
                                            {{ safeGrade.exam.title }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <td>:</td>
                                        <td>
                                            {{ safeGrade.lesson.name }}
                                            <br>
                                            <Link :href="`/user/grades/${safeGrade.id}/questions`" v-if="safeGrade.exam.show_answer_discussion == 1 && safeGrade.is_finished == 1"><span class="badge bg-warning text-dark">Klik untuk pembahasan</span></Link>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Durasi</th>
                                        <td>:</td>
                                        <td>{{ safeGrade.exam.duration }} Menit</td>
                                    </tr>
                                    <tr>
                                        <th>Waktu Mulai</th>
                                        <td>:</td>
                                        <td>{{ formatDateWithTime(safeGrade.start_time) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Waktu Selesai</th>
                                        <td>:</td>
                                        <td>{{ formatDateWithTime(safeGrade.end_time) }}</td>
                                    </tr>
                                    <!-- <tr v-if="grade.exam.repeat_the_exam != 0">
                                        <th>Total Mengulangi</th>
                                        <td>:</td>
                                        <td>{{ grade.total_repeat > 0 ? grade.total_repeat + ' kali mengulangi': 'Belum mengulangi' }}</td>
                                    </tr> -->
                                    <tr>
                                        <th>Nilai</th>
                                        <td>:</td>
                                        <td><h5>{{ gradeFormat(safeGrade.grade) }}</h5></td>
                                    </tr>
                                    <tr>
                                        <th>Action</th>
                                        <td>:</td>
                                        <td>
                                            <Link class="btn btn-warning btn-sm" v-if="safeGrade.exam.exam_group_id == null && safeGrade.is_finished == 0" :href="`/user/categories/${safeGrade.exam.category_id}/lesson-categories/${safeGrade.exam.lesson_category_id}/lessons/${safeGrade.exam.lesson_id}/exams/${safeGrade.exam.id}`">Lanjut Mengerjakan</Link>
                                            <Link class="btn btn-primary btn-sm" v-if="safeGrade.exam.exam_group_id == null && safeGrade.is_finished == 1" :href="`/user/categories/${safeGrade.exam.category_id}/lesson-categories/${safeGrade.exam.lesson_category_id}/lessons/${safeGrade.exam.lesson_id}/exams/${safeGrade.exam.id}`">Lihat Ujian</Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" v-if="safeGrade.grade_details && safeGrade.exam.question_title.add_value_category == 1">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white" style="cursor: pointer;" @click="toggleCollapseTryOutDetailValueByKategory">
                        Detail Nilai Per Kategori
                        <a class="float-end">
                            <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutDetailValueByKategory, 'bx bx-chevron-up': !collapseTryOutDetailValueByKategory }"></i>
                        </a>
                    </h5>
                </div>
                <div v-show="collapseTryOutDetailValueByKategory">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>ASPEK PENILAIAN</th>
                                        <th v-if="safeGrade.exam.question_title.total_section == 1 && safeGrade.exam.question_title.assessment_type != 4">TOTAL BENAR</th>
                                        <th v-if="safeGrade.exam.question_title.total_section == 1 && safeGrade.exam.question_title.assessment_type != 4">TOTAL SALAH</th>
                                        <th v-if="grade.grade_details.some(item => 'rs' in item)">RS</th>
                                        <th v-if="grade.grade_details.some(item => 'ws' in item)">WS</th>
                                        <th>KATEGORI</th>
                                        <th>SKOR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(gradeDetail, index) in safeGrade.grade_details" :key="index">
                                        <td>{{ ++index }}</td>
                                        <td>{{ gradeDetail.grade_category_name }}</td>
                                        <td v-if="safeGrade.exam.question_title.total_section == 1 && safeGrade.exam.question_title.assessment_type != 4">
                                            <Link v-if="safeGrade.exam.show_answer_discussion == 1 && gradeDetail.total_correct > 0" :href="`/user/grades/${safeGrade.id}/questions?valueCategoryId=${gradeDetail.value_category_id}&isCorrect=Y`">{{ gradeDetail.total_correct }}</Link>
                                            <div v-else>{{ gradeDetail.total_correct }}</div>
                                        </td>
                                        <td v-if="safeGrade.exam.question_title.total_section == 1 && safeGrade.exam.question_title.assessment_type != 4">
                                            <Link v-if="safeGrade.exam.show_answer_discussion == 1 && gradeDetail.total_wrong > 0" :href="`/user/grades/${safeGrade.id}/questions?valueCategoryId=${gradeDetail.value_category_id}&isCorrect=N`">{{ gradeDetail.total_wrong }}</Link>
                                            <div v-else>{{ gradeDetail.total_wrong }}</div>
                                        </td>
                                        <td v-if="grade.grade_details.some(item => 'rs' in item)">{{ gradeDetail.rs ?? '-' }}</td>
                                        <td v-if="grade.grade_details.some(item => 'ws' in item)">{{ gradeDetail.ws ?? '-' }}</td>
                                        <td>{{ gradeDetail.grade_category }}</td>
                                        <td>
                                            <span>{{ gradeDetail.grade < 0 ? 0 : gradeDetail.grade }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th :colspan="computedColspan">TOTAL SKOR</th>
                                        <th>{{ safeGrade.final_score }}</th>
                                    </tr>
                                    <tr>
                                        <th :colspan="computedColspan">KONVERSI NILAI AKHIR</th>
                                        <th>{{ gradeFormat(safeGrade.grade) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" v-else>
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white" style="cursor: pointer;" @click="toggleCollapseTryOutDetailValueByKategory">
                        Detail Nilai
                        <a class="float-end">
                            <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutDetailValueByKategory, 'bx bx-chevron-up': !collapseTryOutDetailValueByKategory }"></i>
                        </a>
                    </h5>
                </div>
                <div v-show="collapseTryOutDetailValueByKategory">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>MATA PELAJARAN</th>
                                        <th v-if="safeGrade.exam.question_title.assessment_type != 4">BENAR</th>
                                        <th v-if="safeGrade.exam.question_title.assessment_type != 4">SALAH</th>
                                        <th>SKOR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ safeGrade.lesson.name }}</td>
                                        <td v-if="safeGrade.exam.question_title.assessment_type != 4">
                                            <Link v-if="safeGrade.exam.show_answer_discussion == 1 && safeGrade.total_correct > 0" :href="`/user/grades/${safeGrade.id}/questions?isCorrect=Y`">{{ safeGrade.total_correct }}</Link>
                                            <div v-else>{{ safeGrade.total_correct }}</div>
                                        </td>
                                        <td v-if="safeGrade.exam.question_title.assessment_type != 4">
                                            <Link v-if="safeGrade.exam.show_answer_discussion == 1 && safeGrade.total_wrong > 0" :href="`/user/grades/${safeGrade.id}/questions?isCorrect=N`">{{ safeGrade.total_wrong }}</Link>
                                            <div v-else>{{ safeGrade.total_wrong }}</div>
                                        </td>
                                        <td>{{ gradeFormat(safeGrade.grade) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" v-if="safeGrade.exam.question_title.total_section > 1 && safeGrade.total_correct_per_section">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white" style="cursor: pointer;" @click="toggleCollapseTryOutGraphicBySection">
                        Grafik Ketahanan
                        <a class="float-end">
                            <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutGraphicBySection, 'bx bx-chevron-up': !collapseTryOutGraphicBySection }"></i>
                        </a>
                    </h5>
                </div>
                <div v-show="collapseTryOutGraphicBySection">
                    <div class="card-body">
                        <apexchart :width="chart.width" :height="chart.height" :type="chart.type" :options="chart.options" :series="chart.series"></apexchart>
                    </div>
                </div>
            </div>

            <div class="card" v-if="(safeGrade.exam && safeGrade.exam.show_ranking_exam == 1) || (safeGrade.exam_group && safeGrade.exam_group.show_ranking_exam == 1)">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white" style="cursor: pointer;" @click="toggleCollapseTryOutRanking">
                        Nilai Peserta
                        <a class="float-end">
                            <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutRanking, 'bx bx-chevron-up': !collapseTryOutRanking }"></i>
                        </a>
                    </h5>
                </div>
                <div v-show="collapseTryOutRanking">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="text-start">
                                <form @submit.prevent="handleSearch">
                                    <div class="position-relative">
                                        <input
                                            type="text"
                                            v-model="search"
                                            class="form-control ps-5 radius-20"
                                            placeholder="Cari Peserta...."
                                            size="40"
                                            maxlength="100"
                                        >
                                        <span class="position-absolute top-50 product-show translate-middle-y">
                                            <i class="bx bx-search"></i>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Peringkat</th>
                                        <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'code')?.translate }}</th>
                                        <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</th>
                                        <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'province_id')?.translate }}</th>
                                        <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'city_id')?.translate }}</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(rankingExam, index) in rankingExams.data" :key="index">
                                        <td><span class="badge bg-primary">{{ ++index + (rankingExams.current_page - 1) * rankingExams.per_page }}</span></td>
                                        <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ rankingExam.user.code ?? '-' }}</td>
                                        <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ rankingExam.user.name ?? '-' }}</td>
                                        <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')">{{ rankingExam.user.student && rankingExam.user.student.province ? rankingExam.user.student.province.name :  '-' }}</td>
                                        <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')">{{ rankingExam.user.student && rankingExam.user.student.city ? rankingExam.user.student.city.name :  '-' }}</td>
                                        <th>{{ gradeFormat(rankingExam.grade) }}</th>
                                    </tr>
                                    <tr>
                                    <td align="center" colspan="7" v-if="!rankingExams.data.length">Data Tidak Tersedia</td>
                                </tr>
                                </tbody>
                            </table>

                            <Pagination :links="rankingExams.links" align="end" />

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
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';
    
    import { ref } from 'vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    import { Inertia } from '@inertiajs/inertia';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

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
            grade: Object,
            rankingExams: Object,
            chart: Object,
            answers: [Object, Array],
        },
        setup(props) {
            const safeGrade = ref({
                ...props.grade,
                category: props.grade?.category ?? { name: '-' },
                lesson_category: props.grade?.lesson_category ?? props.grade?.lessonCategory ?? { name: '-' },
                lesson: props.grade?.lesson ?? { name: '-' },
                exam_group: props.grade?.exam_group ?? props.grade?.examGroup ?? null,
                exam: {
                    ...(props.grade?.exam ?? {}),
                    title: props.grade?.exam?.title ?? '-',
                    question_title: props.grade?.exam?.question_title ?? props.grade?.exam?.questionTitle ?? { total_section: 1, assessment_type: 1, add_value_category: 0 },
                },
                grade_details: Array.isArray(props.grade?.grade_details) ? props.grade.grade_details : [],
            });

            const collapseTryOutInformation = ref(true);
            const collapseTryOutDetailValueByKategory = ref(true);
            const collapseTryOutGraphicBySection = ref(true);
            const collapseTryOutRanking = ref(true);

            const toggleCollapseTryOutInformation = () => {
                collapseTryOutInformation.value = !collapseTryOutInformation.value;
                localStorage.setItem('collapseTryOutInformation', JSON.stringify(collapseTryOutInformation.value));
            }

            const toggleCollapseTryOutDetailValueByKategory = () => {
                collapseTryOutDetailValueByKategory.value = !collapseTryOutDetailValueByKategory.value;
                localStorage.setItem('collapseTryOutDetailValueByKategory', JSON.stringify(collapseTryOutDetailValueByKategory.value));
            }

            const toggleCollapseTryOutGraphicBySection = () => {
                collapseTryOutGraphicBySection.value = !collapseTryOutGraphicBySection.value;
                localStorage.setItem('collapseTryOutGraphicBySection', JSON.stringify(collapseTryOutGraphicBySection.value));
            }

            const toggleCollapseTryOutRanking = () => {
                collapseTryOutRanking.value = !collapseTryOutRanking.value;
                localStorage.setItem('collapseTryOutRanking', JSON.stringify(collapseTryOutRanking.value));
            }

            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get(`/user/grades/${safeGrade.value.id}`, {
                    search: search.value,
                })
            }

            return {
                collapseTryOutInformation,
                collapseTryOutDetailValueByKategory,
                collapseTryOutGraphicBySection,
                collapseTryOutRanking,
                safeGrade,

                toggleCollapseTryOutInformation,
                toggleCollapseTryOutDetailValueByKategory,
                toggleCollapseTryOutGraphicBySection,
                toggleCollapseTryOutRanking,
                search,
                handleSearch
            }
        },
        computed: {
            computedColspan() {
                let colspan = this.safeGrade.exam.question_title.total_section > 1 || this.safeGrade.exam.question_title.assessment_type == 4 ? 3 : 5;

                if (this.safeGrade.grade_details.some(item => 'rs' in item)) {
                    colspan += 1;
                }

                if (this.safeGrade.grade_details.some(item => 'ws' in item)) {
                    colspan += 1;
                }

                return colspan;
            }
        }
    }
</script>
