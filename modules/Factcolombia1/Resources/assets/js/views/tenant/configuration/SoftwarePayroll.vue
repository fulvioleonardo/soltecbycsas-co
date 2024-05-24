<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuración de nómina electrónica</h3>
        </div>
        <div class="card-body">
            <div class="software">
                <form autocomplete="off">
                    <div class="form-body">
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.idpayroll}">
                                    <label class="control-label">Id Software Nómina*</label>
                                    <el-input
                                        v-model="form.idpayroll"
                                        autofocus>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.idpayroll" v-text="errors.idpayroll[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.pinpayroll}">
                                    <label class="control-label">Pin Software Nómina*</label>
                                    <el-input
                                        v-model="form.pinpayroll"
                                        maxlength="5"
                                        show-word-limit>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.pinpayroll" v-text="errors.pinpayroll[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.test_set_id_payroll}">
                                    <label class="control-label">Test Set ID Nómina*</label>
                                    <el-input
                                        v-model="form.test_set_id_payroll">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.test_set_id_payroll" v-text="errors.test_set_id_payroll[0]"></small>
                                </div>
                            </div>

                        </div>
                        <div class="form-actions text-right mt-4">
                            <el-button
                                type="primary"
                                :loading="loadingSoftware"
                                @click="validateSoftware()"
                                >Guardar
                            </el-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>

    // import Helper from "../../../mixins/Helper";

    export default {
        // mixins: [Helper],
        props: {
            route: {
                required: true
            }
        },
        data: () => ({
            errors: {},
            form: {},
            company: {},
            loadingSoftware: false,
        }),
        async created() {
            await this.initForm()
            await this.getCompany()
            await this.setDataForm()
        },
        methods: {
            async getCompany(){
                await this.$http.post(`/company`).then(response => {
                    this.company = response.data
                })
            },
            setDataForm() {
                this.form.idpayroll = this.company.id_software_payroll
                this.form.pinpayroll = this.company.pin_software_payroll
                this.form.test_set_id_payroll = this.company.test_set_id_payroll
            },
            initForm() {
                this.form = {
                    idpayroll: null,
                    pinpayroll: null,
                    test_set_id_payroll: null
                }
            },
            validateSoftware() {
                this.loadingSoftware = true
                this.$http.post(`/client/configuration/store-service-software-payroll`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            // this.initForm()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loadingSoftware = false
                    })
            },
        }
    };
</script>
