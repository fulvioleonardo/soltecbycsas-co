<template>
    <el-dialog :title="titleDialog" width="40%" :visible="showDialog" @open="create" 
        :close-on-click-modal="false" 
        :close-on-press-escape="false" 
        :show-close="false" 
        append-to-body
    >
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    
                    <div class="col-md-12">
                        <h4>
                            <b>Nómina: {{recordNumberFull}}</b>
                        </h4>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="form-group" :class="{'has-danger': errors.type_document_id}">
                            <label class="control-label">Resolución
                                <span class="text-danger"> *</span>
                            </label>
                            <el-select @change="changeResolution" v-model="form.type_document_id" class="border-left rounded-left border-info">
                                <el-option v-for="option in resolutions" :key="option.id" :value="option.id" :label="`${option.prefix}`"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_document_id" v-text="errors.type_document_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <label class="control-label">Notas</label>
                            <el-input v-model="form.notes" type="textarea" :rows="2"></el-input>
                        </div>
                    </div>

                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click="clickClose">Cerrar</el-button>
                    <el-button type="primary" native-type="submit" :loading="loading_submit">Generar</el-button>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId', 'recordNumberFull'],
        data() {
            return {
                titleDialog: 'Generar nómina de eliminación',
                loading: false,
                resource: 'payroll/document-payroll-adjust-notes',
                resolutions: [],
                type_payroll_adjust_note_id: 2,
                form: {},
                loading_submit: false,
                errors: {},
            }
        },
        async created() {
            await this.initForm()
            await this.getTables()
        },
        methods: {
            initForm(){

                this.form = {
                    type_document_id: null,
                    prefix: null,
                    resolution_number: null,
                    notes: null,
                    type_payroll_adjust_note_id: 2, //tipo nota de eliminación
                    document_payroll_id: null,
                }

            },
            changeResolution(){

                const resolution = _.find(this.resolutions, {id : this.form.type_document_id})

                if(resolution) 
                {
                    this.form.prefix = resolution.prefix
                    this.form.resolution_number = resolution.resolution_number
                }

            }, 
            async getTables(){

                await this.$http.get(`/${this.resource}/tables/${this.type_payroll_adjust_note_id}`)
                    .then(response => {
                        this.resolutions = response.data.resolutions
                    })
            },
            create() { 
                this.form.document_payroll_id = this.recordId
            }, 
            submit() {

                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {

                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.clickClose()
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
                        this.loading_submit = false
                    })
            }, 
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>
